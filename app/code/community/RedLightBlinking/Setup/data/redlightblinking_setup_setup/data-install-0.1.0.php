<?php
try{
    $installer = $this;

    $installer->startSetup();

    // ------------------------------------------------------
    // --- Set Design Package / Theme -----------------
    // ------------------------------------------------------
    
    $installer
    ->setConfigData('design/package/name', 'b-responsive')
    ->setConfigData('design/theme/default', 'default');
    
    $installer->endSetup();
}catch(Excpetion $e){
    
    Mage::logException($e);
}