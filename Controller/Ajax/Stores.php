<?php
declare(strict_types=1);

namespace Deloitte\ClickAndCollect\Controller\Ajax;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Inventory\Model\ResourceModel\Source\CollectionFactory as SourceCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Responsible for loading page content.
 *
 * This is a basic controller that only loads the corresponding layout file. It may duplicate other such
 * controllers, and thus it is considered tech debt. This code duplication will be resolved in future releases.
 */
class Stores extends Action
{
    /**
     * @var StoreManagerInterface
     */
    public $storeManager;
    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;
    /**
     * @var SourceCollectionFactory
     */
    private $SourceCollectionFactory;

    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        SourceCollectionFactory $SourceCollectionFactory,
        StoreManagerInterface $storeManager
    )
    {
        $this->SourceCollectionFactory = $SourceCollectionFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * Load the page defined in view/frontend/layout/storelocator_index_index.xml
     *
     * @return JsonFactory
     */
    public function execute()
    {
        $collection = $this->SourceCollectionFactory->create()
            ->addFieldToSelect('*')
            ->getData();
        $json = [];
        foreach ($collection as $stockist) {
            $json[] = $stockist;
        }
        return $this->resultJsonFactory->create()->setData($json);
    }
}