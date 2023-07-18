<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\SeoSuite\ViewModel;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;
use SoftCommerce\Core\Framework\DataStorageInterface;
use SoftCommerce\Core\Framework\DataStorageInterfaceFactory;
use SoftCommerce\SeoSuite\Model\ConfigInterface;
use SoftCommerce\SeoSuite\Model\EntityTypeId\EntityPoolInterface;
use SoftCommerce\SeoSuite\Model\Source\XdefaultStoreOptions;

/**
 * @inheritDoc
 */
class HtmlHeadHreflang implements ArgumentInterface
{
    /**#@+
     * Object data keys
     */
    private const LINK_ENTITY_TYP_ID = 'link_entity_type_id';
    /**#@-*/

    /**
     * @var ConfigInterface
     */
    private ConfigInterface $config;

    /**
     * @var DataStorageInterface
     */
    private DataStorageInterface $dataStorage;

    /**
     * @var EntityPoolInterface|null
     */
    private ?EntityPoolInterface $entityPool = null;

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var UrlInterface
     */
    private UrlInterface $urlBuilder;

    /**
     * @param ConfigInterface $config
     * @param DataStorageInterfaceFactory $dataStorageFactory
     * @param RequestInterface $request
     * @param StoreManagerInterface $storeManager
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        ConfigInterface $config,
        DataStorageInterfaceFactory $dataStorageFactory,
        RequestInterface $request,
        StoreManagerInterface $storeManager,
        UrlInterface $urlBuilder
    ) {
        $this->config = $config;
        $this->dataStorage = $dataStorageFactory->create();
        $this->request = $request;
        $this->storeManager = $storeManager;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @return array
     */
    public function getLinkAttributes(): array
    {
        if (!$this->config->isActiveHreflang() || !$this->getLinkEntityTypeId()) {
            return [];
        }

        foreach ($this->storeManager->getStores() as $store) {
            $this->generateLinkAttributes($store);
        }

        return $this->dataStorage->getData();
    }

    /**
     * @return string|null
     */
    public function getLinkXDefaultUrlAttribute(): ?string
    {
        if (!$this->config->isActiveHreflangXdefault()) {
            return null;
        }

        $storeId = $this->config->getHreflangXDefaultStoreId();

        if ($storeId === XdefaultStoreOptions::USE_DEFAULT_STORE) {
            foreach ($this->storeManager->getWebsites() as $website) {
                if ($website->getIsDefault()) {
                    $storeId = (int) $website->getDefaultStore()->getId();
                    break;
                }
            }
        }

        return $this->dataStorage->getData($storeId)['href'] ?? null;
    }

    /**
     * @param StoreInterface $store
     * @return void
     */
    private function generateLinkAttributes(StoreInterface $store): void
    {
        if (!$this->getLinkEntityTypeId()->isActive($store)
            || !$url = $this->getLinkEntityTypeId()->getUrl($store)
        ) {
            return;
        }

        $this->dataStorage->setData(
            [
                'href' => $this->buildUrl($url),
                'hreflang' => $this->config->getLocaleCode((int) $store->getId())
            ],
            $store->getId()
        );
    }

    /**
     * @param string $url
     * @return string
     */
    private function buildUrl(string $url): string
    {
        if (!$queryValue = $this->request->getQueryValue()) {
            return $url;
        }

        $encodedQuery = http_build_query($queryValue, '', '&amp;');
        $urlComponents = parse_url($url);

        $url = "{$urlComponents['scheme']}://{$urlComponents['host']}{$urlComponents['path']}";
        $url = sprintf('%s?%s', $url, $encodedQuery);

        return trim($this->urlBuilder->getUrl($url), '/');
    }

    /**
     * @return EntityPoolInterface|null
     */
    private function getLinkEntityTypeId(): ?EntityPoolInterface
    {
        if (null === $this->entityPool) {
            $this->entityPool = $this->config->getEntityTypeId();
        }

        return $this->entityPool;
    }
}
