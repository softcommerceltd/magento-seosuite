<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\SeoSuite\Model\ResourceModel\UrlRelationship;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use SoftCommerce\SeoSuite\Model\ResourceModel;
use SoftCommerce\SeoSuite\Model\UrlRelationship;

/**
 * @inheritDoc
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'softcommerce_profile_entity_collection';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(UrlRelationship::class, ResourceModel\UrlRelationship::class);
    }
}
