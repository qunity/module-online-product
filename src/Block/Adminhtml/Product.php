<?php

declare(strict_types=1);

namespace Qunity\OnlineProduct\Block\Adminhtml;

use Magento\Catalog\Block\Adminhtml\Product as BaseProduct;
use Qunity\OnlineProduct\Model\Product\Type\Online as OnlineProduct;

/**
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
class Product extends BaseProduct
{
    /**
     * @inheritDoc
     */
    protected function _getProductCreateUrl($type): string
    {
        $product = $this->_productFactory->create();
        if ($type == OnlineProduct::TYPE_CODE) {
            $product->setTypeId(OnlineProduct::TYPE_CODE);
        }

        $attributeSetId = $product->getDefaultAttributeSetId();

        return $this->getUrl('catalog/*/new', ['set' => $attributeSetId, 'type' => $type]);
    }
}
