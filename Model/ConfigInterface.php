<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\SeoSuite\Model;

/**
 * Interface ConfigInterface
 * used to provide system configuration
 * from core_config_data table.
 */
interface ConfigInterface
{
    /**#@+
     * Config Paths
     */
    public const XML_PATH_HREFLANG_IS_ACTIVE = 'softcommerce_seosuite/hreflang/is_active';
    public const XML_PATH_HREFLANG_INC_REG_CODE = 'softcommerce_seosuite/hreflang/inc_reg_code';
    public const XML_PATH_HREFLANG_IS_ACTIVE_XDEFAULT = 'softcommerce_seosuite/hreflang/is_active_x_default';
    public const XML_PATH_HREFLANG_XDEFAULT_STORE_ID = 'softcommerce_seosuite/hreflang/x_default_store';
    /**#@-*/

    /**
     * @return EntityTypeId\EntityPoolInterface|null
     */
    public function getEntityTypeId(): ?EntityTypeId\EntityPoolInterface;

    /**
     * @return bool
     */
    public function isActiveHreflang(): bool;

    /**
     * @return bool
     */
    public function shouldHreflangIncludeRegionCode(): bool;

    /**
     * @return bool
     */
    public function isActiveHreflangXDefault(): bool;

    /**
     * @return int|null
     */
    public function getHreflangXDefaultStoreId(): ?int;

    /**
     * @param int $storeId
     * @return string
     */
    public function getLocaleCode(int $storeId): string;
}
