<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\SeoSuite\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use SoftCommerce\SeoSuite\Model\GetCanonicalUrlInterface;

/**
 * @inheritDoc
 */
class HtmlHeadCanonical implements ArgumentInterface
{
    /**
     * @var GetCanonicalUrlInterface
     */
    private GetCanonicalUrlInterface $getCanonicalUrl;

    /**
     * @param GetCanonicalUrlInterface $getCanonicalUrl
     */
    public function __construct(GetCanonicalUrlInterface $getCanonicalUrl)
    {
        $this->getCanonicalUrl = $getCanonicalUrl;
    }

    /**
     * @return string|null
     */
    public function getCanonicalUrl(): ?string
    {
        return $this->getCanonicalUrl->execute();
    }
}
