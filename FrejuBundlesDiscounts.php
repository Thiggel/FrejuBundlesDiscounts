<?php

namespace FrejuBundlesDiscounts;

use Doctrine\ORM\Tools\SchemaTool;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;
use Shopware\Components\Model\ModelManager;
use FrejuBundlesDiscounts\Models\Bundle;
use FrejuBundlesDiscounts\Models\BundleType;

class FrejuBundlesDiscounts extends Plugin
{
    /**
     * {@inheritdoc}
     */
    public function install(InstallContext $installContext)
    {
        $this->createDatabase();

        $this->addDbData();
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

        $tool->createSchema($classes);
    }

    private function removeDatabase()
    {
        $modelManager = $this->container->get('models');
        $tool = new SchemaTool($modelManager);

        $classes = $this->getClasses($modelManager);

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
            $modelManager->getClassMetadata(BundleType::class)
        ];
    }

    private function addDbData()
    {
        $connection = $this->container->get('dbal_connection');

        $sql = "INSERT IGNORE INTO s_bundle_type (name)
            VALUES ('Spar-Bundle'), ('Gratisartikel-Bundle'), ('Konfigurator-Rabatt')
        ";

        $connection->exec($sql);
    }
}