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

class DeleteButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * Get data for delete button
     *
     * @return array
     */
    public function getButtonData()
    {
        $data = [];
        if ($this->getId()) {
            $data = [
                'label'      => __('Delete Offer'),
                'class'      => 'delete',
                'on_click'   => 'deleteConfirm(\''
                    . __('Are you sure you want to delete this offer?')
                    . '\', \'' . $this->getDeleteUrl() . '\')',
                'sort_order' => 10,
            ];
        }

        return $data;
    }

    /**
     * Get URL for delete button
     *
     * @return string
     */
    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete', ['id' => $this->getId()]);
    }
}
