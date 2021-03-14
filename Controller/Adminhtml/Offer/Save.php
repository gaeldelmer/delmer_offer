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
use Magento\Catalog\Model\ImageUploader;
use Magento\Framework\Exception\LocalizedException;
use Magento\Backend\Model\Session;

use Delmer\Offer\Model\ResourceModel\Offer;
use Delmer\Offer\Model\OfferFactory;

class Save extends Action
{
    /**
     * @var Session
     */
    private $adminSession;

    /**
     * @var ImageUploader
     */
    private $imageUploader;

    /**
     * @var OfferFactory
     */
    private $offerFactory;

    /**
     * @var Offer
     */
    private $offerResourceModel;

    /**
     * Save constructor
     *
     * @param Session $adminSession
     * @param Context $context
     * @param ImageUploader $imageUploader
     * @param OfferFactory $offerFactory
     * @param Offer $offerResourceModel
     */
    public function __construct(
        Session $adminSession,
        Context $context,
        ImageUploader $imageUploader,
        OfferFactory $offerFactory,
        Offer $offerResourceModel
    ) {
        parent::__construct($context);

        $this->adminSession = $adminSession;
        $this->imageUploader = $imageUploader;
        $this->offerFactory = $offerFactory;
        $this->offerResourceModel = $offerResourceModel;
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('delmeroffer/*/index');

        $newOffer = $this->getRequest()->getParam('offers');
        $newOffer['categories_id'] = implode("," , $newOffer['categories']);
        // TODO delete unset ?
        unset($newOffer['categories']);

        if (!empty($newOffer)) {
            $offerModel = $this->offerFactory->create();

            // Load Offer if edit
            if (!empty($newOffer["id"])) {
                $this->offerResourceModel->load($offerModel, $newOffer["id"]);
            }

            // If for upload of new image
            if (isset($newOffer['image'][0]['name']) && isset($newOffer['image'][0]['tmp_name'])) {
                $newOffer['image'] = $newOffer['image'][0]['name'];
                try {
                    $this->imageUploader->moveFileFromTmp($newOffer['image']);
                } catch (LocalizedException $e) {
                    $this->messageManager->addExceptionMessage(
                        $e,
                        __('An error occurred during upload image process.')
                    );
                    return $resultRedirect;
                }
            } elseif (isset($newOffer['image'][0]['name']) && !isset($newOffer['image'][0]['tmp_name'])) {
                $newOffer['image'] = $newOffer['image'][0]['name'];
            }

            try {
                $offerModel->setData($newOffer);
                $this->offerResourceModel->save($offerModel);
                $this->messageManager->addSuccessMessage(__('Offer saved.'));
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('An error occurred during saving process.'));
            }
        } else {
            $this->messageManager->addErrorMessage(__('No offer to save or edit.'));
        }

        return $resultRedirect;
    }
}
