<?php
  if(!defined("INCLUDE")) die();

  header("Content-type: application/atom+xml");
  $blog = parse_rss_feed("http://systemfehler.org/feed/");
  $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

  echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
  echo "<feed xmlns=\"http://www.w3.org/2005/Atom\" xmlns:dc=\"http://purl.org/dc/elements/1.1/\">\n";
  echo "    <link rel=\"self\" href=\"" . htmlentities($protocol . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]) . "\" />\n";
  echo "    <link rel=\"alternate\" type=\"text/html\" href=\"" . htmlentities($protocol . $_SERVER["HTTP_HOST"]) . "\" />\n";
  echo "    <id>http://" . $_SERVER["HTTP_HOST"] . "/</id>\n";
  echo "    <title>" . $config["maintitle"] . "</title>\n";
  echo "    <updated>" . date("Y-m-d\TH:i:s\Z") . "</updated>\n";

  $i = 0;

  foreach($blog as $key => $post) {
    $i++;

    echo "    <entry>\n";
    echo "      <title><![CDATA[" . $post["title"] . "]]></title>\n";
    echo "      <updated>" . date("Y-m-d\TH:i:s\Z", $key) . "</updated>\n";
    echo "      <link rel=\"alternate\" type=\"text/html\" href=\"" . htmlentities($protocol . $_SERVER["HTTP_HOST"] . "/#" . md5($key . $post['title']), ENT_QUOTES, 'UTF-8') . "\" />\n";
    echo "      <id>" . htmlentities($protocol . $_SERVER["HTTP_HOST"] . "/#" . md5($key . $post['title']), ENT_QUOTES, 'UTF-8') . "</id>\n";

    echo "      <content type=\"html\"><![CDATA[" . strip_tags($post["description"], $config["striptags_feed"]) . "]]></content>\n";

    if($post["author"] != "")
      echo "<author><name>" . $post["author"] . "</name></author>\n";
    else
      echo "<author><name>" . htmlentities($post["bloglink"][0], ENT_QUOTES, 'UTF-8') . "</name></author>\n";

    echo "    </entry>\n";
  }

  echo "</feed>\n";
?>
