<?php

namespace SoftCommerce\SeoSuite\Plugin\Model;

class Store
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var \Magento\Framework\Session\SidResolverInterface
     */
    protected $sidResolver;
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;
    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $url;
    /**
     * @var \Magento\Framework\Session\SessionManagerInterface
     */
    protected $session;
    /**
     * @var \Magento\Framework\App\ProductMetadataInterface
     */
    protected $productMetadata;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Session\SidResolverInterface $sidResolver,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\Session\SessionManagerInterface $session,
        \Magento\Framework\App\ProductMetadataInterface $productMetadata
    ) {
        $this->storeManager = $storeManager;
        $this->sidResolver = $sidResolver;
        $this->request = $request;
        $this->url = $url;
        $this->session = $session;
        $this->productMetadata = $productMetadata;
    }

    public function aroundGetCurrentUrl(\Magento\Store\Model\Store $subject, callable $proceed, $fromStore = true)
    {
        if (version_compare($this->productMetadata->getVersion(), '2.3.5', '<')) {
            $sidQueryParam = $this->sidResolver->getSessionIdQueryParam($this->_getSession($subject->getCode()));
        }

        $requestString = $this->url->escape(
            preg_replace(
                '/\?.*?$/',
                '',
                ltrim($this->request->getRequestString(), '/')
            )
        );
        $storeUrl = $subject->getUrl('', ['_secure' => $this->storeManager->getStore()->isCurrentlySecure()]);

        if (!filter_var($storeUrl, FILTER_VALIDATE_URL)) {
            return $storeUrl;
        }

        $storeParsedUrl = parse_url($storeUrl);

        $storeParsedQuery = [];
        if (isset($storeParsedUrl['query'])) {
            parse_str($storeParsedUrl['query'], $storeParsedQuery);
        }

        $currQuery = $this->request->getQueryValue();
        if (isset($sidQueryParam)
            && !empty($currQuery[$sidQueryParam])
            && $this->_getSession($subject->getCode())->getSessionIdForHost($storeUrl) != $currQuery[$sidQueryParam]
        ) {
            unset($currQuery[$sidQueryParam]);
        }

        foreach ($currQuery as $key => $value) {
            $storeParsedQuery[$key] = $value;
        }

        if (!$subject->isUseStoreInUrl()) {
            $storeParsedQuery['___store'] = $subject->getCode();
        }
        if ($fromStore !== false) {
            $storeParsedQuery['___from_store'] = $fromStore ===
            true ? $this->storeManager->getStore()->getCode() : $fromStore;
        }

        $currentUrl = $storeParsedUrl['scheme']
            . '://'
            . $storeParsedUrl['host']
            . (isset($storeParsedUrl['port']) ? ':' . $storeParsedUrl['port'] : '')
            . $storeParsedUrl['path']
            . $requestString
            . ($storeParsedQuery ? '?' . http_build_query($storeParsedQuery, '', '&amp;') : '');

        return $currentUrl;
    }

    /**
     * Retrieve store session object
     *
     * @param $code
     * @return \Magento\Framework\Session\SessionManagerInterface
     */
    protected function _getSession($code)
    {
        if (!$this->session->isSessionExists()) {
            $this->session->setName('store_' . $code);
            $this->session->start();
        }
        return $this->session;
    }
}
