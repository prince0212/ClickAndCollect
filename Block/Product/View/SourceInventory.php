<?php
declare(strict_types=1);

namespace Deloitte\ClickAndCollect\Block\Product\View;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Block\Product\View;
use Magento\Catalog\Helper\Product as ProductHelper;
use Magento\Catalog\Model\ProductTypes\ConfigInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\Json\EncoderInterface as JsonEnconderInterface;
use Magento\Framework\Locale\FormatInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Stdlib\StringUtils;
use Magento\Framework\Url\EncoderInterface;
use Magento\InventoryApi\Api\GetSourceItemsBySkuInterface;
use Magento\InventoryApi\Api\SourceRepositoryInterface;

class SourceInventory extends View
{

    /**
     * @var SourceRepositoryInterface
     */
    private $sourceRepository;

    /**
     * @var GetSourceItemsBySkuInterface
     */
    private $source;

    public function __construct(
        Context $context,
        EncoderInterface $urlEncoder,
        JsonEnconderInterface $jsonEncoder,
        StringUtils $string,
        ProductHelper $productHelper,
        ConfigInterface $productTypeConfig,
        FormatInterface $localeFormat,
        Session $customerSession,
        ProductRepositoryInterface $productRepository,
        PriceCurrencyInterface $priceCurrency,
        GetSourceItemsBySkuInterface $source,
        SourceRepositoryInterface $sourceRepository,
            
        array $data = []
    ) {
        parent::__construct(
            $context,
            $urlEncoder,
            $jsonEncoder,
            $string,
            $productHelper,
            $productTypeConfig,
            $localeFormat,
            $customerSession,
            $productRepository,
            $priceCurrency,
            $data
        );
        $this->source = $source;
        $this->sourceRepository = $sourceRepository;
    }
    
    public function getSourceInventory()
    {
        return $this->getSourceBySku();
    }
    
    /**
     * @return string
     */
    public function getSku()
    {
        return $this->getProduct()->getSku();
    }
    
    /**
     * @return \Magento\InventoryApi\Api\Data\SourceItemInterface[]
     */
    public function getSourceBySku()
    {
        return $this->source->execute($this->getSku());
    }
    
    public function getSourcesDetails($sourceCode)
    {
        //$sourceInfo = null;
        //$sourceCode = 'default';
        try {
            $sourceInfo = $this->sourceRepository->get($sourceCode);
        } catch (\Exception $exception) {
            $sourceInfo = null;
        }

        return $sourceInfo;
    }
}
