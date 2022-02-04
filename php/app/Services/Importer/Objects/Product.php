<?php

namespace App\Services\Importer\Objects;

use Illuminate\Support\Collection;

/**
 * Class Product
 *
 * @package App\Services\Importer\Objects
 */
class Product
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string|null
     */
    private $body;

    /**
     * @var string|null
     */
    private $vendor;

    /**
     * @var string|null
     */
    private $productType;

    /**
     * @var Variant[]|Collection
     */
    private $variants = [];
    /**
     * @var string|null
     */
    private $sysCol;
    /**
     * @var
     */
    private $compText;
    /**
     * @var
     */
    private $season;

    /**
     * Product constructor.
     */
    public function __construct()
    {
        $this->variants = collect($this->variants);
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param string|null $body
     *
     * @return $this
     */
    public function setBody(?string $body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @param string|null $vendor
     *
     * @return $this
     */
    public function setVendor(?string $vendor): self
    {
        $this->vendor = $vendor;

        return $this;
    }

    /**
     * @param string $productType
     *
     * @return $this
     */
    public function setProductType(string $productType): self
    {
        $this->productType = $productType;

        return $this;
    }

    /**
     * @param string $sysCol
     *
     * @return $this
     */
    public function setSysCol($sysCol): self
    {
        $this->sysCol = $sysCol;
        return $this;
    }

    /**
     * @param string $compText
     *
     * @return $this
     */
    public function setCompText($compText): self
    {
        $this->compText = $compText;
        return $this;
    }

    /**
     * @param string $season
     *
     * @return $this
     */
    public function setSeason($season): self
    {
        $this->season = $season;
        return $this;
    }

    /**
     * @param Variant $variant
     *
     * @return $this
     */
    public function addVariant(Variant $variant): self
    {
        $this->variants->add($variant);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string|null
     */
    public function getBody(): ?string
    {
        return $this->body;
    }

    /**
     * @return string|null
     */
    public function getVendor(): ?string
    {
        return $this->vendor;
    }

    /**
     * @return string
     */
    public function getProductType(): string
    {
        return $this->productType;
    }

    /**
     * @return Variant[]|Collection
     */
    public function getVariants(): Collection
    {
        return $this->variants;
    }

    /**
     * @return string
     */
    public function getSysCol()
    {
        return $this->sysCol;
    }

    /**
     * @return string
     */
    public function getCompText()
    {
        return str_replace(',', ';', $this->compText);
    }

    /**
     * @return string
     */
    public function getSeason()
    {
        return $this->season;
    }
}
