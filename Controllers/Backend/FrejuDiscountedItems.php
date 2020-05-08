<?php

use FrejuBundlesDiscounts\Models\DiscountedItem;

class Shopware_Controllers_Backend_FrejuDiscountedItems extends \Shopware_Controllers_Backend_Application
{
    protected $model = DiscountedItem::class;
    protected $alias = 'discountedItem';

    protected function getListQuery()
    {
        $builder = parent::getListQuery();

        $builder->leftJoin('discountedItem.product', 'product');

        $builder->addSelect(array('product'));

        return $builder;
    }

    protected function getDetailQuery($id)
    {
        $builder = parent::getDetailQuery($id);

        $builder->leftJoin('discountedItem.product', 'product');

        $builder->addSelect(array('product'));

        return $builder;
    }
}
