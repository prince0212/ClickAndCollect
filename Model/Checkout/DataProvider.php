<?php
declare(strict_types=1);

namespace Deloitte\ClickAndCollect\Model\Checkout;

use Deloitte\ClickAndCollect\Model\AbstractConfig;
use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Inventory\Model\ResourceModel\Source\CollectionFactory as SourceCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Zend_Json;

class DataProvider implements ConfigProviderInterface
{
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * @var SourceCollectionFactory
     */
    private $SourceCollectionFactory;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    
    /**
     * @var AbstractConfig 
     */
    private $abstractConfig;

    /**
     * @param StoreManagerInterface $storeManager
     * @param ScopeConfigInterface $scopeConfig
     * @param SourceCollectionFactory $SourceCollectionFactory
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig,
        SourceCollectionFactory $SourceCollectionFactory,
        AbstractConfig $abstractConfig
    )
    {
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->SourceCollectionFactory = $SourceCollectionFactory;
        $this->abstractConfig = $abstractConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        $config = [
            'shipping' => [
                'select_store' => [
                    'maps_api_key' => $this->abstractConfig->getApiKey(),
                    'lat' => $this->abstractConfig->getDefaultLatitude(),
                    'lng' => $this->abstractConfig->getDefaultLangitude(),
                    'zoom' => $this->abstractConfig->getDefaultZoom(),
                    'map_styles' => $this->abstractConfig->getMapStyle(),
                    'map_pin' => $this->abstractConfig->getMapPin(),
                    'geolocation' => $this->abstractConfig->getAskLocation(),
                    'radius' => $this->abstractConfig->getRadius(),
                    'template' => $this->abstractConfig->getTemplate(),
                    'unit' => $this->abstractConfig->getUnitLength(),
                    'fillColor' => $this->abstractConfig->getFillColor(),
                    'fillOpacity' => $this->abstractConfig->getFillOpacity(),
                    'strokeColor' => $this->abstractConfig->getStrokeColor(),
                    'strokeOpacity' => $this->abstractConfig->getStrokeOpacity(),
                    'strokeWeight' => $this->abstractConfig->getStrokeWeight(),
                    'stores' => $this->getStores()
                ]
            ]
        ];

        return $config;
    }
    
    public function getStores()
    {
        $stores = $this->SourceCollectionFactory->create()->toArray();
        return Zend_Json::encode($stores);
    }
}