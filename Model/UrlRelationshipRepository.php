<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\SeoSuite\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use SoftCommerce\SeoSuite\Api\Data\UrlRelationship\SearchResultsInterfaceFactory;
use SoftCommerce\SeoSuite\Api\Data\UrlRelationshipInterface;
use SoftCommerce\SeoSuite\Api\UrlRelationshipRepositoryInterface;

/**
 * @inheritDoc
 */
class UrlRelationshipRepository implements UrlRelationshipRepositoryInterface
{
    /**
     * @var ResourceModel\UrlRelationship
     */
    private ResourceModel\UrlRelationship $resource;

    /**
     * @var UrlRelationshipFactory
     */
    private UrlRelationshipFactory $modelFactory;

    /**
     * @var ResourceModel\UrlRelationship\CollectionFactory
     */
    private ResourceModel\UrlRelationship\CollectionFactory $collectionFactory;

    /**
     * @var SearchResultsInterfaceFactory
     */
    private SearchResultsInterfaceFactory $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private CollectionProcessorInterface $collectionProcessor;

    /**
     * @param ResourceModel\UrlRelationship $resource
     * @param UrlRelationshipFactory $modelFactory
     * @param ResourceModel\UrlRelationship\CollectionFactory $collectionFactory
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceModel\UrlRelationship $resource,
        UrlRelationshipFactory $modelFactory,
        ResourceModel\UrlRelationship\CollectionFactory $collectionFactory,
        SearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->modelFactory = $modelFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var ResourceModel\UrlRelationship\Collection $collection */
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function get($entityId, $field = null): UrlRelationshipInterface
    {
        /** @var UrlRelationshipInterface|UrlRelationship $model */
        $model = $this->modelFactory->create();
        $this->resource->load($model, $entityId, $field);
        if (!$model->getId()) {
            throw new NoSuchEntityException(__('Entity with ID "%1" doesn\'t exist.', $entityId));
        }

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function getById($entityId): UrlRelationshipInterface
    {
        return $this->get($entityId, UrlRelationshipInterface::ENTITY_ID);
    }

    /**
     * @inheritDoc
     */
    public function save(UrlRelationshipInterface $model)
    {
        try {
            $this->resource->save($model);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $model;
    }

    /**
     * @inheritDoc
     */
    public function delete(UrlRelationshipInterface $model)
    {
        try {
            $this->resource->delete($model);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($entityId)
    {
        return $this->delete($this->getById($entityId));
    }
}
