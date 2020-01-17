<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_PromoCountdown
 */


namespace Amasty\PromoCountdown\Api\Service;

interface DateDifferenceInterface
{
    /**
     * @param int $start
     * @param int $end
     *
     * @return int
     */
    public function getDifference($start, $end);
}
