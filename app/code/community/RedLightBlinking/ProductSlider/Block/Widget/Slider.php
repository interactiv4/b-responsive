<?php
/**
 *
 * @author Enrique Piatti
 */ 
class RedLightBlinking_ProductSlider_Block_Widget_Slider extends Mage_Core_Block_Abstract
{

	/** @var  Varien_Data_Form_Element_Abstract */
	protected $_element;

	/**
	 * Prepare chooser element HTML
	 *
	 * @param Varien_Data_Form_Element_Abstract $element Form Element
	 * @return Varien_Data_Form_Element_Abstract
	 */
	public function prepareElementHtml(Varien_Data_Form_Element_Abstract $element)
	{
		$this->_element = $element;
		$element->setData('after_element_html', $this->toHtml());
		return $element;
	}

	protected function _toHtml()
	{
		$elementId = $this->_element->getId();
		$html = <<<HTML
<span id="slider-library-help"></span>
<script>
		Event.observe($("$elementId"),'change', function(){
			var library = this.value;
			var help = 'http://sorgalla.com/jcarousel/  (requires jQuery)';
			switch(library){
				case 'jcarousel':
					help = 'http://sorgalla.com/jcarousel/  (requires jQuery)';
					break;
				case 'jcarousel_lite':
					help = 'http://www.gmarwaha.com/jquery/jcarousellite/  (requires jQuery)';
					break;
				case 'tiny_carousel':
					help = 'http://baijs.com/tinycarousel/  (requires jQuery)';
					break;
				case 'carousel':
					help = 'http://getbootstrap.com/javascript/#carousel/  (requires Bootstrap)';
					break;
				case 'prototype_carousel':
					help = 'http://dev.victorstanciu.ro/prototype/carousel/  (requires scriptaculous, is included automatically)';
					break;
			}
			$('slider-library-help').innerHTML = help;
		})
</script>
HTML;

		return $html;
	}


}
