<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\SeoSuite\Ui\DataProvider;

use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Ui\DataProvider\ModifierPoolDataProvider;
use SoftCommerce\SeoSuite\Model\ResourceModel\UrlRelationship\Collection;
use SoftCommerce\SeoSuite\Model\ResourceModel\UrlRelationship\CollectionFactory;

/**
 * @inheritDoc
 */
class UrlRelationshipFormDataProvider extends ModifierPoolDataProvider
{
    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var array|null
     */
    private ?array $loadedData = null;

    /**
     * @param CollectionFactory $collectionFactory
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param array $meta
     * @param array $data
     * @param PoolInterface|null $pool
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null
    ) {
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);
    }

    /**
     * @inheritDoc
     * @throws LocalizedException
     */
    public function getData(): array
    {
        if (null !== $this->loadedData) {
            return $this->loadedData;
        }

        $this->loadedData = [];

        if (!$items = $this->collection->getItems()) {
            return $this->loadedData;
        }

        $data = array_shift($items);
        $this->loadedData[$data->getEntityId()]['general'] = $data->getData();

        return $this->loadedData;
    }
}
