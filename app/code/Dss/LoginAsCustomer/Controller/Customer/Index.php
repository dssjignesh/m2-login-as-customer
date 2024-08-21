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

namespace Dss\LoginAsCustomer\Controller\Customer;

use Magento\Framework\App\Action\Context;
use Dss\LoginAsCustomer\Model\LoginFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\DataObject;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * Index constructor.
     *
     * @param Context $context
     * @param LoginFactory $dssLoginFactory
     * @param Session $customerSession
     */
    public function __construct(
        Context $context,
        protected LoginFactory $dssLoginFactory,
        protected Session $customerSession
    ) {
        parent::__construct($context);
    }

    /**
     * Execute
     *
     * @return ResponseInterface|ResultInterface|void
     */
    public function execute()
    {
        $this->messageManager->getMessages(true);
        $login = $this->initLogin();
        if (!$login) {
            $this->_redirect('/');
            return;
        }

        $isLogIn = $this->customerSession->isLoggedIn();
        if ($isLogIn) {
            $this->customerSession->logout();
        }

        try {
            $login->authenticateCustomer();
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }

        $this->messageManager->addSuccess(
            __('You are logged in as customer: %1', $login->getCustomer()->getName())
        );

        $this->_redirect('*/*/proceed');
    }

    /**
     * Init login
     *
     * @return bool|DataObject
     */
    protected function initLogin(): bool|DataObject
    {
        $secret = $this->getRequest()->getParam('secret');
        if (!$secret) {
            $this->messageManager->addError(__('Cannot login to account. No secret key provided.'));
            return false;
        }

        $oldStoreId = $this->getRequest()->getParam('oldStoreId');
        $login = $this->dssLoginFactory->create()->loadNotUsed($secret);
        if ($login->getId()) {
            if ($oldStoreId === null) {
                $this->messageManager->addNoticeMessage(
                    'The store view where this account has been created was deleted'
                );
            }
            return $login;
        } else {
            $this->messageManager->addError(__('Cannot login to account. Secret key is not valid.'));
            return false;
        }
    }
}
