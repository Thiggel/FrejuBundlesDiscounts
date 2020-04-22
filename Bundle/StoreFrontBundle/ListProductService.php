<?php

namespace FrejuBundlesDiscounts\Bundle\StoreFrontBundle;

use FrejuBundlesDiscounts\Bundle\StoreFrontBundle\DiscountService;
use FrejuBundlesDiscounts\Bundle\StoreFrontBundle\FreeAddArticlesService;
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

    public function __construct(ListProductServiceInterface $service, FreeAddArticlesService $freeAddArticlesService, DiscountService $discountService)
    {
        $this->originalService = $service;
        $this->freeAddArticlesService = $freeAddArticlesService;
        $this->discountService = $discountService;
    }

    private function addDiscounts($product)
    {
        $freeAddArticles = $this->discountService->getList();
        $discounts = $this->discountService->getDiscounts();

        if(isset($freeAddArticles[$product->getId()])) {
            $attribute = new Struct\Attribute(['freeAddArticles' => $freeAddArticles[$product->getId()]]);
            $product->addAttribute('freeAddArticles', $attribute);
        }

        if(isset($discounts[$product->getId()])) {
            $attribute = new Struct\Attribute(['discounts' => $discounts[$product->getId()]]);
            $product->addAttribute('discounts', $attribute);
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
