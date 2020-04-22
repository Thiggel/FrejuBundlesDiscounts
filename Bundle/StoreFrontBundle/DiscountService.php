<?php

namespace FrejuBundlesDiscounts\Bundle\StoreFrontBundle;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Shopware\Bundle\StoreFrontBundle\Struct\Category;
use Shopware\Components\Routing\Context;

class DiscountService
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

    private function formatNum(number $n): string
    {
        return number_format($n, 2, ',', '.');
    }

    private function removeDiscount(string $discount, int $price): float
    {
        if(strpos($discount, '€')) {
            $price += (int)trim(str_replace('€', '', $discount));
        } else if(strpos($discount, '%')) {
            $value = (int)trim(str_replace('%', '', $discount));
            $price /= (100 - $value ) * 100;
        }

        return (float)$price;
    }


    private function addDiscount(string $discount, int $price): float
    {
        if(strpos($discount, '€')) {
            $price -= (int)trim(str_replace('€', '', $discount));
        } else if(strpos($discount, '%')) {
            $value = (int)trim(str_replace('%', '', $discount));
            $price *= (100 - $value) / 100;
        }

        return (float)$price;
    }


    private function getAbsoluteValue(string $discount, int $price, bool $precalculated): float
    {
        if($precalculated) {
            $price = $this->removeDiscount($discount, $price) - $price;
        } else {
            $price -= $this->addDiscount($discount, $price);
        }

        return (float)$price;
    }

    public function getDiscounts(): array
    {
        $sql = "
            SELECT discount_id, product_id, p.price, d.name, d.discounts, d.discount_precalculated, d.cashback, d.active, d.startDate, d.endDate, t.tax
            FROM discount_related_product_id r
            INNER JOIN s_discount d ON d.id = r.discount_id
            INNER JOIN s_articles_prices p ON p.articleID = product_id
            INNER JOIN s_articles a ON a.id = product_id
            INNER JOIN s_core_tax t ON t.id = a.taxID
        ";

        $query = $this->connection->query($sql)->fetchAll();

        $products = [];
        $nOfDiscounts = [];

        foreach($query as $discount) {
            if($discount['active']  && empty($products[$discount['product_id']]['discounts'][$discount['discount_id']]) /* TODO: && startDate <= today && endDate >= today */) {
                if(empty($nOfDiscounts[$discount['discount_id']])) $nOfDiscounts[$discount['discount_id']] = 0;

                // get the nth discount (discounts are put into a string and separated with semicolon)
                $discountValue = explode(";", $discount['discounts'])[$nOfDiscounts[$discount['discount_id']]];

                $products[$discount['product_id']]['price'] = $discount['price'] * (1 + $discount['tax'] / 100);

                $products[$discount['product_id']]['discounts'][$discount['discount_id']] = [
                    'value' => $discountValue,
                    'cashback' => $discount['cashback'],
                    'discount_precalculated' => $discount['discount_precalculated'],
                    'name' => $discount['name'],
                    'absoluteValue' => $this->getAbsoluteValue(
                        $discountValue,
                        $discount['price'] * (1 + $discount['tax'] / 100),
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

    public function applyDiscountToProduct(array $article): array
    {
        $discounts = $this->getDiscounts();

        if(isset($discounts[$article['articleID']])) {
            $article['price'] = $discounts[$article['articleID']]['payablePrice'];
            $article['netprice'] = $article['price'] / (1 + $article['tax_rate'] / 100);
        }

        return $article;
    }

}
