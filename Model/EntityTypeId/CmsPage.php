<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\SeoSuite\Model\EntityTypeId;

use Magento\Cms\Api\Data\PageInterface;
use Magento\Cms\Api\GetUtilityPageIdentifiersInterface;
use Magento\Cms\Model\ResourceModel\Page\CollectionFactory;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\Store;

/**
 * @inheritDoc
 */
class CmsPage implements EntityPoolInterface
{
    /**#@+
     * Const attributes
     */
    public const HREFLANG_ID = 'hreflang_identifier';
    private const CMS_PAGE = 'cms_page';
    private const CMS_PAGE_COLLECTION = 'cms_page_collection';
    private const HOMEPAGE_ID = 'homepage_id';

    /**#@-*/

    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * array
     */
    private array $dataInMemory = [];

    /**
     * @var GetUtilityPageIdentifiersInterface
     */
    private GetUtilityPageIdentifiersInterface $getUtilityPageIdentifiers;

    /**
     * @var PageInterface
     */
    private PageInterface $page;

    /**
     * @param CollectionFactory $collectionFactory
     * @param GetUtilityPageIdentifiersInterface $getUtilityPageIdentifiers
     * @param PageInterface $page
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        GetUtilityPageIdentifiersInterface $getUtilityPageIdentifiers,
        PageInterface $page
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->getUtilityPageIdentifiers = $getUtilityPageIdentifiers;
        $this->page = $page;
    }

    /**
     * @inheritDoc
     */
    public function isActive(StoreInterface $store): bool
    {
        return (bool) $this->getPage((int) $store->getId())?->isActive();
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(): bool
    {
        return (bool) $this->page->getId();
    }

    /**
     * @inheritDoc
     */
    public function getUrl(StoreInterface $store): ?string
    {
        if (!$page = $this->getPage((int) $store->getId())) {
            return null;
        }

        $url = $store->getBaseUrl();

        if ($this->getHomepageIdentifier() != $page->getIdentifier()) {
            $url .= $page->getIdentifier();
        }

        return $url;
    }

    /**
     * @param int $storeId
     * @return PageInterface|null
     */
    private function getPage(int $storeId): PageInterface|null
    {
        if (isset($this->dataInMemory[self::CMS_PAGE][$storeId])) {
            return $this->dataInMemory[self::CMS_PAGE][$storeId];
        }

        $this->dataInMemory[self::CMS_PAGE][$storeId] = $this->page;

        if ($this->isHomePage()
            || !array_diff((array) $this->page->getStoreId(), [$storeId, Store::DEFAULT_STORE_ID])
        ) {
            return $this->dataInMemory[self::CMS_PAGE][$storeId];
        }

        if ($hreflangId = $this->page->getData(self::HREFLANG_ID)) {
            $this->dataInMemory[self::CMS_PAGE][$storeId] = $this->getPageByHreflangId(
                $storeId,
                $hreflangId
            ) ?: '';
        }

        return $this->dataInMemory[self::CMS_PAGE][$storeId] ?: null;
    }

    /**
     * @return string
     */
    private function getHomepageIdentifier(): string
    {
        if (!isset($this->dataInMemory[self::HOMEPAGE_ID])) {
            $this->dataInMemory[self::HOMEPAGE_ID] = (string) ($this->getUtilityPageIdentifiers->execute()[0] ?? '');
        }

        return $this->dataInMemory[self::HOMEPAGE_ID];
    }

    /**
     * @param int $storeId
     * @param string $hreflangId
     * @return PageInterface|null
     */
    private function getPageByHreflangId(int $storeId, string $hreflangId): PageInterface|null
    {

        if (!isset($this->dataInMemory[self::CMS_PAGE_COLLECTION][$hreflangId])) {
            $collection = $this->collectionFactory->create()
                ->addFieldToFilter(self::HREFLANG_ID, $hreflangId)
                ->addFieldToSelect(['identifier', 'page_id', 'is_active']);

            $this->dataInMemory[self::CMS_PAGE_COLLECTION][$hreflangId] = $collection;
        }

        $pageResult = null;
        /** @var PageInterface $page */
        foreach ($this->dataInMemory[self::CMS_PAGE_COLLECTION][$hreflangId] as $page) {
            if (in_array($storeId, (array) $page->getStoreId())) {
                $pageResult = $page;
                break;
            }
        }

        return $pageResult;
    }

    /**
     * @return bool
     */
    private function isHomePage(): bool
    {
        return $this->getHomepageIdentifier() === $this->page->getIdentifier();
    }
}
