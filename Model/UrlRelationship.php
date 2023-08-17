<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\SeoSuite\Model;

use Magento\Framework\DataObject\IdentityInterface;
use SoftCommerce\Core\Model\AbstractModel;
use SoftCommerce\SeoSuite\Api\Data\UrlRelationshipInterface;

/**
 * @inheritDoc
 */
class UrlRelationship extends AbstractModel implements UrlRelationshipInterface, IdentityInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = UrlRelationshipInterface::DB_TABLE_NAME;

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\UrlRelationship::class);
    }

    /**
     * @inheritDoc
     */
    public function getIdentities()
    {
        return [$this->_eventPrefix . '_' . $this->getEntityId()];
    }

    /**
     * @inheritDoc
     */
    public function getEntityId(): int
    {
        return (int) $this->getData(self::ENTITY_ID);
    }

    /**
     * @return bool
     */
    public function getStatus(): bool
    {
        return (bool) $this->getData(self::STATUS);
    }

    /**
     * @param bool $statusId
     * @return $this
     */
    public function setStatus(bool $statusId)
    {
        $this->setData(self::STATUS, $statusId);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRequestPath(): ?string
    {
        return $this->getData(self::REQUEST_PATH);
    }

    /**
     * @inheritDoc
     */
    public function setRequestPath(string $requestPath)
    {
        $this->setData(self::REQUEST_PATH, $requestPath);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getTargetPath(): ?string
    {
        return $this->getData(self::TARGET_PATH);
    }

    /**
     * @inheritDoc
     */
    public function setTargetPath(string $targetPath)
    {
        $this->setData(self::TARGET_PATH, $targetPath);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getTypeId(): int
    {
        return (int) $this->getData(self::TYPE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setTypeId(int $typeId)
    {
        $this->setData(self::TYPE_ID, $typeId);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getStoreId(): int
    {
        return (int) $this->getData(self::STORE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setStoreId(int $storeId)
    {
        $this->setData(self::STORE_ID, $storeId);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedAt(): ?string
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setUpdatedAt(string $updatedAt)
    {
        $this->setData(self::UPDATED_AT, $updatedAt);
        return $this;
    }
}
