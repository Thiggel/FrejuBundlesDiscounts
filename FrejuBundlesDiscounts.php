<?php

namespace FrejuBundlesDiscounts;

use Doctrine\ORM\Tools\SchemaTool;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;
use Shopware\Components\Model\ModelManager;
use FrejuBundlesDiscounts\Models\Bundle;
use FrejuBundlesDiscounts\Models\Discount;
use FrejuBundlesDiscounts\Models\DiscountedItem;

class FrejuBundlesDiscounts extends Plugin
{
    /**
     * {@inheritdoc}
     */
    public function install(InstallContext $installContext)
    {
        $this->createDatabase();
    }

    /**
     * {@inheritdoc}
     */
    public function activate(ActivateContext $activateContext)
    {
        $activateContext->scheduleClearCache(InstallContext::CACHE_LIST_ALL);
    }

    /**
     * {@inheritdoc}
     */
    public function uninstall(UninstallContext $uninstallContext)
    {
        if (!$uninstallContext->keepUserData()) {
            $this->removeDatabase();
        }
    }

    private function createDatabase()
    {
        $modelManager = $this->container->get('models');
        $tool = new SchemaTool($modelManager);

        $classes = $this->getClasses($modelManager);

        try {
            $tool->dropSchema($classes);
        } catch(\Exception $e) {
            // ignore
        }

        $tool->createSchema($classes);
    }

    private function removeDatabase()
    {
        $modelManager = $this->container->get('models');
        $tool = new SchemaTool($modelManager);

        $classes = $this->getClasses($modelManager);

        $sql = "
            DROP TABLE discounted_item_id, related_product_id, discount_related_product_id, s_discount, s_discounted_item, s_bundle
        ";

        Shopware()->Db()->exec($sql);

        //$tool->dropSchema($classes);
    }

    /**
     * @param ModelManager $modelManager
     * @return array
     */
    private function getClasses(ModelManager $modelManager)
    {
        return [
            $modelManager->getClassMetadata(Bundle::class),
            $modelManager->getClassMetadata(Discount::class),
            $modelManager->getClassMetadata(DiscountedItem::class)
        ];
    }
}
