<?php

/**
 * @noinspection PhpUnused
 */

declare(strict_types=1);

namespace Qunity\OnlineProduct\Plugin\Magento\Catalog\Model;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product as Target;
use Qunity\OnlineProduct\Model\Product\Type\Online as OnlineProduct;

class Product
{
    /**
     * Replace default attribute set ID for "Online" product type
     *
     * @param ProductInterface|Target $subject
     * @param callable $proceed
     *
     * @return int
     */
    public function aroundGetDefaultAttributeSetId(ProductInterface|Target $subject, callable $proceed): int
    {
        if ($subject->getTypeId() != OnlineProduct::TYPE_CODE) {
            return (int) $proceed();
        }

        /**
         * @var Target $subject
         * @var OnlineProduct $onlineProduct
         */

        $onlineProduct = $subject->getTypeInstance();
        $attributeSetId = $onlineProduct->getDefaultAttributeSetId();

        return $attributeSetId ?: (int) $proceed();
    }
}
