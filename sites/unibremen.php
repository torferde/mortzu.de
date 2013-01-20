<?php
  if(!defined("INCLUDE")) die();

  define('MARKDOWN_PARSER_CLASS', 'MarkdownExtra_Parser');
  define('MARKDOWN_TAB_WIDTH', 5);
  include 'markdown/markdown.php';

  $text = file_get_contents("unibremen.md");

  $content = Markdown($text);
?>

<div id="content">
<?php echo $content; ?>
</div>
