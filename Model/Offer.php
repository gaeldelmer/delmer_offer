<?php

/**
 * UNLICENSED
 *
 * @package     Delmer_Offer
 * @subpackage  Model
 * @author      Gaël Delmer <gael.delmer@gmail.com>
 */

namespace Delmer\Offer\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;

use Delmer\Offer\Model\ResourceModel\Offer as ResourceModelOffer;

class Offer extends AbstractModel
{
    /**
     * @var ResourceModelOffer
     */
    private $offerResourceModel;

    /**
     * Offer constructor
     *
     * @param Context $context
     * @param ResourceModelOffer $offerResourceModel
     * @param Registry $registry
     */
    public function __construct(
        Context $context,
        ResourceModelOffer $offerResourceModel,
        Registry $registry
    )
    {
        parent::__construct($context, $registry);

        $this->offerResourceModel = $offerResourceModel;
    }

    /**
     * Offer init constructor
     */
    protected function _construct()
    {
        $this->_init(ResourceModelOffer::class);
    }

    /**
     * Get categories for an offer
     *
     * @return array
     */
    public function getCategories()
    {
        if (!$this->hasData('category_ids')) {
            $ids = $this->offerResourceModel->getCategories($this);
            $this->setData('category_ids', $ids);
        }

        return (array)$this->_getData('category_ids');
    }
}
