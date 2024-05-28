<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\SeoSuite\Model\Resolver\Cms;

use Magento\Cms\Api\Data\PageInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Identity for resolved CMS page list
 */
class CmsPageMetaRobots implements ResolverInterface
{
    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        if (!isset($value['model'])) {
            throw new LocalizedException(__('"model" value should be specified'));
        }

        /* @var PageInterface $page */
        $page = $value['model'];

        /** @var StoreInterface $store */
        $store = $context->getExtensionAttributes()->getStore();

        $metaRobots = $page->getData('meta_robots');
        if ($metaRobots && $metaRobots !== 'system') {
            $result = $metaRobots;
        } else {
            $result = $this->scopeConfig->getValue(
                'design/search_engine_robots/default_robots',
                ScopeInterface::SCOPE_STORE,
                $store
            );
        }

        return $result;
    }
}
