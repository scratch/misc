<?php
include_once ("tt_lib.php");

/* Query for comments for a specific blog */
function blog_comments($blog_id, $fd) {
	$blog_cmt_qry = "SELECT m.members_name, m.members_email, bec.blog_entries_comments_blog_entries_id, bec.blog_entries_comments_date, bec.blog_entries_comments_comment " . " FROM members m, blog_entries_comments bec " . " WHERE bec.blog_entries_comments_blog_entries_id=$blog_id " . " AND m.members_id = bec.blog_entries_comments_author_id";

	$qry_result = mysql_query($blog_cmt_qry, $fd);
	if (!$qry_result) {
		printf("Comment QUERY FAILED: %s", mysql_error());
	}

	return $qry_result;
}

$fd = tt_connect();
// tt_print_fields("blogs");
$qry_result = tt_select_fields($fd, "blogs", array("blogs_title", "blogs_description"));

/* Get data about blogs from blogs and blog_entries table. From members, get the bloggers name (based on ID) */
$blog_entry_qry = "SELECT m.members_name, m.members_email, b.blogs_title, b.blogs_description, be.blog_entries_id, be.blog_entries_blogs_id, be.blog_entries_title, be.blog_entries_content, be.blog_entries_last_updated_on " . " FROM blogs b" . " INNER JOIN members m ON m.members_id=b.blogs_author_id " . " INNER JOIN blog_entries be  ON m.members_id=be.blog_entries_id ";
$qry_result = mysql_query($blog_entry_qry, $fd);
if (!$qry_result) {
	printf("QUERY FAILED: %s", mysql_error());
}

$fd_blogcsv = fopen('blogs.csv', 'w');
$fd_blogcmtscsv = fopen('blogs_cmts.csv', 'w');
if (!$fd_blogcsv || !$fd_blogcmtscsv) {
	print("Error opening file\n");
	die();
}

fputcsv($fd_blogcsv, split(',', "blog_id,author,email,title,date,content"));
fputcsv($fd_blogcmtscsv, split(',', "blog_id,comment_author,comment_email, comment_date,comment_content"));

$blog_entry = array();

while (($blog_entry = mysql_fetch_array($qry_result, MYSQL_ASSOC))) {
	$blog_entry = mysql_fetch_array($qry_result, MYSQL_ASSOC);
	if (!$blog_entry) {
		die("Error fetching array from mysql object");
	}

	fputcsv($fd_blogcsv,
 array($blog_entry['blog_entries_id'],
 $blog_entry['members_name'],
 $blog_entry['members_email'],
 $blog_entry['blog_entries_title'],
 $blog_entry['blog_entries_last_updated_on'],
 $blog_entry['blog_entries_content']));
	printf("Author Name: %s\nTitle:  %s\nDate: %s\nblog id: %s\nblog entries id: %s\n\nContent:\n%s\n\nXXX---------\n", 
	$blog_entry['members_name'], $blog_entry['blog_entries_title'], $blog_entry['blog_entries_last_updated_on'], 
	$blog_entry['blog_entries_id'], $blog_entry['blog_entries_blogs_id'], $blog_entry['blog_entries_content']);
	/* Display comments for this blog */
	$cmt_result = blog_comments($blog_entry['blog_entries_id'], $fd);
	if (!$cmt_result)
		continue;

	while (($cmt_entry = mysql_fetch_array($cmt_result, MYSQL_ASSOC)))  {
		printf("\nComment Author: %s\nComment Date: %s\nComment: %s\n XX--comment-end--XX \n\n", $cmt_entry['members_name'], $cmt_entry['blog_entries_comments_date'], $cmt_entry['blog_entries_comments_comment']);
		fputcsv($fd_blogcmtscsv,
 array($cmt_entry['blog_entries_comments_blog_entries_id'],
$cmt_entry['members_name'],
$cmt_entry['members_email'],
 $cmt_entry['blog_entries_comments_date'],
 $cmt_entry['blog_entries_comments_comment']));
	}
}
?>
