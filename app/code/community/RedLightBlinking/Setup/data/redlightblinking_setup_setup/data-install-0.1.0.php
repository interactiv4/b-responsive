<?php
try{
    $installer = $this;

    $installer->startSetup();

    // ------------------------------------------------------
    // --- Set Design Package / Theme -----------------
    // ------------------------------------------------------
    
    $config = new Mage_Core_Model_Config();
    $config->saveConfig('design/package/name', 'b-responsive');
    $config->saveConfig('design/theme/default', 'default');
    
    $installer->endSetup();
}catch(Exception $e){
    
    Mage::logException($e);
}