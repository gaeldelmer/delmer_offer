<?php

/**
 * UNLICENSED
 *
 * @package     Delmer_Offer
 * @subpackage  Controller
 * @author      Gaël Delmer <gael.delmer@gmail.com>
 */

namespace Delmer\Offer\Controller\Adminhtml\Offer;

use \Exception;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Redirect;

use Delmer\Offer\Model\ResourceModel\Offer;
use Delmer\Offer\Model\OfferFactory;

class Delete extends Action
{
    /**
     * @var OfferFactory
     */
    private $offerFactory;

    /**
     * @var Offer
     */
    private $offerResourceModel;

    /**
     * Delete constructor
     *
     * @param Context $context
     * @param OfferFactory $offerFactory
     * @param Offer $offerResourceModel
     */
    public function __construct(
        Context $context,
        OfferFactory $offerFactory,
        Offer $offerResourceModel
    ) {
        parent::__construct($context);

        $this->offerFactory = $offerFactory;
        $this->offerResourceModel = $offerResourceModel;
    }

    /**
     * Delete offer
     *
     * @return Redirect
     * @throws Exception
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('delmeroffer/offer/index');

        $id = $this->getRequest()->getParam('id');
        $offerModel = $this->offerFactory->create();

        try {
            $offer = $this->offerResourceModel->load($offerModel, $id);
            $offer->delete($offerModel);

            $this->messageManager->addSuccessMessage(__('Offer deleted.'));
        } catch (Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('An error occurred during deleting process.'));
        }

        return $resultRedirect;
    }
}
