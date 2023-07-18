<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\SeoSuite\Model\EntityTypeId;

use Magento\Cms\Api\Data\PageInterface;
use Magento\Cms\Api\GetUtilityPageIdentifiersInterface;
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
     * @param GetUtilityPageIdentifiersInterface $getUtilityPageIdentifiers
     * @param PageInterface $page
     */
    public function __construct(
        GetUtilityPageIdentifiersInterface $getUtilityPageIdentifiers,
        PageInterface $page
    ) {
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
    private function getPage(StoreInterface $store): PageInterface|null
    {
        $page = null;
        if ($this->isHomePage()
            || !array_diff((array) $this->page->getStoreId(), [$store->getId(), Store::DEFAULT_STORE_ID])
        ) {
            $page = $this->page;
        }

        return $page;
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

    /**
     * @return bool
     */
    private function isHomePage(): bool
    {
        return $this->getHomepageIdentifier() === $this->page->getIdentifier();
    }
}
