<?php

namespace FrejuBundlesDiscounts\Subscriber;

use Enlight\Event\SubscriberInterface;
use FrejuBundlesDiscounts\Bundle\StoreFrontBundle\ProductBundleCreatorService;
use FrejuBundlesDiscounts\Models\Bundle;

class BundleSubscriber implements SubscriberInterface
{
    private $productBundleCreatorService;

    public function __construct(ProductBundleCreatorService $productBundleCreatorService)
    {
        $this->productBundleCreatorService = $productBundleCreatorService;
    }

    public static function getSubscribedEvents()
    {
        return [
            Bundle::class . '::postPersist' => 'upsertBundle',
            Bundle::class . '::postUpdate' => 'upsertBundle',
        ];
    }

    public function upsertBundle(\Enlight_Event_EventArgs $arguments)
    {
        /** @var Bundle $bundle */
        $bundle = $arguments->getEntity();

        if ($bundle->getBundleType() != Bundle::BUNDLE_SPAR) {
            return;
        }

        $this->productBundleCreatorService->upsertProductBundle($bundle->getId());
    }
}
