<?php

namespace Atwix\ArchiveProduct\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;

class Data {

    protected $_libDirectoryPath;
    protected $_logger;
    protected $_adapter;

    public function __construct(
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->_libDirectoryPath = DirectoryList::getDefaultConfig()[DirectoryList::LIB_INTERNAL]['path'];
        $this->_logger = $logger;

        try {
            require_once $this->_libDirectoryPath . '/atwix/archiveproduct/Adapter.php';
            $this->_adapter = new \Atwix\ArchiveProduct\Adapter\Atwix_ArchiveProduct_Adapter();

        } catch (Exception $e) {
            $logger->critical('Cannot load library for [EXTENSION_NAME]');
        }
    }

    public function someFunction()
    {
        return $this->_adapter->sayHello();
    }
}