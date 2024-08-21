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

use Magento\Framework\UrlInterface;

class FrontendUrl
{
    /**
     * FrontendUrl constructor.
     *
     * @param UrlInterface $frontendUrl
     */
    public function __construct(
        protected UrlInterface $frontendUrl
    ) {
    }

    /**
     * Get fronted url
     *
     * @return UrlInterface
     */
    public function getFrontendUrl(): UrlInterface
    {
        return $this->frontendUrl;
    }
}
