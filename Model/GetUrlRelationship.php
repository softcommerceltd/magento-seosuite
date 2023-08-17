<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\SeoSuite\Model;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use SoftCommerce\SeoSuite\Api\Data\UrlRelationshipInterface;

/**
 * @inheritDoc
 */
class GetUrlRelationship implements GetUrlRelationshipInterface
{
    /**
     * @var array
     */
   private array $dataInMemory = [];

    /**
     * @var AdapterInterface
     */
   private AdapterInterface $connection;

   public function __construct(ResourceConnection $resourceConnection)
   {
       $this->connection = $resourceConnection->getConnection();
   }

    /**
     * @param int $typeId
     * @param string $requestPath
     * @return string|null
     */
   public function getTargetPath(int $typeId, string $requestPath): ?string
   {
       if (!isset($this->dataInMemory[$typeId][$requestPath])) {
           $select = $this->connection->select()
               ->from(
                   $this->connection->getTableName(UrlRelationshipInterface::DB_TABLE_NAME),
                   UrlRelationshipInterface::TARGET_PATH
               )
               ->where(UrlRelationshipInterface::TYPE_ID . ' = ?', $typeId)
               ->where(UrlRelationshipInterface::REQUEST_PATH . ' = ?', $requestPath);

           $this->dataInMemory[$typeId][$requestPath] = (string) $this->connection->fetchOne($select);
       }

       return $this->dataInMemory[$typeId][$requestPath];
   }
}
