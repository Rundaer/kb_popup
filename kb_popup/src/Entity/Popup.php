<?php

namespace PrestaShop\Module\Kb_Popup\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="kb_popup")
 * @ORM\Entity()
 */
class Popup
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_lang;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_shop;

    /**
     * @ORM\Column(type="string")
     */
    private $text;

    /**
     * @ORM\Column(type="string")
     */
    private $link;

    /**
     * @ORM\Column(type="string")
     */
    private $image;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isImage;

    /**
     * @ORM\Column(type="integer")
     */
    private $idProduct;

    /**
     * @ORM\Column(type="integer")
     */
    private $idCategory;

    /**
     * @ORM\Column(type="string")
     */
    private $pageSelect;

     /**
     * @ORM\Column(type="string")
     */
    private $backgroundColor;

    /**
     * @ORM\Column(type="string")
     */
    private $startsAt;

    /**
     * @ORM\Column(type="string")
     */
    private $endsAt;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getId_lang(): ?int
    {
        return $this->id_lang;
    }

    public function setId_lang(int $id_lang): self
    {
        $this->id_lang = $id_lang;

        return $this;
    }

    public function getId_shop(): ?int
    {
        return $this->id_shop;
    }

    public function setId_shop(int $id_shop): self
    {
        $this->id_shop = $id_shop;

        return $this;
    }

    public function getText(): ? string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getLink(): ? string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    public function getIsImage(): ? bool
    {
        return $this->isImage;
    }

    public function setIsImage(bool $isImage): self
    {
        $this->isImage = $isImage;

        return $this;
    }

    public function getIdProduct(): ?int
    {
        return $this->idProduct;
    }

    public function setIdProduct(int $idProduct): self
    {
        $this->idProduct = $idProduct;

        return $this;
    }

    public function getIdCategory(): ?int
    {
        return $this->idCategory;
    }

    public function setIdCategory(int $idCategory): self
    {
        $this->idCategory = $idCategory;

        return $this;
    }

    public function getPageSelect(): ?string
    {
        return $this->pageSelect;
    }

    public function setPageSelect(string $pageSelect): self
    {
        $this->pageSelect = $pageSelect;

        return $this;
    }

    public function getBackgroundColor(): ?string
    {
        return $this->backgroundColor;
    }

    public function setBackgroundColor(string $backgroundColor): self
    {
        $this->backgroundColor = $backgroundColor;

        return $this;
    }

    public function getStartsAt(): ?string
    {
        return $this->startsAt;
    }

    public function setStartsAt(string $startsAt): self
    {
        $this->startsAt = $startsAt;

        return $this;
    }

    public function getEndsAt(): ?string
    {
        return $this->endsAt;
    }

    public function setEndsAt(string $endsAt): self
    {
        $this->endsAt = $endsAt;

        return $this;
    }
}

