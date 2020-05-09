<?php

namespace FrejuBundlesDiscounts\Subscriber;

use FrejuBundlesDiscounts\Bundle\StoreFrontBundle\ConfiguratorBundleDiscountApplierService;
use FrejuBundlesDiscounts\Bundle\StoreFrontBundle\DiscountService;
use Enlight\Event\SubscriberInterface;
use FrejuBundlesDiscounts\Bundle\StoreFrontBundle\ProductBundleCreatorService;

class BasketSubscriber implements SubscriberInterface
{
    /** @var ConfiguratorBundleDiscountApplierService */
    private $configuratorBundleDiscountApplierService;

    /** @var DiscountService */
    private $discountService;

    /** @var ProductBundleCreatorService */
    private $productBundleCreatorService;


    public function __construct(ConfiguratorBundleDiscountApplierService $configuratorBundleDiscountApplierService, DiscountService $discountService, ProductBundleCreatorService $productBundleCreatorService)
    {
        $this->configuratorBundleDiscountApplierService = $configuratorBundleDiscountApplierService;
        $this->discountService = $discountService;
        $this->productBundleCreatorService = $productBundleCreatorService;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'Shopware_Modules_Basket_GetBasket_FilterItemStart' => 'onGetBasket_FilterItemStart',
        ];
    }

    public function onGetBasket_FilterItemStart(\Enlight_Event_EventArgs $eventArguments)
    {
        $article = $eventArguments->getReturn();
        $articleWithDiscount = $this->discountService->applyDiscountToProduct($article);
        $articleWithBundleDiscount = $this->configuratorBundleDiscountApplierService->applyDiscountToProduct($articleWithDiscount);
        $articleWithSparBundleDiscount = $this->productBundleCreatorService->applyDiscountToProduct($articleWithBundleDiscount);

        return $articleWithSparBundleDiscount;
    }
}
