<?php

require __DIR__ . '/../app/bootstrap.php';
#ini_set('display_errors',1);
error_reporting(E_ALL);

    // adding bootstrap
    $bootstraps = Magento\Framework\App\Bootstrap::create(BP, $_SERVER);
    $object_Manager = $bootstraps->getObjectManager();
  
$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$registery = $objectManager->get('Magento\Framework\Registry');

foreach(range(4000000010, 4000000100) as $id) {
    $order = $objectManager->create( 'Magento\Sales\Model\Order' )->loadByIncrementId( $id );
    $registery->register( 'isSecureArea', 'true' );
    $order->delete();
    $registery->unregister( 'isSecureArea' );
}
