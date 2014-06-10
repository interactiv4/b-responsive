<?php
/**
 *
 * @author Enrique Piatti
 */ 
class RedLightBlinking_MegaMenu_Adminhtml_StaticBlockController extends Mage_Adminhtml_Controller_Action
{

	public function indexAction()
	{
		$categoryId = (int)$this->getRequest()->getParam('category_id');
		$staticBlock = Mage::helper('megamenu')->getMegaMenuStaticBlock($categoryId);
		$staticBlockId = $staticBlock->getId();
		if( ! $staticBlockId){
			// create new static block
			$staticBlockIdentifier = Mage::helper('megamenu')->getMegaMenuStaticBlockName($categoryId);
			$block = Mage::getModel('cms/block');
				// ->setStoreId($store) TODO
			$block->setIdentifier($staticBlockIdentifier);
			$block->setIsActive(true);
			$block->setTitle('MegaMenu Block for Category: '.$categoryId);
			$block->save();
			$staticBlockId = $block->getId();
		}

		$this->_redirect('adminhtml/cms_block/edit', array('block_id'=>$staticBlockId ));
	}

}
