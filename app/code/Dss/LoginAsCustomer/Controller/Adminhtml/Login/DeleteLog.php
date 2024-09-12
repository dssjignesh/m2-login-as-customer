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

namespace Dss\LoginAsCustomer\Controller\Adminhtml\Login;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;
use Dss\LoginAsCustomer\Model\ResourceModel\Log\CollectionFactory;
use Magento\Framework\App\Action\HttpPostActionInterface;

class DeleteLog extends \Magento\Backend\App\Action implements HttpPostActionInterface
{
    /**
     * DeleteLog Constructor
     *
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        protected Filter $filter,
        protected CollectionFactory $collectionFactory
    ) {
        parent::__construct($context);
    }

    /**
     * Hold selected orders
     */
    public function execute()
    {
        $log            = $this->collectionFactory->create();
        $collection     = $this->filter->getCollection($log);
        $collectionSize = $collection->getSize();
        foreach ($collection as $customer) {
            $customer->delete();
        }
        $this->messageManager->addSuccess(__('You have deleted %1 customer(s).', $collectionSize));

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        return $resultRedirect->setPath('dssloginascustomer/log/index');
    }
}
