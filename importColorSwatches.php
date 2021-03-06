<?php

use Magento\Framework\App\Bootstrap;
require __DIR__ . '/../../app/bootstrap.php';

// adding bootstrap
$bootstraps = Bootstrap::create(BP, $_SERVER);
$object_Manager = $bootstraps->getObjectManager();

$app_state = $object_Manager->get('\Magento\Framework\App\State');
$app_state->setAreaCode('frontend');




$objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
$connection = $resource->getConnection();

$fileSystem = $objectManager->get('\Magento\Framework\Filesystem');
$mediaPath = $fileSystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath(). 'catalog/product';
$swatchMediaPath = $fileSystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath(). 'attribute/swatch';



//Select Data from table
$sql = 'select p.entity_id, m.`value`, p.value_id
from 
catalog_product_entity_media_gallery as m,
catalog_product_entity_media_gallery_value_to_entity as p
 where m.value LIKE "%colorsymbol%.jpg" AND p.value_id = m.value_id ' ;
$result = $connection->fetchAll($sql); // gives associated array, table fields as key in array.

try {

    foreach ($result as $p) {
        $product = $objectManager->create('Magento\Catalog\Model\Product')->load($p['entity_id']);

        if(!$product) {

            continue;
        }

        $color_main_id = $product->getColorMain();

        if($color_main_id) {

            @mkdir(dirname($swatchMediaPath.$product->getSwatchImage()), 0777, true);
            copy($mediaPath.$product->getSwatchImage(), $swatchMediaPath.$product->getSwatchImage());

            $sql =  "UPDATE `eav_attribute_option_swatch` SET `type`='2', `value`='{$product->getSwatchImage()}' WHERE (`option_id`= {$color_main_id})  LIMIT 1";
            $connection->query($sql);
        }


        //Update Data into table
        $sql = "UPDATE `catalog_product_entity_varchar` SET `value`='{$p['value']}' WHERE attribute_id = 133 and entity_id  = '{$p['entity_id']}' LIMIT 1";
        $connection->query($sql);
       $connection->query("UPDATE `catalog_product_entity_media_gallery_value` SET `disabled`='1' WHERE (`value_id`={$p['value_id']}) LIMIT 1
");
    }
} catch (Exception $e) {

    print_r($e->getMessage());
}


