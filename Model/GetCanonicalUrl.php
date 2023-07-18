<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\SeoSuite\Model;

use Magento\Cms\Api\Data\PageInterface;
use Magento\Framework\UrlInterface;

class GetCanonicalUrl implements GetCanonicalUrlInterface
{
    /**
     * @var ConfigInterface
     */
    private ConfigInterface $config;

    /**
     * @var PageInterface
     */
    private PageInterface $page;

    /**
     * @var UrlInterface
     */
    private UrlInterface $urlBuilder;

    /**
     * @param ConfigInterface $config
     * @param PageInterface $page
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        ConfigInterface $config,
        PageInterface $page,
        UrlInterface $urlBuilder
    ) {
        $this->config = $config;
        $this->page = $page;
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

        $url = $this->urlBuilder->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true]);
        $url = strtok($url, '?');

        if ($this->isStoreCodeUsedInUrl($url)) {
            return $url;
        }

        return rtrim($url, '/');
    }

    /**
     * @return bool
     */
    private function canExecute(): bool
    {
        return $this->config->isActiveCanonical() && $this->page->getId();
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
