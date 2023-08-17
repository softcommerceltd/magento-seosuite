<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\ProfileSchedule\Ui\DataProvider\Schedule\Modifier\Form;

use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use SoftCommerce\ProfileSchedule\Api\Data\ScheduleInterface;
use SoftCommerce\ProfileSchedule\Model\GetProfileIdByScheduleInterface;

/**
 * @inheritDoc
 */
class ProfileAssignmentModifier implements ModifierInterface
{
    private const DATA_COMPONENT = 'assigned_profiles';
    private const DATA_SOURCE = 'profiles';

    /**
     * @var GetProfileIdByScheduleInterface
     */
    private GetProfileIdByScheduleInterface $getProfileIdBySchedule;

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @param GetProfileIdByScheduleInterface $getProfileIdBySchedule
     * @param RequestInterface $request
     */
    public function __construct(
        GetProfileIdByScheduleInterface $getProfileIdBySchedule,
        RequestInterface $request
    ) {
        $this->getProfileIdBySchedule = $getProfileIdBySchedule;
        $this->request = $request;
    }

    /**
     * @inheritDoc
     */
    public function modifyData(array $data): array
    {
        foreach ($data as $scheduleId => $item) {
            if (!$typeId = $item['general'][ScheduleInterface::TYPE_ID] ?? null) {
                continue;
            }

            try {
                $profileData = $this->getProfileIdBySchedule->execute($typeId, (int) $scheduleId);
            } catch (\Exception $e) {
                $profileData = [];
            }

            if ($profileIds = array_column($profileData, GetProfileIdByScheduleInterface::PROFILE_ID)) {
                $data[$scheduleId][self::DATA_SOURCE][self::DATA_COMPONENT] = $profileIds;
            }
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function modifyMeta(array $meta): array
    {
        if (!$this->canHideProfileFieldset()) {
            return $meta;
        }

        $meta[self::DATA_SOURCE]['arguments']['data']['config']['visible'] = false;
        return $meta;
    }

    /**
     * @return bool
     */
    private function canHideProfileFieldset(): bool
    {
        return $this->request->getParam('isModal', false) || $this->request->getParam('popup', false);
    }
}
