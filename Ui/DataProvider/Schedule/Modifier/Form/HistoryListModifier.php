<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\ProfileSchedule\Ui\DataProvider\Schedule\Modifier\Form;

use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use SoftCommerce\Profile\Ui\DataProvider\Modifier\Form\MetadataPoolInterface;

/**
 * @inheritDoc
 */
class HistoryListModifier implements ModifierInterface
{
    private const DATA_COMPONENT = 'profile_history_listing';
    private const DATA_SOURCE = 'history_list';
    private const FORM_NAME = 'softcommerce_profile_schedule_form';

    /**
     * @var MetadataPoolInterface
     */
    private MetadataPoolInterface $metadataPool;

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @param MetadataPoolInterface $metadataPool
     * @param RequestInterface $request
     */
    public function __construct(
        MetadataPoolInterface $metadataPool,
        RequestInterface $request
    ) {
        $this->metadataPool = $metadataPool;
        $this->request = $request;
    }

    /**
     * @inheritDoc
     */
    public function modifyData(array $data): array
    {
        return $data;
    }

    /**
     * @inheritDoc
     */
    public function modifyMeta(array $meta): array
    {
        if (!$this->canIncludeListing()) {
            return $meta;
        }

        $metaData = $this->metadataPool->get(self::FORM_NAME);
        if (!isset($metaData['children'][self::DATA_SOURCE]['children'][self::DATA_COMPONENT])) {
            return $meta;
        }

        $meta[self::DATA_SOURCE]['arguments']['data']['config']['visible'] = true;
        $meta[self::DATA_SOURCE]['children'][self::DATA_COMPONENT]['arguments']['data']['config']['autoRender'] = true;

        return $meta;
    }

    /**
     * @return bool
     */
    private function canIncludeListing(): bool
    {
        return $this->request->getParam('id')
            && !$this->request->getParam('isModal', false)
            && !$this->request->getParam('popup', false);
    }
}
