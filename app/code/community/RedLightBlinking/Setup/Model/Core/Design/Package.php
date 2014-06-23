<?php
/**
 * based on: https://bitbucket.org/markoshust/markshust_enterprisefallback
 */
class RedLightBlinking_Setup_Model_Core_Design_Package extends Mage_Core_Model_Design_Package
{

	/**
	 * Use this one to get existing file name with fallback to default
	 *
	 * $params['_type'] is required
	 *
	 * @param string $file
	 * @param array $params
	 * @return string
	 */
	public function getFilename($file, array $params)
	{
		Varien_Profiler::start(__METHOD__);
		$this->updateParamDefaults($params);
		$result = $this->_fallback($file, $params, array(
			array(),
			array('_theme' => $this->getFallbackTheme()),
			array('_theme' => self::DEFAULT_THEME),
			array('_theme' => self::DEFAULT_THEME, '_package' => 'enterprise'),
			array('_theme' => self::DEFAULT_THEME, '_package' => 'default'),
		));
		Varien_Profiler::stop(__METHOD__);

		return $result;
	}

	/**
	 * Get skin file url
	 *
	 * @param string $file
	 * @param array $params
	 * @return string
	 */
	public function getSkinUrl($file = null, array $params = array())
	{
		Varien_Profiler::start(__METHOD__);
		if (empty($params['_type'])) {
			$params['_type'] = 'skin';
		}
		if (empty($params['_default'])) {
			$params['_default'] = false;
		}
		$this->updateParamDefaults($params);
		if (!empty($file)) {
			$result = $this->_fallback($file, $params, array(
				array(),
				array('_theme' => $this->getFallbackTheme()),
				array('_theme' => self::DEFAULT_THEME),
				array('_theme' => self::DEFAULT_THEME, '_package' => 'enterprise'),
				array('_theme' => self::DEFAULT_THEME, '_package' => 'default'),
			));
		}
		$result = $this->getSkinBaseUrl($params) . (empty($file) ? '' : $file);
		Varien_Profiler::stop(__METHOD__);

		return $result;
	}

}
