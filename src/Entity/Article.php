<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

use Doctrine\ORM\Mapping\JoinColumn;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
{

    /**
     * @ORM\Id()
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

//    /**
//     * @ORM\Column(type="integer")
//     */
//    private $productNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"articleList"})
     */
    private $qty;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="articles")
     * @JoinColumn(name="product_id", referencedColumnName="product_number")
     */
    private $product;

    /**
     * @ORM\Column(type="string")
     * @Groups({"articleList"})
     */
    private $desctription;

    /**
     * @ORM\Column(type="string")
     * @Groups({"articleList"})
     */
    private $unit;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdOn;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateOn;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=14000, nullable=true)
     */
    private $other;

    public function __construct()
    {
        $this->createdOn = new \DateTime();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $pharmaCode): self
    {
        $this->id = $pharmaCode;

        return $this;
    }

    public function getQty(): ?string
    {
        return $this->qty;
    }

    public function setQty(?string $qty): self
    {
        $this->qty = $qty;

        return $this;
    }

    public function getProduct()
    {
        return $this->product;
    }

    public function setProduct($product): void
    {
        $this->product = $product;
    }

    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    public function getUpdateOn()
    {
        return $this->updateOn;
    }

    public function setUpdateOn($updateOn): void
    {
        $this->updateOn = $updateOn;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type): void
    {
        $this->type = $type;
    }

    public function getDefaultLocale()
    {
        return 'de';
    }

    /**
     * @return mixed
     */
    public function getDesctription()
    {
        return $this->desctription;
    }

    /**
     * @param mixed $desctription
     */
    public function setDesctription($desctription): void
    {
        $this->desctription = $desctription;
    }

    /**
     * @return mixed
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @param mixed $unit
     */
    public function setUnit($unit): void
    {
        $this->unit = $unit;
    }

    /**
     * @return mixed
     */
    public function getOther()
    {
        return $this->other;
    }

    /**
     * @param mixed $other
     */
    public function setOther($other): void
    {
        if (strlen($other) > 14000){
            $this->other = substr($other, 0, 14000);
        }else{
            $this->other = $other;
        }

    }

}
