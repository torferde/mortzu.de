<?php
function br2nl($string) {
  return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
}

echo "<table border=\"1\" id=\"quotes\">\n";
echo "<tr><th>#</th><th>Datum/Zeit</th><th>Quote</th></tr>\n";

$content = file("https://planetcyborg.de/backend/quotes.php");
$i = 0;

foreach($content as $row) {
        $i++;
        preg_match('/ _\|\|_ (.*) _\|\|_ (.*) _\|\|_ (.*) _\|\|_/si', $row, $matches);
        echo "<tr>\n";
        echo "<td>" . $i . "</td><td>" . $matches[2] . "</td><td>" . nl2br(htmlentities(br2nl($matches[3]), ENT_COMPAT, "UTF-8")) . "</td>\n";
        echo "</tr>\n";
}

echo "</table><br />\n";
?>
