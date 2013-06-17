<?php
include_once ("tt_lib.php");
$fd = tt_connect();
// tt_print_fields("blogs");

$qry_result = tt_select_fields($fd, "blogs", array("blogs_title", "blogs_description"));

/* Get data about blogs from blogs and blog_entries table. From members, get the bloggers name (based on ID) */
$blog_entry_qry =
    "SELECT m.members_name, b.blogs_title, b.blogs_description,  be.blog_entries_title, be.blog_entries_content, be.blog_entries_last_updated_on"
  . " FROM blogs b"
  . " INNER JOIN members m ON m.members_id=b.blogs_author_id "
  . " INNER JOIN blog_entries be  ON m.members_id=be.blog_entries_id ";
  
  
$qry_result = mysql_query($blog_entry_qry, $fd);
if (!$qry_result) {
		printf("QUERY FAILED: %s", mysql_error());
}

$blog_entry = array();
while (($blog_entry = mysql_fetch_array($qry_result, MYSQL_ASSOC))) {
	if (!$blog_entry) {
		die("Error fetching array from mysql object");
	}

	printf ("Author Name: %s\nTitle:  %s\nDate:  %s\n\nContent:\n%s\n\nXXX---------\n", $blog_entry['members_name'], 
		    $blog_entry['blog_entries_title'], $blog_entry['blog_entries_last_updated_on'], $blog_entry['blog_entries_content']);
}
	
print("Done\n");
?>