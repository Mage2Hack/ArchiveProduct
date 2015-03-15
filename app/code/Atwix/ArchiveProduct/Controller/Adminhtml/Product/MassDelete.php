<?php

namespace Atwix\ArchiveProduct\Controller\Adminhtml\Product;

class MassDelete extends \Magento\Catalog\Controller\Adminhtml\Product\MassDelete
{
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
                    $product->setStatus(2)->setIsArchived(1);
                    $product->save();
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) have been moved to trash.', count($productIds))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        return $this->resultRedirectFactory->create()->setPath('catalog/*/index');
    }
}
