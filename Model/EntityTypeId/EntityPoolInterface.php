<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\SeoSuite\Model\EntityTypeId;

use Magento\Store\Api\Data\StoreInterface;

/**
 * Interface EntityPoolInterface
 */
interface EntityPoolInterface
{
    /**
     * @param StoreInterface $store
     * @return bool
     */
    public function isActive(StoreInterface $store): bool;

    /**
     * @return bool
     */
    public function isApplicable(): bool;

    /**
     * @param StoreInterface $store
     * @return string|null
     */
    public function getUrl(StoreInterface $store): ?string;
}
