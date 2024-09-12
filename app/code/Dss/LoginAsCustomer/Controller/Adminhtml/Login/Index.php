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

namespace Dss\LoginAsCustomer\Controller\Adminhtml\Login;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Url;
use Dss\LoginAsCustomer\Helper\Data;
use Dss\LoginAsCustomer\Model\LogFactory;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Backend\Model\Auth\Session;
use Magento\User\Model\User;

class Index extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Dss_LoginAsCustomer::allow';

    /**
     * Index Constructor]
     *
     * @param Context $context
     * @param LogFactory $logFactory
     * @param Session $authSession
     * @param OrderRepositoryInterface $orderRepository
     * @param CustomerRepositoryInterface $customerRepository
     * @param Data $helper
     */
    public function __construct(
        Context $context,
        protected LogFactory $logFactory,
        protected Session $authSession,
        protected OrderRepositoryInterface $orderRepository,
        protected CustomerRepositoryInterface $customerRepository,
        protected Data $helper
    ) {
        parent::__construct($context);
    }

    /**
     * This will for get costomer
     *
     * @return User
     */
    public function getCurrentUser(): User
    {
        return $this->authSession->getUser();
    }

    /**
     * Excute the action
     *
     * @return ResponseInterface|ResultInterface
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function execute()
    {
        try {
            $params   = $this->getRequest()->getParams();
            $customer = $this->customerRepository->getById($params['customer_id']);
            $log      = $this->logFactory->create();
            $log->setCustomerId($customer->getId())
            ->setCustomerEmail($customer->getEmail())
            ->setAdminId($this->getCurrentUser()->getId())
            ->setAdminName($this->getCurrentUser()->getUsername())
            ->save();
            $store  = $this->helper->getStore($customer);
            $params = [
                'customer_id' => $customer->getId(),
                'admin_key'   => $this->authSession->getSessionId()
            ];
            $key      = base64_encode(json_encode($params));
            $loginUrl = $this->_objectManager->create(Url::class)
                ->setScope($store)
                ->getUrl('dssloginascustomer/login/index', ['key' => $key, '_nosid' => true]);
            return $this->getResponse()->setRedirect($loginUrl);

        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__('Customer does not exist.'));
            return $this->_redirect('customer');
        }

        $this->messageManager->addErrorMessage(__('An unspecified error occurred. Please contact us for assistance.'));
        return $this->_redirect('customer');
    }
}
