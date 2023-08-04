<?php

declare(strict_types=1);

namespace Qunity\OnlineProduct\Ui\DataProvider\Product\Listing\Modifier;

use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Qunity\OnlineProduct\Model\Product\Type\Online as OnlineProduct;

class Quantity implements ModifierInterface
{
    /**
     * @inheritDoc
     */
    public function modifyData(array $data): array
    {
        if (!isset($data['items'])) {
            return $data;
        }

        foreach ($data['items'] as &$item) {
            if (!isset($item['type_id']) || $item['type_id'] != OnlineProduct::TYPE_CODE) {
                continue;
            }

            $item['qty'] = null;
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function modifyMeta(array $meta): array
    {
        return $meta;
    }
}
