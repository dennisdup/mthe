<?php

namespace App\Services\Export\DTO;

use App\Models\Item;
use App\Models\ItemText;
use App\Models\Store;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class TextDataTransferObject
 * @package App\Services\Export\DTO
 */
class TextDataTransferObject extends DataTransferObject
{
    /**
     * @var Store[]|Collection
     */
    private static $stores;

    /**
     * @param array $entity
     * @return \Illuminate\Database\Eloquent\Model|void
     */
    public function getEntity(array $entity)
    {
        /** @var ItemText $model */
        $model = parent::getEntity($entity);

        $stores = $this
            ->getStores()
            ->where('language', ItemText::LANGUAGES[$entity['language']]);

        /** @var Item|null $item */
        $item = Item::where('external_id', $entity['itemno'])->first();

        $stores->each(function (Store $store) use ($item, $model) {
            $this->assign($item, $store, clone $model);
        });
    }

    /**
     * @return Store[]|Collection
     */
    protected function getStores()
    {
        if (!$this::$stores) {
            $this::$stores = Store::all();
        }

        return $this::$stores;
    }

    /**
     * @param Item $item
     * @param Store $store
     * @param ItemText $itemText
     */
    protected function assign(Item $item, Store $store, ItemText $itemText): void
    {
        if (ItemText::where(['item_id' => $item->id, 'store_id' => $store->id])->exists()) {
            return;
        }

        $itemText->store()->associate($store);
        $item->storeItemText($store)->delete();

        $item->itemTexts()->save($itemText);
    }

}
