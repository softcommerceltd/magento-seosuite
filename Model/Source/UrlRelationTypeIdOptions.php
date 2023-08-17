<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\SeoSuite\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * @inheritDoc
 */
class UrlRelationTypeIdOptions implements OptionSourceInterface
{
    public const CANONICAL = 1;
    public const HREFLANG = 2;

    /**
     * Options array
     *
     * @var array
     */
    private array $options = [];

    /**
     * @inheritDoc
     */
    public function toOptionArray(): array
    {
        if (!$this->options) {
            $this->options = [
                ['value' => self::CANONICAL, 'label' => __('Canonical')],
                ['value' => self::HREFLANG, 'label' => __('Hreflang')]
            ];
        }

        return $this->options;
    }
}
