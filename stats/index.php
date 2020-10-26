<?php
# Copyright (c) 2007-2020 Linh Pham
# wwdt.me_v2 is relased under the terms of the Apache License 2.0

define('STATIC_FILE', 'static.html');
define('DYNAMIC_FILE', 'current.php');

if (file_exists(STATIC_FILE) && file_exists(DYNAMIC_FILE)) {
	if (filemtime(STATIC_FILE) >= filemtime(DYNAMIC_FILE)) {
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime(STATIC_FILE)));
		require_once(STATIC_FILE);
	} else {
		require_once(DYNAMIC_FILE);
	}
} else {
	print 'Error: unable to load Wait Wait... Don\'t Tell Me! Statistics Page';
}
