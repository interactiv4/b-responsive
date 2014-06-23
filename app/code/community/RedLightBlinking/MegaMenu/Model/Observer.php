<?php
/**
 *
 * @author Enrique Piatti
 */ 
class RedLightBlinking_MegaMenu_Model_Observer {

	/**
	 * Add button to Edit/Create Mega Menu static block from Category Edit form (Display Settings tab)
	 * @param $event
	 */
	public function adminhtmlCatalogCategoryEditPrepareForm($event)
	{
		$category = Mage::registry('current_category');
		if($category && $category->getLevel() == 2)
		{
			$form = $event->getForm();
			/** @var Varien_Data_Form_Element_Collection $elements */
			$elements = $form->getElements();
			foreach($elements as $element){
				if($element instanceof Varien_Data_Form_Element_Fieldset){
					if($element->getLegend() == "Display Settings")
					{
						// $storeId = getStoreFromCategory() // TODO
						$categoryId = $category->getId();
						$url = Mage::getModel('adminhtml/url')->getUrl('megamenu/staticBlock/index', array('category_id' => $categoryId));
						$megaMenuBlockId = Mage::helper('megamenu')->getMegaMenuStaticBlock($categoryId)->getId();
						$element->addField('megamenu_block', 'button', array(
							'name'  => '',
							'label' => 'Mega Menu static Block',
							'note'  => 'save your changes on this category before '.($megaMenuBlockId ? 'editing' : 'creating').' the mega menu static block',
							'value' => ($megaMenuBlockId ? 'Edit' : 'Create') . ' Mega Menu Static Block',
							'onclick' => "window.location='".$url."';"
						));
					}
				}
			}
		}
	}
}
