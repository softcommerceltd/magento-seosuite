<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\SeoSuite\Controller\Adminhtml\SeoSuiteUrl;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use SoftCommerce\SeoSuite\Api\Data\UrlRelationshipInterface;
use SoftCommerce\SeoSuite\Api\Data\UrlRelationshipInterfaceFactory;
use SoftCommerce\SeoSuite\Api\UrlRelationshipRepositoryInterface;

/**
 * @inheritDoc
 */
class Save extends Action implements HttpPostActionInterface
{
    /**
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'SoftCommerce_SeoSuite::url_relationship_manage';

    /**
     * @var DataPersistorInterface
     */
    private DataPersistorInterface $dataPersistor;

    /**
     * @var UrlRelationshipInterface|null
     */
    private ?UrlRelationshipInterface $model = null;

    /**
     * @var UrlRelationshipInterfaceFactory
     */
    private UrlRelationshipInterfaceFactory $modelFactory;

    /**
     * @var UrlRelationshipRepositoryInterface
     */
    private UrlRelationshipRepositoryInterface $repository;

    /**
     * @param DataPersistorInterface $dataPersistor
     * @param UrlRelationshipInterfaceFactory $modelFactory
     * @param UrlRelationshipRepositoryInterface $repository
     * @param Context $context
     */
    public function __construct(
        DataPersistorInterface $dataPersistor,
        UrlRelationshipInterfaceFactory $modelFactory,
        UrlRelationshipRepositoryInterface $repository,
        Context $context
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->modelFactory = $modelFactory;
        $this->repository = $repository;
        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute(): ResultInterface
    {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            $this->messageManager->addErrorMessage(__('Wrong request.'));
            return $this->processRedirectOnError();
        }

        try {
            $this->processSave();
            $this->messageManager->addSuccessMessage(__('The entity has been saved.'));
            $resultRedirect = $this->processRedirectOnSuccess();
        } catch (AlreadyExistsException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $resultRedirect = $this->processRedirectOnError('*/*/index');
        } catch (CouldNotSaveException $e) {
            $this->messageManager->addErrorMessage(__('Could not save the entity. Reason: %1', $e->getMessage()));
            $resultRedirect = $this->processRedirectOnError();
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $resultRedirect = $this->processRedirectOnError();
        }

        return $resultRedirect;
    }

    /**
     * @throws CouldNotSaveException
     * @throws LocalizedException
     */
    private function processSave(): void
    {
        if (!$request = $this->getRequest()->getParam('general')) {
            throw new LocalizedException(__('Could not retrieve request data for save.'));
        }

        $model = $this->getModel();

        if ($model->getOrigData(UrlRelationshipInterface::ENTITY_ID)) {
            $model->addData($request);
        } else {
            $model->setData($request);
        }

        $this->model = $this->repository->save($model);
        $this->dataPersistor->set(UrlRelationshipInterface::DB_TABLE_NAME, $model->getData());
    }

    /**
     * @return ResultInterface
     */
    private function processRedirectOnSuccess(): ResultInterface
    {
        if ($this->getRequest()->getParam('back', false)) {
            return $this->resultRedirect(
                '*/*/edit',
                ['id' => $this->getModel()->getEntityId(), '_current' => true]
            );
        } elseif ($this->getRequest()->getParam('redirect_to_new')) {
            return $this->resultRedirect('*/*/new', [], ['error' => false]);
        } else {
            return $this->resultRedirect('*/*/', [], ['error' => false]);
        }
    }

    /**
     * @param string|null $path
     * @return ResultInterface
     */
    private function processRedirectOnError(?string $path = null): ResultInterface
    {
        $entityId = $this->getModel()->getEntityId();
        return $this->resultRedirect(
            $path ?: '*/*/new',
            $entityId ? ['id' => $entityId, '_current' => true] : []
        );
    }

    /**
     * @param string $path
     * @param array $params
     * @return ResultInterface
     */
    private function resultRedirect(string $path = '', array $params = []): ResultInterface
    {
        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath($path, $params);
    }

    /**
     * @return UrlRelationshipInterface
     */
    private function getModel(): UrlRelationshipInterface
    {
        if (null !== $this->model) {
            return $this->model;
        }

        if (!$entityId = $this->getRequest()->getParam('general')[UrlRelationshipInterface::ENTITY_ID] ?? null) {
            $this->model = $this->modelFactory->create();
            return $this->model;
        }

        try {
            $this->model = $this->repository->get($entityId);
        } catch (\Exception $e) {
            $this->model = $this->modelFactory->create();
        }

        return $this->model;
    }
}
