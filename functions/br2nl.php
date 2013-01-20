<?php
  if(!defined("INCLUDE")) die();

  function br2nl($string) {
    return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
  }
?>
