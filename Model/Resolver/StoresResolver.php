<?php

namespace Deloitte\ClickAndCollect\Model\Resolver;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Query\Resolver\Value;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Inventory\Model\ResourceModel\Source\CollectionFactory as SourceCollectionFactory;

/**
 * Product collection resolver
 */
class StoresResolver implements ResolverInterface
{

    /**
     * @var string
     */
    const API_KEY_CONFIG_PATH = 'cnc/map_settings/api_key';


    private StoreManagerInterface $storemanager;

    /**
     * @param StoreManagerInterface $storemanager
     */
    public function __construct(
        StoreManagerInterface $storemanager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Deloitte\ClickAndCollect\Block\Stockists $stockists
    ) {
        $this->_storeManager = $storemanager;
        $this->_scopeConfig = $scopeConfig;
        $this->stockists = $stockists;
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $storesData = $this->getStoresDetails();
        return $storesData;
    }

    private function getStoresDetails()
    {
        try {
            $storeRecord = [];
            $storeRecord['gmap_api_key'] = $this->_scopeConfig->getValue(
                "cnc/map_settings/api_key",
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $this->_storeManager->getStore()->getStoreId()
            );
            $data = $this->stockists->getStoresForFrontend();
            $storeRecord['allStores'] = [];
            $i = 1;
            foreach ($data as $item) {
                $storeRecord['allStores'][$item->getSourceCode()]['id'] = $i;
                $storeRecord['allStores'][$item->getSourceCode()]['source_code'] = $item->getSourceCode();
                $storeRecord['allStores'][$item->getSourceCode()]['latitude'] = $item->getLatitude();
                $storeRecord['allStores'][$item->getSourceCode()]['longitude'] = $item->getLongitude();
                $storeRecord['allStores'][$item->getSourceCode()]['name'] = $item->getName();
                $storeRecord['allStores'][$item->getSourceCode()]['address'] = $item->getCity();
                $storeRecord['allStores'][$item->getSourceCode()]['contact_number'] = $item->getPhone();
                $storeRecord['allStores'][$item->getSourceCode()]['email'] = $item->getEmail();
                $i++;
            }
        } catch (NoSuchEntityException $e) {
            throw new GraphQlNoSuchEntityException(__($e->getMessage()), $e);
        }
        return $storeRecord;
    }
}
