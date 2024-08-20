<?php

declare(strict_types=1);

/**
 * Digit Software Solutions..
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 *
 * @category   Dss
 * @package    Dss_LoginAsCustomer
 * @author     Extension Team
 * @copyright Copyright (c) 2024 Digit Software Solutions. ( https://digitsoftsol.com )
 */

namespace Dss\LoginAsCustomer\Model\PageCache;

use Dss\LoginAsCustomer\Helper\Data;

class ConfigPlugin
{
    /**
     * Initialize dependencies.
     *
     * @param Data $helper
     */
    public function __construct(
        protected Data $helper
    ) {
    }

    /**
     * Disable page cache if needed when admin is logged as customer
     *
     * @param \Magento\PageCache\Model\Config $subject
     * @param bool $result
     * @return bool
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterIsEnabled(\Magento\PageCache\Model\Config $subject, $result): bool
    {
        if ($result) {
            $disable = $this->helper->isDisablePageCache();
            $moduleEnable = $this->helper->isEnable();
            if ($disable && $moduleEnable) {
                $result = false;
            }
        }
        return $result;
    }
}
