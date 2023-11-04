<?php

namespace Magento\StockDeliveryDate\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class ProductTypes
 * @package Magento\StockDeliveryDate\Model\Config\Source
 */
class ProductTypes implements ArrayInterface
{
    /**
     * Prepare Product Type List
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'simple', 'label' => 'Simple Product'],
            ['value' => 'configurable', 'label' => 'Configurable Product'],
            ['value' => 'bundle', 'label' => 'Bundle Product'],
            ['value' => 'grouped', 'label' => 'Grouped Product'],
        ];
    }
}
