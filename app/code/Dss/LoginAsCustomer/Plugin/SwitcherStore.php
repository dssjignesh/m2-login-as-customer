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

namespace Dss\LoginAsCustomer\Plugin;

use Magento\Customer\Controller\Account\LoginPost;
use Magento\Customer\Model\Session;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Dss\LoginAsCustomer\Helper\SwitchStore;

class SwitcherStore
{
    /**
     * SwitcherStore constructor.
     *
     * @param StoreManagerInterface $storeManager
     * @param Session $customerSession
     * @param CustomerRepositoryInterface $customerRepositoryInterface
     * @param UrlInterface $url
     * @param SwitchStore $switchStoreview
     */
    public function __construct(
        private StoreManagerInterface $storeManager,
        private Session $customerSession,
        private CustomerRepositoryInterface $customerRepositoryInterface,
        private UrlInterface $url,
        private SwitchStore $switchStoreview
    ) {
    }

    /**
     * After Customer Login
     *
     * @param LoginPost $subject
     * @param string $result
     * @return mixed
     * @throws NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Store\Model\StoreSwitcher\CannotSwitchStoreException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterExecute(
        LoginPost $subject,
        $result
    ): mixed {
        $customerId = $this->customerSession->getCustomerId();
        $storeId = null;
        if ($customerId) {
            if (isset($customer['store_id'])) {
                $storeId = $customer['store_id'];
            }
            if ($this->customerRepositoryInterface->getById($customerId)) {
                $customerStoreId = $this->customerRepositoryInterface->getById($customerId)->getStoreId();
            } else {
                $customerStoreId = null;
            }

            if ($customerStoreId !== null) {
                $storeId = $customerStoreId;
            }
            $storeCode = $this->storeManager->getStore($storeId)->getCode();
            $url = $this->url->getCurrentUrl();
            $this->switchStoreview->switchStoreView($url, $storeCode);
        }
        return $result;
    }
}
