<?php
  if(!defined("INCLUDE")) die();

  echo '<?xml version="1.0" encoding="utf-8"?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>

<?php
if($mode != "mr") {
?>
    <title>helios, chaoticbilly, momo, moe, Moritz Rudert - names are different</title>
<?php
} else {
?>
    <title>Moritz Rudert</title>
<?php
}
?>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" href="<?php echo $config['absolute_path']; ?>style.css" />
    <link rel="alternate" type="application/atom+xml" title="ATOM" href="/feed/atom" />
    <link rel="alternate" type="application/rss+xml" title="RSS" href="/feed/rss" />

  </head>

  <body>
    <div id="main">
      <div id="header">
        <img src="<?php echo $config['absolute_path']; ?>images/logo.png" alt="" />

        <div id="headertwo">
          <img src="<?php echo $config['absolute_path']; ?>images/me.png" alt="" />
        </div>
      </div>

      <div id="content">
