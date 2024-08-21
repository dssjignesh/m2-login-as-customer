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

namespace Dss\LoginAsCustomer\Controller\Adminhtml\Customer;

use Dss\LoginAsCustomer\Plugin\FrontendUrl;
use Magento\Backend\App\Action;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Store\Api\StoreRepositoryInterface;
use Dss\LoginAsCustomer\Model\LoginFactory;
use Magento\Backend\Model\Auth\Session;
use Magento\Store\Model\StoreManagerInterface;
use Dss\LoginAsCustomer\Helper\SwitchStore;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\Customer;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;

class Login extends Action
{
    /**
     * Login constructor.
     *
     * @param Action\Context $context
     * @param LoginFactory $dssLoginFactory
     * @param ession $session
     * @param StoreManagerInterface $storeManager
     * @param FrontendUrl $frontendUrl
     * @param CustomerFactory $customerFactory
     * @param SwitchStore $switchStoreview
     * @param StoreRepositoryInterface $storeRepository
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        Action\Context $context,
        protected LoginFactory $dssLoginFactory,
        protected Session $session,
        protected StoreManagerInterface $storeManager,
        protected FrontendUrl $frontendUrl,
        private CustomerFactory $customerFactory,
        protected SwitchStore $switchStoreview,
        private StoreRepositoryInterface $storeRepository,
        private CustomerRepositoryInterface $customerRepository
    ) {
        parent::__construct($context);
    }

    /**
     * Execute
     *
     * @return ResponseInterface|ResultInterface|void
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(): void
    {
        $customerId = (int) $this->getRequest()->getParam('customer_id');
        $customers = $this->customerRepository->getById($customerId);
        $storeIds = $customers->getStoreId();
        if (!isset($storeId)) {
            $storeId = $this->storeManager->getStore(
                $customers->getStoreId()
            )->getId();
        }
        $login = $this->dssLoginFactory->create()->setCustomerId($customerId);
        $login->deleteNotUsed();
        $customer = $login->getCustomer();
        if (!$customer->getId()) {
            $this->messageManager->addError(__('Customer with this ID are no longer exist.'));
            $this->_redirect('customer/index/index');
            return;
        }
        $user = $this->session->getUser();
        $login->generate($user->getId());
        $store = $this->storeRepository->getById($storeId);
        $storeCode = $this->storeManager->getStore($storeId)->getCode();
        $url = $this->frontendUrl->getFrontendUrl()->setScope($store);

        $redirectUrl = $url->getUrl('loginascustomer/customer/index', ['secret' => $login->getSecret(),
            '_nosid' => true,
            'oldStoreId' => $storeIds
            ]);
        $this->getResponse()->setRedirect($redirectUrl);
        $this->switchStoreview->switchStoreView($redirectUrl, $storeCode);
    }

    /**
     * Get customer
     *
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        return $this->customerFactory->create();
    }

    /**
     * Check is allowed access
     *
     * @return bool
     */
    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('Dss_LoginAsCustomer::login_button');
    }
}
