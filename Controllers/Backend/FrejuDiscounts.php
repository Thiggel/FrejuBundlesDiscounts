<?php

use FrejuBundlesDiscounts\Models\Discount;

class Shopware_Controllers_Backend_FrejuDiscounts extends \Shopware_Controllers_Backend_Application
{
    protected $model = Discount::class;
    protected $alias = 'discount';
}
