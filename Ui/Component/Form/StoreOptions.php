<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\SeoSuite\Ui\Component\Form;

use Magento\Store\Ui\Component\Listing\Column\Store\Options;

/**
 * @inheritDoc
 */
class StoreOptions extends Options
{
    private const DEFAULT_STORE_VIEW = '0';

    /**
     * @inheritDoc
     */
    public function toOptionArray(): array
    {
        if (null === $this->options) {
            $this->currentOptions['All Store Views']['label'] = __('All Store Views');
            $this->currentOptions['All Store Views']['value'] = self::DEFAULT_STORE_VIEW;

            $this->generateCurrentOptions();

            $this->options = array_values($this->currentOptions);
        }

        return $this->options;
    }
}
