<?php

use FrejuBundlesDiscounts\Models\Bundle;

class Shopware_Controllers_Backend_FrejuBundles extends \Shopware_Controllers_Backend_Application
{
    protected $model = Bundle::class;
    protected $alias = 'bundle';

    protected function getListQuery()
    {
        $builder = parent::getListQuery();

        $builder->leftJoin('bundle.mainProduct', 'mainProduct');
        $builder->addSelect(array('mainProduct'));

        return $builder;
    }

    protected function getDetailQuery($id)
    {
        $builder = parent::getDetailQuery($id);

        $builder->leftJoin('bundle.mainProduct', 'mainProduct');

        $builder->addSelect(array('mainProduct'));

        return $builder;
    }
}
