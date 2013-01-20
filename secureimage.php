<?php
  $allowed_extensions = array("jpg", "gif", "png");

  $tmp = pathinfo($_GET['url']);

  if(parse_url($_GET['url'], PHP_URL_HOST) == "systemfehler.org") {
    if(in_array($tmp['extension'], $allowed_extensions)) {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $_GET['url']);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $data = curl_exec($ch);

      header("Content-type: " . curl_getinfo($ch, CURLINFO_CONTENT_TYPE));
      echo $data;

      curl_close($ch);
    } else {
      header('HTTP/1.1 403 Forbidden');
      echo "403 Forbidden";
    }
  } else {
    header('HTTP/1.1 403 Forbidden');
    echo "403 Forbidden";
  }
?>
