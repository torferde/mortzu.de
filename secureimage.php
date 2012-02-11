<?php
  $files = array("http://status.freamware.net/irc/helios/06.png");

  if(isset($files[$_SERVER['QUERY_STRING']], $files)) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $files[$_SERVER['QUERY_STRING']]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);

    header("Content-type: " . curl_getinfo($ch, CURLINFO_CONTENT_TYPE));
    echo $data;

    curl_close($ch);
  }
?>
