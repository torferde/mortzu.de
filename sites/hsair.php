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

  $hsair_cache_file = __DIR__ . "/../cache/" . $hsair_cache_file;

  $hsairjson = get_mensa_json($hsair_cache_file);

  if ($hsairjson === false)
    $hsairjson = refresh_mensa($hsair_url, $hsair_match, $hsair_cache_file);

  if($hsairjson == "") $hsairjson = get_mensa_json($hsair_cache_file, false);

  $hsair = json_decode($hsairjson, true);

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
    foreach($hsair["datum"]['v'] as $key => $value) {
      if($key == 0) continue;

      echo "<h3>" . $value . "</h3>\n";
      echo "<h4>Essen 1</h4>\n";
      echo "<p>" . $hsair["essen1"]['v'][$key] . " (" . $hsair["essen1"]['p'][$key] . ")</p>\n";

      echo "<h4>Essen 2</h4>\n";
      echo "<p>" . $hsair["essen2"]['v'][$key] . " (" . $hsair["essen2"]['p'][$key] . ")</p>\n";

      echo "<hr />\n";
    }
  } else {
    if(date("N") == 5 && date("H") >= "14")
      echo "<p>it's friday, after 14:00; no hsair data available</p>\n";
    elseif($weekday == 6 || date("N") == 6)
      echo "<p>it's saturday; no hsair data available</p>\n";
    elseif($weekday == 7 || date("N") == 7)
      echo "<p>it's sunday; no hsair data available</p>\n";
    else {
      $comparator = trim($hsair['essen1']['v'][$weekday]);
      foreach(array('essen1', 'essen2') as $essen) {
        if(trim($hsair[$essen]['v'][$weekday]) != $comparator) {
          $comparator = false;
          break;
        }
      }
      if($comparator)
        echo "<p> it's $comparator; no hsair data available</p>\n";
      else {
        echo "<h3>Essen 1</h3>\n";
        echo "<p>" . $hsair["essen1"]['v'][$weekday] . " (" . $hsair["essen1"]['p'][$weekday] . ")</p>\n";

        echo "<h3>Essen 2</h3>\n";
        echo "<p>" . $hsair["essen2"]['v'][$weekday] . " (" . $hsair["essen2"]['p'][$weekday] . ")</p>\n";
      }
    }
  }

  echo "</div>\n";
?>
