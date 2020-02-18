<?php
// in helper
  Mage::app()->setCurrentStore(2);
    Mage::getDesign()->setArea('frontend');
    $layout = Mage::app()->getLayout();
    $layout->getUpdate()->addHandle('default')->load();
    Mage::getDesign()->setTheme(Mage::getStoreConfig('design/theme/default', 2));
    $totalsRenderer =  Mage::app()->getLayout()->createBlock('sales/order_totals')->setTemplate('sales/order/totalsForPdf.phtml');
    $totalsRenderer->setChild('tax', Mage::app()->getLayout()->createBlock('tax/sales_order_tax')->setTemplate('tax/order/tax.phtml'));
