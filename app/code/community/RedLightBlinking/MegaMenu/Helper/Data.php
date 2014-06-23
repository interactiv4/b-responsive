<?php
/**
 *
 * @author Enrique Piatti
 */ 
class RedLightBlinking_MegaMenu_Helper_Data extends Mage_Core_Helper_Abstract
{

	const MEGA_MENU_CONFIG = 'catalog/navigation/megamenu';

	public function isMegaMenuEnabled($store = null)
	{
		return Mage::getStoreConfig(self::MEGA_MENU_CONFIG, $store);
	}


	public function getMegaMenuStaticBlockName($categoryId)
	{
		return 'megamenu_category_'.$categoryId;
	}

	public function getMegaMenuStaticBlock($categoryId, $store = null)
	{
		$staticBlockIdentifier = $this->getMegaMenuStaticBlockName($categoryId);
		$store = $store ? : Mage::app()->getStore()->getId();
		$block = Mage::getModel('cms/block')
			->setStoreId($store)
			->load($staticBlockIdentifier);
		return $block;
	}

	public function getMegaMenuStaticBlockHtml($categoryId)
	{
		$staticBlockIdentifier = $this->getMegaMenuStaticBlockName($categoryId);
		$html = Mage::app()->getLayout()->createBlock('cms/block')
			->setBlockId($staticBlockIdentifier)
			->toHtml();
		return $html;
	}

}
