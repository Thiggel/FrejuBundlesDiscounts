<?php

namespace FrejuBundlesDiscounts\Models;

use Shopware\Components\Model\ModelEntity;
use Doctrine\ORM\Mapping as ORM;

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
    const BUNDLE_GRATIS = 'Gratis-Bundle';
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
    protected $mainProductID;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="Shopware\Models\Article\Article")
     * @ORM\JoinColumn(name="main_product_id", referencedColumnName="id")
     */
    protected $mainProduct;

    /**
     * @var integer $articleOneID
     *
     * @ORM\Column(type="integer")
     */
    private $articleOneID;

    /**
     * @var integer $articleTwoID
     *
     * @ORM\Column(type="integer")
     */
    private $articleTwoID;

    /**
     * @var integer $articleThreeID
     *
     * @ORM\Column(type="integer")
     */
    private $articleThreeID;

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

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $active
     */
    public function setActive($active)
    {
        $this->active = $active;
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
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;
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
     */
    public function setBundleType($bundleType)
    {
        if (!in_array($bundleType, array(self::BUNDLE_GRATIS, self::BUNDLE_SPAR, self::BUNDLE_KONFIG))) {
            throw new \InvalidArgumentException("Invalid bundle type");
        }
        $this->bundleType = $bundleType;
    }

    /**
     * @return int
     */
    public function getMainProduct() {
        return $this->mainProduct;
    }

    /**
     * @param $mainProduct
     */
    public function setMainProduct($mainProduct) {
        $this->mainProduct = $mainProduct;
    }
    /**
     * @return int
     */
    public function getBundleBonus() {
        return $this->bundleBonus;
    }

    /**
     * @param $bonus
     */
    public function setBundleBonus($bonus) {
        $this->bundleBonus = $bonus;
    }
}
