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

use Magento\Backend\App\Action;
use Dss\LoginAsCustomer\Model\LoginFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;

class Index extends Action
{
    /**
     * Index constructor.
     *
     * @param Action\Context $context
     * @param LoginFactory $dssLoginFactory
     */
    public function __construct(
        Action\Context $context,
        protected LoginFactory $dssLoginFactory
    ) {
        parent::__construct($context);
    }

    /**
     * Execute
     *
     * @return ResponseInterface|ResultInterface|void
     */
    public function execute(): void
    {
        if ($this->getRequest()->getParam('ajax')) {
            $this->_forward('grid');
            return;
        }

        $this->dssLoginFactory->create()->deleteNotUsed();

        $this->_view->loadLayout();
        $this->_setActiveMenu('Dss_LoginAsCustomer::login_log');
        $title = __('Login As Customer Log ');
        $this->_view->getPage()->getConfig()->getTitle()->prepend($title);
        $this->_addBreadcrumb($title, $title);
        $this->_view->renderLayout();
    }

    /**
     * Check is allowed access
     *
     * @return bool
     */
    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('Dss_LoginAsCustomer::login_log');
    }
}
