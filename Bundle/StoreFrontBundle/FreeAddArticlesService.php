<?php

namespace FrejuBundlesDiscounts\Bundle\StoreFrontBundle;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Shopware\Bundle\StoreFrontBundle\Struct\Category;
use Shopware\Components\Routing\Context;

class FreeAddArticlesService
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return Category[] indexed by product id
     * @throws DBALException
     */
    public function getList()
    {
        return $this->getFreeProducts();
    }

    /**
     * @param $id
     * @return string
     */
    private function getImage($id) {
        $media = Shopware()->Models()->getRepository('Shopware\Models\Media\Media')->findOneBy(['id' => $id]);

        if ($media)
            return Shopware()->Container()->get('shopware_media.media_service')->getUrl($media->getPath());

        return "";
    }

    private function getUrl($id)
    {
        // Context
        $shop = Shopware()->Models()->getRepository(\Shopware\Models\Shop\Shop::class)->getById(1);
        $shopContext = Context::createFromShop($shop, Shopware()->Container()->get('config'));


        // get seo url
        $query = [
            'module' => 'frontend',
            'controller' => 'detail',
            'sArticle' => $id,
        ];

        return Shopware()->Router()->assemble($query, $shopContext);

    }

    /**
     * @return array
     * @throws DBALException
     */
    private function getFreeProducts()
    {
        $sql = "
            SELECT s_bundle.main_product_id, p.price, product_id, s_articles_img.media_id AS image_id, s_articles.name
            FROM related_product_id
            INNER JOIN s_bundle ON s_bundle.id = related_product_id.bundle_id
            INNER JOIN s_articles_img ON s_articles_img.articleID = product_id
            INNER JOIN s_articles ON s_articles.id = product_id
            INNER JOIN s_articles_prices p ON p.articleID = product_id
        ";

        $query = $this->connection->query($sql)->fetchAll();

        $products = [];

        foreach($query as $relations) {
            $img = $this->getImage($relations['image_id']);
            $id = $relations['product_id'];
            $name = $relations['name'];
            $url = $this->getUrl($id);
            $price = $relations['price'] * 1.19;

            $products[$relations['main_product_id']][] = [
                'id' => $id,
                'name' => $name,
                'img' => $img,
                'url' => $url,
                'price' => $price
            ];
        }

        return $products;
    }

    /**
     * @param $n
     * @return string
     */
    private function formatNum($n) {
        return number_format($n, 2, ',', '.');
    }

    /**
     * @param $discount
     * @param $price
     * @return float|int
     */
    private function removeDiscount($discount, $price) {
        if(strpos($discount, '€')) {
            $price += (int)trim(str_replace('€', '', $discount));
        } else if(strpos($discount, '%')) {
            $value = (int)trim(str_replace('%', '', $discount));
            $price /= (100 - $value ) * 100;
        }

        return $price;
    }

    /**
     * @param $discount
     * @param $price
     * @return float|int
     */
    private function addDiscount($discount, $price) {
        if(strpos($discount, '€')) {
            $price -= (int)trim(str_replace('€', '', $discount));
        } else if(strpos($discount, '%')) {
            $value = (int)trim(str_replace('%', '', $discount));
            $price *= (100 - $value) / 100;
        }

        return $price;
    }

    /**
     * @param $discount
     * @param $price
     * @param $precalculated
     * @return float|int
     */
    private function getAbsoluteValue($discount, $price, $precalculated) {
        if($precalculated) {
            $price = $this->removeDiscount($discount, $price) - $price;
        } else {
            $price -= $this->addDiscount($discount, $price);
        }

        return $price;
    }

    /**
     * @param int $id
     * @return int
     * @throws DBALException
     */
    public function getProdIdByBasketId(int $id) {
        return $this->connection
            ->query("SELECT articleID FROM s_order_basket WHERE id = '$id'")
            ->fetchAll()[0]['articleID'];
    }

    public function convertToNum(string $num): float {
        if(strpos($num, ',')) {
            $num = str_replace(',', '', $num);
            $num = (float)$num / 100;
        }

        return $num;
    }

    /**
     * @return array
     * @throws DBALException
     */
    public function getDiscounts()
    {
        $sql = "
            SELECT discount_id, product_id, p.price, d.name, d.discounts, d.discount_precalculated, d.cashback, d.active, d.startDate, d.endDate
            FROM discount_related_product_id r
            INNER JOIN s_discount d ON d.id = r.discount_id
            INNER JOIN s_articles_prices p ON p.articleID = product_id
        ";

        $query = $this->connection->query($sql)->fetchAll();

        $products = [];
        $nOfDiscounts = [];

        foreach($query as $discount) {
            if($discount['active'] /* TODO: && startDate <= today && endDate >= today */) {
                if(empty($nOfDiscounts[$discount['discount_id']])) $nOfDiscounts[$discount['discount_id']] = 0;

                // get the nth discount (discounts are put into a string and separated with semicolon)
                $discountValue = explode(";", $discount['discounts'])[$nOfDiscounts[$discount['discount_id']]];

                $products[$discount['product_id']]['price'] = $discount['price'] * 1.19;

                $products[$discount['product_id']]['discounts'][] = [
                    'value' => $discountValue,
                    'cashback' => $discount['cashback'],
                    'discount_precalculated' => $discount['discount_precalculated'],
                    'name' => $discount['name'],
                    'absoluteValue' => $this->getAbsoluteValue(
                        $discountValue,
                        $discount['price'] * 1.19,
                        $discount['discount_precalculated']
                    )
                ];

                // increment number of discounts
                $nOfDiscounts[$discount['discount_id']]++;
            }
        }

        $freeAddArticles = $this->getFreeProducts();

        foreach($products as $key => &$product) {
            $payablePrice = $product['price'];
            $prePrice = $product['price'];
            $postPrice = $product['price'];
            $systemPrice = $product['price'];

            foreach($product['discounts'] as $discount) {
                if($discount['discount_precalculated']) {
                    $prePrice = $this->removeDiscount($discount['value'], $prePrice);
                } else if($discount['cashback']) {
                    $postPrice = $this->addDiscount($discount['value'], $postPrice);
                } else {
                    $payablePrice = $this->addDiscount($discount['value'], $payablePrice);
                    $postPrice = $this->addDiscount($discount['value'], $postPrice);
                }
            }

            if($freeAddArticles[$key]) {
                foreach($freeAddArticles[$key] as $article) {
                    $prePrice += $article['price'];
                }

                $product['freeAddArticles'] = $freeAddArticles[$key];
            }

            $product['prePrice'] = $prePrice;
            $product['payablePrice'] = $payablePrice;
            $product['postPrice'] = $postPrice;
            $product['systemPrice'] = $systemPrice;
        }

        return $products;
    }
}
