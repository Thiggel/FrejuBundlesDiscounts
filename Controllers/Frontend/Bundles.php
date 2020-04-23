<?php

use Doctrine\ORM\EntityRepository;
use FrejuBundlesDiscounts\Models\Bundle;
use Shopware\Bundle\MediaBundle\MediaService;
use Shopware\Components\Routing\Router;

class Shopware_Controllers_Frontend_Bundles extends Enlight_Controller_Action
{
    /** @var EntityRepository */
    private $bundleRepository;

    public function init()
    {
        $this->bundleRepository = Shopware()->Models()->getRepository(Bundle::class);
    }

    public function configuratorAction()
    {
        $sql = "
            SELECT s_articles.id,
                   s_media.path AS image,
                   s_articles.name,
                   s_articles_prices.price,
                   s_bundle.bundlebonus,
                   s_articles_details.ean,
                   s_articles.active,
                   s_articles_details.instock,
                   s_articles_details.minpurchase,
                   s_articles_details.shippingtime,
                   s_articles_details.releasedate
            FROM s_articles
            JOIN related_product_id ON related_product_id.product_id = s_articles.id
            JOIN s_articles_prices ON s_articles_prices.articleid = s_articles.id
            JOIN s_bundle ON related_product_id.bundle_id = s_bundle.id
            JOIN s_articles_details ON s_articles_details.articleid = s_articles.id
            JOIN s_articles_img ON s_articles.id = s_articles_img.articleid
            JOIN s_media ON s_articles_img.media_id = s_media.id
            GROUP BY s_articles.id;
        ";

        $products = Shopware()->Db()->query($sql)->fetchAll();

        /** @var MediaService $mediaService */
        $mediaService = Shopware()->Container()->get('shopware_media.media_service');

        /** @var Router $routerService */
        $routerService = Shopware()->Container()->get('router');

        foreach ($products as &$product) {
            $product['image'] = $mediaService->getUrl($product['image']);
            $product['url'] = $routerService->assemble([
                'module' => 'frontend',
                'controller' => 'detail',
                'sArticle' => $product['id']
            ]);
        }

        $this->Front()->Plugins()->ViewRenderer()->setNoRender();
        $this->Response()->setHeader('Content-type', 'application/json', true);
        $this->Response()->setBody(Zend_Json::encode($products));
    }

    public function basketAction()
    {
        $basket = Shopware()->Modules()->Basket()->sGetBasket();

        $this->Front()->Plugins()->ViewRenderer()->setNoRender();
        $this->Response()->setHeader('Content-type', 'application/json', true);
        $this->Response()->setBody(Zend_Json::encode($basket));
    }
}
