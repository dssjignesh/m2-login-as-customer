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

namespace Dss\LoginAsCustomer\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\UrlInterface;
use Dss\LoginAsCustomer\Helper\Data as HelperData;

class ViewAction extends Column
{
    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param HelperData $helper
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        protected UrlInterface $urlBuilder,
        protected HelperData $helper,
        array $components = [],
        array $data = [],
    ) {
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
        $isAllowed = $this->helper->isAllowed();
        
        if (isset($dataSource['data']['items']) && $isAllowed) {
            $storeId = $this->context->getFilterParam('store_id');
            foreach ($dataSource['data']['items'] as &$item) {
                if ($item['customer_id']) {
                    $item[$this->getData('name')]['entity_id'] = [
                        'href' => $this->urlBuilder->getUrl(
                            'dssloginascustomer/login/index',
                            ['customer_id' => $item['customer_id'], 'store' => $storeId]
                        ),
                        'label' => __('Login As Customer'),
                        'hidden' => false,
                        'target' => '_blank',
                        '__disableTmpl' => true
                    ];
                }
            }
        }

        return $dataSource;
    }
}
