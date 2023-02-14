<?php

namespace Deloitte\ClickAndCollect\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Deloitte\ClickAndCollect\Model\Config;

class AbstractConfig
{
    /**
     * @var ScopeConfigInterface 
     */
    private $scopeConfig;
    
    /**
     * @var StoreManagerInterface 
     */
    private $storeManager;
    
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }
    
    public function getStoreId() : int
    {
        return $this->storeManager->getStore()->getStoreId();
    }
    
    public function getConfigValue($xmlPath)
    {
        return $this->scopeConfig->getValue($xmlPath, ScopeInterface::SCOPE_STORE, $this->getStoreId());
    }
    
    public function getMapStyle() : string
    {
        return $this->getConfigValue(Config::MAP_STYLES_CONFIG_PATH);
    }
    
    public function getUrl() : string
    {
        return $this->getConfigValue(Config::URL_CONFIG_PATH);
    }
    
    /**
     * @return string|null
     */
    public function getMapPin()
    {
        return $this->getConfigValue(Config::MAP_PIN_CONFIG_PATH);
    }
    
    public function getAskLocation() : int
    {
        return $this->getConfigValue(Config::ASK_LOCATION_CONFIG_PATH);
    }
    
    public function getTemplate() : string
    {
        return $this->getConfigValue(Config::TEMPLATE_CONFIG_PATH);
    }
    
    public function getApiKey() : string
    {
        return $this->getConfigValue(Config::API_KEY_CONFIG_PATH);
    }
    
    public function getUnitLength() : string
    {
        return $this->getConfigValue(Config::UNIT_LENGTH_CONFIG_PATH);
    }
    
    public function getDefaultLatitude() : float
    {
        return $this->getConfigValue(Config::LATITUDE_CONFIG_PATH);
    }
    
    public function getDefaultLangitude() : float
    {
        return $this->getConfigValue(Config::LONGITUDE_CONFIG_PATH);
    }
    
    public function getDefaultZoom() : int 
    {
        return $this->getConfigValue(Config::ZOOM_CONFIG_PATH);
    }
    
    public function getZoomIndividual() : int
    {
        return $this->getConfigValue(Config::ZOOM_INDIVIDUAL_CONFIG_PATH);
    }
    
    public function getSlider() : int
    {
        return $this->getConfigValue(Config::SLIDER_CONFIG_PATH);
    }
    
    public function getOtherStore() : int
    {
        return $this->getConfigValue(Config::OTHER_STORES_CONFIG_PATH);
    }
    
    public function getRadius() : float
    {
        return $this->getConfigValue(Config::RADIUS_CONFIG_PATH);
    }
    
    public function getStrokeWeight() : float
    {
        return $this->getConfigValue(Config::STROKE_WEIGHT_CONFIG_PATH);
    }
    
    public function getStrokeOpacity() : float
    {
        return $this->getConfigValue(Config::STROKE_OPACITY_CONFIG_PATH);
    }
    
    public function getStrokeColor() : string
    {
        return $this->getConfigValue(Config::STROKE_COLOR_CONFIG_PATH);
    }
    
    public function getFillOpacity() : float
    {
        return $this->getConfigValue(Config::FILL_OPACITY_CONFIG_PATH);
    }
    
    public function getFillColor() : string
    {
        return $this->getConfigValue(Config::FILL_COLOR_CONFIG_PATH);
    }
}
