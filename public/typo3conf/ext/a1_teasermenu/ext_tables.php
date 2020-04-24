<?php
if (!defined ("TYPO3_MODE")) 	die ("Access denied.");
$tempColumns = Array (
	"tx_a1teasermenu_cols" => Array (		
		"exclude" => 1,		
		"label" => "LLL:EXT:a1_teasermenu/locallang_db.php:tt_content.tx_a1teasermenu_cols",		
		"config" => Array (
			"type" => "input",	
			"size" => "4",
			"max" => "4",
			"eval" => "int",
			"checkbox" => "0",
			"range" => Array (
				"upper" => "99",
				"lower" => "1"
			),
			"default" => 0
		)
	),
	"tx_a1teasermenu_type" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:a1_teasermenu/locallang_db.php:tt_content.tx_a1teasermenu_type",		
			"config" => Array (
				"type" => "select",
				"items" => Array (
					Array("LLL:EXT:a1_teasermenu/locallang_db.php:tt_content.tx_a1teasermenu_type.I.0", "0"),
					Array("LLL:EXT:a1_teasermenu/locallang_db.php:tt_content.tx_a1teasermenu_type.I.1", "1"),
				),
				"size" => 1,	
				"maxitems" => 1,
			)
		),
	
);


t3lib_div::loadTCA("tt_content");
t3lib_extMgm::addTCAcolumns("tt_content",$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes("tt_content","tx_a1teasermenu_cols;;;;1-1-1,tx_a1teasermenu_type;;;;1-1-1");



$tempColumns = Array (
	"tx_a1teasermenu_images" => Array (		
		"exclude" => 1,		
		"label" => "LLL:EXT:a1_teasermenu/locallang_db.php:pages.tx_a1teasermenu_images",		
		"config" => Array (
			"type" => "group",
			"internal_type" => "file",
			"allowed" => $GLOBALS["TYPO3_CONF_VARS"]["GFX"]["imagefile_ext"],	
			"max_size" => 500,	
			"uploadfolder" => "uploads/tx_a1teasermenu",
			"show_thumbs" => 1,	
			"size" => 2,	
			"minitems" => 0,
			"maxitems" => 2,
		)
	),
);


t3lib_div::loadTCA("pages");
t3lib_extMgm::addTCAcolumns("pages",$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes("pages","tx_a1teasermenu_images;;;;1-1-1");




t3lib_extMgm::addPlugin(Array("LLL:EXT:a1_teasermenu/locallang_db.php:tt_content.menu_type_pi1", $_EXTKEY."_pi1"),"menu_type");
?>