<?php
define( 'MARKDOWN_PARSER_CLASS',  'MarkdownExtra_Parser' );
define( 'MARKDOWN_TAB_WIDTH',     5 );
include 'markdown/markdown.php';

$text = file_get_contents("unibremen.md");

$content = Markdown($text);

echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>

    <title>helios - gesammelte Uni-Bremen Informationen</title>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

  </head>

  <body>
    <div id="main">
<?php echo $content; ?>

    </div>
  </body>
</html>
