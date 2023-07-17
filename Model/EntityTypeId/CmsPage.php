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
use Magento\Framework\DataObject;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\Store;

/**
 * @inheritDoc
 */
class CmsPage implements EntityPoolInterface
{
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
     * @var CollectionFactory
     */
    private CollectionFactory $cmsPageCollectionFactory;

    /**
     * @param CollectionFactory $cmsPageCollectionFactory
     * @param GetUtilityPageIdentifiersInterface $getUtilityPageIdentifiers
     * @param PageInterface $page
     */
    public function __construct(
        CollectionFactory $cmsPageCollectionFactory,
        GetUtilityPageIdentifiersInterface $getUtilityPageIdentifiers,
        PageInterface $page
    ) {
        $this->cmsPageCollectionFactory = $cmsPageCollectionFactory;
        $this->getUtilityPageIdentifiers = $getUtilityPageIdentifiers;
        $this->page = $page;
    }

    /**
     * @inheritDoc
     */
    public function isActive(StoreInterface $store): bool
    {
        return (bool) $this->getPage($store)?->isActive();
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
        if (!$page = $this->getPage($store)) {
            return null;
        }

        $url = $store->getBaseUrl();

        if ($this->getHomepageIdentifier() != $page->getIdentifier()) {
            $url .= $page->getIdentifier();
        }

        return $url;
    }

    /**
     * @param StoreInterface $store
     * @return PageInterface|null
     */
    public function getCmsPage(StoreInterface $store): ?PageInterface
    {
        if (!isset($this->dataInMemory[$store->getId()]['cmspage'])) {
            $collection = $this->cmsPageCollectionFactory->create()
                ->addFieldToFilter('store_id', $store->getId())
                ->addFieldToFilter('page_group_identifier', $this->page->getPageGroupIdentifier())
                ->addFieldToSelect(['page_id', 'identifier', 'is_active']);
            $this->dataInMemory[$store->getId()]['cmspage'] = $collection->getFirstItem();
        }


        return $this->dataInMemory[$store->getId()]['cmspage'] ?? null;
    }

    /**
     * @param StoreInterface $store
     * @return DataObject|PageInterface|null
     */
    private function getPage(StoreInterface $store): DataObject|PageInterface|null
    {
        if (in_array($store->getId(), $this->page->getStoreId())) {
            return $this->page;
        }

        if (in_array(Store::DEFAULT_STORE_ID, $this->page->getStoreId())) {
            return $this->page;
        }

        if (!$this->page->getPageGroupIdentifier()) {
            return null;
        }

        $page = $this->getCmsPage($store);

        return $page?->getId() ? $page : null;
    }

    /**
     * @return string
     */
    private function getHomepageIdentifier(): string
    {
        if (!isset($this->dataInMemory['homepageid'])) {
            $this->dataInMemory['homepageid'] = (string) ($this->getUtilityPageIdentifiers->execute()[0] ?? '');
        }

        return $this->dataInMemory['homepageid'];
    }
}
