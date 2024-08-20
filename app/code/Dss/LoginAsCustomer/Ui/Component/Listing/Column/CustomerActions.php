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

namespace Dss\LoginAsCustomer\Ui\Component\Listing\Column;

use Dss\LoginAsCustomer\Helper\Data;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class CustomerActions extends Column
{
    /**
     * CustomerActions constructor.
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param AuthorizationInterface $authorization
     * @param Data $dataHelper
     * @param array $components
     * @param array $data
     */
    // @codingStandardsIgnoreStart
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        protected UrlInterface $urlBuilder,
        protected AuthorizationInterface $authorization,
        protected Data $dataHelper,
        array $components = [],
        array $data = []
    ) {
        if (
            !$this->dataHelper->isEnable() || $this->dataHelper->getCustomerGridLoginColumn() == 'actions' ||
            !$this->authorization->isAllowed('Dss_LoginAsCustomer::login_button')
        ) {
            unset($data);
            $data = [];
        }

        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $item[$this->getData('name')] = $this->prepareItem($item);
            }
        }

        return $dataSource;
    }

    /**
     * Get data
     *
     * @param array $item
     * @return string
     */
    protected function prepareItem($item): string
    {
        $url = $this->urlBuilder->getUrl('loginascustomer/customer/login', ['customer_id' => $item['entity_id']]);
        return '<a onMouseOver="this.style.cursor=&#039;pointer&#039;"
            onclick="window.open(&quot;' . $url . '&quot;)">' . 'Login' . '</a>';
    }
}
