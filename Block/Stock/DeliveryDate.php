<?php

namespace Magento\StockDeliveryDate\Block\Stock;

use Magento\Catalog\Api\ProductRepositoryInterface;

/**
 * Class DeliveryDate
 * @package Magento\StockDeliveryDate\Block\Stock
 */
class DeliveryDate extends \Magento\Framework\View\Element\Template
{
    CONST CONFIG_PATH_PRODUCT_TYPE = 'stockdeliverydate/general/product_type_allowed';
    CONST CONFIG_PATH_MODULE_STATUS = 'stockdeliverydate/general/enable';

    CONST MODULE_STATUS_CODE = 0;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * DeliveryDate constructor.
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        \Magento\Framework\Registry $registry,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        ProductRepositoryInterface $productRepository
    )
    {
        $this->_scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->registry = $registry;
        $this->productRepository = $productRepository;
        parent::__construct($context);
    }

    /**
     * Get BaseUrl
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getBaseUrl()
    {
        $baseUrl = $this->storeManager->getStore()->getBaseUrl();
        return $baseUrl;
    }

    /**
     * Getting Current Product Stock Availability Status
     * @return array
     */
    public function getCurrentProductType()
    {
        $response = [];
        $product = $this->registry->registry('current_product');
        $productType = $this->productRepository->getById($product->getId())->getTypeId();
        if (($this->getModuleStatus() != self::MODULE_STATUS_CODE) && ($productType != 'virtual') || ($productType != 'downloadable')) :
            $allowedType = $this->getShowStockProductType();
            if ((!empty($allowedType)) && (in_array($productType, $allowedType))):
                $response['type'] = $productType;
                $stockDeliveryDate = $this->productRepository->getById($product->getId())
                    ->getCustomAttribute('stock_delivery_date');
                if (!empty($stockDeliveryDate)):
                    $deliveryDate = $stockDeliveryDate->getValue();
                    $response['date'] = date("d-m-Y", strtotime($deliveryDate));
                endif;
            endif;
        endif;
        return $response;
    }

    /**
     * Getting Allowed Product Type For Stock Availability Message
     * @return array
     */
    public function getShowStockProductType()
    {
        $typeArray = [];
        $allowedType = $this->_scopeConfig->getValue(self::CONFIG_PATH_PRODUCT_TYPE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if (!empty($allowedType)):
            $typeArray = explode(',', $allowedType);
        endif;
        return $typeArray;
    }

    public function getModuleStatus()
    {
        return $this->_scopeConfig->getValue(self::CONFIG_PATH_MODULE_STATUS, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
}
