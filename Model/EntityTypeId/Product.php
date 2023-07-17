<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\SeoSuite\Model\EntityTypeId;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\ResourceModel;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Registry;
use Magento\Store\Api\Data\StoreInterface;
use Magento\UrlRewrite\Model\UrlFinderInterface;

/**
 * @inheritDoc
 */
class Product implements EntityPoolInterface
{
    /**
     * @var Registry
     */
    private Registry $registry;

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @var ResourceModel\Product
     */
    private ResourceModel\Product $productResource;

    /**
     * @var UrlFinderInterface
     */
    private UrlFinderInterface $urlFinder;

    /**
     * @param Registry $registry
     * @param RequestInterface $request
     * @param ResourceModel\Product $productResource
     * @param UrlFinderInterface $urlFinder
     */
    public function __construct(
        Registry $registry,
        RequestInterface $request,
        ResourceModel\Product $productResource,
        UrlFinderInterface $urlFinder
    ) {
        $this->registry = $registry;
        $this->request = $request;
        $this->productResource = $productResource;
        $this->urlFinder = $urlFinder;
    }

    /**
     * @inheritDoc
     */
    public function isActive(StoreInterface $store): bool
    {
        if (!$product = $this->getProduct()) {
            return false;
        }

        if (!in_array($store->getId(), $product->getStoreIds())) {
            return false;
        }

        $status = $this->productResource->getAttributeRawValue(
            $product->getId(),
            'status',
            $store->getId()
        );

        return $status == Status::STATUS_ENABLED;
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(): bool
    {
        return (bool) $this->getProduct();
    }

    /**
     * @inheritDoc
     */
    public function getUrl(StoreInterface $store): ?string
    {
        if ($urlRewrite = $this->urlFinder->findOneByData([
            'target_path' => trim($this->request->getPathInfo(), '/'),
            'store_id' => $store->getId()
        ])) {
            return $store->getBaseUrl() . $urlRewrite->getRequestPath();
        }

        return $store->getCurrentUrl(false);
    }

    /**
     * @return ProductInterface|null
     */
    private function getProduct(): ?ProductInterface
    {
        return $this->registry->registry('product');
    }
}
