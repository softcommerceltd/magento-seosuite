<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\SeoSuite\Model\ResourceModel;

use SoftCommerce\Core\Model\ResourceModel\AbstractResource;
use SoftCommerce\SeoSuite\Api\Data\UrlRelationshipInterface;

/**
 * @inheritDoc
 */
class UrlRelationship extends AbstractResource
{
    /**
     * @var string
     */
    protected $_useIsObjectNew = true;

    /**
     * @var string
     */
    protected string $_eventPrefix = UrlRelationshipInterface::DB_TABLE_NAME;

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(UrlRelationshipInterface::DB_TABLE_NAME, UrlRelationshipInterface::ENTITY_ID);
    }
}
