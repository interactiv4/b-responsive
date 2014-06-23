<?php
/**
 *
 * @author Enrique Piatti
 */ 
class RedLightBlinking_ProductSlider_Model_Source_Slider
{
	const JCAROUSEL = 'jcarousel';
	const JCAROUSEL_LITE = 'jcarousel_lite';
	const TINY_CAROUSEL = 'tiny_carousel';
	const BOOTSTRAP_CAROUSEL = 'carousel';
	const PROTOTYPE_CAROUSEL = 'prototype_carousel';

	public function toOptionArray()
	{
		$options = array();
		$sliders = $this->getSliders();
		foreach ( $sliders as $slider => $label){
			$options[] = array(
				'value' => $slider,
				'label' => $label,
			);
		}
		return $options;
	}



	public function getSliders()
	{
		// TODO: implement more sliders
		return array(
			//self::PROTOTYPE_CAROUSEL => 'Prototype Carousel',
			//self::BOOTSTRAP_CAROUSEL => 'Bootstrap Carousel',
			self::JCAROUSEL => 'jCarousel',
			//self::JCAROUSEL_LITE => 'jCarousel Lite',
			//self::TINY_CAROUSEL => 'Tiny Carousel',
		);
	}

	public function isSliderAllowed($slider)
	{
		$sliders = array_keys($this->getSliders());
		return in_array($slider, $sliders);
	}

}
