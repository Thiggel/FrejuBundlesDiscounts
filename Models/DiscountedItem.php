<?php

namespace FrejuBundlesDiscounts\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Shopware\Components\Model\ModelEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="s_discounted_item")
 */
class DiscountedItem extends ModelEntity
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
     * @var
     *
     * @ORM\Column(name="main_product_id", type="integer")
     */
    protected $productId;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="Shopware\Models\Article\Article")
     * @ORM\JoinColumn(name="main_product_id", referencedColumnName="id")
     */
    protected $product;

    /**
     * @var integer $id
     *
     * @ORM\Column(type="integer")
     */
    private $discount;

    const DISCOUNT_PERCENT = '%';
    const DISCOUNT_EURO = '€';

    /**
     * @var character $discountType
     *
     * @ORM\Column(type="string", length=1, options={"fixed" = true})
     */
    private $discountType;



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
     * @return int
     */
    public function getProduct() {
        return $this->mainProduct;
    }

    /**
     * @param $product
     * @return DiscountedItem
     */
    public function setProduct($product) {
        $this->product = $product;

        return $this;
    }

    /**
     * @return int
     */
    public function getDiscount() {
        return $this->discount;
    }

    /**
     * @param $discount
     * @return DiscountedItem
     */
    public function setDiscount($discount) {
        $this->discount = $discount;

        return $this;
    }

    /**
     * @return string
     */
    public function getDiscountType()
    {
        return $this->discountType;
    }

    /**
     * @param $discountType
     * @return DiscountedItem
     */
    public function setDiscountType($discountType)
    {
        if (!in_array($discountType, array(self::DISCOUNT_PERCENT, self::DISCOUNT_EURO))) {
            throw new \InvalidArgumentException("Invalid discount type");
        }
        $this->discountType = $discountType;

        return $this;
    }
}
