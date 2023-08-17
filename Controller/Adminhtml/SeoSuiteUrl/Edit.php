<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\SeoSuite\Controller\Adminhtml\SeoSuiteUrl;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;

/**
 * @inheritDoc
 */
class Edit extends Action implements HttpGetActionInterface
{
    /**
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'SoftCommerce_SeoSuite::url_relationship_manage';

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage
            ->setActiveMenu('SoftCommerce_SeoSuite::url_relationship')
            ->addBreadcrumb(__('URL Relationship'), __('URL Relationship'))
            ->addBreadcrumb(
                $id ? __('Manage URL Relationship') : __('New URL Relationship'),
                $id ? __('Manage URL Relationship') : __('New URL Relationship')
            );

        $resultPage->getConfig()->getTitle()->prepend(
            $id ? __('Manage URL Relationship') : __('New URL Relationship')
        );

        return $resultPage;
    }
}
