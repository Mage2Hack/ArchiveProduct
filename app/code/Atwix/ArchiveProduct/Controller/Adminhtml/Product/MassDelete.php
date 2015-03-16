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
        $storeId = (int) $this->getRequest()->getParam('store', 0);

        if (!is_array($productIds) || empty($productIds)) {
            $this->messageManager->addError(__('Please select product(s).'));
        } else {
            try {
                $isArchived = 1;
                foreach ($productIds as $productId) {
                    $this->_objectManager->get('Magento\Catalog\Model\Product\Action')
                        ->updateAttributes($productIds, ['is_archived' => $isArchived], $storeId);
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
