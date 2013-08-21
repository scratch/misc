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
 * $table: The table to use.
 * $fields_arr:  array consisting of all the fields to be accessed in the table.
 * 
 * Return: query result object.
 */
function tt_select_fields ($db_fd, $table, $fields_arr)  {
	$fields = implode(",", $fields_arr);
	$qry = sprintf ("SELECT  %s FROM %s", $fields , $table);
	$qry_result = mysql_query($qry, $db_fd);
	if (!$qry_result) {
		printf("QUERY FAILED: %s", mysql_error());
	}
	
	return $qry_result;
}


/* connect to DB. Bug: shouldn't die() from a lib
 *
 */
function tt_connect($host, $user, $pwd, $db) {
	$fd = mysql_connect($host, $user, $pwd);
	if (!$fd) {
		die("Error opening connection" . mysql_error() . "\n");
	}

	$retVal = mysql_select_db($db, $fd); // Rigel: tibetimes;
	if (!$retVal) {
		die("DB select failed: " . mysql_error() . "\n");
		return $retVal;
	}

	return $fd;
}
?>
