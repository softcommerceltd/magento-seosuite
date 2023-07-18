<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\SeoSuite\Block\Html\Head;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use SoftCommerce\SeoSuite\Model\GetCanonicalUrlInterface;

/**
 * @inheritDoc
 */
class Canonical extends Template
{
    /**
     * @var GetCanonicalUrlInterface
     */
    protected GetCanonicalUrlInterface $getCanonicalUrl;

    /**
     * @param GetCanonicalUrlInterface $getCanonicalUrl
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        GetCanonicalUrlInterface $getCanonicalUrl,
        Context $context,
        array $data = []
    ) {
        $this->getCanonicalUrl = $getCanonicalUrl;
        parent::__construct($context, $data);
    }

    /**
     * @return string|null
     */
    public function getCanonicalUrl(): ?string
    {
        return $this->getCanonicalUrl->execute();
    }
}
