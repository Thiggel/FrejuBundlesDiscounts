<?php

use FrejuBundlesDiscounts\Models\Discount;

class Shopware_Controllers_Backend_FrejuDiscounts extends \Shopware_Controllers_Backend_Application
{
    protected $model = Discount::class;
    protected $alias = 'discount';

    protected function getAdditionalDetailData(array $data)
    {
        $data['relatedDiscountedItems'] = $this->getRelatedProducts($data['id']);
        return $data;
    }

    protected function getRelatedProducts($discountId)
    {
        $builder = $this->getManager()->createQueryBuilder();
        $builder->select(array('discounts', 'relatedDiscountedItems'))
            ->from(Discount::class, 'discounts')
            ->innerJoin('discounts.relatedDiscountedItems', 'relatedDiscountedItems')
            ->where('discounts.id = :id')
            ->setParameter('id', $discountId);

        $paginator = $this->getQueryPaginator($builder);

        $data = $paginator->getIterator()->current();

        return $data['relatedDiscountedItems'];
    }
}
