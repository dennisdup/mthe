<?php

namespace App\Services\Importer\Objects;

use Illuminate\Support\Collection;

/**
 * Class Variant
 * @package App\Services\Importer\Objects
 */
class Variant
{
    /**
     * @var string|null
     */
    private $id;

    /**
     * @var Option[]|Collection
     */
    private $options = [];

    /**
     * @var string
     */
    private $price;

    /**
     * @var string
     */
    private $count;

    /**
     * @var string
     */
    private $sku;

    /**
     * @var string
     */
    private $ean;

    private $inventory_item_id;

    /**
     * Variant constructor.
     */
    public function __construct()
    {
        $this->options = collect($this->options);
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId(string $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param string $price
     * @return $this
     */
    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @param Option $option
     * @return $this
     */
    public function addOption(Option $option): self
    {
        if (!$this->options->has($option->getName())) {
            $this->options->put($option->getName(), $option);
        }

        return $this;
    }

    /**
     * @param string $count
     * @return $this
     */
    public function setCount(string $count): self
    {
        $this->count = $count;

        return $this;
    }

    /**
     * @param string $sku
     * @return $this
     */
    public function setSku(string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * @param string $ean
     * @return $this
     */
    public function setEan(string $ean): self
    {
        $this->ean = $ean;

        return $this;
    }

    /**
     * @param string $inventory_item_id
     *
     * @return $this
     */
    public function setInventoryLevel(string $inventory_item_id): self
    {
        $this->inventory_item_id = $inventory_item_id;

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
     * @return Collection
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    /**
     * @return string
     */
    public function getPrice(): string
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getCount(): string
    {
        return $this->count;
    }

    /**
     * @return string
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * @return string
     */
    public function getEan(): string
    {
        return $this->ean;
    }

    /**
     * @return string
     */
    public function getInventoryLevel()
    {
        return $this->inventory_item_id;
    }
}
