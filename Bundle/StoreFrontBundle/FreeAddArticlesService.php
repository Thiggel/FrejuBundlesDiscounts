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

    public function getFreeProducts(): array
    {
        $sql = "
            SELECT s_bundle.main_product_id, p.price, product_id, s_articles_img.media_id AS image_id, s_articles.name
            FROM related_product_id
            INNER JOIN s_bundle ON s_bundle.id = related_product_id.bundle_id
            INNER JOIN s_articles_img ON s_articles_img.articleID = product_id
            INNER JOIN s_articles ON s_articles.id = product_id
            INNER JOIN s_articles_prices p ON p.articleID = product_id
            WHERE s_bundle.bundleType = 'Gratisartikel-Bundle'
        ";

        $query = $this->connection->query($sql)->fetchAll();

        $products = [];

        foreach($query as $relations) {
            $img = $this->getImage($relations['image_id']);
            $id = $relations['product_id'];
            $name = $relations['name'];
            $url = $this->getUrl($id);
            $price = $relations['price'] * 1.19;

            $products[$relations['main_product_id']][$id] = [
                'id' => $id,
                'name' => $name,
                'img' => $img,
                'url' => $url,
                'price' => $price
            ];
        }

        return $products;
    }
}
