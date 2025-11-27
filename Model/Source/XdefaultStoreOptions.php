<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\SeoSuite\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * @inheritDoc
 */
class XdefaultStoreOptions implements OptionSourceInterface
{
    public const USE_DEFAULT_STORE = 0;

    /**
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(private StoreManagerInterface $storeManager)
    {}

    /**
     * @inheritDoc
     */
    public function toOptionArray(): array
    {
        $options = [
            [
                'label' => __('Use Default Store'),
                'value' => self::USE_DEFAULT_STORE
            ]
        ];

        foreach ($this->storeManager->getStores() as $store) {
            $label = "W: {$store->getWebsite()->getName()}";
            $label .= " | S: {$store->getName()} [{$store->getCode()}: {$store->getStoreId()}]";

            $options[] = [
                'label' => $label,
                'value' => $store->getStoreId()
            ];
        }

        return $options;
    }
}
