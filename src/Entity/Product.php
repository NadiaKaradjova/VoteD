<?php

namespace App\Entity;



use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private $productNumber;

    /**
     * @ORM\ManyToOne(targetEntity="PharmaCompany", inversedBy="product")
     *
     */
    private $company;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $dose;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $doseUnit;

    /**
     * @ORM\Column(type="string")
     */
    private $desctription;

    /**
     * @ORM\Column(type="string")
     *  @Groups({"test"})
     */
    private $longDesctription;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $shortNamePart;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $longNamePart;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $form;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $trade;

    /**
     * @ORM\OneToMany(targetEntity="Article", mappedBy="product", cascade={"remove"})
     */
    private $articles;

    /**
     * @ORM\Column(type="string", length=14000, nullable=true)
     */
    private $other;

    public function __construct(int $productNumber)
    {
        $this->articles = new ArrayCollection();
        $this->productNumber = $productNumber;
    }


    public function getProductNumber(): ?int
    {
        return $this->productNumber;
    }


    public function getDose(): ?string
    {
        return $this->dose;
    }

    public function setDose(?string $dose): self
    {
        $this->dose = $dose;

        return $this;
    }

    public function getDoseUnit(): ?string
    {
        return $this->doseUnit;
    }

    public function setDoseUnit(?string $doseUnit): self
    {
        $this->doseUnit = $doseUnit;

        return $this;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function setCompany(?PharmaCompany $company): void
    {
        $this->company = $company;
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
    public function getLongDesctription()
    {
        return $this->longDesctription;
    }

    /**
     * @param mixed $longDesctription
     */
    public function setLongDesctription($longDesctription): void
    {
        $this->longDesctription = $longDesctription;
    }

    /**
     * @return mixed
     */
    public function getShortNamePart()
    {
        return $this->shortNamePart;
    }

    /**
     * @param mixed $shortNamePart
     */
    public function setShortNamePart($shortNamePart): void
    {
        $this->shortNamePart = $shortNamePart;
    }

    /**
     * @return mixed
     */
    public function getLongNamePart()
    {
        return $this->longNamePart;
    }

    /**
     * @param mixed $longNamePart
     */
    public function setLongNamePart($longNamePart): void
    {
        $this->longNamePart = $longNamePart;
    }

    /**
     * @return mixed
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param mixed $form
     */
    public function setForm($form): void
    {
        $this->form = $form;
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

    /**
     * @return mixed
     */
    public function getTrade()
    {
        return $this->trade;
    }

    /**
     * @param mixed $trade
     */
    public function setTrade($trade): void
    {
        $this->trade = $trade;
    }

    /**
     * @return mixed
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * @param mixed $articles
     */
    public function setArticles($articles): void
    {
        $this->articles = $articles;
    }

}
