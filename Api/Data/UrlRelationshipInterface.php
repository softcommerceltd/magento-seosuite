<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\SeoSuite\Api\Data;

/**
 * Interface UrlRelationshipInterface
 * used to provide model entity data.
 */
interface UrlRelationshipInterface
{
    public const DB_TABLE_NAME = 'softcommerce_seosuite_url';

    public const ENTITY_ID = 'entity_id';
    public const STATUS = 'status';
    public const REQUEST_PATH = 'request_path';
    public const TARGET_PATH = 'target_path';
    public const TYPE_ID = 'type_id';
    public const STORE_ID = 'store_id';
    public const UPDATED_AT = 'updated_at';

    /**
     * @return int
     */
    public function getEntityId(): int;

    /**
     * @return bool
     */
    public function getStatus(): bool;

    /**
     * @param bool $statusId
     * @return $this
     */
    public function setStatus(bool $statusId);

    /**
     * @return string|null
     */
    public function getRequestPath(): ?string;

    /**
     * @param string $requestPath
     * @return $this
     */
    public function setRequestPath(string $requestPath);

    /**
     * @return string|null
     */
    public function getTargetPath(): ?string;

    /**
     * @param string $targetPath
     * @return $this
     */
    public function setTargetPath(string $targetPath);

    /**
     * @return int
     */
    public function getTypeId(): int;

    /**
     * @param int $typeId
     * @return $this
     */
    public function setTypeId(int $typeId);

    /**
     * @return int
     */
    public function getStoreId(): int;

    /**
     * @param int $storeId
     * @return $this
     */
    public function setStoreId(int $storeId);

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string;

    /**
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt(string $updatedAt);
}
