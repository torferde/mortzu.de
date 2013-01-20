<?php
  if(!defined("INCLUDE")) die();

  function parse_rss_feed($url) {
    global $config;

    $entry = array();

    $feed = new SimplePie();
    $feed->set_feed_url($url);
    $feed->enable_cache(true);
    $feed->init();

    $items = $feed->get_items();

    foreach ($items as $item) {
      $encoded_tmp = $item->get_item_tags(SIMPLEPIE_NAMESPACE_RSS_10_MODULES_CONTENT, 'encoded');
      $encoded = $encoded_tmp[0]["data"];

      if(isset($encoded) && $encoded != "")
        $content = $encoded;
      else
        $content = $item->get_content();

      $content = strip_tags($content, $config["striptags"]);
      $timestamp = strtotime($item->get_date());
      $title = $item->get_title();

      if($author = $item->get_author())
        $author = $author->get_name();
      else
        $author = "";

      $link = $item->get_link();
      $bloglink = array($feed->get_title(), $feed->get_link());

      if($title == "")
        $title = substr(md5($link), 0, 9);

      $entry[$timestamp] = array("title" => $title, "link" => $link, "description" => $content, "author" => $author, "bloglink" => $bloglink);
    }

    return $entry;
  }
?>
