<?php

namespace FrejuBundlesDiscounts\Bundle\StoreFrontBundle;

use Doctrine\ORM\EntityRepository;
use FrejuBundlesDiscounts\Models\Bundle;
use phpDocumentor\Reflection\Types\Boolean;
use Shopware\Components\Model\ModelManager;
use Shopware\Models\Article\Article;

class ConfiguratorBundleDiscountApplierService
{
    /** @var EntityRepository */
    private $bundleRepository;

    public function __construct(ModelManager $modelManager)
    {
        $this->bundleRepository = $modelManager->getRepository(Bundle::class);
    }

    public function getBundles(array $article): array
    {
        /** @var Bundle[] $bundles */
        $allBundles = $this->bundleRepository->findBy(
            [
                'bundleType' => Bundle::BUNDLE_KONFIG,
            ]
        );

        $bundlesWithArticleInCart = array_filter(
            $allBundles,
            function (Bundle $bundle) use ($article) {
                if ($bundle->getMainProduct()->getId() === (int)$article['articleID']) {
                    return true;
                }

                $relatedProductIds = array_map(
                    function (Article $article) {
                        return $article->getId();
                    },
                    $bundle->getRelatedProducts()->toArray()
                );

                return in_array((int)$article['articleID'], $relatedProductIds);
            }
        );

        return $bundlesWithArticleInCart;
    }

    public function isBundled(array $article): bool
    {
        $bundlesWithArticleInCart = $this->getBundles($article);

        return is_array($bundlesWithArticleInCart) && count($bundlesWithArticleInCart) !== 0;
    }

    public function applyDiscountToProduct(array $article): array
    {
        $bundlesWithArticleInCart = $this->getBundles($article);

        foreach ($bundlesWithArticleInCart as $bundle) {
            $article['netprice'] = $article['netprice'] * (1 - $bundle->getBundleBonus() / 100);
            $article['price'] = $article['netprice'] * (1 + $article['tax_rate'] / 100);
        }

        return $article;
    }
}
