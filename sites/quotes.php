<?php
  if(!defined("INCLUDE")) die();
?>

<table border="1" id="quotes">
<tr><th>#</th><th>Datum/Zeit</th><th>Quote</th></tr>

<?php
  $content = json_decode(file_get_contents('https://dronf.net/quotes.php'), true);
  $i = 0;

  foreach($content as $row) {
    if(empty($row['content'])) continue;

    $i++;
    echo "<tr>\n";
    echo "<td>" . $i . "</td><td>" . $row['date'] . "</td><td style=\"width: 600px;\">" . $row['content'] . "</td>\n";
    echo "</tr>\n";
  }
?>

</table><br />
