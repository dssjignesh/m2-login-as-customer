<?php

declare(strict_types=1);

/**
 * Digit Software Solutions.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 *
 * @category  Dss
 * @package   Dss_LoginAsCustomer
 * @author    Extension Team
 * @copyright Copyright (c) 2024 Digit Software Solutions. ( https://digitsoftsol.com )
 */

namespace Dss\LoginAsCustomer\Plugin;

use Magento\Sales\Block\Adminhtml\Order\View;
use Dss\LoginAsCustomer\Helper\Data;

class LoginButton
{
    /**
     * @param Data $helper
     */
    public function __construct(
        protected Data $helper
    ) {
    }

    /**
     * Function for before get back url
     *
     * @param View $subject
     */
    public function beforeGetBackUrl(View $subject)
    {
        $customerId = $subject->getOrder()->getCustomerId();
        $isAllowed  = $this->helper->isAllowed();
        if ($customerId && $isAllowed) {
            $subject->addButton('dss_login_as_customer', [
                'label'    => __('Login as Customer'),
                'on_click' => sprintf(
                    "window.open('%s');",
                    $subject->getUrl('dssloginascustomer/login/index', ['customer_id' => $customerId])
                )
            ], 60);
        }
    }
}
