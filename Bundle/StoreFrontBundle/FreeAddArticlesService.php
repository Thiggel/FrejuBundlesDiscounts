<?php

namespace FrejuBundlesDiscounts\Bundle\StoreFrontBundle;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Shopware\Bundle\StoreFrontBundle\Struct\Category;
use Shopware\Components\Api\Exception\NotFoundException;
use Shopware\Components\Api\Manager;
use Shopware\Components\Api\Resource\Article;
use Shopware\Components\Routing\Context;

class FreeAddArticlesService
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getList()
    {
        return $this->getFreeProducts();
    }

    private function getImage(int $id): string
    {
        $media = Shopware()->Models()->getRepository('Shopware\Models\Media\Media')->findOneBy(['id' => $id]);

        if ($media)
            return Shopware()->Container()->get('shopware_media.media_service')->getUrl($media->getPath());

        return "";
    }

    private function getUrl(int $id): string
    {
        // Context
        $shop = Shopware()->Models()->getRepository(\Shopware\Models\Shop\Shop::class)->getById(Shopware()->Shop()->getId());
        $shopContext = Context::createFromShop($shop, Shopware()->Container()->get('config'));


        // get seo url
        $query = [
            'module' => 'frontend',
            'controller' => 'detail',
            'sArticle' => $id,
        ];

        return Shopware()->Router()->assemble($query, $shopContext);

    }

    public function getFreeProducts(): array
    {
        $customerGroup = Shopware()->Shop()->getCustomerGroup()->getKey();

        $sql = "
            SELECT s_bundle.main_product_id, p.price, product_id, s_articles_img.media_id AS image_id, s_articles.name, d.ordernumber
            FROM related_product_id
            INNER JOIN s_bundle ON s_bundle.id = related_product_id.bundle_id
            INNER JOIN s_articles_img ON s_articles_img.articleID = product_id
            INNER JOIN s_articles ON s_articles.id = product_id
            INNER JOIN s_articles_prices p ON p.articleID = product_id
            INNER JOIN s_articles_details d ON d.articleid = product_id
                WHERE p.pricegroup = '$customerGroup'
                AND s_bundle.bundleType = 'Gratisartikel-Bundle'
        ";

        $query = $this->connection->query($sql)->fetchAll();

        $products = [];

        foreach($query as $relations) {
            $img = $this->getImage($relations['image_id']);
            $id = $relations['product_id'];
            $name = $relations['name'];
            $url = $this->getUrl($id);
            $price = $relations['price'] * 1.19;
            $ordernumber = $relations['ordernumber'];

            $products[$relations['main_product_id']][$id] = [
                'id' => $id,
                'name' => $name,
                'img' => $img,
                'url' => $url,
                'price' => $price,
                'ordernumber' => $ordernumber
            ];
        }

        return $products;
    }

    public function discountFreeArticle(array $article, &$isFree): array
    {
        $basketProductIds = Shopware()->Modules()->Basket()->sGetBasketIds();

        foreach($this->getFreeProducts() as $key => $mainProduct)
        {
            if(in_array($key, $basketProductIds))
            {
                $sessionId = Shopware()->Session()->get( "sessionId" );
                $sql = "
                    SELECT quantity
                    FROM s_order_basket
                    WHERE sessionID = '$sessionId'
                    AND articleID = '$key'
                ";

                $query = Shopware()->Db()->query($sql)->fetchAll();

                if(!empty($query))
                {
                    foreach($mainProduct as $freeProduct)
                    {
                        if($freeProduct['id'] == $article['articleID'])
                        {
                            $oldPrice = $article['price'];
                            $cartQuant = $article['quantity'];
                            $discountedQuant = $query[0]['quantity'];
                            $newPrice = $oldPrice - ($discountedQuant * $oldPrice / $cartQuant);

                            $article['price'] = $newPrice;
                            $article['netprice'] = $newPrice / (1 + $article['tax_rate'] / 100);

                            $isFree = true;
                            break;
                        }
                    }
                }
            }
        }

        return $article;
    }

    public function applyFreeArticlePrice(array $article): array
    {
        $article['price'] = 0;
        $article['netprice'] = 0;

        return $article;
    }
}
