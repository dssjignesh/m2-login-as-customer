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

namespace Dss\LoginAsCustomer\Model;

use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\Session;
use Dss\LoginAsCustomer\Helper\Data;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use \Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\DataObject;
use Dss\LoginAsCustomer\Model\ResourceModel\Login as LoginResourceModel;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\LocalizedException;

class Login extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'dss_login_as_customer';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'loginascustomer_login';

    /**
     * Login constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param CustomerFactory $customerFactory
     * @param Session $customerSession
     * @param Data $helperData
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        protected CustomerFactory $customerFactory,
        protected Session $customerSession,
        protected Data $helperData,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(LoginResourceModel::class);
    }

    /**
     * Retrieve not used admin login
     *
     * @param  string $secret
     * @return DataObject
     */
    public function loadNotUsed($secret): DataObject
    {
        return $this->getCollection()
            ->addFieldToFilter('secret', $secret)
            ->addFieldToFilter('used', 0)
            ->addFieldToFilter('created_at', ['gt' => $this->helperData->getDateTimePoint()])
            ->setPageSize(1)
            ->getLastItem();
    }

    /**
     * Delete not used credentials
     *
     * @return void
     */
    public function deleteNotUsed(): void
    {
        $resource = $this->getResource();
        $resource->getConnection()->delete(
            $resource->getTable('dss_login_as_customer'),
            [
                'created_at < ?' => $this->helperData->getDateTimePoint(),
                'used = ?' => 0,
            ]
        );
    }

    /**
     * Retrieve customer
     *
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        if (empty($this->customer)) {
            $this->customer = $this->customerFactory->create()
                ->load($this->getCustomerId());
        }
        return $this->customer;
    }

    /**
     * Login customer
     *
     * @return Customer
     * @throws \Exception
     */
    public function authenticateCustomer(): Customer
    {
        $customer = $this->getCustomer();

        if (!$customer->getId()) {
            throw new NoSuchEntityException(__('Customer no longer exists.'));
        }

        try {
            if (!$this->customerSession->loginById($customer->getId())) {
                throw new LocalizedException(__('Customer authentication failed.'));
            }

            $this->customerSession->regenerateId();
            $this->customerSession->setLoggedAsCustomerAdmindId($this->getAdminId());
        } catch (LocalizedException $e) {
            throw $e; // Re-throw to propagate the exception
        }

        $this->setUsed(1)->save();

        return $customer;
    }

    /**
     * Save data
     *
     * @param int $adminId
     * @return Login
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function generate($adminId): Login
    {
        return $this->setData([
            'customer_id' => $this->getCustomerId(),
            'admin_id' => $adminId,
            'secret' => $this->helperData->getRandomString(),
            'used' => 0,
            'created_at' => $this->helperData->gmtTimestamp(),
        ])->save();
    }
}
