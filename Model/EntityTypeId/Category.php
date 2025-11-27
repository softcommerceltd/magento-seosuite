<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\SeoSuite\Model\EntityTypeId;

use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Catalog\Model\ResourceModel;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Registry;
use Magento\Store\Api\Data\StoreInterface;
use Magento\TestFramework\Exception\NoSuchActionException;
use Magento\UrlRewrite\Model\UrlFinderInterface;

/**
 * @inheritDoc
 */
class Category implements EntityPoolInterface
{
    /**
     * @param Registry $registry
     * @param RequestInterface $request
     * @param ResourceModel\Category $categoryResource
     * @param UrlFinderInterface $urlFinder
     */
    public function __construct(
        private Registry $registry,
        private RequestInterface $request,
        private ResourceModel\Category $categoryResource,
        private UrlFinderInterface $urlFinder
    ) {
    }

    /**
     * @inheritDoc
     */
    public function isActive(StoreInterface $store): bool
    {
        if (!$category = $this->getCategory()) {
            return false;
        }

        return (bool) $this->categoryResource->getAttributeRawValue(
            $category->getId(),
            'is_active',
            $store->getId()
        );
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(): bool
    {
        return !$this->registry->registry('product') && $this->getCategory();
    }

    /**
     * @inheritDoc
     */
    public function getUrl(StoreInterface $store): ?string
    {
        if ($url = $this->urlFinder->findOneByData([
            'target_path' => trim($this->request->getPathInfo(), '/'),
            'store_id' => $store->getId()
        ])) {
            return $store->getBaseUrl() . $url->getRequestPath();
        }

        try {
            $url = $store->getCurrentUrl(false);
        } catch (NoSuchActionException $e) {
            $url = null;
        }

        return $url;
    }

    /**
     * @return CategoryInterface|null
     */
    private function getCategory(): ?CategoryInterface
    {
        return $this->registry->registry('current_category');
    }
}
