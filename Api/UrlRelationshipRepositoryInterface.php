<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\SeoSuite\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use SoftCommerce\SeoSuite\Api\Data\UrlRelationship\SearchResultsInterface;
use SoftCommerce\SeoSuite\Api\Data\UrlRelationshipInterface;

/**
 * Interface UrlRelationshipRepositoryInterface
 */
interface UrlRelationshipRepositoryInterface
{
    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * @param $entityId
     * @param $field
     * @return UrlRelationshipInterface
     * @throws NoSuchEntityException
     */
    public function get($entityId, $field = null): UrlRelationshipInterface;

    /**
     * @param $entityId
     * @return UrlRelationshipInterface
     * @throws NoSuchEntityException
     */
    public function getById($entityId): UrlRelationshipInterface;

    /**
     * @param UrlRelationshipInterface $model
     * @return UrlRelationshipInterface
     * @throws CouldNotSaveException
     */
    public function save(UrlRelationshipInterface $model);

    /**
     * @param UrlRelationshipInterface $model
     * @return true
     * @throws CouldNotDeleteException
     */
    public function delete(UrlRelationshipInterface $model);

    /**
     * @param $entityId
     * @return true
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($entityId);
}
