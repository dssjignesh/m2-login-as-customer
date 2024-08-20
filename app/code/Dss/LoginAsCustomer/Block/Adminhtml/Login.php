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

namespace Dss\LoginAsCustomer\Block\Adminhtml;

class Login extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct(): void
    {
        parent::_construct();
        $this->removeButton('add');
    }
}
