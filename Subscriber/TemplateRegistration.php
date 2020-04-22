<?php

namespace FrejuBundlesDiscounts\Subscriber;

use Enlight\Event\SubscriberInterface;
use FrejuBundlesDiscounts\Bundle\StoreFrontBundle\FreeAddArticlesService;
use FrejuBundlesDiscounts\Bundle\StoreFrontBundle\DiscountService;
use FrejuBundlesDiscounts\Models\Bundle;

class TemplateRegistration implements SubscriberInterface
{
    /** @var string */
    private $pluginDirectory;

    /** @var \Enlight_Template_Manager */
    private $templateManager;

    /** @var FreeAddArticlesService  */
    private $freeAddArticlesService;

    /** @var DiscountService */
    private $discountService;


    public function __construct($pluginDirectory, \Enlight_Template_Manager $templateManager, FreeAddArticlesService $freeAddArticlesService, DiscountService $discountService)
    {
        $this->pluginDirectory = $pluginDirectory;
        $this->templateManager = $templateManager;
        $this->freeAddArticlesService = $freeAddArticlesService;
        $this->discountService = $discountService;
    }


    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PreDispatch' => 'onPreDispatch',
            Bundle::class . '::postUpdate' => 'upsertBundle',
        ];
    }

    public function onPreDispatch()
    {
        $this->templateManager->addTemplateDir($this->pluginDirectory . '/Resources/views');
    }

    public function upsertBundle(\Enlight_Event_EventArgs $arguments)
    {
        /** @var Bundle $bundle */
        $bundle = $arguments->getEntity();

        if ($bundle->getBundleType() != Bundle::BUNDLE_SPAR) {
            return;
        }

        $this->freeAddArticlesService->upsertProductBundle($bundle->getId());
    }
}
