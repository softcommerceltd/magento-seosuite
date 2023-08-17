<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SoftCommerce\SeoSuite\Api\Data\UrlRelationship;

use Magento\Framework\Api;

/**
 * Interface SearchResultsInterface
 */
interface SearchResultsInterface extends Api\SearchResultsInterface
{
    /**
     * @return Api\ExtensibleDataInterface[]
     */
    public function getItems();

    /**
     * @param array $items
     * @return Api\SearchResultsInterface
     */
    public function setItems(array $items);
}
