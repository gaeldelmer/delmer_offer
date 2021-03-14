<?php

/**
 * UNLICENSED
 *
 * @package     Delmer_Offer
 * @subpackage  Model
 * @author      Gaël Delmer <gael.delmer@gmail.com>
 */

namespace Delmer\Offer\Model\Offer;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Framework\UrlInterface;

use Delmer\Offer\Model\ResourceModel\Offer\CollectionFactory;

class DataProvider extends AbstractDataProvider
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * DataProvider constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $offerCollectionFactory
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        CollectionFactory $offerCollectionFactory,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, [], []);

        $this->collection = $offerCollectionFactory->create();
        $this->storeManager = $storeManager;
    }

    /**
     * Retrieve and convert offer data
     *
     * @return array
     * @throws
     */
    public function getData()
    {
        $this->data = [];
        $offers = $this->collection->getItems();

        // Format image and categories data
        foreach ($offers as $offer) {
            $offerId = $offer->getId();
            $offerData = $offer->getData();

            if (isset($offerData['image']) && $offerData['image'] != '') {
                $image = $offerData['image'];
                unset($offerData['image']);
                $offerData['image'][0]['name'] = $image;
                $offerData['image'][0]['url']  =
                    $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA)
                    . ''
                    . 'delmer/offer/'
                    . $image
                ;
            }

            $this->data[$offerId]['offers'] = $offerData;
            $this->data[$offerId]['offers']["categories"] = $offer->getCategories();
        }

        return $this->data;
    }
}
