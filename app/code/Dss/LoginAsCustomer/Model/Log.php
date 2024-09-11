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

namespace Dss\LoginAsCustomer\Model;

use Dss\LoginAsCustomer\Api\Data\LogInterface;
use Magento\Framework\Model\AbstractModel;

class Log extends AbstractModel implements LogInterface
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(\Dss\LoginAsCustomer\Model\ResourceModel\Log::class);
    }

    /**
     * Get Entity Id
     *
     * @return int
     */
    public function getEntityId(): int
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * Get Customer Id
     *
     * @return string
     */
    public function getCustomerId(): string
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * Get Customer Email
     *
     * @return string
     */
    public function getCustomerEmail(): string
    {
        return (string)$this->getData(self::CUSTOMER_EMAIL);
    }

    /**
     * Get Admin Id
     *
     * @return string
     */
    public function getAdminId(): string
    {
        return $this->getData(self::ADMIN_ID);
    }

    /**
     * Get Admin Name
     *
     * @return string
     */
    public function getAdminName(): string
    {
        return $this->getData(self::ADMIN_NAME);
    }

    /**
     * Get Logged In
     *
     * @return string
     */
    public function getLoggedIn(): string
    {
        return $this->getData(self::LOGGED_IN);
    }

    /**
     * Set Entity Id
     *
     * @param int $id
     * @return LogInterface
     */
    public function setEntityId($id): LogInterface
    {
        return $this->setData(self::ENTITY_ID, $id);
    }

    /**
     * Set Customer Id
     *
     * @param string $customerId
     * @return LogInterface
     */
    public function setCustomerId(string $customerId): LogInterface
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * Set Customer Email
     *
     * @param string $customerEmail
     * @return LogInterface
     */
    public function setCustomerEmail(string $customerEmail): LogInterface
    {
        return $this->setData(self::CUSTOMER_EMAIL, $customerEmail);
    }

    /**
     * Set Admin Id
     *
     * @param string $adminId
     * @return LogInterface
     */
    public function setAdminId(string $adminId): LogInterface
    {
        return $this->setData(self::ADMIN_ID, $adminId);
    }

    /**
     * Set Admin Name
     *
     * @param string $adminName
     * @return LogInterface
     */
    public function setAdminName(string $adminName): LogInterface
    {
        return $this->setData(self::ADMIN_NAME, $adminName);
    }

    /**
     * Set Logged In
     *
     * @param string $loggedIn
     * @return LogInterface
     */
    public function setLoggedIn(string $loggedIn): LogInterface
    {
        return $this->setData(self::LOGGED_IN, $loggedIn);
    }
}
