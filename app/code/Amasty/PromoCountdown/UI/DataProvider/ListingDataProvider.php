<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_PromoCountdown
 */


namespace Amasty\PromoCountdown\UI\DataProvider;

use Amasty\Base\Model\Serializer;
use Magento\Widget\Model\ResourceModel\Widget\Instance\CollectionFactory;

class ListingDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var Serializer
     */
    private $serializer;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        Serializer $serializer,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);

        $this->collection = null;
        $this->collectionFactory = $collectionFactory;
        $this->serializer = $serializer;
    }

    public function getCollection()
    {
        if (!$this->collection) {
            $this->collection = $this->collectionFactory->create()->addFieldToFilter(
                'instance_type',
                \Amasty\PromoCountdown\Block\Widgets\Countdown::class
            );
        }

        return parent::getCollection();
    }

    public function getData()
    {
        $collection = $this->getCollection();
        $data['totalRecords'] = $collection->getSize();
        $data['items'] = [];

        $items = $collection->getItems();

        /** @var \Magento\Widget\Model\Widget\Instance $item */
        foreach ($items as $item) {
            $params = $item->getWidgetParameters();

            if (!$params) {
                $params = $this->serializer->unserialize($item->getData('widget_parameters'));
            }

            $itemData = $item->getData();
            $itemData['date_to'] = empty($params['date_to']) ? __('No end time.') : $params['date_to'];
            $itemData['date_from'] = empty($params['date_from']) ? __('No start time.') : $params['date_from'];
            $data['items'][] = $itemData;
        }

        return $data;
    }
}
