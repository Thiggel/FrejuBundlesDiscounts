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
            'Shopware_Modules_Basket_AddArticle_Start' => 'onAddArticle_Start',
            'Shopware_Modules_Order_SaveOrder_ProcessDetails' => 'onOrderCreated'
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

                $articleWithDiscount = $this->configuratorBundleDiscountApplierService->applyDiscountToProduct($article);
                $articleWithDiscount = $this->discountService->applyDiscountToProduct($articleWithDiscount);
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

    public function onOrderCreated(\Enlight_Event_EventArgs $eventArguments)
    {
        $details = $eventArguments->getDetails();
        $orderId = $eventArguments->get('orderId');
        $discount = 0;

        foreach($details as $item)
        {
            $orderNumber = $item['ordernumber'];
            $sql = "
                SELECT s_articles_prices.price
                FROM s_articles_details
                JOIN s_articles_prices ON s_articles_details.id = s_articles_prices.articledetailsID
                WHERE s_articles_details.ordernumber = '$orderNumber'
            ";

            try {
                $query = Shopware()->Db()->query($sql)->fetchAll()[0];
            } catch (\Zend_Db_Adapter_Exception $e) {
                Shopware()->PluginLogger()->error($e);
            } catch (\Zend_Db_Statement_Exception $e) {
                Shopware()->PluginLogger()->error($e);
            }

            $normalPrice = $query['price'] * (1 + $item['tax_rate'] / 100);
            if($normalPrice > 0)
            {
                $price = str_replace(',', '.', $item['price']);
                $itemDiscount = ($normalPrice  - $price) * $item['quantity'];

                if($itemDiscount > 0) $discount += round($itemDiscount, 2);
            }
        }

        $sql = "
            UPDATE s_order_attributes
            SET discount = '$discount'
            WHERE orderID = '$orderId'
        ";

        try {
            Shopware()->Db()->exec($sql);
        } catch (\Zend_Db_Adapter_Exception $e) {
            Shopware()->PluginLogger()->error($e);
        }
    }
}
