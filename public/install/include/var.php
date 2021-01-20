<?php
$env_items = array();
$dirfile_items = array(
		array('type' => 'dir', 'path' => 'storage'),
		array('type' => 'dir', 'path' => 'config'),
);

$func_items = array(
        array('name' => 'putenv'),
		array('name' => 'fsockopen'),
		array('name' => 'gethostbyname'),
		array('name' => 'file_get_contents'),
		array('name' => 'mb_convert_encoding'),
		array('name' => 'json_encode'),
		array('name' => 'curl_init'),
);
