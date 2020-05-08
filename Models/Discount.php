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
     * @var integer $active
     *
     * @ORM\Column(type="boolean")
     */
    private $active = false;

    /**
     * @var string $startDate
     *
     * @ORM\Column(type="string")
     */
    private $startDate = null;

    /**
     * @var string $endDate
     *
     * @ORM\Column(type="string")
     */
    private $endDate = null;

    /**
     * @var string $description
     *
     * @ORM\Column(type="string")
     */
    private $description;

    /**
     * @var string $badge
     *
     * @ORM\Column(type="string")
     */
    private $badge;

    /**
     * @var string $color
     *
     * @ORM\Column(type="string")
     */
    private $color;


    public function __construct()
    {
        $this->relatedDiscountedItems = new ArrayCollection();
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
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param $description
     * @return Discount
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getBadge() {
        return $this->badge;
    }

    /**
     * @param $badge
     * @return Discount
     */
    public function setBadge($badge) {
        $this->badge = $badge;

        return $this;
    }

    /**
     * @return string
     */
    public function getColor() {
        return $this->color;
    }

    /**
     * @param $color
     * @return Discount
     */
    public function setColor($color) {
        $this->color = $color;

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
    public function getRelatedDiscountedItems()
    {
        return $this->relatedDiscountedItems;
    }

    /**
     * @param ArrayCollection $relatedDiscountedItems
     * @return Discount
     */
    public function setRelatedDiscountedItems($relatedDiscountedItems)
    {
        $this->relatedDiscountedItems = $relatedDiscountedItems;

        return $this;
    }
}
