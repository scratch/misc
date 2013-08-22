<?php
/* category.csv contents: cat_id, parent_id, name, weight 
 */

include_once("tt_lib.php");

$fd_catcsv = fopen('category.csv', 'w');

if (!$fd_catcsv) {
    print("Error opening file\n");
    die();
}
fputcsv($fd_catcsv, split(',',"category_id,name,parent_id,weight"));

$fd = tt_connect('localhost', 'sc', 'calvin', 'tibettimes_old');
$qry = "SELECT c.article_categories_id, c.article_categories_name,
           c.article_categories_parent_id,
           c.article_categories_weight
        FROM article_categories c";

$result = mysql_query($qry, $fd);
if (!$result)  {
    printf ("Query Failed: " . mysql_error() . "\n");
    die();
}

$cat_entry = array(); $catCnt = 0; 
while (($cat_entry = mysql_fetch_array($result, MYSQL_ASSOC))) {
    $catCnt++;
    fputcsv ($fd_catcsv, 
      array(
        $cat_entry['article_categories_id'], 
        $cat_entry['article_categories_name'],
        $cat_entry['article_categories_parent_id'],
        $cat_entry['article_categories_weight']
      ));
}

printf ("%d categories written\n", $catCnt);
fclose($fd_catcsv);
mysql_close($fd);
?>
