<?php
/**
 *
 * @author Enrique Piatti
 */ 
class RedLightBlinking_ProductSlider_Block_Widget extends Mage_Core_Block_Template
	implements Mage_Widget_Block_Interface
{

	protected $_defaultTemplate = 'productslider/widget/slider.phtml';
	/** @var Mage_Review_Block_Helper _reviewsHelperBlock */
	protected $_reviewsHelperBlock;


	protected function _construct()
	{
		$this->setTemplate($this->_defaultTemplate);
		parent::_construct();
	}

	public function getTemplate()
	{
		return trim($this->getData('template_path')) ? : $this->_defaultTemplate;
	}

	/**
	 * @return Mage_Catalog_Model_Resource_Product_Collection
	 */
	public function getProducts()
	{
		/** @var RedLightBlinking_ProductSlider_Model_Resource_Product_Collection $products */
		$products = Mage::getResourceModel('productslider/product_collection')
			->addStoreFilter();

		$this->_addAttributesToSelect($products);

		$this->_filterByCategory($products);

		$this->_filterByAttribute($products);

		// Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
		// Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);
		$products->addAttributeToFilter('visibility', array(Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG, Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH));
		$products->addAttributeToFilter('status',Mage_Catalog_Model_Product_Status::STATUS_ENABLED);

		$this->_sortProducts($products);

		$maxItems = (int)$this->getMaxItems();
		if($maxItems) {
			$products->setPageSize($maxItems);
		}
		return $products;
	}

	/**
	 * @param $products RedLightBlinking_ProductSlider_Model_Resource_Product_Collection
	 */
	private function _filterByCategory($products)
	{
		$categoryId = $this->getCategory();
		if($categoryId){
			$prefix = 'category/';
			if(strpos($categoryId, $prefix) === 0){
				$categoryId = substr($categoryId, strlen($prefix));
			}
			$category = Mage::getModel('catalog/category')->load($categoryId);
			$category->setIsAnchor(true);
			$products->addCategoryFilter($category);
		}
	}

	/**
	 * @param $products RedLightBlinking_ProductSlider_Model_Resource_Product_Collection
	 */
	private function _filterByAttribute($products)
	{
		$useAttribute = $this->getUseAttribute();
		if($useAttribute){
			$attribute = $this->getAttribute();
			$attributeValue = trim($this->getAttributeValue());
			if($attributeValue)
			{
				$special = array(
					'neq' => '!=',
					'gt' => '>',
					'gteq' => '>=',
					'lt' => '<',
					'lteq' => '<='
				);
				$condition = array('eq' => $attributeValue);
				foreach($special as $cond => $symbol){
					if(strpos($attributeValue, $symbol) === 0){
						$attributeValue = trim(substr($attributeValue, strlen($symbol)));
						$condition = array($cond => $attributeValue);
						break;
					}
				}
				$products->addAttributeToFilter($attribute, $condition);
			}
		}
	}

	/**
	 * @param $products RedLightBlinking_ProductSlider_Model_Resource_Product_Collection
	 */
	private function _sortProducts($products)
	{
		$order = $this->getOrder();
		switch($order)
		{
			case 'top_sellers':
				$products->addOrderedQty()->setOrderByOrderedQty();
				break;
			case 'most_viewed':
				$products->addViewsCount()->setOrderByViews();
				break;
			case 'just_added':
				$products->setOrderByNewestFirst();
				break;
			case 'random':
				$products->setOrderByRandom();
				break;
			case 'created_at':
				$products->setOrder('created_at');
				break;
//			case 'top_rated':
//				$products->addViewsCount()->setOrderByViews();
//				break;
//			case 'most_reviewed':
//				$products->addOrderedQty()->setOrderByOrderedQty();
//				break;
		}
	}

	/**
	 * @param $products RedLightBlinking_ProductSlider_Model_Resource_Product_Collection
	 */
	private function _addAttributesToSelect($products)
	{

		$products
			->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
			->addMinimalPrice()
			->addFinalPrice()
			->addTaxPercents()
			->addUrlRewrite();

		// append Rating Review:
		// Mage::getModel('review/review')->appendSummary($products);

		$attributes = $this->getAttributes();
		$forcedAttributes = array(
			'name',
			'small_image'
		);
		$products->addAttributeToSelect($forcedAttributes);
		if($attributes){
			$attributes = explode(',', $attributes);
			foreach($attributes as $attribute){
				if(in_array($attribute, $forcedAttributes)){
					continue;
				}
				$products->addAttributeToSelect($attribute);
				if($attribute == 'price'){
					$products->addPriceData();
				}
			}
		}
	}

	public function getSliderJs()
	{
		$sliderLibrary = $this->getSlider();
		/** @var Mage_Core_Block_Template $block */
		$block = $this->getLayout()->createBlock('core/template', 'productslider_js');
		$slidersSource = Mage::getModel('productslider/source_slider');
		if($slidersSource->isSliderAllowed($sliderLibrary) ){
			$template = 'productslider/js/'.$sliderLibrary.'.phtml';
			$block->setTemplate($template);
		}
		$block->setSliderId($this->getSliderId());
		return $block->toHtml();
	}

	public function getSliderId()
	{
		return 'product_slider_'.$this->_nameInLayout;
	}


	/**
	 * Get product reviews summary
	 *
	 * @param Mage_Catalog_Model_Product $product
	 * @param bool $templateType
	 * @param bool $displayIfNoReviews
	 * @return string
	 */
	public function getReviewsSummaryHtml(Mage_Catalog_Model_Product $product, $templateType = false,
										  $displayIfNoReviews = false)
	{
		if (!Mage::helper('catalog')->isModuleEnabled('Mage_Review')) {
			return '';
		} else {
			if( ! $this->_reviewsHelperBlock){
				$this->_reviewsHelperBlock = $this->getLayout()->createBlock('review/helper');
			}
			return $this->_reviewsHelperBlock->getSummaryHtml($product, $templateType, $displayIfNoReviews);
		}
	}

}
