<?php

declare(strict_types=1);

namespace Qunity\OnlineProduct\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Qunity\Base\Component\ArrayManager;
use Qunity\OnlineProduct\Model\Product\Type\Online as OnlineProduct;

class AttributeSet extends AbstractModifier
{
    private const DATA_ATTRIBUTE_SET_ID_KEY = 'attribute_set_id';

    private const
        META_ATTRIBUTE_SET_ID_DISABLED_KEY =
            ['attribute_set_id', '*' => ['arguments', 'data', 'config', 'disabled']],
        META_ATTRIBUTE_SET_ID_OPTION_GET_KEY =
            ['attribute_set_id', '*' => ['arguments', 'data', 'config', 'options']];

    /**
     * @var LocatorInterface
     */
    private LocatorInterface $locator;

    /**
     * @var ArrayManager
     */
    private ArrayManager $arrayManager;

    /**
     * @var int
     */
    private int $onlineAttributeSetId;

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
        /** @var Product $product */
        $product = $this->locator->getProduct();
        if ($product->getTypeId() != OnlineProduct::TYPE_CODE) {
            return $data;
        }

        $this->arrayManager->set(self::DATA_ATTRIBUTE_SET_ID_KEY, $data, $this->getOnlineAttributeSetId());

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function modifyMeta(array $meta): array
    {
        if ($this->locator->getProduct()->getTypeId() != OnlineProduct::TYPE_CODE) {
            $attributeSetId = $this->getOnlineAttributeSetId();
            $this->arrayManager->remove($this->getAttributeSetOptionKey($attributeSetId, $meta), $meta);

            return $meta;
        }

        $this->arrayManager->set(self::META_ATTRIBUTE_SET_ID_DISABLED_KEY, $meta, true);

        return $meta;
    }

    /**
     * Get "Online" attribute set ID
     *
     * @return int
     */
    private function getOnlineAttributeSetId(): int
    {
        if (!isset($this->onlineAttributeSetId)) {
            /** @var Product $product */
            $product = (clone $this->locator->getProduct())->setTypeId(OnlineProduct::TYPE_CODE);
            $this->onlineAttributeSetId = $product->getDefaultAttributeSetId();
        }

        return $this->onlineAttributeSetId;
    }

    /**
     * Get attribute set option key by attribute set ID
     *
     * @param int $attributeSetId
     * @param array $array
     *
     * @return array
     */
    private function getAttributeSetOptionKey(int $attributeSetId, array $array): array
    {
        return array_merge_recursive(self::META_ATTRIBUTE_SET_ID_OPTION_GET_KEY, [
            '*' => array_key_last(array_filter(
                $this->arrayManager->get(self::META_ATTRIBUTE_SET_ID_OPTION_GET_KEY, $array),
                fn (array $value): bool => $value['value'] == $attributeSetId,
            ))
        ]);
    }
}
