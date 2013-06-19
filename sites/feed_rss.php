<?php
  if(!defined("INCLUDE")) die();

  header("Content-type: application/rss+xml; charset: utf-8\n\n");
  $blog = parse_rss_feed($config['blogurl'] . "feed/");
  $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

  echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
  echo "<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">\n";
  echo "  <channel>\n";
  echo "    <title>" . $config['maintitle'] . "</title>\n";
  echo "    <link>" . $protocol . "" . $_SERVER['HTTP_HOST'] . "</link>\n";
  echo "    <atom:link href=\"" . htmlentities($protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) . "\" rel=\"self\" type=\"application/rss+xml\" />\n";
  echo "    <description><![CDATA[" . $config['maintitle'] . "]]></description>\n";
  echo "    <language>de-de</language>\n";

  foreach($blog as $key => $post) {
    echo "<item>\n";
    echo "<title><![CDATA[" . $post['title'] . "]]></title>\n";

    echo "<link>" . htmlentities($protocol . $_SERVER['HTTP_HOST'] . "/#" . md5($key . $post['title']), ENT_QUOTES, 'UTF-8') . "</link>\n";
    echo "<guid isPermaLink=\"true\">" . htmlentities($protocol . $_SERVER['HTTP_HOST'] . "/#" . md5($key . $post['title']), ENT_QUOTES, 'UTF-8') . "</guid>\n";
    echo "<description><![CDATA[" . strip_tags(preg_replace("#https?://mortzu.de/blog/wp-content/uploads/#", $config['absolute_path'] . "secureimage.php?url=" . $config['blogurl'] . "wp-content/uploads/", $post['description']), $config['striptags_feed']) . "]]></description>\n";
    echo "<pubDate>" . date("r", $key) . "</pubDate>\n";
    echo "</item>\n";
  }

  echo "</channel>\n";
  echo "</rss>\n";
?>
