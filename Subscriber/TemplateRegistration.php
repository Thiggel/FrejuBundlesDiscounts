<?php

namespace FrejuBundlesDiscounts\Subscriber;

use Enlight\Event\SubscriberInterface;
use FrejuBundlesDiscounts\Bundle\StoreFrontBundle\FreeAddArticlesService;

class TemplateRegistration implements SubscriberInterface
{
    /**
     * @var string
     */
    private $pluginDirectory;

    /**
     * @var \Enlight_Template_Manager
     */
    private $templateManager;

    /**
     * @var FreeAddArticlesService
     */
    private $freeAddArticlesService;

    /**
     * @param $pluginDirectory
     * @param \Enlight_Template_Manager $templateManager
     * @param FreeAddArticlesService $service
     */
    public function __construct($pluginDirectory, \Enlight_Template_Manager $templateManager, FreeAddArticlesService $service)
    {
        $this->pluginDirectory = $pluginDirectory;
        $this->templateManager = $templateManager;
        $this->freeAddArticlesService = $service;
    }
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PreDispatch' => 'onPreDispatch',
            'Shopware_Modules_Basket_UpdateArticle_FilterSqlDefaultParameters' => 'addDiscountsInCart'
        ];
    }

    public function onPreDispatch()
    {
        $this->templateManager->addTemplateDir($this->pluginDirectory . '/Resources/views');
    }

    /**
     * @param \Enlight_Event_EventArgs $args
     * @return mixed
     * @throws \Doctrine\DBAL\DBALException
     */
    public function addDiscountsInCart(\Enlight_Event_EventArgs $args) {
        $basketItem = $args->getReturn();
        $gross = &$basketItem[1];
        $net = &$basketItem[2];
        $id = $this->freeAddArticlesService->getProdIdByBasketId($basketItem[6]);
        $discounts = $this->freeAddArticlesService->getDiscounts();

        if(isset($discounts[$id])) {
            $gross = $this->freeAddArticlesService->convertToNum($discounts[$id]['payablePrice']);
            $net = $gross / 1.19;
        }

        return $basketItem;
    }
}