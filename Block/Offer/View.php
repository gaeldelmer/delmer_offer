<?php

/**
 * UNLICENSED
 *
 * @package     Delmer_Offer
 * @subpackage  Block
 * @author      Gaël Delmer <gael.delmer@gmail.com>
 */

namespace Delmer\Offer\Block\Offer;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Framework\View\Element\Template;
use Magento\Framework\UrlInterface;

use Delmer\Offer\Model\ResourceModel\Offer\CollectionFactory;
use Delmer\Offer\Model\ResourceModel\Offer\Collection as OfferCollection;
use Delmer\Offer\Model\ResourceModel\Offer;

class View extends Template
{
    /**
     * @var CollectionFactory
     */
    private $offerCollection;

    /**
     * @var Resolver
     */
    private $layerResolver;

    /**
     * @var Offer
     */
    private $offerResourceModel;

    /**
     * View constructor
     *
     * @param CollectionFactory $offerCollection
     * @param Context $context
     * @param Offer $offerResourceModel
     * @param Resolver $layerResolver
     * @param array $data
     */
    public function __construct(
        CollectionFactory $offerCollection,
        Context $context,
        Offer $offerResourceModel,
        Resolver $layerResolver,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->layerResolver = $layerResolver;
        $this->offerCollection = $offerCollection;
        $this->offerResourceModel = $offerResourceModel;
    }

    /**
     * Get offers for current page
     *
     * @return OfferCollection
     */
    public function getOffers()
    {
        $now = new \DateTime();
        $offerCollection = $this->offerCollection->create();

        // Get current page category
        $categoryId = $this->layerResolver->get()
            ->getCurrentCategory()
            ->getId()
        ;

        $offerIds = $this->offerResourceModel->getOffersByCategory($categoryId);

        $offerCollection->addFieldToFilter('id', ['in' => $offerIds])
            ->addFieldToFilter('begin_date', ['lteq' => $now->format('Y-m-d H:i:s')])
            ->addFieldToFilter('end_date', ['gteq' => $now->format('Y-m-d H:i:s')]);

        return $offerCollection;
    }

    /**
     * Return Image Folder Path
     *
     * @return string
     * @throws NoSuchEntityException
     */
    public function getMediaPath()
    {
        return $this->_storeManager->getStore()
                ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . 'delmer/offer/';
    }
}
