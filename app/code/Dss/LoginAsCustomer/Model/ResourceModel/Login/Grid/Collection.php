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

namespace Dss\LoginAsCustomer\Model\ResourceModel\Login\Grid;

class Collection extends \Dss\LoginAsCustomer\Model\ResourceModel\Login\Collection
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct(): void
    {
        parent::_construct();
        $this->_map['fields']['email'] = 'c.email';
    }

    /**
     * Init collection select
     *
     * @return $this
     */
    protected function _initSelect(): self
    {
        parent::_initSelect();
        $this->getSelect()
            ->joinLeft(
                ['c' => $this->getTable('customer_entity')],
                'c.entity_id = main_table.customer_id',
                ['email']
            )->joinLeft(
                ['a' => $this->getTable('admin_user')],
                'a.user_id = main_table.admin_id',
                ['username']
            );
        $this->addFilterToMap('created_at', 'main_table.created_at');
        return $this;
    }
}
