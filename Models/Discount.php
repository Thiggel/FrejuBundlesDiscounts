<?php

namespace FrejuBundlesDiscounts\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Shopware\Components\Model\ModelEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="s_discount")
 */
class Discount extends ModelEntity
{
    /**
     * @var integer $id
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Shopware\Models\Article\Article")
     * @ORM\JoinTable(name="discount_related_product_id",
     *      joinColumns={
     *          @ORM\JoinColumn(
     *              name="discount_id",
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
     * @var integer $active
     *
     * @ORM\Column(type="boolean")
     */
    private $active = false;

    /**
     * @var integer $discount_precalculated
     *
     * @ORM\Column(type="boolean")
     */
    private $discount_precalculated = false;

    /**
     * @var integer $cashback
     *
     * @ORM\Column(type="boolean")
     */
    private $cashback = false;

    /**
     * @var \DateTime $startDate
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private $startDate = null;

    /**
     * @var \DateTime $endDate
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private $endDate = null;


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
     * @param $name
     * @return Discount
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param int $active
     * @return Discount
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
     * @param int $discount_precalculated
     * @return Discount
     */
    public function setDiscountPrecalculated($discount_precalculated)
    {
        $this->discount_precalculated = $discount_precalculated;

        return $this;
    }

    /**
     * @return int
     */
    public function getDiscountPrecalculated()
    {
        return $this->discount_precalculated;
    }

    /**
     * @param int $cashback
     * @return Discount
     */
    public function setCashback($cashback)
    {
        $this->cashback = $cashback;

        return $this;
    }

    /**
     * @return int
     */
    public function getCashback()
    {
        return $this->cashback;
    }

    /**
     * @param \DateTime $startDate
     * @return Discount
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param \DateTime $endDate
     * @return Discount
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
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
     * @return Discount
     */
    public function setRelatedProducts($relatedProducts)
    {
        $this->relatedProducts = $relatedProducts;

        return $this;
    }
}
