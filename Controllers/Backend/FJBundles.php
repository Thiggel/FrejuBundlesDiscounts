<?php

/**
 * Class Shopware_Controllers_Backend_FJBundles
 */
class Shopware_Controllers_Backend_FJBundles extends \Enlight_Controller_Action
{
    /**
     * @throws Exception
     */
    public function preDispatch()
    {
        $pluginPath = $this->container->getParameter('fj_product_bundles.plugin_dir');

        $this->get('template')->addTemplateDir($pluginPath . '/Resources/views/');
        $this->get('snippets')->addConfigDir($pluginPath . '/Resources/snippets/');
    }

    public function index()
    {

    }
}