<?php
/* member.php -- Get all users from members table */

include_once("tt_lib.php");

$fd = tt_connect();

/*printf ("Print out all the fields of members array:  \n"); 
tt_print_fields("members");
*/

$qry_result = tt_select_fields($fd, "members", array("members_name", "members_email", "members_username", "members_status","members_last_activity_date", "members_registration_date"));

printf ("Member name, email, registration date, last activity date, status\n");
while (($members = mysql_fetch_array($qry_result, MYSQL_ASSOC))) {
   printf ("%s, %s, %s, %s, %s\n", $members['members_name'], $members['members_email'], $members['members_registration_date'], 
           $members['members_last_activity_date'], $members['members_status']);
}
print("Done\n");

$retVal = mysql_close($fd);
if (!$retVal) {
	die("Error closing mysql connection");
}
?>

