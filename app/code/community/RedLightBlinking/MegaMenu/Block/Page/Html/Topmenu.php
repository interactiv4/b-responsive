<?php
/**
 *
 * @author Enrique Piatti
 */ 
class RedLightBlinking_MegaMenu_Block_Page_Html_Topmenu extends Mage_Page_Block_Html_Topmenu
{


	/**
	 * Change template if Mega Menu is enabled
	 *
	 * @return string
	 */
	protected function _toHtml()
	{
		if(Mage::helper('megamenu')->isMegaMenuEnabled()){
			$this->setTemplate('megamenu/page/html/megamenu.phtml');
		}
		return parent::_toHtml();
	}


	/**
	 * include submenu items inside a div container with the possibility to add a static block there
	 *
	 * @param Varien_Data_Tree_Node $menuTree
	 * @param string $childrenWrapClass
	 * @return string
	 */
	protected function _getHtml(Varien_Data_Tree_Node $menuTree, $childrenWrapClass)
	{
		if( ! Mage::helper('megamenu')->isMegaMenuEnabled()){
			return parent::_getHtml($menuTree, $childrenWrapClass);
		}

		$html = '';

		$children = $menuTree->getChildren();
		$parentLevel = $menuTree->getLevel();
		$childLevel = is_null($parentLevel) ? 0 : $parentLevel + 1;

		$counter = 1;
		$childrenCount = $children->count();

		$parentPositionClass = $menuTree->getPositionClass();
		$itemPositionClassPrefix = $parentPositionClass ? $parentPositionClass . '-' : 'nav-';

		foreach ($children as $child) {

			$child->setLevel($childLevel);
			$child->setIsFirst($counter == 1);
			$child->setIsLast($counter == $childrenCount);
			$child->setPositionClass($itemPositionClassPrefix . $counter);

			$outermostClassCode = '';
			$outermostClass = $menuTree->getOutermostClass();

			if ($childLevel == 0 && $outermostClass) {
				$outermostClassCode = ' class="' . $outermostClass . '" ';
				$child->setClass($outermostClass);
			}

			$html .= '<li ' . $this->_getRenderedMenuItemAttributes($child) . '>';
			$html .= '<a href="' . $child->getUrl() . '" ' . $outermostClassCode . '><span>'
				. $this->escapeHtml($child->getName()) . '</span></a>';

			if ($child->hasChildren()) {
				if (!empty($childrenWrapClass)) {
					$html .= '<div class="' . $childrenWrapClass . '">';
				}

				if($childLevel == 0){
					$html .= '<div class="submenu-container"><div class="container">';
				}

				$grandChildren = $child->getChildren();
				$grandChildrenCount = $grandChildren->count();
				$columns = ceil($grandChildrenCount / 8);
				$columnsClass = 'columns'.$columns;

				$html .= '<ul class="level' . $childLevel . ' '. $columnsClass .'">';
				$html .= $this->_getHtml($child, $childrenWrapClass);
				$html .= '</ul>';

				if($childLevel == 0){
					$staticBlock = $this->_getStaticBlockHtml($child);
					$html .= '<div class="static-container">'.$staticBlock.'</div>';
					$html .= '</div></div>';
				}

				$html .= '<div class="see-all"><a href="'. $child->getUrl() .'">All '. $this->escapeHtml($child->getName()) .'</a></div>';

				if (!empty($childrenWrapClass)) {
					$html .= '</div>';
				}
			}

			$html .= '</li>';

			$counter++;
		}

		return $html;
	}


	protected function _getStaticBlockHtml($child)
	{
		$nodeId = $child->getId();
		$prefix = 'category-node-';
		$categoryId = substr($nodeId, strlen($prefix));
		return Mage::helper('megamenu')->getMegaMenuStaticBlockHtml($categoryId);
	}

}
