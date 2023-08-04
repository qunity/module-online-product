<?php

declare(strict_types=1);

namespace Qunity\OnlineProduct\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Qunity\Base\Component\ArrayManager;
use Qunity\OnlineProduct\Model\Product\Type\Online as OnlineProduct;

class Quantity extends AbstractModifier
{
    private const
        DATA_QUANTITY_KEY = ['product', '*' => ['quantity_and_stock_status', 'qty']],
        DATA_MANAGE_STOCK_KEY = ['product', '*' => ['stock_data', 'manage_stock']],
        DATA_USE_CONFIG_MANAGE_STOCK_KEY = ['product', '*' => ['stock_data', 'use_config_manage_stock']],
        DATA_ENABLE_QTY_INC_KEY = ['product', '*' => ['stock_data', 'enable_qty_increments']],
        DATA_USE_CONFIG_ENABLE_QTY_INC_KEY = ['product', '*' => ['stock_data', 'use_config_enable_qty_inc']],
        DATA_MIN_SALE_QTY_KEY = ['product', '*' => ['stock_data', 'min_sale_qty']],
        DATA_USE_CONFIG_MIN_SALE_QTY_KEY = ['product', '*' => ['stock_data', 'use_config_min_sale_qty']],
        DATA_MAX_SALE_QTY_KEY = ['product', '*' => ['stock_data', 'max_sale_qty']],
        DATA_USE_CONFIG_MAX_SALE_QTY_KEY = ['product', '*' => ['stock_data', 'use_config_max_sale_qty']];

    private const
        META_ADVANCED_INVENTORY_MODAL_DISABLED_KEY =
            ['advanced_inventory_modal', '*' => ['children', 'stock_data', 'arguments', 'data', 'config', 'disabled']],
        META_QUANTITY_AND_STOCK_STATUS_QTY_DISABLED_KEY =
            ['quantity_and_stock_status_qty', '*' => ['children', 'qty', 'arguments', 'data', 'config', 'disabled']],
        META_ADVANCED_INVENTORY_BUTTON_DISABLED_KEY =
            ['advanced_inventory_button', '*' => ['arguments', 'data', 'config', 'disabled']],
        META_QUANTITY_AND_STOCK_STATUS_QTY_VISIBLE_KEY =
            ['quantity_and_stock_status_qty', '*' => ['arguments', 'data', 'config', 'visible']];

    /**
     * @var LocatorInterface
     */
    private LocatorInterface $locator;

    /**
     * @var ArrayManager
     */
    private ArrayManager $arrayManager;

    /**
     * @param LocatorInterface $locator
     * @param ArrayManager $arrayManager
     */
    public function __construct(LocatorInterface $locator, ArrayManager $arrayManager)
    {
        $this->locator = $locator;
        $this->arrayManager = $arrayManager;
    }

    /**
     * @inheritDoc
     */
    public function modifyData(array $data): array
    {
        if ($this->locator->getProduct()->getTypeId() != OnlineProduct::TYPE_CODE) {
            return $data;
        }

        $this->arrayManager->set(self::DATA_QUANTITY_KEY, $data, null);

        $this->arrayManager->set(self::DATA_MANAGE_STOCK_KEY, $data, '0');
        $this->arrayManager->set(self::DATA_USE_CONFIG_MANAGE_STOCK_KEY, $data, '0');

        $this->arrayManager->set(self::DATA_ENABLE_QTY_INC_KEY, $data, '0');
        $this->arrayManager->set(self::DATA_USE_CONFIG_ENABLE_QTY_INC_KEY, $data, '0');

        $this->arrayManager->set(self::DATA_MIN_SALE_QTY_KEY, $data, 1);
        $this->arrayManager->set(self::DATA_USE_CONFIG_MIN_SALE_QTY_KEY, $data, '0');

        $this->arrayManager->set(self::DATA_MAX_SALE_QTY_KEY, $data, 1);
        $this->arrayManager->set(self::DATA_USE_CONFIG_MAX_SALE_QTY_KEY, $data, '0');

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function modifyMeta(array $meta): array
    {
        if ($this->locator->getProduct()->getTypeId() != OnlineProduct::TYPE_CODE) {
            return $meta;
        }

        $this->arrayManager->set(self::META_QUANTITY_AND_STOCK_STATUS_QTY_DISABLED_KEY, $meta, true);
        $this->arrayManager->set(self::META_ADVANCED_INVENTORY_BUTTON_DISABLED_KEY, $meta, true);
        $this->arrayManager->set(self::META_ADVANCED_INVENTORY_MODAL_DISABLED_KEY, $meta, true);

        $this->arrayManager->set(self::META_QUANTITY_AND_STOCK_STATUS_QTY_VISIBLE_KEY, $meta, false);

        return $meta;
    }
}
