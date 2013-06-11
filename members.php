<?php
/* member.php -- Get all users from members table */

  $fd = mysql_connect ("192.168.1.4", "tibetimes","tibetimes");
  if (!$fd)  {
    die("Error opening connection\n");
  }

  $retVal = mysql_select_db("tibetimes", $fd);
  if (!$retVal)  {
    die("DB select failed: " . mysql_error());
  }
  $qry_result = mysql_query("SELECT members_name,members_email,members_username FROM members", $fd);
  if (!$qry_result)  {
    die("QUERY FAILED: " . mysql_error());
  }

  $file_fd = fopen ("/tmp/mem.csv", "w");
  if (!$file_fd)  {
    die ("Error Opening file\n");
  } 

  $members = array();
  while (($members = mysql_fetch_array($qry_result)))  {
    if (!$members)  {
      die ("Error fetching array from mysql object");
    }

    fputcsv($file_fd, $members);
  }
  print("Member CSV written\n");


  $retVal = fclose($file_fd);
  if (!$retVal)  {
    die("Error closing CSV file.");
  }

  $retVal = mysql_close($fd);
  if (!$retVal)  {
    die("Error closing mysql connection");
  }
?>

