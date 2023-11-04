<?php

namespace Magento\StockDeliveryDate\Controller\Stock;

use Magento\Catalog\Api\ProductRepositoryInterface;

/**
 * Class Index
 * @package Magento\StockDeliveryDate\Controller\Stock
 */
class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_pageFactory;
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * Index constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $pageFactory
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        ProductRepositoryInterface $productRepository
    )
    {
        $this->_pageFactory = $pageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->productRepository = $productRepository;
        return parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $response = [];
        if (!empty($id)):
            $stockDeliveryDate = $this->productRepository->getById($id)
                ->getCustomAttribute('stock_delivery_date');
            if (!empty($stockDeliveryDate)):
                $deliveryDate = $stockDeliveryDate->getValue();
                $response['date'] = date("d-m-Y", strtotime($deliveryDate));
                $response['response'] = 'true';
            else:
                $response['response'] = 'false';
            endif;
        else:
            $response['response'] = 'false';
        endif;
        $resultJson = $this->resultJsonFactory->create();
        $resultJson->setData($response);
        return $resultJson;
    }

}