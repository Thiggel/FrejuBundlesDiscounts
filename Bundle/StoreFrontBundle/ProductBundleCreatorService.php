<?php

namespace FrejuBundlesDiscounts\Bundle\StoreFrontBundle;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use phpDocumentor\Reflection\Types\String_;
use Shopware\Components\Routing\Context;

class ProductBundleCreatorService
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    private function getUrl(int $id): string
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

    private function getLink(int $id, string $name): string
    {
        return '<a href="' . $this->getUrl($id) . '">' . $name . '</a>';
    }

    private function getProductData(array $array, int $id, string $ordernumber, string $name, float $price, float $tax, float $bonus): array
    {
        $productPrice = $price * (1 + $tax / 100);

        $array['products'][$id] = [
            'name' => $name,
            'price' => $productPrice,
            'ordernumber' => $ordernumber,
            'url' => $this->getUrl($id),
            'bonus' => $bonus,
            'absoluteBonus' => $productPrice * $bonus / 100
        ];

        $totalPrice = 0;
        $totalBonus = 0;
        $name = "";
        $ordernumbers = "";

        foreach($array['products'] as $key => $product)
        {
            $totalPrice += floatval($product['price']);
            $totalBonus += $product['absoluteBonus'];

            $name .= $name !== "" ? " <span>+</span> " : "";
            $name .= $this->getLink($key, $product['name']);

            $ordernumbers .= $ordernumbers !== "" ? "," : "";
            $ordernumbers .= $product['ordernumber'];
        }

        $array['bundleBonus'] = $bonus;
        $array['totalPrice'] = $totalPrice - $totalBonus;
        $array['totalBonus'] = $totalBonus;
        $array['name'] = $name;
        $array['ordernumbers'] = $ordernumbers;

        return $array;
    }

    public function getBundles(): array
    {
        $sql = "
            SELECT b.id, r.product_id, b.main_product_id, b.bundleBonus, a.name, a2.name AS mainProductName, t.tax, t2.tax AS mainProductTax, p.price, p2.price AS mainProductPrice, d.ordernumber, d2.ordernumber AS mainOrderNumber
            FROM related_product_id r
            INNER JOIN s_bundle b ON b.id = r.bundle_id
            INNER JOIN s_articles a ON a.id = r.product_id
            INNER JOIN s_articles a2 ON a2.id = b.main_product_id
            INNER JOIN s_articles_prices p ON p.articleID = r.product_id
            INNER JOIN s_articles_prices p2 ON p2.articleID = b.main_product_id
            INNER JOIN s_core_tax t ON t.id = a.taxID
            INNER JOIN s_core_tax t2 ON t2.id = a2.taxID
            INNER JOIN s_articles_details d ON d.articleid = a.id
            INNER JOIN s_articles_details d2 ON d2.articleid = a2.id
            WHERE b.bundleType = 'Spar-Bundle'
        ";

        try {
            $query = $this->connection->query($sql)->fetchAll();
        } catch (DBALException $e) {
            return [];
        }

        $products = [];

        foreach($query as $bundlePart)
        {
            $products[$bundlePart['main_product_id']][$bundlePart['id']] = $this->getProductData(
                $products[$bundlePart['main_product_id']][$bundlePart['id']] ?? [],
                $bundlePart['main_product_id'],
                $bundlePart['mainOrderNumber'],
                $bundlePart['mainProductName'],
                $bundlePart['mainProductPrice'],
                $bundlePart['mainProductTax'],
                $bundlePart['bundleBonus']
            );

            $products[$bundlePart['main_product_id']][$bundlePart['id']] = $this->getProductData(
                $products[$bundlePart['main_product_id']][$bundlePart['id']] ?? [],
                $bundlePart['product_id'],
                $bundlePart['ordernumber'],
                $bundlePart['name'],
                $bundlePart['price'],
                $bundlePart['tax'],
                $bundlePart['bundleBonus']
            );

            foreach($products[$bundlePart['main_product_id']][$bundlePart['id']]['products'] as $id => $product)
                $products[$id][$bundlePart['id']] = $products[$bundlePart['main_product_id']][$bundlePart['id']];

        }

        return $products;
    }

    public function applyDiscountToProduct(array $article): array
    {
        $bundles = $this->getBundles();
        $basketProductIds = Shopware()->Modules()->Basket()->sGetBasketIds();

        if(isset($bundles[$article['articleID']])) {
            foreach($bundles[$article['articleID']] as $bundle)
            {
                // get all ids of the products that belong to the bundle
                $bundleInBasket = true;
                foreach($bundle['products'] as $id => $product)
                {
                    if(!in_array($id, $basketProductIds)) {
                        $bundleInBasket = false;
                        break;
                    }
                }

                if($bundleInBasket) {
                    // apply discounts to product of product bundle
                    $article['price'] = $article['price'] * (1 - $bundle['bundleBonus'] / 100);
                    $article['netprice'] = $article['price'] / (1 + $article['tax_rate'] / 100);
                }
            }
        }

        return $article;
    }

}
