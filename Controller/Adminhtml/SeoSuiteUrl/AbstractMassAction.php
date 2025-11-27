<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\SeoSuite\Controller\Adminhtml\SeoSuiteUrl;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Framework\Data\Collection;
use SoftCommerce\SeoSuite\Model\ResourceModel;

/**
 * Class AbstractMassAction
 */
abstract class AbstractMassAction extends Action
{
    /**
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'SoftCommerce_SeoSuite::url_relationship_manage';

    /**
     * @var string
     */
    protected string $redirectUrl = '*/*/index';

    /**
     * @param ResourceModel\UrlRelationship\CollectionFactory $collectionFactory
     * @param Filter $filter
     * @param Context $context
     */
    public function __construct(
        protected ResourceModel\UrlRelationship\CollectionFactory $collectionFactory,
        protected Filter $filter,
        Context $context
    ) {
        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute(): Redirect
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        if (!$this->canExecute()) {
            $this->messageManager->addErrorMessage(__('Could not process given request.'));
            return $resultRedirect->setPath($this->getComponentRefererUrl());
        }

        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            $this->massAction($collection);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        $resultRedirect->setPath($this->getComponentRefererUrl());
        return $resultRedirect;
    }

    /**
     * Return component referer url
     *
     * @return string
     */
    protected function getComponentRefererUrl()
    {
        return $this->filter->getComponentRefererUrl() ?: $this->_redirect->getRefererUrl();
    }

    /**
     * @param Collection $collection
     * @return void
     */
    abstract protected function massAction(Collection $collection): void;

    /**
     * @return bool
     */
    private function canExecute(): bool
    {
        return $this->_formKeyValidator->validate($this->getRequest()) && $this->getRequest()->isPost();
    }
}
