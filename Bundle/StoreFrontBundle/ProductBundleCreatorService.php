<?php

namespace FrejuBundlesDiscounts\Bundle\StoreFrontBundle;

use Doctrine\DBAL\Connection;
use Shopware\Components\Api\Exception\NotFoundException;
use Shopware\Components\Api\Manager;
use Shopware\Components\Api\Resource\Article;

class ProductBundleCreatorService
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function upsertProductBundle(int $bundleId)
    {
        $sql = "
            SELECT DISTINCT a.name, a.description, a.description_long, a.supplierID, p.price * (1 - b.bundleBonus / 100) AS price, GROUP_CONCAT(DISTINCT cat.categoryid) AS categories, d.instock
            FROM s_bundle b
            INNER JOIN related_product_id r ON r.bundle_id = b.id
            INNER JOIN s_articles a ON r.product_id = a.id
            INNER JOIN s_articles_prices p ON p.articleID = a.id
            INNER JOIN s_articles_categories cat ON cat.articleID = a.id
            INNER JOIN s_articles_details d ON d.articleID = a.id
            WHERE b.id = '$bundleId'
            GROUP BY a.id
        ";

        $products = $this->connection->query($sql)->fetchAll();

        $name = "";
        $desIntro = "Ein Produkt-Bundle aus folgenden Produkten: ";
        $descriptionShort = $desIntro;
        $descriptionLong = "";
        $suppliers = [];
        $categories = [];
        $price = 0;
        $instock = -1;

        foreach ($products as $product) {
            // name
            $prodName = $product['name'];
            $name = $name == "" ? $prodName : "$name + $prodName";

            $price += $product['price'] * 1.19;

            if ($descriptionShort != $desIntro) {
                $descriptionShort .= ",";
            }
            $descriptionShort .= " " . $prodName;

            $prodDesLong = $product['description_long'];
            $descriptionLong .= "$prodName: $prodDesLong\n\n";

            $categories = array_unique(array_merge($categories, explode(',', $product['categories'])));

            if (!in_array($product['supplierID'], $suppliers)) {
                $suppliers[] = ['id' => $product['supplierID']];
            }

            if ($instock < 0 || $instock > $product['instock']) {
                $instock = $product['instock'];
            }
        }

        /** @var Article $article */
        $article = Manager::getResource('article');

        $articleNumber = 'BUNDLE-' . $bundleId;

        $articleData = [
            'name' => $name,
            'taxId' => 1,
            'mainDetail' => [
                'number' => $articleNumber,
                'inStock' => $instock,
                'prices' => [
                    [
                        'customerGroupKey' => 'EK',
                        'from' => 1,
                        'to' => 'beliebig',
                        'price' => $price,
                        'basePrice' => 0,
                        'percent' => 0,
                    ],
                ],
            ],
            'categories' => $categories,
            'supplier' => $suppliers,
            'description' => $descriptionShort,
            'descriptionLong' => $descriptionLong,
            'active' => true,
        ];

        try {
            $article->updateByNumber($articleNumber, $articleData);
        } catch (NotFoundException $ignored) {
            $article->create($articleData);
        }
    }
}
