<?php

/**
 * UNLICENSED
 *
 * @package     Delmer_Offer
 * @subpackage  Block
 * @author      Gaël Delmer <gael.delmer@gmail.com>
 */

namespace Delmer\Offer\Block\Adminhtml\Offer\Edit;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\UrlInterface;

class GenericButton
{
    /**
     * @var UrlInterface
     */
    protected $urlInterface;

    /**
     * @var Context
     */
    protected $context;

    /**
     * GenericButton constructor
     *
     * @param Context $context
     */
    public function __construct(Context $context) {
        $this->context = $context;
    }

    /**
     * Get Id from request
     *
     * @return int|null
     */
    public function getId()
    {
        try {
            return $this->context->getRequest()->getParam('id');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Generate url by route and parameters
     *
     * @param string $route
     * @param array  $params
     *
     * @return  string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
