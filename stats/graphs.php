<?php
# Copyright (c) 2007-2020 Linh Pham
# wwdt.me_v2 is relased under the terms of the Apache License 2.0

// Set up path constants
define('GRAPH_OUTPUT_PATH', dirname(__file__) . '/graphs/');
define('GRAPH_FONT_PATH', dirname(__file__) . '/_includes/LiberationMono-Regular.ttf');
define('GRAPH_MAX_WIDTH', 800);
define('GRAPH_MIN_WIDTH', 640);
define('GRAPH_HEIGHT', 300);
define('GRAPH_BACKGROUND_COLOR', 'white');
define('GRAPH_BAR_COLOR', '#336699@0.625');
define('GRAPH_LINE_COLOR', 'black');

// Include graph building file
require_once '_includes/graphs.php';

// Call graphing function
retrievePanelistScoreBreakdown();

// Print out a valid empty HTML 3.2 document if not executed from CLI
if (!(isCli())) { //'cli' !== php_sapi_name()) {
?>
<html><head><title>Graphs Generated...</title></head><body>Done.</body></html>
<?php
}
?>
