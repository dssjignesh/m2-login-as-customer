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

namespace Dss\LoginAsCustomer\Helper;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Math\Random;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    public const TIME_FRAME = 60;
    public const XML_PATH_ENABLED = 'dss_loginAscustomer/general/enable';
    public const XML_PATH_CUSTOMER_GRID_LOGIN_COLUMN = 'dss_loginAscustomer/general/customer_grid_login_column';
    public const XML_PATH_DSIABLE_PAGE_CACHE = 'dss_loginAscustomer/general/disable_page_cache';

    /**
     * Data constructor.
     *
     * @param Context $context
     * @param DateTime $dateTime
     * @param Random $random
     */
    public function __construct(
        Context $context,
        protected DateTime $dateTime,
        protected Random $random
    ) {
        parent::__construct($context);
    }

    /**
     * Is enable
     *
     * @return mixed
     */
    public function isEnable(): mixed
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get login column
     *
     * @return mixed
     */
    public function getCustomerGridLoginColumn(): mixed
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CUSTOMER_GRID_LOGIN_COLUMN,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is diable page cache
     *
     * @return mixed
     */
    public function isDisablePageCache(): mixed
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_DSIABLE_PAGE_CACHE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve login datetime point
     *
     * @return false|string
     */
    public function getDateTimePoint(): false|string
    {
        return date('Y-m-d H:i:s', $this->dateTime->gmtTimestamp() - self::TIME_FRAME);
    }

    /**
     * GMT timestamp
     *
     * @return int
     */
    public function gmtTimestamp(): int
    {
        return $this->dateTime->gmtTimestamp();
    }

    /**
     * Get random string
     *
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getRandomString(): string
    {
        return $this->random->getRandomString(64);
    }
}
