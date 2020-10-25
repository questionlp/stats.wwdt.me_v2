<?php
# Copyright (c) 2007-2020 Linh Pham
# wwdt.me_v2 is relased under the terms of the Apache License 2.0

/**
 * This file includes the functions pertaining to processing the various
 * data about the show.
 */

// Read in database connection string from db_conn.php
require_once 'db_conn.php';

// Global Variables
$showCount = 0;
$showDates = array();
$showYears = array();
$showInfo = array();
$showInfoByYear = array();
$showHosts = array();
$showScorekeepers = array();
$panelistStats = array();
$panelistScores = array();
$guestScores = array();

function isCLI() {
	if (php_sapi_name() == 'cli' && empty($_SERVER['REMOTE_ADDR'])) {
		return true;
	} else {
		return false;
	}
}

function parsePanelists($showDate) {
	$returnValue = null;

	// Pull in panelist scores variable into local scope
	global $panelistScores;

	if (is_null($panelistScores) || (!(array_key_exists($showDate, $panelistScores)))) {
		return '<span class="nd">(N/A)</span>';
	}

	$scores = $panelistScores[$showDate];

	if (is_null($scores)) {
		return '<em>(Multiple Panelists)</em>';
	}

	foreach (array_keys($scores) as $panelist) {
		$score = (array_key_exists('score', $scores[$panelist]) ?  $scores[$panelist]['score'] : null);
		$rank = (array_key_exists('rank', $scores[$panelist]) ? $scores[$panelist]['rank'] : null);

		if ($panelist == 'Luke Burbank') {
			$cPanelist = '<span title="Luuuuuuke">' . htmlentities($panelist, ENT_IGNORE, 'utf-8') . '</span>';
		} else {
			$cPanelist = htmlentities($panelist, ENT_IGNORE, 'utf-8');
		}

		if ($score != null) {
			switch ($rank) {
				case '1':
					$returnValue .= '<span class="r1">' . $cPanelist . ' (' . $score . ')</span>' . "\n";
					break;
				case '1t':
					$returnValue .= '<span class="r1t">' . $cPanelist . ' (' . $score . ')</span>' . "\n";
					break;
				case '2':
					$returnValue .= '<span class="r2">' . $cPanelist . ' (' . $score . ')</span>' . "\n";
					break;
				case '2t':
					$returnValue .= '<span class="r2t">' . $cPanelist . ' (' . $score . ')</span>' . "\n";
					break;
				case '3':
					$returnValue .= '<span class="r3">' . $cPanelist . ' (' . $score . ')</span>' . "\n";
					break;
				default:
					$returnValue .= $cPanelist . ' (' . $score . ')' . "\n";
					break;
			}
		} else {
			$returnValue .= $cPanelist . "\n";
		}
	}

	return str_replace("\n", '<br />', trim($returnValue));
}

function parseGuests($showDate) {
	$returnValue = null;

	// Pull in guest scores variable into local scope
	global $guestScores;

	if (is_null($guestScores) || (!(array_key_exists($showDate, $guestScores)))) {
		return '<span class="nd">(N/A)</span>';
	}

	$scores = $guestScores[$showDate];

	if (is_null($scores)) {
		return '<span class="nd">(N/A)</span>';
	}

	foreach (array_keys($scores) as $guest) {
		if ($guest == '[None]') {
			$returnValue .= '<span class="ng">No Guest</span>';
			break;
		} else {
			$cGuest = htmlentities($guest, ENT_IGNORE, 'utf-8');
		}

		$score = (array_key_exists('score', $scores[$guest]) ? $scores[$guest]['score'] : null);

		//if (!is_null($score) && !empty($score)) {
		if ($score != null) {
			/* Guests win if the guest answers 2 or more questions correctly,
			 * with the following detailed exceptions.
			 */
			if (($guest == 'Maj. Robert Bateman') && ($score == 1)) {
				/* Maj. Robert Bateman was given an exception by the host,
				 * Peter Sagal, who only answered the last question correct.
				 */
				if ($showDate == '2005-06-25') {
					/* Only include id=ofn1 for first instance of Maj.
					 * Robert Bateman's appearance
					 */
					$returnValue .= '<span class="gw">' . $cGuest . ' (' . $score . ')</span>' . '<a href="#fn1" id="ofn1"><sup class="fn">1</sup></a>' . "\n";
				} else {
					$returnValue .= '<span class="gw">' . $cGuest . ' (' . $score . ')</span>' . '<a href="#fn1"><sup class="fn">1</sup></a>' . "\n";
				}
			} else if ($guest == 'Patrick Fitzgerald') {
				/* Patrick Fitzgerald initially answered one question correctly,
				 * but after a "mock" appeal by the show's producer and a
				 * technicality on the last question, Patrick Fitzgerald won the
				 * game.
				 */
				if ($showDate == '2007-07-21') {
					/* Only include id=ofn2 for first instance of
					 * Patrick Fitzgerald's appearance
					 */
					$returnValue .= '<span class="gw">' . $cGuest . ' (' . $score . ')</span>' . '<a href="#fn2" id="ofn2"><sup class="fn">2</sup></a>' . "\n";
				} else {
					$returnValue .= '<span class="gw">' . $cGuest . ' (' . $score . ')</span>' . '<a href="#fn2"><sup class="fn">2</sup></a>' . "\n";
				}
			} else if ($score >= 2) {
				$returnValue .= '<span class="gw">' . $cGuest . ' (' . $score . ')</span>' . "\n";
			} else {
				$returnValue .= $cGuest . ' (' . $score . ')' . "\n";
			}
		} else {
			$returnValue .= $cGuest . "\n";
		}
	}

	return str_replace("\n", '<br />', trim($returnValue));
}

function parsePanelistPlacement($panelist) {
	// Pull in panelist statistics variable into local scope
	global $panelistStats;


	if (is_null($panelistStats)) {
		return;
	}

	$pnlPlacement = null;
	$pnlAppearances = $panelistStats[$panelist]['appearances'];
	$pnlFirst = (array_key_exists('1', $panelistStats[$panelist]['ranks']) ? $panelistStats[$panelist]['ranks']['1'] : '0');
	$pnlFirstTied = (array_key_exists('1t', $panelistStats[$panelist]['ranks']) ? $panelistStats[$panelist]['ranks']['1t'] : '0');
	$pnlSecond = (array_key_exists('2', $panelistStats[$panelist]['ranks']) ? $panelistStats[$panelist]['ranks']['2'] : '0');
	$pnlSecondTied = (array_key_exists('2t', $panelistStats[$panelist]['ranks']) ? $panelistStats[$panelist]['ranks']['2t'] : '0');
	$pnlThird = (array_key_exists('3', $panelistStats[$panelist]['ranks']) ? $panelistStats[$panelist]['ranks']['3'] : '0');

	$pnlPlacement .= 'First: ' . $pnlFirst . ' (' . (100 * round($pnlFirst / $pnlAppearances, 4)) . '%)<br />';
	$pnlPlacement .= 'First (Tied): ' . $pnlFirstTied . ' (' . (100 * round($pnlFirstTied / $pnlAppearances, 4)) . '%)<br />';
	$pnlPlacement .= 'Second: ' . $pnlSecond . ' (' . (100 * round($pnlSecond / $pnlAppearances, 4)) . '%)<br />';
	$pnlPlacement .= 'Second (Tied): ' . $pnlSecondTied . ' (' . (100 * round($pnlSecondTied / $pnlAppearances, 4)) . '%)<br />';
	$pnlPlacement .= 'Third: ' . $pnlThird . ' (' . (100 * round($pnlThird / $pnlAppearances, 4)) . '%)<br />';
	return $pnlPlacement;
}

function generatePanelistStats() {
	// Pull in database and panelist stats variables into local scope
	global $dbUri, $dbOptions, $panelistStats;

	// Connect to database and check for connection errors
	$dbConnection = DB::connect($dbUri, $dbOptions);
	if (PEAR::isError($dbConnection)) {
		die($dbConnection->getMessage());
	}

	// Pull in data from database
	$panelistScores = $dbConnection->getAssoc('select * from v_ww_panelistscore_basic', false, array(), DB_FETCHMODE_ORDERED, true);
	if (PEAR::isError($panelistScores)) {
		die($panelistScores->getMessage());
	}

	$panelistRankings = $dbConnection->getAssoc('select * from v_ww_panelistranks_norepeats', false, array(), DB_FETCHMODE_ASSOC, true);
	if (PEAR::isError($panelistRankings)) {
		die($panelistRankings->getMessage());
	}

	$panelistAppearanceFL = $dbConnection->getAssoc('select * from v_ww_panelist_appearance_fl', false, array(), DB_FETCHMODE_ASSOC, true);
	if (PEAR::isError($panelistAppearanceFL)) {
		die($panelistAppearanceFL->getMessage());
	}

	// Populate panelist stats with score and rank information
	foreach (array_keys($panelistScores) as $panelist) {
		// Pull and process basic statistics
		$appearances = count($panelistScores[$panelist]);
		$firstAppearance = $panelistAppearanceFL[$panelist][0]['first'];
		$latestAppearance = $panelistAppearanceFL[$panelist][0]['latest'];
		$sum = array_sum($panelistScores[$panelist]);
		$average = round(($sum / $appearances), 3);

		// Populate panelist statics
		$panelistStats[$panelist]['appearances'] = $appearances;
		$panelistStats[$panelist]['firstapp'] = $firstAppearance;
		$panelistStats[$panelist]['latestapp'] = $latestAppearance;
		$panelistStats[$panelist]['average'] = $average;
		$panelistStats[$panelist]['maxScore'] = max($panelistScores[$panelist]);
		$panelistStats[$panelist]['minScore'] = min($panelistScores[$panelist]);

		// Populate panelist rankings
		for ($i = 0; $i < count($panelistRankings[$panelist]); $i++) {
			$rank = $panelistRankings[$panelist][$i]['showpnlrank'];
			$panelistStats[$panelist]['ranks'][$rank] = $panelistRankings[$panelist][$i]['showpnlrank_count'];
		}
	}

	// Disconnect from database
	$dbConnection->disconnect();
}

function retrieveShowInfoByYear() {
	// Pull in database and show info variables into local scope
	global $dbUri, $dbOptions, $showInfoByYear;

	// Connect to database
	$dbConnection = DB::connect($dbUri, $dbOptions);
	if (PEAR::isError($dbConnection)) {
		die($dbConnection->getMessage());
	}

	// Pull in data from database into working array set
	$info = $dbConnection->getAssoc('select * from v_ww_showinfo_withyear', false, array(), DB_FETCHMODE_ASSOC, true);
	if (PEAR::isError($info)) {
		die($info->getMessage());
	}

	// Copy working array set into actual array
	$showInfoByYear = $info;

	// Disconnect from database
	$dbConnection->disconnect();
}

function retrieveShowHosts() {
	// Pull in database and host variables into local scope
	global $dbUri, $dbOptions, $showHosts;

	// Connect to database
	$dbConnection = DB::connect($dbUri, $dbOptions);
	if (PEAR::isError($dbConnection)) {
		die($dbConnection->getMessage());
	}

	// Pull in data from database
	$hosts = $dbConnection->getAssoc('select * from v_ww_hosts', false, array(), DB_FETCHMODE_ASSOC, true);
	if (PEAR::isError($hosts)) {
		die($hosts->getMessage());
	};

	// Populate hosts
	foreach (array_keys($hosts) as $show) {
		$showHosts[$show] = $hosts[$show][0];
	}

	// Disconnect from database
	$dbConnection->disconnect();
}

function retrieveShowScorekeepers() {
	// Pull in database and scorekeeper variables into local scope
	global $dbUri, $dbOptions, $showScorekeepers;

	// Connect to database
	$dbConnection = DB::connect($dbUri, $dbOptions);
	if (PEAR::isError($dbConnection)) {
		die($dbConnection->getMessage());
	}

	// Pull in data from database
	$scorekeepers = $dbConnection->getAssoc('select * from v_ww_scorekeepers', false, array(), DB_FETCHMODE_ASSOC, true);
	if (PEAR::isError($scorekeepers)) {
		die($scorekeepers->getMessage());
	}

	// Populate scorekeepers
	foreach (array_keys($scorekeepers) as $show) {
		$showScorekeepers[$show] = $scorekeepers[$show][0];
	}

	// Disconnect from database
	$dbConnection->disconnect();
}

function retrievePanelistScores() {
	// Pull in database and scores variables into local scope
	global $dbUri, $dbOptions, $panelistScores;

	// Connect to database
	$dbConnection = DB::connect($dbUri, $dbOptions);
	if (PEAR::isError($dbConnection)) {
		die($dbConnection->getMessage());
	}

	// Pull in data from database
	$scores = $dbConnection->getAssoc('select * from v_ww_panelistscores_bydate', false, array(), DB_FETCHMODE_ASSOC, true);
	if (PEAR::isError($scores)) {
		die($scores->getMessage());
	}

	// Populate show scores with panelist scores
	foreach (array_keys($scores) as $show) {
		for ($i = 0; $i < count($scores[$show]); $i++) {
			// Pull in panelist score
			$panelist = $scores[$show][$i]['panelist'];

			if (empty($panelist) || ($panelist == '<Multiple>')) {
				$panelistScores[$show] = null;
				break;
			} else {
				if ((array_key_exists('panelistscore', $scores[$show][$i]) && (($scores[$show][$i] != null) || ($scores[$show][$i] == '')))) {
					$panelistScore = $scores[$show][$i]['panelistscore'];
				} else {
					$panelistScore = null;
				}

				if (array_key_exists('showpnlrank', $scores[$show][$i])) {
					$panelistRank = $scores[$show][$i]['showpnlrank'];
				} else {
					$panelistRank = null;
				}

				// Populate scores
				$panelistScores[$show][$panelist]['score'] = $panelistScore;
				$panelistScores[$show][$panelist]['rank'] = $panelistRank;
			}
		}
	}

	// Disconnect from database
	$dbConnection->disconnect();
}

function getScoresByDate($showDate) {
	// Pull in scores variable into local scope
	global $panelistScores;

	// Return portion of scores array for showDate requested
	if (array_key_exists($showDate, $panelistScores)) {
		return $panelistScores[$showDate];
	} else {
		return null;
	}
}

function retrieveGuestScores() {
	// Pull in database and guest scores variable into local scope
	global $dbUri, $dbOptions, $guestScores;

	// Connect to database
	$dbConnection = DB::Connect($dbUri, $dbOptions);
	if (PEAR::isError($dbConnection)) {
		die($dbConnection->getMessage());
	}

	// Pull in data from database
	$scores = $dbConnection->getAssoc('select * from v_ww_guestscores_bydate', false, array(), DB_FETCHMODE_ASSOC, true);
	if (PEAR::isError($scores)) {
		die($scores->getMessage());
	}

	// Populate guest scores
	foreach (array_keys($scores) as $show) {
		for ($i = 0; $i < count($scores[$show]); $i++) {
			// Pull in guest score
			$guest = $scores[$show][$i]['guest'];

			if ((array_key_exists('guestscore', $scores[$show][$i]) && (($scores[$show][$i] != null) || ($scores[$show][$i] == '')))) {
				$guestScore = $scores[$show][$i]['guestscore'];
			} else {
				$guestScore = null;
			}

			// Populate scores
			$guestScores[$show][$guest]['score'] = $guestScore;
		}
	}

	// Disconnect from database
	$dbConnection->disconnect();
}

function retrieveShowCount() {
	// Pull in database and show count variables into local scope
	global $dbUri, $dbOptions, $showCount;

	// Connect to database
	$dbConnection = DB::Connect($dbUri, $dbOptions);
	if (PEAR::isError($dbConnection)) {
		die($dbConnection->getMessage());
	}

	// Pull in data from database
	$count = $dbConnection->getOne('select count(showid) as showcount from ww_shows');
	if (PEAR::isError($count)) {
		die($count->getMessage());
	}

	$showCount = intval($count);
}

function retrieveShowYears() {
	// Pull in database and show dates variable into local scope
	global $dbUri, $dbOptions, $showYears;

	// Connect to database
	$dbConnection = DB::Connect($dbUri, $dbOptions);
	if (PEAR::isError($dbConnection)) {
		die($dbConnection->getMessage());
	}

	// Pull in data from database
	$years = $dbConnection->query('select showyear from v_ww_showyears');
	if (PEAR::isError($years)) {
		die($years->getMessage());
	}

	// Populate show dates
	while ($years->fetchInto($row)) {
		array_push($showYears, $row[0]);
	}

	// Disconnect from database
	$dbConnection->disconnect();
}

function retrieveShowDates() {
	// Pull in database and show dates variable into local scope
	global $dbUri, $dbOptions, $showDates;

	// Connect to database
	$dbConnection = DB::Connect($dbUri, $dbOptions);
	if (PEAR::isError($dbConnection)) {
		die($dbConnection->getMessage());
	}

	// Pull in data from database
	$dates = $dbConnection->query('select showdate from v_ww_showdates');
	if (PEAR::isError($dates)) {
		die($dates->getMessage());
	}

	// Populate show dates
	while ($dates->fetchInto($row, DB_FETCHMODE_ORDERED)) {
		array_push($showDates, $row[0]);
	}

	// Disconnect from database
	$dbConnection->disconnect();
}

function cleanPanelistName($panelistName) {
	// Create array of character replacements
	$replace = array(' ' => '', '\'' => '', '.' => '', ',' => '');
	return strtr($panelistName, $replace);
}

function processShowURL($showDate) {
	$dtShowDate = new DateTime($showDate);
	$wwNewFormatDate = new DateTime('2006-01-07');
	
	$currentURLTemplate = 'https://www.npr.org/programs/wait-wait-dont-tell-me/archive?date=';
	$legacyURLTemplate = 'https://legacy.npr.org/programs/waitwait/archrndwn';
	
	if ($dtShowDate >= $wwNewFormatDate) {
		return $currentURLTemplate . $dtShowDate->format('m-d-Y');
	} else {
		$showDateFmt = $dtShowDate->format('ymd');
		$showYearFmt = $dtShowDate->format('Y');
		$monthName = strtolower($dtShowDate->format('M'));
		return $legacyURLTemplate . $showYearFmt . '/' . $monthName . '/' . $showDateFmt . '.waitwait.html';
	}
}

function processShowRedirURL($showDate) {
	$dtShowDate = new DateTime($showDate);
	$showDateFormat = $dtShowDate->format('Ymd');
	$url = "/s/$showDateFormat";
	return $url;
}

function generateShowInfoBlock($showDate, $showRating, $showFullDescription, $showHost, $showSK) {
	//$showDateURL = processShowURL($showDate);
	$showDateURL = processShowRedirURL($showDate);
	$showDateID = str_replace('-', '', $showDate);
	
	$infoBlock  = "<tr>\n";
	if (empty($showFullDescription)) {
		$infoBlock .= '<td>';
	} else {
		$infoBlock .= '<td rowspan="2">';
	}
	
	$infoBlock .= "<span class=\"sd\" id=\"s$showDateID\">";
	$infoBlock .= "<a href=\"$showDateURL\" class=\"popup\">$showDate</a>&nbsp;";
	$infoBlock .= '<img src="img/popup.png" height="9" width="9" alt="Opens in a new tab or window" />';
	$infoBlock .= "</span>";
	
	if (!is_null($showRating)) {
		$infoBlock .= "\n<br /><br /><span class=\"sr\">Rating:</span> $showRating\n";
	}
	
	$infoBlock .= "</td>\n";
	
	if (!empty($showFullDescription)) {
		$infoBlock .= '<td colspan="4">' . $showFullDescription . "</td>\n";
		$infoBlock .= "</tr>\n";
		$infoBlock .= "<tr>\n";
	}
	
	$infoBlock .= "<td>$showHost</td>";
	$infoBlock .= "<td>$showSK</td>";
	$infoBlock .= '<td>' . parsePanelists($showDate) . "</td>";
	$infoBlock .= '<td>' . parseGuests($showDate) . "</td>\n";
	$infoBlock .= "</tr>\n";
	return $infoBlock;
}
