<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\SeoSuite\Setup\Patch\Data;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

/**
 * Class InstallMetaRobotsCategoryAttribute
 * used to add `meta_robots` attribute to category eav entity.
 */
class InstallMetaRobotsCategoryAttribute implements DataPatchInterface
{
    /**
     * @var CategorySetupFactory
     */
    private CategorySetupFactory $categorySetupFactory;

    /**
     * @var ModuleDataSetupInterface
     */
    private ModuleDataSetupInterface $moduleDataSetup;

    /**
     * @param CategorySetupFactory $categorySetupFactory
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        CategorySetupFactory $categorySetupFactory,
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        $this->categorySetupFactory = $categorySetupFactory;
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * @inheritDoc
     */
    public function apply(): void
    {
        $categorySetup = $this->categorySetupFactory->create(['setup' => $this->moduleDataSetup]);
        $entityTypeId = $categorySetup->getEntityTypeId(Category::ENTITY);

        $categorySetup->addAttribute(
            $entityTypeId,
            'meta_robots',
            [
                'type' => 'varchar',
                'label' => 'Meta Robots',
                'input' => 'select',
                'source' => \SoftCommerce\SeoSuite\Model\Source\Eav\MetaRobots::class,
                'required' => false,
                'sort_order' => 50,
                'visible'  => true,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Search Engine Optimization'
            ]
        );
    }

    /**
     * @return string[]
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @return string[]
     */
    public function getAliases(): array
    {
        return [];
    }
}
