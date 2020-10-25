<?php
# Copyright (c) 2007-2020 Linh Pham
# wwdt.me_v2 is relased under the terms of the Apache License 2.0

/**
 * This file contains information used to connect to a database server
 */

// Require PEAR::DB
require_once 'DB.php';

// Database login, server and connection settings
$dbType = 'mysql';
$dbUsername = '';
$dbPassword = '';
$dbServer = '';
$dbName = '';
$dbOptions = array('portability' => DB_PORTABILITY_ALL);

// Build Database URI string
$dbUri = $dbType . '://' . $dbUsername . ':' . $dbPassword . '@' .
	 $dbServer . '/' . $dbName;
