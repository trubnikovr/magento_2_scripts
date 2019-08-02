<?php


use Magento\Framework\App\Bootstrap;
use Magento\Checkout\Exception;

require __DIR__ . '/../../app/bootstrap.php';

// adding bootstrap
$bootstraps = Bootstrap::create(BP, $_SERVER);
$object_Manager = $bootstraps->getObjectManager();

$app_state = $object_Manager->get('\Magento\Framework\App\State');
$app_state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);
$objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager

ini_set('auto_detect_line_endings', TRUE);

try {

    $file = dirname(__FILE__) . '/../imports/sku_amazon_fba_no.csv';


    $handle = fopen($file, "r");

    if (empty($handle) === false) {

        $headers = $data = fgetcsv($handle, 1000, ",");

        if (!$headers) {

            throw Exception('file is empty');
        }

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

            try {
                $data = array_combine($headers, $data);

                if (!$data || !isset($data['sku'])) {

                    echo 'something is wrong\n';
                    continue;
                }
                echo "{$data['sku']} \n";
                $product = $objectManager->create('Magento\Catalog\Model\ProductRepository')->get($data['sku']);

                if (!$product) {
                    echo "{$data['sku']} is not found\n";
                    continue;
                }

                $product->setCustomAttribute('price_1100', $data['price_1100']);
                $product->save();
            } catch (Magento\Framework\Exception\NoSuchEntityException $e) {

                echo "{$data['sku']} is not found \n";
                continue;
            }
        }
        fclose($handle);
    }
} catch (Exception $e) {

    print_r($e);
}
echo "done!!!";
