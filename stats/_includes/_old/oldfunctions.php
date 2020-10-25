<?php
# Copyright (c) 2007-2020 Linh Pham
# wwdt.me_v2 is relased under the terms of the Apache License 2.0

/**
 * This file includes old functions pertaining to processing the various
 * data about the show.
 */
 
function retrieveShowInfo() {
	// Pull in database and show info variables into local scope
	global $dbUri, $dbOptions, $showInfo;

	// Connect to database
	$dbConnection =& DB::connect($dbUri, $dbOptions);
	if (PEAR::isError($dbConnection)) {
		die($dbConnection->getMessage());
	}

	// Pull in data from database
	$info = $dbConnection->getAssoc('select * from v_ww_showinfo', false, array(), DB_FETCHMODE_ASSOC, true);
	if (PEAR::isError($info)) {
		die($info->getMessage());
	}

	// Populate show info
	foreach(array_keys($info) as $show) {
		// Pull in show information
		$tempDescription = htmlentities($info[$show][0]['showdescription']);
		// Replace "//" with "<hr class="thin" />"
		$showInfo[$show]['showdescription'] = str_replace('//', '<hr class="thin" />', $tempDescription);
		$showInfo[$show]['bestof'] = ($info[$show][0]['bestof'] == 1 ? true : false);
		$showInfo[$show]['showrating'] = $info[$show][0]['showrating'];
	}
	
	// Disconnect from database
	$dbConnection->disconnect();
}