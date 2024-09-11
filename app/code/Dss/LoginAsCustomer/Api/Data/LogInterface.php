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

namespace Dss\LoginAsCustomer\Api\Data;

interface LogInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    public const ENTITY_ID       = 'entity_id';
    public const CUSTOMER_ID     = 'customer_id';
    public const CUSTOMER_EMAIL  = 'customer_email';
    public const ADMIN_ID        = 'admin_id';
    public const ADMIN_NAME      = 'admin_name';
    public const LOGGED_IN       = 'logged_in';
    /**#@-*/

    /**
     * Get Entity Id
     *
     * @return int|null
     */
    public function getEntityId(): ?int;

    /**
     * Get Customer Id
     *
     * @return string
     */
    public function getCustomerId(): string;

    /**
     * Get Customer Email
     *
     * @return string|null
     */
    public function getCustomerEmail(): ?string;

    /**
     * Get Admin Id
     *
     * @return string|null
     */
    public function getAdminId(): ?string;

    /**
     * Get Admin Name
     *
     * @return string|null
     */
    public function getAdminName(): ?string;
    
    /**
     * Get Logged In
     *
     * @return string|null
     */
    public function getLoggedIn(): ?string;

    /**
     * Set Entity Id
     *
     * @param int $id
     * @return LogInterface
     */
    public function setEntityId($id): LogInterface;

    /**
     * Set Customer Id
     *
     * @param string $customerId
     * @return LogInterface
     */
    public function setCustomerId(string $customerId): LogInterface;

    /**
     * Set Cutomer Email
     *
     * @param string $cutomerEmail
     * @return LogInterface
     */
    public function setCustomerEmail(string $cutomerEmail): LogInterface;

    /**
     * Set Admin Id
     *
     * @param string $adminId
     * @return LogInterface
     */
    public function setAdminId(string $adminId): LogInterface;

    /**
     * Set Admin Name
     *
     * @param string $adminName
     * @return LogInterface
     */
    public function setAdminName(string $adminName): LogInterface;

    /**
     * Set Logged In
     *
     * @param string $loggedIn
     * @return LogInterface
     */
    public function setLoggedIn(string $loggedIn): LogInterface;
}
