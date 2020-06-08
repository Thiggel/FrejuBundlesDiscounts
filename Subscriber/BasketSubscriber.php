<?php

namespace FrejuBundlesDiscounts\Subscriber;

use FrejuBundlesDiscounts\Bundle\StoreFrontBundle\ConfiguratorBundleDiscountApplierService;
use FrejuBundlesDiscounts\Bundle\StoreFrontBundle\DiscountService;
use Enlight\Event\SubscriberInterface;
use FrejuBundlesDiscounts\Bundle\StoreFrontBundle\FreeAddArticlesService;
use FrejuBundlesDiscounts\Bundle\StoreFrontBundle\ProductBundleCreatorService;

class BasketSubscriber implements SubscriberInterface
{
    /** @var ConfiguratorBundleDiscountApplierService */
    private $configuratorBundleDiscountApplierService;

    /** @var DiscountService */
    private $discountService;

    /** @var ProductBundleCreatorService */
    private $productBundleCreatorService;

    /** @var FreeAddArticlesService */
    private $freeAddArticlesService;


    public function __construct(ConfiguratorBundleDiscountApplierService $configuratorBundleDiscountApplierService, DiscountService $discountService, ProductBundleCreatorService $productBundleCreatorService, FreeAddArticlesService $freeAddArticlesService)
    {
        $this->configuratorBundleDiscountApplierService = $configuratorBundleDiscountApplierService;
        $this->discountService = $discountService;
        $this->productBundleCreatorService = $productBundleCreatorService;
        $this->freeAddArticlesService = $freeAddArticlesService;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'Shopware_Modules_Basket_GetBasket_FilterItemStart' => 'onGetBasket_FilterItemStart',
            'Shopware_Modules_Basket_AddArticle_Start' => 'onAddArticle_Start'
        ];
    }

    public function onGetBasket_FilterItemStart(\Enlight_Event_EventArgs $eventArguments)
    {
        $article = $eventArguments->getReturn();
        $isConfBundled = $this->configuratorBundleDiscountApplierService->isBundled($article);

        $isFree = false;
        $articleWithDiscount = $this->freeAddArticlesService->discountFreeArticle($article, $isFree);

        if(!$isFree)
        {
            $isBundled = false;
            $articleWithDiscount = $this->productBundleCreatorService->applyDiscountToProduct($article, $isBundled);

            if(!$isBundled)
            {
                if($isConfBundled)
                {
                    $articleWithDiscount = $this->configuratorBundleDiscountApplierService->applyDiscountToProduct($article);
                }
                else
                {
                    $articleWithDiscount = $this->discountService->applyDiscountToProduct($article);
                }
            }
        }

        return $articleWithDiscount;
    }

    public function onAddArticle_Start(\Enlight_Event_EventArgs $eventArguments)
    {
        $orderNumber = $eventArguments->getId();
        $quantity = $eventArguments->getQuantity();
        $freeArticles = $this->freeAddArticlesService->getFreeProducts();

        $sql = "
            SELECT s_articles.id FROM s_articles
            INNER JOIN s_articles_details ON s_articles_details.articleid = s_articles.id
            WHERE s_articles_details.ordernumber = '$orderNumber'
        ";

        $id = Shopware()->Db()->query($sql)->fetchAll()[0]['id'];

        foreach($freeArticles[$id] as $product)
        {
            Shopware()->Modules()->Basket()->sAddArticle($product['ordernumber'], $quantity);
        }
    }
}
