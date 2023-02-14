<?php

namespace Deloitte\ClickAndCollect\Plugin\Checkout\Model;

use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Quote\Model\QuoteRepository;

class ShippingInformationManagement
{
    protected $quoteRepository;

    public function __construct(
        QuoteRepository $quoteRepository
    )
    {
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * @param \Magento\Checkout\Model\ShippingInformationManagement $subject
     * @param $cartId
     * @param ShippingInformationInterface $addressInformation
     */
    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $cartId,
        ShippingInformationInterface $addressInformation
    )
    {
        $extAttributes = $addressInformation->getExtensionAttributes();
        $pickupDate = $extAttributes->getPickupDate();
        $pickupStore = $extAttributes->getPickupStore();
        $quote = $this->quoteRepository->getActive($cartId);
        $quote->setPickupDate($pickupDate);
        $quote->setPickupStore($pickupStore);
    }
}