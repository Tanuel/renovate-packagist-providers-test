<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2004 Mirko Balluff (balluff@amt1.de)
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
 * Plugin 'A1 Teasermenu' for the 'a1_teasermenu' extension.
 *
 * @author	Mirko Balluff <balluff@amt1.de>
 */


require_once(PATH_tslib."class.tslib_pibase.php");

class tx_a1teasermenu_pi1 extends tslib_pibase {
	var $prefixId = "tx_a1teasermenu_pi1";		// Same as class name
	var $scriptRelPath = "pi1/class.tx_a1teasermenu_pi1.php";	// Path to this script relative to the extension dir.
	var $extKey = "a1_teasermenu";	// The extension key.

	var $cols = 1;				// numer of columns to render
	var $conf = "";				// configuration array
	var $templateCode = "";		// the template code for the menuitems
	var $type = 0;				// the type of the menu (0: menu of subpages; 1: mewnu of pages)

	/**
	 * The main function, renders the menu and calls some helper functions.
	 *
	 * @param	string		$content: the content
	 * @param	array		$conf: the configuration
	 * @return	string		the HTML-Code of the teasermenu
	 */
	function main($content,$conf)	{
		$this->conf = $conf;

		
		// Get the pids to render the menu from. Search in this order: typoscript conf, pages, current page
		$menuPids = array();
		if($conf["pid"]){
			$menuPids = explode(',', trim($this->cObj->stdWrap($conf["pid"],$conf["pid."])));
		}elseif($this->cObj->data["pages"]){
			$menuPids = explode(',', $this->cObj->data["pages"]);
		}else{
			$menuPids[] = $GLOBALS["TSFE"]->id;
		}
		
		
		// Include some Javascript for the rollover effect
		$GLOBALS["TSFE"]->additionalJavaScript["tx_a1teasermenu_pi1"] = '
			function tx_a1teasermenu_pi1_swapImgRestore() { //v3.0
				var i,x,a=document.tx_a1teasermenu_pi1_sr;
				for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) {
					x.src=x.oSrc;
				}
			}

			function tx_a1teasermenu_pi1_findObj(n, d) { //v4.0
				var p,i,x;
				if(!d) d=document;
				if((p=n.indexOf("?"))>0&&parent.frames.length) {
			    	d=parent.frames[n.substring(p+1)].document;
					n=n.substring(0,p);
				}
			  	if(!(x=d[n])&&d.all) x=d.all[n];
				for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
				for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=tx_a1teasermenu_pi1_findObj(n,d.layers[i].document);
				if(!x && document.getElementById) x=document.getElementById(n);
				return x;
			}

			function tx_a1teasermenu_pi1_swapImage() { //v3.0
			  var i,j=0,x,a=tx_a1teasermenu_pi1_swapImage.arguments;


			  document.tx_a1teasermenu_pi1_sr=new Array;
			  for(i=0;i<(a.length-2);i+=3)
			  	if ((x=tx_a1teasermenu_pi1_findObj(a[i]))!=null){
					document.tx_a1teasermenu_pi1_sr[j++]=x;
					if(!x.oSrc) x.oSrc=x.src;
					x.src=a[i+2];
				}
			}
		';
		
		// load the template code
		$this->templateCode = $this->cObj->fileResource($this->conf["templateFile"]);
		
		// get the number of columns
		$this->cols = ($this->cObj->data["tx_a1teasermenu_cols"]?$this->cObj->data["tx_a1teasermenu_cols"]:1);
		
		// get the type
		$this->type = $this->cObj->data["tx_a1teasermenu_type"]?$this->cObj->data["tx_a1teasermenu_type"]:0;

			// Now, get an array with all the subpages to this pid:
			// (Function getMenu() is found in class.t3lib_page.php)
		
		// get the pid's to render
		$menuItemsLevel1 = array();
		while(list(,$pid) = each($menuPids)){
			switch($this->type){
				case 0:
					$menuItemsLevel1 = array_merge($menuItemsLevel1, $GLOBALS["TSFE"]->sys_page->getMenu($pid));
				break;
				case 1:
					$menuItemsLevel1[] = $GLOBALS["TSFE"]->sys_page->getPage($pid);
				break;
			}

		}
		
		// get the content from the selected pages
		
			// Prepare vars:
		$items=array();

			// Traverse menuitems:
		reset($menuItemsLevel1);
		
		while(list($uid,$pages_row)=each($menuItemsLevel1))	{
			$items[] = $this->getItem($pages_row);
		}

		$totalMenu = '<table class="tx-a1teasermenu-pi1">';
		$z = 1;
		$totalMenu .= '<tr class="tx-a1teasermenu-pi1">';
		while(list($uid,$item) = each($items)){
			$totalMenu .= '<td class="tx-a1teasermenu-pi1">' . $item . '</td>';
			if(($z % $this->cols) == 0){
				$totalMenu .= '</tr>';
			}
			$z++;
		}
		// append needed td's


		if ((count($items) % $this->cols)!=0){
			for($i=1;$i<=($this->cols - (count($items) % $this->cols));$i++){
				$totalMenu .= '<td class="tx-a1teasermenu-pi1">&nbsp;</td>';
			}
		}

		$totalMenu .= '</table>';

		return $totalMenu;
	}

	/**
	 * Returns the HTML-Code for the current for the given page
	 *
	 * @param	array		$pageRow: the pagedata for the menuitem to render
	 * @return	string		the html-code for the menuitem
	 */
	function getItem($pageRow){
		$content = $this->cObj->getSubpart($this->templateCode, "###MENU_ITEM###");
		$content = $this->cObj->substituteMarkerArrayCached($content,$this->getMarkerArray($pageRow),array(),$this->getWrappedSubpartArray($pageRow));

		return $content;
	}

	/**
	 * Returns the array needed to replace wrapped items. In this case the Suppartmarker for the pagelink
	 *
	 * @param	array		$row: an array with pagedata
	 * @return	array		the wrapped subpart array
	 */
	function getWrappedSubpartArray($row){
		$wrappedSubpartArray = array();

		$wrappedSubpartArray["###LINK_ITEM###"] = array('<a href="'.$this->pi_getPageLink($row["uid"],$row["target"],array()).'">','</a>');

		return $wrappedSubpartArray;
	}

	/**
	 * Returns the markerarray for the menuitem. it contains all fields of the given row
	 * converted to upper case and additionaly an marker called image
	 *
	 * @param	array		$row: array of pagedata to substitute later
	 * @return	array		the marker array
	 */
	function getMarkerArray($row){
		$markerArray = array();
		while(list($key, $value)=each($row)){
			$markerArray["###".strtoupper($key)."###"] = $this->formatData($row, $key);
		}
		$markerArray["###IMAGE###"] = $this->formatData($row,"image");
		return $markerArray;
	}

	/**
	 * Returns the formatted data for fild $s in $row
	 *
	 * @param	array		$row: array of page data
	 * @param	string		$s: item (key) to format
	 * @return	string		thew formatted data
	 */
	function formatData($row, $s){
		switch($s){
		case "title":
			// if nav_title is set return it, else return title
			return $row["nav_title"]?$row["nav_title"]:$row["title"];
		case "abstract":
			// render the abstract field using the configuration from txtConf
			$txtConf = $this->conf["txtConf."];
			$txtConf["value"] = $row["abstract"]; // is there a nicer way to do this?
			return $this->cObj->cObjGetSingle($this->conf["txtConf"], $txtConf);
		case "image":
			// Returns the image-code using imgConf as configuraqtion, including the rollover-functions, create the rollover-image
			$a = explode(",",$row["tx_a1teasermenu_images"]);

			$imgRoConf = $this->conf["imgConf."];
			$imgRoConf["file"] = "uploads/tx_a1teasermenu/" . ($a[1]?$a[1]:$a[0]); // is there a nicer way to do this?
			$imgRoPath = $this->cObj->cObjGetSingle(
				'IMG_RESOURCE', // Contains the name, here "IMAGE"
				$imgRoConf // Contains the properties of "IMAGE"
			);


			$imgConf = $this->conf["imgConf."];

			$imgConf["file"] = "uploads/tx_a1teasermenu/" . $a[0];
			$imgConf["params"] .= ' name="tx_a1teasermenu_pi1_menuImage_'.$row["uid"].'" onMouseOut="tx_a1teasermenu_pi1_swapImgRestore();" onMouseOver="tx_a1teasermenu_pi1_swapImage(\'tx_a1teasermenu_pi1_menuImage_'.$row["uid"].'\', \'\', \''.$imgRoPath.'\')"';
			return $this->cObj->cObjGetSingle($this->conf["imgConf"], $imgConf);
		default:
			return $row[$s];
		}
	}
}

if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/a1_teasermenu/pi1/class.tx_a1teasermenu_pi1.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/a1_teasermenu/pi1/class.tx_a1teasermenu_pi1.php"]);
}

?>