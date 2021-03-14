<?php

/**
 * UNLICENSED
 *
 * @package     Delmer_Offer
 * @subpackage  Block
 * @author      Gaël Delmer <gael.delmer@gmail.com>
 */

namespace Delmer\Offer\Block\Adminhtml\Offer\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class ResetButton implements ButtonProviderInterface
{
    /**
     * Get data for reset button
     *
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label'      => __('Reset Form'),
            'class'      => 'reset',
            'on_click'   => 'location.reload();',
            'sort_order' => 20,
        ];
    }
}
