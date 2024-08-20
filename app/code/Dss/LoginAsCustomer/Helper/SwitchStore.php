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

use Magento\Framework\App\Action\Context as ActionContext;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\Store\Api\StoreRepositoryInterface;
use Magento\Store\Model\StoreIsInactiveException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\StoreSwitcherInterface;

class SwitchStore
{
    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * SwitcherStore constructor.
     * @param StoreSwitcherInterface $storeSwitcher
     * @param StoreRepositoryInterface $storeRepository
     * @param StoreManagerInterface $storeManager
     * @param UrlInterface $url
     * @param ActionContext $context
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        private StoreSwitcherInterface $storeSwitcher,
        private StoreRepositoryInterface $storeRepository,
        private StoreManagerInterface $storeManager,
        private UrlInterface $url,
        private ActionContext $context
    ) {
        $this->messageManager = $context->getMessageManager();
    }

    /**
     * After Customer Login
     *
     * @param string $url
     * @param string $storecode
     * @return mixed
     * @throws NoSuchEntityException
     * @throws \Magento\Store\Model\StoreSwitcher\CannotSwitchStoreException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function switchStoreView($url, $storecode): mixed
    {
        $error = null;
        $fromStoreStoreCode = $this->storeManager->getStore()->getCode();
        try {
            $fromStore = $this->storeRepository->get($fromStoreStoreCode);
            $targetStore = $this->storeRepository->getActiveStoreByCode($storecode);
        } catch (NoSuchEntityException $e) {
            $error = __("The store that was requested wasn't found. Verify the store and try again.");
        } catch (StoreIsInactiveException $e) {
            $error = __('Requested store is inactive');
        }
        if ($error !== null) {
            $this->messageManager->addErrorMessage($error);
        } else {
            $this->storeSwitcher->switch($fromStore, $targetStore, $url);
            $this->messageManager->getMessages(true);
        }
        return $this;
    }
}
