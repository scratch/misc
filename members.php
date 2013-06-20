<?php
/* member.php -- Get all users from members table */

/*
 * Format required by user csv plugin: http://wordpress.org/plugins/import-users-from-csv/
"user_login","user_email","user_pass","first_name","last_name","display_name","role","custom_usermeta_1","custom_usermeta_2"
"johndoe","john.doe@localhost.localdomain",,"John","Doe","John Doe","administrator","Test","Test"
"janedoe","jane.doe@localhost.localdomain.com","test","Jane","Doe","Jane Doe","contributor","0",
*/

include_once("tt_lib.php");

$fd = tt_connect();
/*
printf ("Print out all the fields of members array:  \n"); 
tt_print_fields("members");
*/

$qry_result = tt_select_fields($fd, "members", array("members_name", "members_email", "members_username", "members_status","members_last_activity_date", "members_registration_date"));

print ('"user_login","user_email","user_pass","first_name","last_name","display_name","role","custom_usermeta_1","custom_usermeta_2"'."\n");
while (($members = mysql_fetch_array($qry_result, MYSQL_ASSOC))) {
   printf ("\"%s\",\"%s\",,\"%s\",,\"%s\",,,\n", $members['members_username'], $members['members_email'], $members['members_name'], $members['members_name']);
}

$retVal = mysql_close($fd);
if (!$retVal) {
	printf("Error closing mysql connection: %s\n", mysql_error());
}
?>

