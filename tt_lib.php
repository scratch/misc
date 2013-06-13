<?php

/* (Pretty) print contents of table
 *
 */
function tt_print_fields($table_name) {
	$flds_qry = mysql_query("DESCRIBE $table_name");
	if (!$flds_qry) {
		printf("mysql_listfields() ERROR: %s", mysql_error());
	}
	while (($flds = mysql_fetch_array($flds_qry))) {
		printf("%s\n", $flds['Field']);
	}
}


/* Return array with the field entries
 *
 */
 
function tt_select_fields ($table, $fields_arr)  {
	
}


/* connect to DB. Bug: shouldn't die() from a lib
 *
 */
function tt_connect() {
	$fd = mysql_connect("192.168.1.4", "tibetimes", "tibetimes");
	if (!$fd) {
		die("Error opening connection\n");
	}

	$retVal = mysql_select_db("tibetimes", $fd);
	if (!$retVal) {
		die("DB select failed: " . mysql_error());
	}

	return $fd;
}
?>