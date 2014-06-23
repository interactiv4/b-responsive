<?php
/**
 *
 * @author Enrique Piatti
 */ 
class RedLightBlinking_ProductSlider_Model_Source_Product_Attribute
{
	public function toOptionArray()
	{
		$options = array();
		$entityTypeId = Mage::getModel('catalog/product')->getResource()->getEntityType()->getId();
		$attributes = Mage::getResourceModel('eav/entity_attribute_collection')
			->setEntityTypeFilter($entityTypeId)
			->setOrder('attribute_code', 'asc')
			->getData();
		foreach ( $attributes as $option){
			$options[] = array(
				'value' => $option['attribute_code'],
				'label' => empty($option['frontend_label']) ? $option['attribute_code'] : $option['frontend_label'],
			);
		}
		return $options;
	}
}
