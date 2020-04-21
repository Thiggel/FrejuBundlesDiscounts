<?php

namespace FrejuBundlesDiscounts\Subscriber;

use FrejuBundlesDiscounts\Bundle\StoreFrontBundle\ConfiguratorBundleDiscountApplierService;
use Enlight\Event\SubscriberInterface;

class BasketSubscriber implements SubscriberInterface
{
    /** @var ConfiguratorBundleDiscountApplierService */
    private $configuratorBundleDiscountApplierService;

    public function __construct(ConfiguratorBundleDiscountApplierService $configuratorBundleDiscountApplierService)
    {
        $this->configuratorBundleDiscountApplierService = $configuratorBundleDiscountApplierService;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'Shopware_Modules_Basket_GetBasket_FilterItemStart' => 'onGetBasket_FilterItemStart',
        ];
    }

    public function onGetBasket_FilterItemStart(\Enlight_Event_EventArgs $eventArguments)
    {
        return $this->configuratorBundleDiscountApplierService->applyDiscountToProduct($eventArguments->getReturn());
    }
}
