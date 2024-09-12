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

namespace Dss\LoginAsCustomer\Helper;

use Magento\Customer\Model\Data\Customer;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\AuthorizationInterface;
use Magento\Store\Api\Data\StoreInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    public const CONFIG_MODULE_PATH = 'loginascustomer';

    /**
     * Data constructor.
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param AuthorizationInterface $authorization
     */
    public function __construct(
        Context $context,
        protected StoreManagerInterface $storeManager,
        protected AuthorizationInterface $authorization
    ) {
        parent::__construct($context);
    }

    /**
     * Get Config Data key
     *
     * @param  string $key
     * @param  null|int $store
     * @return null|string
     */
    public function getConfig($key, $store = null)
    {
        $store  = $this->storeManager->getStore($store);
        $result = $this->scopeConfig->getValue(
            'dss_login_as_customer/' . $key,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
        return $result;
    }

    /**
     * Enable the Module
     *
     * @return boolean
     */
    public function isEnabled(): bool
    {
        return (bool) $this->getConfig('general/enabled');
    }
    
    /**
     * Allowed the module
     *
     * @return boolean
     */
    public function isAllowed(): bool
    {
        return (bool) $this->isEnabled() && $this->authorization->isAllowed('Dss_LoginAsCustomer::allow');
    }

    /**
     * Get Store for Customer
     *
     * @param Customer $customer
     * @return StoreInterface|null
     * @throws NoSuchEntityException
     */
    public function getStore(Customer $customer): ?StoreInterface
    {
        if ($storeId = $customer->getStoreId()) {
            return $this->storeManager->getStore($storeId);
        }

        return $this->storeManager->getDefaultStoreView();
    }
}
