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

namespace Dss\LoginAsCustomer\Block\Adminhtml\Customer\Edit;

use Dss\LoginAsCustomer\Helper\Data;
use Magento\Backend\Block\Widget\Context;
use Magento\Customer\Block\Adminhtml\Edit\GenericButton;
use Magento\Customer\Model\Session;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Framework\AuthorizationInterface;

class Login extends GenericButton implements ButtonProviderInterface
{
    /**
     * Login constructor.
     *
     * @param Data $helper
     * @param Context $context
     * @param Registry $registry
     * @param Session $backendSession
     * @param AuthorizationInterface $authorization
     */
    public function __construct(
        protected Data $helper,
        Context $context,
        Registry $registry,
        private Session $backendSession,
        protected AuthorizationInterface $authorization
    ) {
        parent::__construct($context, $registry);
    }

    /**
     * Get button data
     *
     * @return array
     */
    public function getButtonData(): array
    {
        $customerId = $this->getCustomerId();
        $data = [];
        $canModify = $customerId && $this->authorization->isAllowed('Dss_LoginAsCustomer::login_button');
        if (($canModify) && ($this->helper->isEnable())) {
            $data = [
                'label' => __('Login As Customer'),
                'class' => 'login login-button',
                'on_click' => 'window.open( \'' . $this->getInvalidateTokenUrl() .
                    '\')',
                'sort_order' => 70,
            ];
        }
        return $data;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getInvalidateTokenUrl(): string
    {
        $customers = $this->backendSession->getCustomerData();
        $storeId = null;
        if (isset($customers['account']['store_id'])) {
            $storeId = $customers['account']['store_id'];
        }
        return $this->getUrl('loginascustomer/customer/login', [
            'customer_id' => $this->getCustomerId(),
            'store_id' => $storeId
        ]);
    }
}
