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

namespace Dss\LoginAsCustomer\Controller\Login;

use Magento\Customer\Model\Account\Redirect as AccountRedirect;
use Magento\Customer\Model\SessionFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Forward;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Dss\LoginAsCustomer\Helper\Data;
use Magento\Framework\Url\DecoderInterface;

class Index extends Action
{
    /**
     * Index constructor.
     *
     * @param Context $context
     * @param SessionFactory $customerSession
     * @param AccountRedirect $accountRedirect
     * @param Data $helper
     * @param DecoderInterface $urlDecoder
     */
    public function __construct(
        Context $context,
        protected SessionFactory $customerSession,
        protected AccountRedirect $accountRedirect,
        protected Data $helper,
        protected DecoderInterface $urlDecoder
    ) {
        parent::__construct($context);
    }

    /**
     * Execute the action
     *
     * @return ResponseInterface|Redirect|ResultInterface
     * @throws LocalizedException
     */
    public function execute()
    {
        /**
         * Login new customer as requested on the admin interface
        */
        try {
            $key     = $this->getRequest()->getParam('key');
            $session = $this->customerSession->create();
            $params  = json_decode($this->urlDecoder->decode($key), true);
            $sessionCollection = $this->_objectManager->create(
                \Magento\Security\Model\ResourceModel\AdminSessionInfo\Collection::class
            );
            $sessionCollection->addFieldToFilter('session_id', $params['admin_key'])->addFieldToFilter('status', 1);

            /**
             * Logout any currently logged in customer
            */
            if ($session->isLoggedIn()) {
                $session->logout();
            }

            $session->loginById($params['customer_id']);
            $session->regenerateId();
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('customer/account');

            return $resultRedirect;
        } catch (\Exception $e) {
            throw new LocalizedException(__($e->getMessage()));
        }

        return $this->accountRedirect->getRedirect();
    }
}
