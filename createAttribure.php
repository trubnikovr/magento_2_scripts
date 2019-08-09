<?php
//create attributes for category
use Magento\Framework\App\Bootstrap;
use Magento\Checkout\Exception;
use function Magento\Framework\Session\ini_set;

require __DIR__ . '/../app/bootstrap.php';
#ini_set('display_errors',1);
error_reporting(E_ALL);

try {
// adding bootstrap
$bootstraps = Bootstrap::create(BP, $_SERVER);
$object_Manager = $bootstraps->getObjectManager();
$app_state = $object_Manager->get('\Magento\Framework\App\State');
$app_state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);
$objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
$eavSetup = $objectManager->get('Magento\Eav\Setup\EavSetupFactory');
$setup = $objectManager->get('Magento\Framework\Setup\ModuleDataSetupInterface');


$eavSetup1 = $eavSetup->create(['setup' => $setup]);
$eavSetup1->addAttribute(\Magento\Catalog\Model\Category::ENTITY, 'custom_nombre_menu', [
    'type'     => 'varchar',
    'label'    => 'Nombre Menu',
    'input'    => 'text',
    'source'   => 'Magento\Eav\Model\Entity\Attribute\Source\Text',
    'visible'  => true,
    'visible_on_front' => true,
    'default'  => '',
    'required' => false,
    'user_defined' => true,
    'global'   => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
    'group'    => 'Display Settings',
]);

} catch( Exception $e) {
        print_r($e);
}
