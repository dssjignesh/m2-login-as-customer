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

namespace Dss\LoginAsCustomer\Plugin\Adminhtml\Grid;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\UrlInterface;
use Dss\LoginAsCustomer\Helper\Data;
use Magento\Framework\AuthorizationInterface;
use Magento\Backend\Model\Session;

class CustomerActions
{
    /**
     * CustomerActions constructor.
     *
     * @param ContextInterface $context
     * @param UrlInterface $urlBuilder
     * @param Data $dataHelper
     * @param AuthorizationInterface $authorization
     * @param Session $session
     */
    public function __construct(
        protected ContextInterface $context,
        protected UrlInterface $urlBuilder,
        protected Data $dataHelper,
        protected AuthorizationInterface $authorization,
        private Session $session
    ) {
    }

    /**
     * Prepare data source
     *
     * @param \Magento\Customer\Ui\Component\Listing\Column\Actions $subject
     * @param array $dataSource
     * @return array
     */
    public function afterPrepareDataSource(
        \Magento\Customer\Ui\Component\Listing\Column\Actions $subject,
        array $dataSource
    ): array {
        if (isset($dataSource['data']['items'])) {
            if ($this->dataHelper->isEnable() && $this->dataHelper->getCustomerGridLoginColumn() == 'actions'
                && $this->authorization->isAllowed('Dss_LoginAsCustomer::login_button')
            ) {
                foreach ($dataSource['data']['items'] as &$item) {
                    $item[$subject->getData('name')] = $this->prepareItem($item, 'preview');
                }
            } else {
                foreach ($dataSource['data']['items'] as &$item) {
                    $item[$subject->getData('name')] = $this->prepareItem($item);
                }
            }
        }

        return $dataSource;
    }

    /**
     * Get data
     *
     * @param array $item
     * @param mixed $type
     * @return string
     */
    protected function prepareItem($item, $type = null): string
    {
        if ($type == 'preview') {
            $urlLogin = $this->urlBuilder
                ->getUrl('loginascustomer/customer/login', ['customer_id' => $item['entity_id']]);
            $urlEdit = $this->urlBuilder->getUrl('customer/index/edit', ['id' => $item['entity_id']]);
            $html = '';
            $html .= '<ul style="list-style:none"><li>' .
                '<a onMouseOver="this.style.cursor=&#039;pointer&#039;"
                href="' . $urlEdit . '">' . 'Edit' . '</a></li>';
            $html .= '<li><a onMouseOver="this.style.cursor=&#039;pointer&#039;"
                onclick="window.open(&quot;' . $urlLogin . '&quot;)">' . 'Login' . '</a></li>';
            $html .= '</ul>';
            return $html;
        } else {
            $urlEdit = $this->urlBuilder->getUrl('customer/index/edit', ['id' => $item['entity_id']]);
            return '<a onMouseOver="this.style.cursor=&#039;pointer&#039;"
                href="' . $urlEdit . '">' . 'Edit' . '</a></li>';
        }
    }
}
