<?php
declare(strict_types=1);
 
namespace Deloitte\ClickAndCollect\Block;

use Magento\Backend\Block\Template\Context;
use Magento\Directory\Model\Config\Source\Country;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\ScopeInterface;
use Magento\Inventory\Model\ResourceModel\Source\CollectionFactory as SourceCollectionFactory;

class Stockists extends Template
{
        
    /**
     * @var string
     */
    const MAP_STYLES_CONFIG_PATH = 'cnc/map_settings/map_style';

    /**
     * @var string
     */
    const URL_CONFIG_PATH = 'cnc/stockist_content/url';
            
    /**
     * @var string
     */
    const MAP_PIN_CONFIG_PATH = 'cnc/map_settings/map_pin';
            
    /**
     * @var string
     */
    const ASK_LOCATION_CONFIG_PATH = 'cnc/map_settings/ask_location';
    
    /**
     * @var string
     */
    const TEMPLATE_CONFIG_PATH = 'cnc/map_settings/template';

    /**
     * @var string
     */
    const API_KEY_CONFIG_PATH = 'cnc/map_settings/api_key';

    /**
     * @var string
     */
    const UNIT_LENGTH_CONFIG_PATH = 'cnc/map_settings/unit_length';
        
    /**
     * @var int
     */
    const LATITUDE_CONFIG_PATH = 'cnc/map_settings/latitude';
            
    /**
     * @var int
     */
    const LONGITUDE_CONFIG_PATH = 'cnc/map_settings/longitude';
            
    /**
     * @var int
     */
    const ZOOM_CONFIG_PATH = 'cnc/map_settings/zoom';

    /**
     * @var int
     */
    const ZOOM_INDIVIDUAL_CONFIG_PATH = 'cnc/stockist_individual/zoom_individual';

    /**
     * @var int
     */
    const SLIDER_CONFIG_PATH = 'cnc/stockist_individual/stores_slider';

    /**
     * @var int
     */
    const OTHER_STORES_CONFIG_PATH = 'cnc/stockist_individual/other_stores';
            
    /**
     * @var string
     */
    const RADIUS_CONFIG_PATH = 'cnc/map_settings/radius';
            
    /**
     * @var string
     */
    const STROKE_WEIGHT_CONFIG_PATH = 'cnc/stockist_radius/circle_stroke_weight';
    
    /**
     * @var string
     */
    const STROKE_OPACITY_CONFIG_PATH = 'cnc/stockist_radius/circle_stroke_opacity';
        
    /**
     * @var string
     */
    const STROKE_COLOR_CONFIG_PATH = 'cnc/stockist_radius/circle_stroke_color';
            
    /**
     * @var string
     */
    const FILL_OPACITY_CONFIG_PATH = 'cnc/stockist_radius/circle_fill_opacity';
            
    /**
     * @var string
     */
    const FILL_COLOR_CONFIG_PATH = 'cnc/stockist_radius/circle_fill_color';
    
    /**
     * @var Country
     */
    public $countryHelper;
    /**
     * @var SourceCollectionFactory
     */
    private $SourceCollectionFactory;

    public function __construct(
        SourceCollectionFactory $SourceCollectionFactory,
        Country $countryHelper,
        Context $context,
        array $data = []
    ) {
        $this->SourceCollectionFactory = $SourceCollectionFactory;
        $this->countryHelper = $countryHelper;
        parent::__construct($context, $data);
    }
    
     /**
      * return stockists collection
      */
    public function getStoresForFrontend()
    {
        return $this->SourceCollectionFactory->create()
            ->addFieldToSelect('*');
    }

    /**
     * Get store identifier
     *
     * @return  string
     */
    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }
    
    /**
     * get an array of country codes and country names: AF => Afganisthan
     *
     * @return array
     */
    public function getCountries(): array
    {

        $loadCountries = $this->countryHelper->toOptionArray();
        $countries = [];
        $i = 0;
        foreach ($loadCountries as $country ) {
            $i++;
            if ($i == 1) { //remove first element that is a select
                continue;
            }
            $countries[$country["value"]] = $country["label"];
        }
        return $countries;
    }
    
    /**
     * get media url
     *
     * @return string
     */  
    public function getMediaUrl(): string
    {
	return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA );
    }
    
    /**
     * get map style from configuration
     *
     * @return string
     */   
    public function getMapStyles(): string
    {
	return $this->_scopeConfig->getValue(self::MAP_STYLES_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }
        
    /**
     * get map pin from configuration
     *
     * @return string or null
     */   
    public function getMapPin()
    {
	return $this->_scopeConfig->getValue(self::MAP_PIN_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }
    
    /**
     * get location settings from configuration
     *
     * @return int
     */   
    public function getLocationSettings(): int
    {
	return (int)$this->_scopeConfig->getValue(self::ASK_LOCATION_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }
        
    /**
     * get template settings from configuration, i.e full width or page width
     *
     * @return string
     */   
    public function getTemplateSettings(): string
    {
	return $this->_scopeConfig->getValue(self::TEMPLATE_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * get api key settings from configuration
     *
     * @return string
     */
    public function getApiKeySettings(): string
    {
	return $this->_scopeConfig->getValue(self::API_KEY_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }
        
    /**
     * get unit of length settings from configuration
     *
     * @return string
     */   
    public function getUnitOfLengthSettings(): string
    {
	return $this->_scopeConfig->getValue(self::UNIT_LENGTH_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }
            
    /**
     * get zoom settings from configuration
     *
     * @return int
     */   
    public function getZoomSettings(): int
    {
	return (int)$this->_scopeConfig->getValue(self::ZOOM_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * get url settings from configuration
     *
     * @return string
     */
    public function getModuleUrlSettings(): string
    {
	return $this->_scopeConfig->getValue(self::URL_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * get slider settings from configuration
     *
     * @return int
     */
    public function getSliderSettings(): int
    {
	return (int)$this->_scopeConfig->getValue(self::SLIDER_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * get other stores settings from configuration
     *
     * @return int
     */
    public function getOtherStoresSettings(): int
    {
	return (int)$this->_scopeConfig->getValue(self::OTHER_STORES_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * get individual zoom settings from configuration for store details page
     *
     * @return int
     */
    public function getZoomIndividualSettings(): int
    {
        return (int)$this->_scopeConfig->getValue(self::ZOOM_INDIVIDUAL_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * get latitude settings from configuration
     *
     * @return float
     */   
    public function getLatitudeSettings(): float
    {
	return (float)$this->_scopeConfig->getValue(self::LATITUDE_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }
            
    /**
     * get longitude settings from configuration
     *
     * @return float
     */   
    public function getLongitudeSettings(): float
    {
	return (float)$this->_scopeConfig->getValue(self::LONGITUDE_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }
                
    /**
     * get radius settings from configuration
     *
     * @return float
     */   
    public function getRadiusSettings(): float
    {
	return (float)$this->_scopeConfig->getValue(self::RADIUS_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }
                
    /**
     * get stroke weight settings from configuration
     *
     * @return float
     */   
    public function getStrokeWeightSettings(): float
    {
	return (float)$this->_scopeConfig->getValue(self::STROKE_WEIGHT_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }
                
    /**
     * get stroke opacity settings from configuration
     *
     * @return float
     */   
    public function getStrokeOpacitySettings(): float
    {
	return (float)$this->_scopeConfig->getValue(self::STROKE_OPACITY_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }
                
    /**
     * get stroke color settings from configuration
     *
     * @return string
     */   
    public function getStrokeColorSettings(): string
    {
	return $this->_scopeConfig->getValue(self::STROKE_COLOR_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }
                
    /**
     * get fill opacity settings from configuration
     *
     * @return string
     */   
    public function getFillOpacitySettings(): float
    {
	return (float)$this->_scopeConfig->getValue(self::FILL_OPACITY_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }
                
    /**
     * get fill color settings from configuration
     *
     * @return string
     */   
    public function getFillColorSettings(): string
    {
	return $this->_scopeConfig->getValue(self::FILL_COLOR_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }
    
     /**
     * get base image url
     *
     * @return string
     */ 
    public function getBaseImageUrl(): string
    {
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }
    
}
