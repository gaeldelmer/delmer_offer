<?php

/**
 * UNLICENSED
 *
 * @package     Delmer_Offer
 * @subpackage  Model
 * @author      Gaël Delmer <gael.delmer@gmail.com>
 */

namespace Delmer\Offer\Model\ResourceModel\Offer;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

use Delmer\Offer\Model\Offer;
use Delmer\Offer\Model\ResourceModel\Offer as ResourceModelOffer;

class Collection extends AbstractCollection
{
    /**
     * Collection constructor
     */
    protected function _construct()
    {
        $this->_init(Offer::class, ResourceModelOffer::class);
    }
}
