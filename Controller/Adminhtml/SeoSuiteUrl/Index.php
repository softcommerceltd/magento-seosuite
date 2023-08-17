<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\SeoSuite\Controller\Adminhtml\SeoSuiteUrl;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\View\Result\PageFactory;

/**
 * @inheritDoc
 */
class Index extends Action
{
    /**
     * @inheritDoc
     */
    public const ADMIN_RESOURCE = 'SoftCommerce_SeoSuite::url_relationship_manage';

    /**
     * @var PageFactory
     */
    private PageFactory $resultPageFactory;

    /**
     * @var ForwardFactory
     */
    private ForwardFactory $resultForwardFactory;

    /**
     * @param ForwardFactory $resultForwardFactory
     * @param PageFactory $resultPageFactory
     * @param Action\Context $context
     */
    public function __construct(
        ForwardFactory $resultForwardFactory,
        PageFactory $resultPageFactory,
        Action\Context $context
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        if ($this->getRequest()->getQuery('ajax')) {
            $resultForward = $this->resultForwardFactory->create();
            $resultForward->forward('grid');
            return $resultForward;
        }

        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('SoftCommerce_SeoSuite::url_relationship');
        $resultPage->getConfig()->getTitle()->prepend(__('SEO Suite: URL Relationship'));
        $resultPage->addBreadcrumb(__('SEO Suite URL Relationship'), __('SEO Suite URL Relationship'));
        $resultPage->addBreadcrumb(__('Manage SEO Suite URL Relationship'), __('Manage SEO Suite URL Relationship'));

        return $resultPage;
    }
}
