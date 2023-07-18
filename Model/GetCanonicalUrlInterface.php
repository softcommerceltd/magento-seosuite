<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\SeoSuite\Model;

/**
 * Interface GetCanonicalUrlInterface
 * used to provide canonical URL.
 */
interface GetCanonicalUrlInterface
{
    /**
     * @return string|null
     */
    public function execute(): ?string;
}
