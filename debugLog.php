<?php
  $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/test.log');
  $logger = new \Zend\Log\Logger();
  $logger->addWriter($writer);
  $logger->info($order['increment_id'] .  ' is missing');
