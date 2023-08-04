<?php

/**
 * @noinspection PhpUnused
 * @noinspection PhpUndefinedClassInspection
 */

declare(strict_types=1);

namespace Qunity\OnlineProduct\Setup\Patch\Data;

use Exception;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Eav\Model\Entity\Attribute\Set as AttributeSet;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use Qunity\OnlineProduct\Model\Product\Type\Online as OnlineProduct;

class CreateOnlineAttributeSet implements DataPatchInterface, PatchVersionInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private ModuleDataSetupInterface $moduleDataSetup;

    /**
     * @var AttributeSetFactory
     */
    private AttributeSetFactory $attributeSetFactory;

    /**
     * @var CategorySetupFactory
     */
    private CategorySetupFactory $categorySetupFactory;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param AttributeSetFactory $attributeSetFactory
     * @param CategorySetupFactory $categorySetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        AttributeSetFactory $attributeSetFactory,
        CategorySetupFactory $categorySetupFactory,
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->categorySetupFactory = $categorySetupFactory;
    }

    /**
     * @inheritDoc
     * @throws LocalizedException|Exception
     * @noinspection PhpDeprecationInspection
     */
    public function apply(): static
    {
        $categorySetup = $this->categorySetupFactory->create(['setup' => $this->moduleDataSetup]);

        $entityTypeId = $categorySetup->getEntityTypeId(Product::ENTITY);
        $attributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId);

        $data = [
            AttributeSet::KEY_ATTRIBUTE_SET_NAME => OnlineProduct::VALUE_ATTRIBUTE_SET_NAME,
            AttributeSet::KEY_ENTITY_TYPE_ID => $entityTypeId,
            AttributeSet::KEY_SORT_ORDER => 10,
        ];

        $attributeSet = $this->attributeSetFactory->create();
        $attributeSet->setData($data)->save();
        $attributeSet->initFromSkeleton($attributeSetId)->save();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public static function getVersion(): string
    {
        return '2.4.6-p1';
    }

    /**
     * @inheritDoc
     */
    public function getAliases(): array
    {
        return [];
    }
}
