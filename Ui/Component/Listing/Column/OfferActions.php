<?php

/**
 * UNLICENSED
 *
 * @package     Delmer_Offer
 * @subpackage  Ui
 * @author      Gaël Delmer <gael.delmer@gmail.com>
 */

namespace Delmer\Offer\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class OfferActions extends Column
{
    /**
     * @var UrlInterface
     */
    protected $urlInterface;

    /**
     * OfferActions constructor
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlInterface
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlInterface,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);

        $this->urlInterface = $urlInterface;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $item[$this->getData('name')]['edit'] = [
                    'href' => $this->urlInterface->getUrl(
                        'delmeroffer/offer/add',
                        ['id' => $item['id']]
                    ),
                    'label' => __('Edit'),
                ];
                $item[$this->getData('name')]['delete'] = [
                    'href' => $this->urlInterface->getUrl(
                        'delmeroffer/offer/delete',
                        ['id' => $item['id']]
                    ),
                    'label' => __('Delete'),
                ];
            }
        }

        return $dataSource;
    }
}
