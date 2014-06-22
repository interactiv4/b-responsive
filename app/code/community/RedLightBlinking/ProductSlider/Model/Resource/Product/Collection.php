<?php
/**
 *
 * @author Enrique Piatti
 */ 
class RedLightBlinking_ProductSlider_Model_Resource_Product_Collection extends Mage_Catalog_Model_Resource_Product_Collection
{

	/**
	 * Bugfix for Group By
	 * Get SQL for get record count
	 *
	 * @return Varien_Db_Select
	 */
	public function getSelectCountSql()
	{
		$this->_renderFilters();
		$countSelect = clone $this->getSelect();
		$countSelect->reset(Zend_Db_Select::ORDER);
		$countSelect->reset(Zend_Db_Select::LIMIT_COUNT);
		$countSelect->reset(Zend_Db_Select::LIMIT_OFFSET);
		$countSelect->reset(Zend_Db_Select::COLUMNS);

		// Count doesn't work with group by columns keep the group by
		if(count($this->getSelect()->getPart(Zend_Db_Select::GROUP)) > 0) {
			$countSelect->reset(Zend_Db_Select::GROUP);
			$countSelect->distinct(true);
			$group = $this->getSelect()->getPart(Zend_Db_Select::GROUP);
			$countSelect->columns("COUNT(DISTINCT ".implode(", ", $group).")");
		} else {
			$countSelect->columns('COUNT(*)');
		}
		return $countSelect;
	}


	/**
	 * Add ordered qty's
	 *
	 * @return $this
	 */
	public function addOrderedQty()
	{
		$compositeTypeIds     = Mage::getSingleton('catalog/product_type')->getCompositeTypes();

		$this->addFieldToFilter('type_id', array('nin' => $compositeTypeIds));

		$this->getSelect()->joinInner(
			array('order_item' => $this->getTable('sales/order_item')),
			'order_item.product_id=e.entity_id',
			array('ordered_qty' => new Zend_Db_Expr('SUM(order_item.qty_ordered)'))
		);

		$this->getSelect()->where('order_item.parent_item_id IS NULL');
		$this->getSelect()->group('e.entity_id');
		//$this->getSelect()->having('ordered_qty > 0');

		// don't count canceled orders
//		$canceledStatus = Mage_Sales_Model_Order::STATE_CANCELED;
//		$this->getSelect()->joinInner(
//			array('order' => $this->getTable('sales/order')),
//			"order_item.order_id=order.entity_id AND order.state <> '$canceledStatus'",
//			array()
//		);

//		if ($from != '' && $to != '') {
//			$fieldName            = $orderTableAliasName . '.created_at';
//			$orderJoinCondition[] = $this->_prepareBetweenSql($fieldName, $from, $to);
//		}

		return $this;
	}


	public function setOrderByOrderedQty()
	{
		$this->getSelect()->order('ordered_qty DESC');
	}

	public function setOrderByRandom()
	{
		$this->getSelect()->order('rand()');
	}

	/**
	 * Add views count
	 *
	 * @return $this
	 */
	public function addViewsCount()
	{
		$productViewEvent = 0;
		/**
		 * Getting event type id for catalog_product_view event
		 */
		foreach (Mage::getModel('reports/event_type')->getCollection() as $eventType) {
			if ($eventType->getEventName() == 'catalog_product_view') {
				$productViewEvent = (int)$eventType->getId();
				break;
			}
		}

		$this->getSelect()->joinInner(
			array('views' => $this->getTable('reports/event')),
			'views.object_id=e.entity_id',
			array('views' => new Zend_Db_Expr('COUNT(views.event_id)'))
		);

		$this->getSelect()->where('views.event_type_id = ?', $productViewEvent);
		$this->getSelect()->group('e.entity_id');
		//$this->getSelect()->having('COUNT(report_table_views.event_id) > ?', 0);

//		if ($from != '' && $to != '') {
//			$this->getSelect()
//				->where('logged_at >= ?', $from)
//				->where('logged_at <= ?', $to);
//		}

		return $this;
	}

	public function setOrderByViews()
	{
		$this->getSelect()->order('views DESC');
	}

	public function setOrderByNewestFirst()
	{
		$todayDate  = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
		$this->addAttributeToFilter('news_from_date', array('or'=> array(
				0 => array('date' => true, 'to' => $todayDate),
				1 => array('is' => new Zend_Db_Expr('null')))
			), 'left')
			->addAttributeToFilter('news_to_date', array('or'=> array(
				0 => array('date' => true, 'from' => $todayDate),
				1 => array('is' => new Zend_Db_Expr('null')))
			), 'left')
			->addAttributeToFilter(
				array(
					array('attribute' => 'news_from_date', 'is'=>new Zend_Db_Expr('not null')),
					array('attribute' => 'news_to_date', 'is'=>new Zend_Db_Expr('not null'))
				)
			)
			->addAttributeToSort('news_from_date', 'desc');
	}

}
