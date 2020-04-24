<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2005 Eirik Wulff (e@wulff.biz)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * Plugin 'Google Analytics' for the 'googleanalytics' extension.
 *
 * @author	Eirik Wulff <e@wulff.biz>
 */


require_once(PATH_tslib.'class.tslib_pibase.php');

class tx_googleanalytics_pi1 extends tslib_pibase {
	var $prefixId = 'tx_googleanalytics_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_googleanalytics_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey = 'googleanalytics';	// The extension key.
	var $pi_checkCHash = TRUE;
	
	/**
	 * [Put your description here]
	 */
	function main($content,$conf)	{
		$user_account = $conf['uacct'];
		$GLOBALS['TSFE']->additionalHeaderData[] = '
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "'.$user_account.'";
urchinTracker();
</script>';
		return '<!-- Plugin: tx_googleanalytics_pi1 was inserted -->';
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/googleanalytics/pi1/class.tx_googleanalytics_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/googleanalytics/pi1/class.tx_googleanalytics_pi1.php']);
}

?>