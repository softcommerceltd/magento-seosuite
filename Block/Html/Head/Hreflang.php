<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\SeoSuite\Block\Html\Head;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Api\Data\StoreInterface;
use SoftCommerce\Core\Framework\DataStorageInterface;
use SoftCommerce\Core\Framework\DataStorageInterfaceFactory;
use SoftCommerce\SeoSuite\Model\ConfigInterface;
use SoftCommerce\SeoSuite\Model\EntityTypeId\EntityPoolInterface;
use SoftCommerce\SeoSuite\Model\Source\XdefaultStoreOptions;

/**
 * @inheritDoc
 */
class Hreflang extends Template
{
    /**#@+
     * Object data keys
     */
    private const LINK_ENTITY_TYP_ID = 'link_entity_type_id';
    /**#@-*/

    /**
     * @var ConfigInterface
     */
    protected ConfigInterface $config;

    /**
     * @var DataStorageInterface
     */
    protected DataStorageInterface $dataStorage;

    /**
     * @param ConfigInterface $config
     * @param DataStorageInterfaceFactory $dataStorageFactory
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        ConfigInterface $config,
        DataStorageInterfaceFactory $dataStorageFactory,
        Context $context,
        array $data = []
    ) {
        $this->config = $config;
        $this->dataStorage = $dataStorageFactory->create();
        parent::__construct($context, $data);
    }

    /**
     * @return array
     */
    public function getLinkAttributes(): array
    {
        if (!$this->config->isActiveHreflang() || !$this->getLinkEntityTypeId()) {
            return [];
        }

        foreach ($this->_storeManager->getStores() as $store) {
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
            foreach ($this->_storeManager->getWebsites() as $website) {
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
    protected function generateLinkAttributes(StoreInterface $store): void
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
    protected function buildUrl(string $url): string
    {
        if (!$queryValue = $this->_request->getQueryValue()) {
            return $url;
        }

        $encodedQuery = http_build_query($queryValue, '', '&amp;');
        $urlComponents = parse_url($url);

        $url = "{$urlComponents['scheme']}://{$urlComponents['host']}{$urlComponents['path']}";
        $url = sprintf('%s?%s', $url, $encodedQuery);

        return trim($this->_urlBuilder->getUrl($url), '/');
    }

    /**
     * @return EntityPoolInterface|null
     */
    private function getLinkEntityTypeId(): ?EntityPoolInterface
    {
        if (!$this->hasData(self::LINK_ENTITY_TYP_ID)) {
            $this->setData(self::LINK_ENTITY_TYP_ID, $this->config->getEntityTypeId());
        }

        return $this->getData(self::LINK_ENTITY_TYP_ID);
    }
}
