<?php
# Copyright (c) 2007-2020 Linh Pham
# wwdt.me_v2 is relased under the terms of the Apache License 2.0
?>

<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.1//EN"
     "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<?php
// Set start page start time
$plTime = microtime(true);

// Set app version
define('APP_VERSION', '2.5.0.1');

// Set if the page should display all records and minimum year
define('DISPLAY_ALL', true);
define('DISPLAY_MIN_YEAR', 2003);

// Require functions include file
require_once '_includes/functions.php';

// Start pre-processing of show, panelist and guest details
retrieveShowDates();
retrieveShowYears();
retrieveShowInfoByYear();
retrieveShowHosts();
retrieveShowScoreKeepers();
retrievePanelistScores();
generatePanelistStats();
retrieveGuestScores();
retrieveShowCount();
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta name="Title" content="Wait Wait... Don't Tell Me!: Show Details and Statistics" />
	<meta name="Description" content="A reference for the Wait Wait... Don't Tell Me! NPR radio program, which lists panelists, guests and scores for each show; as well as, panelist statistics." />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>NPR's Wait Wait... Don't Tell Me!: Show Details and Statistics</title>
	<link rel="stylesheet" href="style.css" type="text/css" />
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
	<script type="text/javascript" src="js/popup.js"></script>
	<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
	<script type="text/javascript">
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', '']);
	_gaq.push(['_setDomainName', '']);
	_gaq.push(['_trackPageview']);

	(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
	</script>
</head>
<body>
<h1 id="top">NPR's Wait Wait... Don't Tell Me!: Show Details and Statistics</h1>
<h2>Introduction</h2>
<p>
<strong>Announcement:</strong> I have launched a <a href="https://blog.wwdt.me/">new blog</a> that will cover the development of the new version of this site and will be used for longer commentaries.
Please visit the new blog at: <a href="https://blog.wwdt.me/">blog.wwdt.me</a>.
</p>
<p>
With a <em>slight</em> obsession over a radio program called, &quot;<a href="http://waitwait.npr.org/">Wait Wait... Don't Tell Me!</a>&quot;
(also known as: WWDTM), I have started collecting various information regarding the radio program and storing the information in a database.
The inspiration for creating my own statistics and page came from another avid <a href="http://www.mrkland.com/zone/WWDTM/index.htm">Wait Wait fan</a>,
which I also used as a basis to help collect information for the initial shows hosted by Dan Coffey.
</p>

<p>
Please note that I am not affiliated or associated, nor do I receive any direct or indirect support from
<a href="http://www.npr.org/">NPR</a>, <a href="http://www.wbez.org/">WBEZ</a>,
or Urgent Haircut Productions.
</p>

<p>
BTW, if you are wondering why <a href="http://www.MoRoccaTheWorldsWorstObstetrician.net/">MoRoccaTheWorldsWorstObstetrician.net</a> redirects to this site?
It is a reference to the <a href="#s20110618">2011-06-18</a> show. The site is also mentioned on the <a href="#s20110827">2011-08-27</a> show as well.
</p>

<h2>Content</h2>
<p>
This page is broken into four sections, one that details each of the program's show statistics and one that details the statistics for each panelist.
</p>

<ul>
	<li><a href="#showstats">Show Statistics</a> (<a href="#sfn">Footnotes</a>)
	<ul>
<?php
for ($i = 0; $i < count($showYears); $i++) {
	$year = $showYears[$i];

	// If DISPLAY_ALL is set and false, skip years that fall under minimum year limit
	if ((defined('DISPLAY_ALL') && DISPLAY_ALL == false) && ((defined('DISPLAY_MIN_YEAR') && $year < DISPLAY_MIN_YEAR))) {
		continue;
	}
?>
		<li><a href="#year<?php print $year; ?>"><?php print $showYears[$i]; ?></a></li>
<?php
}
?>
	</ul>
	</li>
	<li><a href="#pnlstats">Panelist Statistics</a> (<a href="#pfn">Footnotes</a>)</li>
	<li><a href="#hoststats">Host Statistics</a></li>
	<li><a href="#skstats">Scorekeeper Statistics</a></li>
</ul>

<p>
If you have any comments, corrections or questions, please feel free to post them under the <a href="https://blog.wwdt.me/2010/10/plans-for-wait-wait-statistics-site/">Plans for Wait Wait Statistics Site</a> blog post on my website.
</p>

<h2 id="showstats">Show Statistics</h2>
<p>
The following tables contain various details for each of the shows entered into the database. Instead of collecting every minute detail of each show,
show descriptions only contain high-level details and do not include information about the panelist rounds or the Lightning Fill-in-the-Blank round.
The "Are You an NPR Geek?" is also known as the NPR Geek Game.
</p>

<p>
The panelist names are color coded based on how each one finished the &quot;Lightning Fill-in-the-Blank&quot; round. The color scheme used is:
</p>

<ul>
	<li><span class="r1">Dark Red</span>: First place</li>
	<li><span class="r1t">Red</span>: Tied for first place</li>
	<li><span class="r2">Dark Green</span>: Second place</li>
	<li><span class="r2t">Green</span>: Tied for second place</li>
	<li><span class="r3">Default</span>: Third place or Not Applicable (for &quot;Best of&quot; shows)</li>
</ul>

<p>
Number of shows in database: <?php print $showCount; ?>
</p>

<?php
for ($j = 0; $j < count($showYears); $j++) {
	$year = $showYears[$j];

	// If DISPLAY_ALL is set and false, skip years that fall under minimum year limit
	if ((defined('DISPLAY_ALL') && DISPLAY_ALL == false) && ((defined('DISPLAY_MIN_YEAR') && $year < DISPLAY_MIN_YEAR))) {
		continue;
	}
?>
<h3 id="year<?php print $year; ?>"><?php print $year; ?></h3>

<table border="1" cellspacing="1" cellpadding="2">
<thead>
	<tr>
		<td style="width: 9em">Show Air Date<br /><span style="font-size: 90%; font-style: italic">(Show Rating)</span></td>
		<td style="width: 12em">Host</td>
		<td style="width: 12em">Scorekeeper</td>
		<td style="width: 15em">Panelists</td>
		<td>Guest(s)</td>
	</tr>
</thead>
<tbody>

<?php
	for ($i = 0; $i < count($showInfoByYear[$year]); $i++) {
		$showDate = $showInfoByYear[$year][$i]['showdate'];
		$showNotes = (!empty($showInfoByYear[$year][$i]['shownotes']) ? nl2br(htmlentities(trim($showInfoByYear[$year][$i]['shownotes']), ENT_IGNORE, 'utf-8')) : null);
		$showDescription = (!empty($showInfoByYear[$year][$i]['showdescription']) ? htmlentities(trim($showInfoByYear[$year][$i]['showdescription']), ENT_IGNORE, 'utf-8') : null);
		$showRating = (!empty($showInfoByYear[$year][$i]['showrating']) ? htmlentities(trim($showInfoByYear[$year][$i]['showrating']), ENT_IGNORE, 'utf-8') : null);

		// Build full show description
		if (is_null($showNotes) && is_null($showDescription)) {
			$showFullDescription = null;
		} else if (is_null($showNotes) && !(is_null($showDescription))) {
			$showFullDescription = nl2br($showDescription);
		} else {
			$showFullDescription = '<span class="sn">' . $showNotes . '</span><hr class="thin" />' . nl2br($showDescription);
		}

		// Add style to host or scorekeeper if not Peter Sagal or Carl Kasell
		$tsh = trim($showHosts[$showDate]);
		$tsk = trim($showScorekeepers[$showDate]);

		if ($tsh == '(TBD)') {
			$showHost = '<span class="tbdHost">' . $tsh . '</span>';
		} else if ($tsh != 'Peter Sagal') {
			$showHost = '<span class="subHost">' . $tsh . '</span>';
		} else {
			$showHost = $tsh;
		}

		if ($tsk == '(TBD)') {
			$showSK = '<span class="tbdSK">' . $tsk . '</span>';
		} else if ($tsk != 'Carl Kasell') {
			$showSK = '<span class="subSK">' . $tsk . '</span>';
		} else {
			$showSK = $tsk;
		}

		// Build out row(s) based on show information and metadata
		print generateShowInfoBlock($showDate, $showRating, $showFullDescription, $showHost, $showSK);
	}
?>
</tbody>
</table>

<p class="linkTop">
<a href="#top">[Go to Top]</a>
</p>
<?php
}
?>
<h3 id="sfn">Show Statistics Footnotes</h3>
<ol class="footnotes">
	<li id="fn1"><a href="#ofn1">^</a>&nbsp;The host, Peter Sagal, gave an exception for Maj. Robert Bateman as he was fighting in Iraq.</li>
	<li id="fn2"><a href="#ofn2">^</a>&nbsp;There was an <em>appeal</em> filed for Patrick Fitzgerald and therefore he won the Not My Job game on a
	technicality after only answering one question correct.</li>
</ol>

<h2 id="pnlstats">Panelist Statistics</h2>
<p>Panelist statistics displayed for data entered for shows available from 2004-01-01 through <?php print $showDates[count($showDates) - 1]; ?>.</p>
<p>The panelist appearances are based on the number of shows that a panelist has appeared in and participated in the "Lightning Fill In The Blank" round, but
excluding "Best Of" shows and repeats.</p>

<p>The panelist placement methodology used is the <a href="http://en.wikipedia.org/wiki/Ranking">Standard competition ranking</a>;
in which, for a given show, if two panelists are tied for first then the other panelist is designated as being third.</p>
<?php
if (!isCli() && preg_match('/(192\.168\.1.*|127\.0\.0.*|72\.1\.133\.2)/', $_SERVER['REMOTE_ADDR'])) {
?>
<p><a href="graphs.php" rel="nofollow">Update Graphs</a></p>
<?php
}
?>

<table border="1" cellspacing="1" cellpadding="2" width="95%">
<thead>
	<tr>
		<td>Panelist</td>
		<td>Appearance Info<sup class="fn"><a href="#pfn1" id="opfn1">1</a></sup></td>
		<td>Placements</td>
		<td>Average Score</td>
		<td>Lowest Score</td>
		<td class="pnlLastCell">Highest Score</td>
	</tr>
</thead>
<tbody>
<?php
foreach (array_keys($panelistStats) as $panelist) {
	$panelistFilename = cleanPanelistName($panelist);
	$graphFileName = 'graphs/' . $panelistFilename . '.png';

	if ($panelist == 'Luke Burbank') {
		$cPanelist = '<span title="Luuuuuuke">' . htmlentities($panelist, ENT_IGNORE, 'utf-8') . '</span>';
	} else {
		$cPanelist = htmlentities($panelist, ENT_IGNORE, 'utf-8');
	}

	$panelistFirstApp = htmlentities($panelistStats[$panelist]['firstapp'], ENT_IGNORE, 'utf-8');
	$panelistFirstAppUID = str_replace('-', '', $panelistFirstApp);
	$panelistLatestApp = htmlentities($panelistStats[$panelist]['latestapp'], ENT_IGNORE, 'utf-8');
	$panelistLatestAppUID = str_replace('-', '', $panelistLatestApp);
?>
	<tr>
		<td rowspan="2"><div id="pnl<?php print $panelistFilename; ?>"><?php print $cPanelist; ?></div></td>
		<td>Appearances<sup class="fn"><a href="#pfn1">1</a></sup>: <?php print htmlentities($panelistStats[$panelist]['appearances'], ENT_IGNORE, 'utf-8'); ?><br />
		First Appearance: <a href="#s<?php print $panelistFirstAppUID; ?>"><?php print $panelistFirstApp; ?></a><br />
		Latest Appearance: <a href="#s<?php print $panelistLatestAppUID; ?>"><?php print $panelistLatestApp; ?></a><br />
		</td>
		<td><?php print parsePanelistPlacement($panelist); ?></td>
		<td><?php print htmlentities($panelistStats[$panelist]['average'], ENT_IGNORE, 'utf-8'); ?></td>
		<td><?php print htmlentities($panelistStats[$panelist]['minScore'], ENT_IGNORE, 'utf-8'); ?></td>
		<td class="pnlLastCell"><?php print htmlentities($panelistStats[$panelist]['maxScore'], ENT_IGNORE, 'utf-8'); ?></td>
	</tr>
	<tr>
		<td colspan="6">
			<strong>Scoring Breakdown</strong><br />
			<img src="<?php print $graphFileName; ?>" alt="Scoring Breakdown Graph for <?php print $panelist; ?>" title="Scoring Breakdown Graph for <?php print $panelist; ?>" style="border: 0" />
		</td>
	</tr>
<?php
}
?>

</tbody>
</table>

<h3 id="pfn">Panelist Statistics Footnotes</h3>
<ol class="footnotes">
	<li id="pfn1"><a href="#opfn1">^</a>&nbsp;The panelist appearances are based on the number of shows that a panelist has appeared in and participated
	in the &quot;Lightning Fill In The Blank&quot; round, but excluding &quot;Best Of&quot; shows and repeats. Also, note that not all of the show data include panelist
	data and, therefore, the First/Latest Appearance dates may not be 100% accurate.</li>
</ol>

<h2 id="hoststats">Host Statistics</h2>
<p><em>Work in Progress!</em></p>

<h2 id="skstats">Scorekeeper Statistics</h2>
<p><em>Work in Progress!</em></p>

<hr />

<div class="footer">
<p>
Copyright &copy; 2007&#8211;2020 <a href="https://linhpham.org/">Linh Pham</a>.
All Rights Reserved.<br />
</p>

<p>
All of the information presented on this page was collected from various public resources, including the <a href="http://waitwait.npr.org/">program's website</a>,
information written down from the program's broadcasts, podcasts and archival recordings. Information regarding future programs were obtained from information
posted about upcoming live tapings, as provided by <a href="https://www.wbez.org/events/wait-wait-dont-tell-me-tickets">WBEZ</a>.
</p>

<p>
Valid <a href="http://validator.w3.org/check?uri=referer">XHTML 1.1</a> and <a href="http://jigsaw.w3.org/css-validator/check/referer">CSS 2.1</a>.
</p>

<p>
Version: <?php print APP_VERSION ?><br />
Page generated on: <?php print date('l, d M, Y H:i:s T') ?><br />
Page generated in: <?php print round((microtime(true) - $plTime), 5) ?> seconds
</p>
</div>

</body>
</html>
