<?php

namespace Mageesh\EmptyCategoryNotification\Observer;

use Mageesh\EmptyCategoryNotification\Block\Email;
use Mageesh\EmptyCategoryNotification\Helper\Config;
use Magento\Catalog\Helper\Data;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Stdlib\DateTime;


class NotificationObserver implements ObserverInterface
{
    private Email $helperEmail;
    private CollectionFactory $productCollectionFactory;
    private Status $productStatus;
    private Visibility $productVisibility;
    private DateTime $dateTime;
    private Data $catalogHelper;
    private Config $configHelper;


    /**
     * @param Email $helperEmail
     * @param Data $catalogHelper
     * @param CollectionFactory $productCollectionFactory
     * @param Status $productStatus
     * @param Visibility $productVisibility
     * @param DateTime $dateTime
     * @param Config $configHelper
     */
    public function __construct(
        Email             $helperEmail,
        Data              $catalogHelper,
        CollectionFactory $productCollectionFactory,
        Status            $productStatus,
        Visibility        $productVisibility,
        DateTime          $dateTime,
        Config            $configHelper
    ) {
        $this->helperEmail = $helperEmail;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->productStatus = $productStatus;
        $this->productVisibility = $productVisibility;
        $this->dateTime = $dateTime;
        $this->catalogHelper = $catalogHelper;
        $this->configHelper = $configHelper;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $collection = $observer->getEvent()->getData('collection');
        if (!$collection->count()) {
            $this->processError();

        }
    }

    /**
     * @return void
     */
    public function processError()
    {
        if ($this->configHelper->isEnabled()) {
            $category = $this->catalogHelper->getCategory();
            $pcollection = $this->getProductCollectionByCategories($category->getId());
            if ($pcollection->count()) {
                 $this->helperEmail->sendEmail(
                    $category->getName(),
                    $category->getUrl(),
                    $this->getFormatDate());
            }
        }
        return;
    }

    /**
     * @param DateTime $dateTime
     */
    public function setDateTime(DateTime $dateTime): void
    {
        $this->dateTime = $dateTime;
    }

    function getFormatDate()
    {
        return $this->dateTime->formatDate(time());

    }

    /**
     * @param $ids
     * @return Collection
     */
    public function getProductCollectionByCategories($ids)
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addCategoriesFilter(['in' => $ids]);
        $collection->addAttributeToFilter('status', ['in' => $this->productStatus->getVisibleStatusIds()]);
        $collection->setVisibility($this->productVisibility->getVisibleInSiteIds());
        return $collection;
    }
}


