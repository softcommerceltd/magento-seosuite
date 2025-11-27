<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\SeoSuite\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\Store;

/**
 * @inheritDoc
 */
class Config implements ConfigInterface
{
    /**#@+
     * XML Config Paths
     */
    private const XML_PATH_GENERAL_LOCALE_CODE = 'general/locale/code';
    /**#@-*/

    /**
     * @var EntityTypeId\EntityPoolInterface[]
     */
    private array $entityTypeIds;

    /**
     * @var array
     */
    private array $localeInMemory = [];

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param array $entityTypeIds
     */
    public function __construct(
        private ScopeConfigInterface $scopeConfig,
        array $entityTypeIds = []
    ) {
        $this->entityTypeIds = $entityTypeIds;
    }

    /**
     * @inheritDoc
     */
    public function getEntityTypeId(): ?EntityTypeId\EntityPoolInterface
    {
        $result = null;
        foreach ($this->entityTypeIds as $entityTypeId) {
            if ($entityTypeId->isApplicable()) {
                $result = $entityTypeId;
                break;
            }
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function isActiveHreflang(): bool
    {
        return (bool) $this->scopeConfig->isSetFlag(
            self::XML_PATH_HREFLANG_IS_ACTIVE,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * @inheritDoc
     */
    public function shouldHreflangIncludeRegionCode(): bool
    {
        return (bool) $this->scopeConfig->isSetFlag(
            self::XML_PATH_HREFLANG_INC_REG_CODE,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * @inheritDoc
     */
    public function isActiveHreflangXDefault(): bool
    {
        return (bool) $this->scopeConfig->isSetFlag(
            self::XML_PATH_HREFLANG_IS_ACTIVE_XDEFAULT,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * @inheritDoc
     */
    public function getHreflangXDefaultStoreId(): ?int
    {
        if (!$this->isActiveHreflangXDefault()) {
            return null;
        }

        return (int) $this->scopeConfig->getValue(
            self::XML_PATH_HREFLANG_XDEFAULT_STORE_ID,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * @inheritDoc
     */
    public function isActiveCanonical(): bool
    {
        return (bool) $this->scopeConfig->isSetFlag(
            self::XML_PATH_CANONICAL_IS_ACTIVE,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * @inheritDoc
     */
    public function getLocaleCode(int $storeId): string
    {
        if (!isset($this->localeInMemory[$storeId])) {
            $localCode = $this->scopeConfig->getValue(
                self::XML_PATH_GENERAL_LOCALE_CODE,
                ScopeInterface::SCOPE_STORES,
                $storeId
            );

            if ($this->shouldHreflangIncludeRegionCode()) {
                $localCode = str_replace('_', '-', $localCode);
            } else {
                $localCode = substr($localCode, 0, 2);
            }

            $this->localeInMemory[$storeId] = strtolower($localCode);
        }

        return $this->localeInMemory[$storeId];
    }

    /**
     * @inheritDoc
     */
    public function isStoreCodeUsedInUrl(): bool
    {
        return (bool) $this->scopeConfig->isSetFlag(Store::XML_PATH_STORE_IN_URL);
    }
}
