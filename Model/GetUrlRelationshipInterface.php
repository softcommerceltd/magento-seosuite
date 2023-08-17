<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\SeoSuite\Model;

/**
 * Interface GetUrlRelationshipInterface
 * used to obtain URL relationship data
 * in the array format.
 */
interface GetUrlRelationshipInterface
{
    /**
     * @param int $typeId
     * @param string $requestPath
     * @return string|null
     */
    public function getTargetPath(int $typeId, string $requestPath): ?string;
}
