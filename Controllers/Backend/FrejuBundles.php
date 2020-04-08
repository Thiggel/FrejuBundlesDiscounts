<?php

use FrejuBundlesDiscounts\Models\Bundle;

class Shopware_Controllers_Backend_FrejuBundles extends \Shopware_Controllers_Backend_Application
{
    protected $model = Bundle::class;
    protected $alias = 'bundle';

    protected function getListQuery()
    {
        $builder = parent::getListQuery();

        $builder->leftJoin('bundle.bundleType', 'bundleType')
                ->leftJoin('bundle.mainProduct', 'mainProduct');

        $builder->addSelect(array('mainProduct', 'bundleType'));

        return $builder;
    }

    protected function getDetailQuery($id)
    {
        $builder = parent::getDetailQuery($id);

        $builder->leftJoin('bundle.bundleType', 'bundleType')
                ->leftJoin('bundle.mainProduct', 'mainProduct');

        $builder->addSelect(array('mainProduct', 'bundleType'));

        return $builder;
    }

    protected function getAdditionalDetailData(array $data)
    {
        $data['relatedProducts'] = $this->getRelatedProducts($data['id']);
        return $data;
    }

    protected function getRelatedProducts($bundleId)
    {
        $builder = $this->getManager()->createQueryBuilder();
        $builder->select(array('bundles', 'relatedProducts'))
            ->from(Bundle::class, 'bundles')
            ->innerJoin('bundles.relatedProducts', 'relatedProducts')
            ->where('bundles.id = :id')
            ->setParameter('id', $bundleId);

        $paginator = $this->getQueryPaginator($builder);

        $data = $paginator->getIterator()->current();

        return $data['relatedProducts'];
    }
}
