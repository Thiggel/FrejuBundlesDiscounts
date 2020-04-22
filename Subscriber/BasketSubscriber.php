<?php

namespace FrejuBundlesDiscounts\Subscriber;

use FrejuBundlesDiscounts\Bundle\StoreFrontBundle\ConfiguratorBundleDiscountApplierService;
use FrejuBundlesDiscounts\Bundle\StoreFrontBundle\DiscountService;
use Enlight\Event\SubscriberInterface;

class BasketSubscriber implements SubscriberInterface
{
    /** @var ConfiguratorBundleDiscountApplierService */
    private $configuratorBundleDiscountApplierService;

    /** @var DiscountService */
    private $discountService;

    public function __construct(ConfiguratorBundleDiscountApplierService $configuratorBundleDiscountApplierService, DiscountService $discountService)
    {
        $this->configuratorBundleDiscountApplierService = $configuratorBundleDiscountApplierService;
        $this->discountService = $discountService;
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

        return $articleWithBundleDiscount;
    }
}
