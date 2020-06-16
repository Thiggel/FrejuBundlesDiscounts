<?php

namespace FrejuBundlesDiscounts\Bundle\StoreFrontBundle;

use FrejuBundlesDiscounts\Bundle\StoreFrontBundle\DiscountService;
use FrejuBundlesDiscounts\Bundle\StoreFrontBundle\FreeAddArticlesService;
use FrejuBundlesDiscounts\Bundle\StoreFrontBundle\ProductBundleCreatorService;
use Shopware\Bundle\StoreFrontBundle\Service\ListProductServiceInterface;
use Shopware\Bundle\StoreFrontBundle\Struct;

class ListProductService implements ListProductServiceInterface
{

    /** @var ListProductServiceInterface The previously existing service */
    private $originalService;

    /** @var freeAddArticlesService */
    private $freeAddArticlesService;

    /** @var DiscountService */
    private $discountService;

    /** @var ProductBundleCreatorService */
    private $bundleCreatorService;

    public function __construct(ListProductServiceInterface $service, FreeAddArticlesService $freeAddArticlesService, DiscountService $discountService, ProductBundleCreatorService $bundleCreatorService)
    {
        $this->originalService = $service;
        $this->freeAddArticlesService = $freeAddArticlesService;
        $this->discountService = $discountService;
        $this->bundleCreatorService = $bundleCreatorService;
    }

    private function addDiscounts($product)
    {
        $freeAddArticles = $this->freeAddArticlesService->getList();
        $discounts = $this->discountService->getDiscounts();
        $bundles = $this->bundleCreatorService->getBundles();

        if(isset($freeAddArticles[$product->getId()])) {
            $attribute = new Struct\Attribute(['freeAddArticles' => $freeAddArticles[$product->getId()]]);
            $product->addAttribute('freeAddArticles', $attribute);
        }

        if(isset($discounts[$product->getId()])) {
            $attribute = new Struct\Attribute(['discounts' => $discounts[$product->getId()]]);
            $product->addAttribute('discounts', $attribute);
        }

        if(isset($bundles[$product->getId()])) {
            $attribute = new Struct\Attribute(['bundles' => $bundles[$product->getId()]]);
            $product->addAttribute('bundles', $attribute);

            $attribute = new Struct\Attribute(['bundle' => array_pop(array_reverse($bundles[$product->getId()]))['products'][$product->getId()]]);
            $product->addAttribute('bundle', $attribute);

            if($this->bundleCreatorService->bundleInBasket($product->getId())) {
                $attribute = new Struct\Attribute(['bundleInBasket' => 'true']);
                $product->addAttribute('bundleInBasket', $attribute);
            }
        }

        return $product;
    }


    public function getList(array $numbers, Struct\ProductContextInterface $context)
    {
        $coreProducts = $this->originalService->getList($numbers, $context);

        foreach($coreProducts as &$product)
            $product = $this->addDiscounts($product);

        return $coreProducts;
    }

    public function get($number, Struct\ProductContextInterface $context)
    {
        $product = $this->originalService->get($number, $context);

        return $this->addDiscounts($product);
    }
}
