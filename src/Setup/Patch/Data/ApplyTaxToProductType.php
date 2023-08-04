<?php

declare(strict_types=1);

namespace Qunity\OnlineProduct\Setup\Patch\Data;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use Qunity\Base\Service\Setup\EavSetup\Product\ApplyTo as ApplyToService;
use Qunity\OnlineProduct\Model\Product\Type\Online as OnlineProduct;

class ApplyTaxToProductType implements DataPatchInterface, PatchVersionInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private ModuleDataSetupInterface $moduleDataSetup;

    /**
     * @var EavSetupFactory
     */
    private EavSetupFactory $eavSetupFactory;

    /**
     * @var ApplyToService
     */
    private ApplyToService $applyToService;

    /**
     * @var array|string[]
     */
    private array $attrCodes = [
        'tax_class_id',
    ];

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     * @param ApplyToService $applyToService
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory,
        ApplyToService $applyToService,
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->applyToService = $applyToService;
    }

    /**
     * @inheritDoc
     */
    public function apply(): static
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $this->applyToService->execute($eavSetup, $this->attrCodes, OnlineProduct::TYPE_CODE);

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
