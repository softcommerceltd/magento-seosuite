<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\SeoSuite\Controller\Adminhtml\SeoSuiteUrl;

use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Framework\Data\Collection;
use SoftCommerce\SeoSuite\Api\Data\UrlRelationshipInterface;
use SoftCommerce\SeoSuite\Model\ResourceModel;

/**
 * @inheritDoc
 */
class MassDelete extends AbstractMassAction
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'SoftCommerce_SeoSuite::url_relationship_manage';

    /**
     * @param ResourceModel\UrlRelationship $resource
     * @param ResourceModel\UrlRelationship\CollectionFactory $collectionFactory
     * @param Filter $filter
     * @param Context $context
     */
    public function __construct(
        private ResourceModel\UrlRelationship $resource,
        ResourceModel\UrlRelationship\CollectionFactory $collectionFactory,
        Filter $filter,
        Context $context
    ) {
        parent::__construct($collectionFactory, $filter, $context);
    }

    /**
     * @inheritDoc
     */
    protected function massAction(Collection $collection): void
    {
        $ids = $collection->getAllIds();
        $result = $this->resource->remove(
            [
                UrlRelationshipInterface::ENTITY_ID . ' IN (?)' => $ids
            ]
        );

        if ($result > 0) {
            $this->messageManager->addSuccessMessage(
                __(
                    'Selected schedules have been deleted. Effected IDs: %1',
                    implode(', ', $ids)
                )
            );
        }
    }
}
