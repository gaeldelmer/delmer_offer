<?php

/**
 * UNLICENSED
 *
 * @package     Delmer_Offer
 * @subpackage  Controller
 * @author      Gaël Delmer <gael.delmer@gmail.com>
 */

namespace Delmer\Offer\Controller\Adminhtml\Offer;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\Model\Session;

use Delmer\Offer\Model\ResourceModel\Offer;
use Delmer\Offer\Model\OfferFactory;

class Add extends Action
{
    /**
     * @var OfferFactory
     */
    protected $offerFactory;

    /**
     * @var Offer
     */
    private $offerResourceModel;

    /**
     * @var PageFactory
     */
    protected $pageFactory;

    /**
     * @var Session
     */
    protected $session;

    /**
     * Add constructor
     *
     * @param Context $context
     * @param OfferFactory $offerFactory
     * @param Offer $offerResourceModel
     * @param PageFactory $pageFactory
     * @param Session $session
     */
    public function __construct(
        Context $context,
        OfferFactory $offerFactory,
        Offer $offerResourceModel,
        PageFactory $pageFactory,
        Session $session
    ) {
        parent::__construct($context);

        $this->offerFactory = $offerFactory;
        $this->offerResourceModel = $offerResourceModel;
        $this->pageFactory = $pageFactory;
        $this->session = $session;
    }

    public function execute()
    {
        $resultPage = $this->pageFactory->create();
        $offerModel = $this->offerFactory->create();

        if ($offerId = $this->getRequest()->getParam('id')) {
            $this->offerResourceModel->load($offerModel, $offerId);
        }

        return $resultPage;
    }
}
