<?php

/**
 * UNLICENSED
 *
 * @package     Delmer_Offer
 * @subpackage  Model
 * @author      Gaël Delmer <gael.delmer@gmail.com>
 */

namespace Delmer\Offer\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Filesystem;
use Magento\Framework\Exception\FileSystemException;

use Delmer\Offer\Model\OfferFactory;
use Delmer\Offer\Model\Offer as OfferModel;

class Offer extends AbstractDb
{
    /**
     * @var OfferFactory
     */
    private $offerFactory;

    /**
     * @var File
     */
    private $file;

    /**
     * @var Filesystem
     */
    private $fileSystem;

    /**
     * Offer constructor
     *
     * @param Context $context
     * @param OfferFactory $offerFactory
     * @param File $file
     * @param Filesystem $fileSystem
     */
    public function __construct(
        Context $context,
        File $file,
        Filesystem $fileSystem,
        OfferFactory $offerFactory
    ) {
        parent::__construct($context);

        $this->file = $file;
        $this->fileSystem = $fileSystem;
        $this->offerFactory = $offerFactory;
    }

    /**
     * Offer init constructor
     */
    protected function _construct()
    {
        $this->_init('delmer_offer', 'id');
    }

    /**
     * @param AbstractModel $object
     *
     * @return Offer
     */
    protected function _afterSave(AbstractModel $object)
    {
        $this->saveCategories($object);

        return parent::_afterSave($object);
    }

    /**
     * @param AbstractModel $object
     *
     * @return Offer
     * @throws FileSystemException
     */
    protected function _afterDelete(AbstractModel $object)
    {
        $this->deleteImage($object);

        return parent::_afterSave($object);
    }

    /**
     * Manage insert and delete in table offers_category (used in _afterSave)
     *
     * @param OfferModel $offer
     *
     * @return $this
     */
    public function saveCategories(OfferModel $offer)
    {
        $offerCategoryTable = $this->getTable('delmer_offer_category');
        $dbConnection = $this->getConnection();
        $offerId = $offer->getId();

        $newCategories = explode(",", $offer->getCategoriesId());
        $oldCategories = $offer->getCategories();

        // Delete old categories
        $categoriesToDelete = array_diff($oldCategories, $newCategories);
        if (!empty($categoriesToDelete)) {
            foreach ($categoriesToDelete as $categoryId) {
                $dbConnection->delete($offerCategoryTable, [
                    'category_id=?' => $categoryId,
                    'offer_id=?' => $offerId
                ]);
            }
        }

        // Add new categories
        $categoriesToAdd = array_diff($newCategories, $oldCategories);
        if (!empty($categoriesToAdd)) {
            $data = [];
            foreach ($categoriesToAdd as $category) {
                $data[] = [
                    'offer_id'    => (int)$offerId,
                    'category_id' => (int)$category
                ];
            }
            $dbConnection->insertMultiple($offerCategoryTable, $data);
        }

        return $this;
    }

    /**
     * Delete image (used in _afterDelete)
     *
     * @param OfferModel $offer
     *
     * @throws FileSystemException
     */
    public function deleteImage(OfferModel $offer)
    {
        $mediaDirectory = $this->fileSystem->getDirectoryWrite(DirectoryList::MEDIA);
        $imagePath = $mediaDirectory->getAbsolutePath() . 'delmer/offer/' . $offer->getData('image');

        if ($this->file->isExists($imagePath))  {
            $this->file->deleteFile($imagePath);
        }
    }

    /**
     * Get categories for an offer
     *
     * @param OfferModel $offer
     *
     * @return array
     */
    public function getCategories(OfferModel $offer)
    {
        $offerCategoryTable = $this->getTable('delmer_offer_category');
        $dbConnection = $this->getConnection();

        $categories  = $dbConnection
            ->select()
            ->from($offerCategoryTable, 'category_id')
            ->where('offer_id=?', (int)$offer->getId())
        ;

        return $dbConnection->fetchCol($categories);
    }

    /**
     * Return offers for one category
     *
     * @param int $categoryId
     *
     * @return array
     */
    public function getOffersByCategory(int $categoryId)
    {
        $offerCategoryTable = $this->getTable('delmer_offer_category');
        $dbConnection   = $this->getConnection();

        $offers = $dbConnection
            ->select()
            ->from($offerCategoryTable, 'offer_id')
            ->where('category_id=?', $categoryId);

        return $dbConnection->fetchAll($offers);
    }
}
