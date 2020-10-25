<?php
# Copyright (c) 2007-2020 Linh Pham
# wwdt.me_v2 is relased under the terms of the Apache License 2.0

/**
 * This file includes the functions pertaining to generating panelist graphics
 */

// Require db.inc
require_once 'db_conn.php';

// Require functions
require_once 'functions.php';

// Require PEAR::Image_Graph
require_once 'Image/Graph.php';

// Global variables
$panelistScoreBreakdown = null;

function retrievePanelistScoreBreakdown() {
	// Pull in database, score breakdown and graph variables into local scope
	global $dbUri, $dbOptions, $panelistScoreBreakdown;

	// Connect to database
	$dbConnection = DB::connect($dbUri, $dbOptions);
	if (PEAR::isError($dbConnection)) {
		die($dbconnection->getMessage());
	}

	// Pull in data from database
	$minScore = $dbConnection->getOne('select min(panelistscore) from ww_showpnlmap where panelistscore is not null');
	if (PEAR::isError($minScore)) {
		die($minScore->getMessage());
	}

	$maxScore = $dbConnection->getOne('select max(panelistscore) from ww_showpnlmap where panelistscore is not null');
	if (PEAR::isError($maxScore)) {
		die($maxScore->getMessage());
	}

	$scoreBreakdown = $dbConnection->getAssoc('select * from v_ww_panelistscore_breakdown', false, array(), DB_FETCHMODE_ASSOC, true);
	if (PEAR::isError($scoreBreakdown)) {
		die($scoreBreakdown->getMessage());
	}

	// Populate panelists with score breakdown
	foreach (array_keys($scoreBreakdown) as $panelist) {
		// Create temporary array with index range of $minScore and $maxScore
		$s = array_fill($minScore, 1 + ($maxScore - $minScore), 0);

		// Populate temporary array with panelist score breakdown
		for ($i = 0; $i < count($scoreBreakdown[$panelist]); $i++) {
			$s[$scoreBreakdown[$panelist][$i]['panelistscore']] = $scoreBreakdown[$panelist][$i]['panelistscorecount'];
		}

		$panelistScoreBreakdown[$panelist] = $s;
		$panelistFilename = cleanPanelistName($panelist) . '.png';

		// Build up graph
		// Programmatically determine graph width based on number of scores, but
		// using GRAPH_MAX_WIDTH and GRAPH_MIN_WIDTH as max and min widths, respectively
		// (width is equal to the number of scores times 40, plus an additional 10 for y-axis)
		$tempWidth = ((40 * (1 + ($maxScore - $minScore))) + 10);
		if (GRAPH_MAX_WIDTH <= $tempWidth) {
			$graphWidth = GRAPH_MAX_WIDTH;
		} else if (GRAPH_MIN_WIDTH >= $tempWidth) {
			$graphWidth = GRAPH_MIN_WIDTH;
		} else {
			$graphWidth = $tempWidth;
		}

		$Graph = Image_Graph::factory('graph', array($graphWidth, GRAPH_HEIGHT));
		$Font = $Graph->addNew('ttf_font', GRAPH_FONT_PATH);
		$Font->setSize(9);
		$Graph->setFont($Font);

		$Plotarea = $Graph->addNew('plotarea');
		$Dataset = Image_Graph::factory('dataset');

		// Add in data into dataset
		$maxCount = 0;
		for ($i = $minScore; $i <= $maxScore; $i++) {
			$Dataset->addPoint($i, $panelistScoreBreakdown[$panelist][$i]);
			$tempCount = $panelistScoreBreakdown[$panelist][$i];
			if ($tempCount > $maxCount) {
				$maxCount = $tempCount;
			}
		}
		
		$Grid = $Plotarea->addNew('line_grid', null, IMAGE_GRAPH_AXIS_Y);
		$Grid->setLineColor('lightgray@0.2');
		$Plot = $Plotarea->addNew('bar', array(&$Dataset));
		$Plot->setFillColor(GRAPH_BAR_COLOR);
		$Plot->setBackgroundColor(GRAPH_BACKGROUND_COLOR);
		$Plot->setLineColor(GRAPH_LINE_COLOR);
		$AxisY = $Plotarea->getAxis(IMAGE_GRAPH_AXIS_Y);
		$AxisY->setLabelInterval(ceil($maxCount / 10.0));

		// Write out graph to file
		$Graph->done(array('filename' => GRAPH_OUTPUT_PATH . $panelistFilename));

		// Null out graph variables
		$Graph = null;
		$Font = null;
		$Plotarea = null;
		$Dataset = null;
		$Plot = null;
		$AxisY = null;
	}	
}
