<?php
  if(!defined("INCLUDE")) die();

  if(!file_exists("../mensa/mensa_new.php")) {
    echo "An error occured\n";
    echo "Please contact helios@planetcyborg.de (JID/Mail)\n";
    die();
  }

  ini_set("default_socket_timeout", 2);

  date_default_timezone_set('Europe/Berlin');
  $weekday = date("N");

  include "../mensa/mensa_new.php";
  include "../mensa/mensa_new_config.php";

  $gw2_cache_file = __DIR__ . "/../cache/" . $gw2_cache_file;

  $gw2json = get_mensa_json($gw2_cache_file);

  if ($gw2json === false)
    $gw2json = refresh_mensa($gw2_url, $gw2_match, $gw2_cache_file);

  if($gw2json == "") $gw2json = get_mensa_json($gw2_cache_file, false);

  $gw2 = json_decode($gw2json, true);

  if(isset($_GET["when"]))
    $when = $_GET["when"];

  if(isset($when) && $when == "tomorrow")
    $weekday = date("N") + 1;
?>

<p><a href="/gw2">GW2</a> | <a href="/mensa">Uni-Mensa</a> | <a href="/hsmensa">Hochschulmensa</a> | <a href="/hsair">Hochschulmensa Airport</a></p>

<?php
  echo "<p><a href=\"" . htmlentities("?when=week", ENT_QUOTES, "UTF-8") . "\">week</a> | <a href=\"" . htmlentities("?when=tomorrow", ENT_QUOTES, "UTF-8") . "\">tomorrow</a> | <a href=\"" . htmlentities("?", ENT_QUOTES, "UTF-8") . "\">today</a></p><hr />\n";
  echo "<div id=\"mensadata\">\n";

  if(isset($when) && $when == "week") {
    foreach($gw2["datum"]['v'] as $key => $value) {
      if($key == 0) continue;

      echo "<h3>" . $value . "</h3>\n";
      echo "<h4>Pizza</h4>\n";
      echo "<p>" . $gw2["pizza"]['v'][$key] . " (" . $gw2["pizza"]['p'][$key] . ")</p>\n";

      echo "<h4>Pasta</h4>\n";
      echo "<p>" . $gw2["pasta"]['v'][$key] . " (" . $gw2["pasta"]['p'][$key] . ")</p>\n";

      echo "<h4>Front Cooking</h4>\n";
      echo "<p>" . $gw2["frontcooking"]['v'][$key] . " (" . $gw2["frontcooking"]['p'][$key] . ")</p>\n";

      echo "<hr />\n";
    }
  } else {
    if(date("N") == 5 && date("H") >= "14")
      echo "<p>it's friday, after 14:00; no gw2 data available</p>\n";
    elseif($weekday == 6 || date("N") == 6)
      echo "<p>it's saturday; no gw2 data available</p>\n";
    elseif($weekday == 7 || date("N") == 7)
      echo "<p>it's sunday; no gw2 data available</p>\n";
    else {
      $comparator = trim($gw2['pizza']['v'][$weekday]);
      foreach(array('pizza', 'pasta', 'frontcooking') as $essen) {
        if(trim($gw2[$essen]['v'][$weekday]) != $comparator) {
          $comparator = false;
          break;
        }
      }
      if($comparator)
        echo "<p> it's $comparator; no gw2 data available</p>\n";
      else {
        echo "<h3>Pizza</h3>\n";
        echo "<p>" . $gw2["pizza"]['v'][$weekday] . " (" . $gw2["pizza"]['p'][$weekday] . ")</p>\n";

        echo "<h3>Pasta</h3>\n";
        echo "<p>" . $gw2["pasta"]['v'][$weekday] . " (" . $gw2["pasta"]['p'][$weekday] . ")</p>\n";

        echo "<h3>Front Cooking</h3>\n";
        echo "<p>" . $gw2["frontcooking"]['v'][$weekday] . " (" . $gw2["frontcooking"]['p'][$weekday] . ")</p>\n";
      }
    }
  }

  echo "</div>\n";
?>
