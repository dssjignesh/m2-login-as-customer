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

namespace Dss\LoginAsCustomer\Block\Adminhtml\Edit;

use Magento\Backend\Block\Widget\Context;
use Magento\Customer\Block\Adminhtml\Edit\GenericButton;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Dss\LoginAsCustomer\Helper\Data;

class LoginAsCustomerButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * Button constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param Data $helper
     */
    public function __construct(
        Context $context,
        Registry $registry,
        protected Data $helper
    ) {
        parent::__construct($context, $registry);
    }

    /**
     * This will return the button's Data
     *
     * @return array
     */
    public function getButtonData(): array
    {
        $customerId = $this->getCustomerId();
        $isAllowed  = $this->helper->isAllowed();
        $data       = [];
        if ($customerId && $isAllowed) {
            $data = [
                'label'      => __('Login as Customer'),
                'on_click'   => sprintf("window.open('%s');", $this->getLoginUrl()),
                'sort_order' => 60
            ];
        }
        return $data;
    }

    /**
     * This will return the Login Url
     *
     * @return string
     */
    public function getLoginUrl(): string
    {
        return $this->getUrl('dssloginascustomer/login/index', ['customer_id' => $this->getCustomerId()]);
    }
}
