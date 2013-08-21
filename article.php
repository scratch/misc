<?php
/* article.csv contents: article_id, title, summary (maps to post_excerpts?), category(ID?),
           content, members_only, authorised, last_updated_on, files_present(bool)

    article_cmt.csv: comment_id, article_id, date, author_id, author_name, 
	             email, comment, authorized, IP
 */
 
include_once ("tt_lib.php");

/* Check if article has files attached to it */
function HasFilesUploaded($fd, $id)  {
    $qry = "SELECT a.articles_uploaded_files_name 
            FROM articles_uploaded_files a 
            WHERE a.articles_id=$id";

    $qry_result = mysql_query($qry, $fd);
    if ($qry_result && mysql_fetch_array ($qry_result, MYSQL_ASSOC))  {
        return 1;
    }

    return 0;
}



/* Fetch comments associated with article, if any  */
function ArticleComments($fd, $id)  {
	$qry = 
        "SELECT c.article_comments_id, $id, 
            c.article_comments_date, c.article_comments_author_id,
            c.article_comments_author_name,
            c.article_comments_author_email,
            c.article_comments_comment,
            c.article_comments_authorized,
            c.article_comments_ip
        FROM
            article_comments c
        WHERE
            c.article_comments_article_id=$id";

    return mysql_query($qry, $fd);
}



/* Open the articles CSV for writing */
$fd_artcsv = fopen('article.csv', 'w');
$fd_artcmtcsv = fopen('article_cmt.csv', 'w');

if (!$fd_artcsv  ||  !$fd_artcmtcsv) {
    print("Error opening file\n");
    die();
}

// Write the CSV header.
fputcsv($fd_artcsv,split(',',"articles_id,articles_title,articles_summary,articles_category,articles_content,articles_members_only,articles_authorised,articles_last_updated_on,files_present"));
fputcsv($fd_artcmtcsv,split(',', "comment_id,article_id,date,author_id,author_name,email,comment,authorized,IP"));

$fd = tt_connect('localhost', 'sc', 'calvin', 'tibettimes_old');
$article_entry_qry = 
    "SELECT a.articles_id, a.articles_title, 
            a.articles_summary, a.articles_category, a.articles_content, 
            a.articles_members_only, a.articles_authorised, 
            a.articles_last_updated_on 
    FROM articles a";
       
$artqry_result = mysql_query($article_entry_qry, $fd);
if (!$artqry_result) {
    printf("QUERY FAILED: %s", mysql_error());
}

/* DEBUG
$article = mysql_fetch_array($qry_result, MYSQL_ASSOC);
var_dump($article);
*/
 
$art_entry = array(); $cnt = 0;
while (($art_entry = mysql_fetch_array($artqry_result, MYSQL_ASSOC))) {
    $cnt++;
    $file_uploaded_flg = HasFilesUploaded($fd, $art_entry['articles_id']);
    fputcsv($fd_artcsv,
         array(
            $art_entry['articles_id'], 
            $art_entry['articles_title'], 
            $art_entry['articles_summary'],
            $art_entry['articles_category'],
            $art_entry['articles_content'],
            $art_entry['articles_members_only'],
            $art_entry['articles_authorised'], 
            $art_entry['articles_last_updated_on'],
            $file_uploaded_flg
         ));

    $cmtqry_result = ArticleComments($fd, $art_entry['articles_id']);
    if (!$cmtqry_result)
        continue;

    while (($cmt = mysql_fetch_array ($cmtqry_result, MYSQL_ASSOC)) )  {
        fputcsv($fd_artcmtcsv,
          array(
            $cmt['article_comments_id'], 
            $art_entry['articles_id'],
            $cmt['article_comments_date'], 
            $cmt['article_comments_author_id'],
            $cmt['article_comments_author_name'],
            $cmt['article_comments_author_email'],
            $cmt['article_comments_comment'],
            $cmt['article_comments_authorized'],
            $cmt['article_comments_ip']));
    }
}

printf ("%d articles printed\n", $cnt);
?>
