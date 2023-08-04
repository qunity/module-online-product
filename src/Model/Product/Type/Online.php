<?php

/**
 * @noinspection PhpDeprecationInspection
 */

declare(strict_types=1);

namespace Qunity\OnlineProduct\Model\Product\Type;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product\Option as ProductOption;
use Magento\Catalog\Model\Product\Type as ProductType;
use Magento\Catalog\Model\Product\Type\Virtual as VirtualProduct;
use Magento\Eav\Api\AttributeSetRepositoryInterface;
use Magento\Eav\Api\Data\AttributeSetInterface;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Eav\Model\Entity\Attribute\Set as EntityAttributeSet;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Event\ManagerInterface as EventManagerInterface;
use Magento\Framework\File\UploaderFactory;
use Magento\Framework\Filesystem;
use Magento\Framework\Registry;
use Magento\Framework\Serialize\Serializer\Json as SerializerJson;
use Magento\MediaStorage\Helper\File\Storage\Database as StorageDatabase;
use Psr\Log\LoggerInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Online extends VirtualProduct
{
    public const TYPE_CODE = 'online';
    public const VALUE_ATTRIBUTE_SET_NAME = 'Online';

    /**
     * @var AttributeSetRepositoryInterface
     */
    private AttributeSetRepositoryInterface $attrSetRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private SearchCriteriaBuilder $criteriaBuilder;

    /**
     * @var int
     */
    private int $attributeSetId;

    /**
     * @param ProductOption $catalogProductOption
     * @param EavConfig $eavConfig
     * @param ProductType $catalogProductType
     * @param EventManagerInterface $eventManager
     * @param StorageDatabase $fileStorageDb
     * @param Filesystem $filesystem
     * @param Registry $coreRegistry
     * @param LoggerInterface $logger
     * @param ProductRepositoryInterface $productRepository
     * @param AttributeSetRepositoryInterface $attrSetRepository
     * @param SearchCriteriaBuilder $criteriaBuilder
     * @param SerializerJson|null $serializer
     * @param UploaderFactory|null $uploaderFactory
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        ProductOption $catalogProductOption,
        EavConfig $eavConfig,
        ProductType $catalogProductType,
        EventManagerInterface $eventManager,
        StorageDatabase $fileStorageDb,
        Filesystem $filesystem,
        Registry $coreRegistry,
        LoggerInterface $logger,
        ProductRepositoryInterface $productRepository,
        AttributeSetRepositoryInterface $attrSetRepository,
        SearchCriteriaBuilder $criteriaBuilder,
        SerializerJson $serializer = null,
        UploaderFactory $uploaderFactory = null,
    ) {
        parent::__construct(
            $catalogProductOption,
            $eavConfig,
            $catalogProductType,
            $eventManager,
            $fileStorageDb,
            $filesystem,
            $coreRegistry,
            $logger,
            $productRepository,
            $serializer,
            $uploaderFactory
        );
        $this->attrSetRepository = $attrSetRepository;
        $this->criteriaBuilder = $criteriaBuilder;
    }

    /**
     * Get default attribute set ID for "Online" product type
     *
     * @return int|null
     */
    public function getDefaultAttributeSetId(): ?int
    {
        if (isset($this->attributeSetId)) {
            return $this->attributeSetId;
        }

        $criteria = $this->criteriaBuilder->addFilter(
            EntityAttributeSet::KEY_ATTRIBUTE_SET_NAME,
            self::VALUE_ATTRIBUTE_SET_NAME
        )->create();

        $searchResults = $this->attrSetRepository->getList($criteria);

        $items = $searchResults->getItems();
        if (!$items) {
            return null;
        }

        $attributeSet = current($items);
        if (!($attributeSet instanceof AttributeSetInterface)) {
            return null;
        }

        $attributeSetId = $attributeSet->getAttributeSetId();
        if ($attributeSetId === null) {
            return null;
        }

        return $this->attributeSetId = (int) $attributeSetId;
    }
}
