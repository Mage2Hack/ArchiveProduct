<?php

namespace Atwix\ArchiveProduct\Controller\Adminhtml\Product;

class MassRestore extends \Atwix\ArchiveProduct\Controller\Adminhtml\Product
{
    /**
     * @var \Magento\Backend\Model\View\Result\RedirectFactory
     */
    protected $resultRedirectFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Catalog\Controller\Adminhtml\Product\Builder $productBuilder
     * @param \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Catalog\Controller\Adminhtml\Product\Builder $productBuilder,
        \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory
    ) {
        parent::__construct($context, $productBuilder);
        $this->resultRedirectFactory = $resultRedirectFactory;
    }
    
    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $productIds = $this->getRequest()->getParam('product');
        if (!is_array($productIds) || empty($productIds)) {
            $this->messageManager->addError(__('Please select product(s).'));
        } else {
            try {
                foreach ($productIds as $productId) {
                    // TODO: Might be shared as a lib method
                    // TODO: Check associated products and mark them as disabled as well

                    /** @var \Magento\Catalog\Model\Product $product */
                    $product = $this->_objectManager->get('Magento\Catalog\Model\Product')->load($productId);
                    $product->setStatus(1)->setIsArchived(0);
                    $product->save();
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) have been restored.', count($productIds))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        return $this->resultRedirectFactory->create()->setPath('archiveproduct/*/index');
    }
}
