<?php

namespace FrejuBundlesDiscounts\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Shopware\Components\Model\ModelEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * @ORM\Entity
 * @ORM\Table(name="s_bundle")
 */
class Bundle extends ModelEntity
{
    /**
     * @var integer $id
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    const BUNDLE_SPAR = 'Spar-Bundle';
    const BUNDLE_GRATIS = 'Gratisartikel-Bundle';
    const BUNDLE_KONFIG = 'Konfigurator-Rabatt';

    /**
     * @var string $bundleType
     *
     * @ORM\Column(type="string")
     */
    private $bundleType;

    /**
     * @var
     *
     * @ORM\Column(name="main_product_id", type="integer")
     */
    protected $mainProductId;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="Shopware\Models\Article\Article")
     * @ORM\JoinColumn(name="main_product_id", referencedColumnName="id")
     */
    protected $mainProduct;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Shopware\Models\Article\Article")
     * @ORM\JoinTable(name="related_product_id",
     *      joinColumns={
     *          @ORM\JoinColumn(
     *              name="bundle_id",
     *              referencedColumnName="id"
     *          )
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(
     *              name="product_id",
     *              referencedColumnName="id"
     *          )
     *      }
     * )
     */
    protected $relatedProducts;

    /**
     * @var integer $bundleBonus
     *
     * @ORM\Column(type="integer")
     */
    private $bundleBonus;

    /**
     * @var integer $active
     *
     * @ORM\Column(type="boolean")
     */
    private $active = false;

    /**
     * @var \DateTime $added
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private $createDate = null;


    public function __construct()
    {
        $this->relatedProducts = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param int $active
     * @return Bundle
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return int
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param \DateTime $createDate
     * @return Bundle
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * @return string
     */
    public function getBundleType()
    {
        return $this->bundleType;
    }

    /**
     * @param $bundleType
     * @return Bundle
     */
    public function setBundleType($bundleType)
    {
        if (!in_array($bundleType, array(self::BUNDLE_GRATIS, self::BUNDLE_SPAR, self::BUNDLE_KONFIG))) {
            throw new \InvalidArgumentException("Invalid bundle type");
        }
        $this->bundleType = $bundleType;

        return $this;
    }

    /**
     * @return int
     */
    public function getMainProduct() {
        return $this->mainProduct;
    }

    /**
     * @param $mainProduct
     * @return Bundle
     */
    public function setMainProduct($mainProduct) {
        $this->mainProduct = $mainProduct;

        return $this;
    }

    /**
     * @return int
     */
    public function getBundleBonus() {
        return $this->bundleBonus;
    }

    /**
     * @param $bonus
     * @return Bundle
     */
    public function setBundleBonus($bonus) {
        $this->bundleBonus = $bonus;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getRelatedProducts()
    {
        return $this->relatedProducts;
    }

    /**
     * @param ArrayCollection $relatedProducts
     * @return Bundle
     */
    public function setRelatedProducts($relatedProducts)
    {
        $this->relatedProducts = $relatedProducts;

        return $this;
    }

    /**
     * @param LifecycleEventArgs $arguments
     */
    public function postPersist(LifecycleEventArgs $arguments)
    {
        /** @var ModelManager $modelManager */
        $modelManager = $arguments->getEntityManager();

        $model = $arguments->getEntity();

        $bundleType = $model->getBundleType();
        $id = $model->getId();

        if($bundleType!= BUNDLE_SPAR)
            return;

        Shopware()->Events()->notify("FrejuBundlesDiscounts_SparBundle_Create", ['id' => $id]);
    }
}
