<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\SeoSuite\Model;

use Magento\Catalog\Helper\Data as CatalogHelper;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;
use SoftCommerce\SeoSuite\Model\Source\UrlRelationTypeIdOptions;

/**
 * @inheritDoc
 */
class GetCanonicalUrl implements GetCanonicalUrlInterface
{
    /**
     * @var CatalogHelper
     */
    private CatalogHelper $catalogHelper;

    /**
     * @var ConfigInterface
     */
    private ConfigInterface $config;

    /**
     * @var GetUrlRelationshipInterface
     */
    private GetUrlRelationshipInterface $getUrlRelationship;

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @var UrlInterface
     */
    private UrlInterface $urlBuilder;

    /**
     * @param ConfigInterface $config
     * @param CatalogHelper $catalogHelper
     * @param GetUrlRelationshipInterface $getUrlRelationship
     * @param RequestInterface $request
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        ConfigInterface $config,
        CatalogHelper $catalogHelper,
        GetUrlRelationshipInterface $getUrlRelationship,
        RequestInterface $request,
        UrlInterface $urlBuilder
    ) {
        $this->config = $config;
        $this->catalogHelper = $catalogHelper;
        $this->getUrlRelationship = $getUrlRelationship;
        $this->request = $request;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @inheritDoc
     */
    public function execute(): ?string
    {
        if (!$this->canExecute()) {
            return null;
        }

        $url = $this->getUrl();

        if ($this->isStoreCodeUsedInUrl($url)) {
            return $url;
        }

        return rtrim($url, '/');
    }

    /**
     * @return string
     */
    private function getUrl(): string
    {
        $requestPath = trim($this->request->getPathInfo(), '/');
        if ($requestPath
            && $targetPath = $this->getUrlRelationship->getTargetPath(
                UrlRelationTypeIdOptions::CANONICAL,
                $requestPath
            )
        ) {
            return $this->urlBuilder->getDirectUrl($targetPath);
        }

        return $this->getCurrentUrlRewrite();
    }

    /**
     * @return string
     */
    private function getCurrentUrlRewrite(): string
    {
        $url = $this->urlBuilder->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true]);
        return strtok($url, '?');
    }

    /**
     * @return bool
     */
    private function canExecute(): bool
    {
        return $this->config->isActiveCanonical() && !$this->catalogHelper->getCategory();
    }

    /**
     * @param string $url
     * @return bool
     */
    private function isStoreCodeUsedInUrl(string $url): bool
    {
        if (!$this->config->isStoreCodeUsedInUrl()) {
            return false;
        }

        $result = false;
        if ($url === $this->urlBuilder->getUrl('', ['_current' => true])) {
            $result = !!parse_url($url, PHP_URL_PATH);
        }
        return $result;
    }
}
