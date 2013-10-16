<?php
  if(!defined("INCLUDE")) die();

  if(!file_exists("extlib/mensa/mensa_new.php")) {
    echo "An error occured\n";
    echo "Please contact me@mortzu.de (JID/Mail)\n";
    die();
  }

  ini_set("default_socket_timeout", 2);

  date_default_timezone_set('Europe/Berlin');
  $weekday = date("N");

  include "extlib/mensa/mensa_new.php";
  include "extlib/mensa/mensa_new_config.php";

  $hsmensa_cache_file = __DIR__ . "/../cache/" . $hsmensa_cache_file;

  $hsmensajson = get_mensa_json($hsmensa_cache_file);

  if ($hsmensajson === false)
    $hsmensajson = refresh_mensa($hsmensa_url, $hsmensa_match, $hsmensa_cache_file);

  if($hsmensajson == "") $hsmensajson = get_mensa_json($hsmensa_cache_file, false);

  $hsmensa = json_decode($hsmensajson, true);

  if(isset($_GET['when']))
    $when = $_GET['when'];

  if(isset($when) && $when == "tomorrow")
    $weekday = date("N") + 1;
?>

<p><a href="/gw2">GW2</a> | <a href="/mensa">Uni-Mensa</a> | <a href="/hsmensa">Hochschulmensa</a> | <a href="/hsair">Hochschulmensa Airport</a></p>

<?php
  echo "<p><a href=\"" . htmlentities("?when=week", ENT_QUOTES, "UTF-8") . "\">week</a> | <a href=\"" . htmlentities("?when=tomorrow", ENT_QUOTES, "UTF-8") . "\">tomorrow</a> | <a href=\"" . htmlentities("?", ENT_QUOTES, "UTF-8") . "\">today</a></p><hr />\n";
  echo "<div id=\"mensadata\">\n";

  if(isset($when) && $when == "week") {
    foreach($hsmensa['datum']['v'] as $key => $value) {
      if($key == 0) continue;

      echo "<h3>" . $value . "</h3>\n";
      echo "<h4>Essen 1</h4>\n";
      echo "<p>" . $hsmensa['essen1']['v'][$key] . " (" . $hsmensa['essen1']['p'][$key] . ")</p>\n";

      echo "<h4>Essen 2</h4>\n";
      echo "<p>" . $hsmensa['essen2']['v'][$key] . " (" . $hsmensa['essen2']['p'][$key] . ")</p>\n";

      echo "<h4>Front-Cooking</h4>\n";
      echo "<p>" . $hsmensa['frontcooking']['v'][$key] . " (" . $hsmensa['frontcooking']['p'][$key] . ")</p>\n";

      echo "<h4>Bio-Men&uuml;</h4>\n";
      echo "<p>" . $hsmensa['biomenue']['v'][$key] . " (" . $hsmensa['biomenue']['p'][$key] . ")</p>\n";

      echo "<h4>Beilagen</h4>\n";
      echo "<ul>\n";
      $beilagen = explode(" |,| ", $hsmensa['beilagen']['v'][$key]);
      foreach($beilagen as $beilage)
        echo "<li>" . $beilage . "</li>\n";
      echo "</ul>\n";

      echo "<hr />\n";
    }
  } else {
    if(date("N") == 5 && date("H") >= "14")
      echo "<p>it's friday, after 14:00; no hsmensa data available</p>\n";
    elseif($weekday == 6 || date("N") == 6)
      echo "<p>it's saturday; no hsmensa data available</p>\n";
    elseif($weekday == 7 || date("N") == 7)
      echo "<p>it's sunday; no hsmensa data available</p>\n";
    else {
      $comparator = trim($hsmensa['essen1']['v'][$weekday]);
      foreach(array('essen2', 'frontcooking', 'biomenue', 'beilagen') as $essen) {
        if(trim($hsmensa[$essen]['v'][$weekday]) != $comparator) {
          $comparator = false;
          break;
        }
      }
      if($comparator)
        echo "<p> it's $comparator; no hsmensa data available</p>\n";
      else {
        echo "<h3>Essen 1</h3>\n";
        echo "<p>" . $hsmensa['essen1']['v'][$weekday] . " (" . $hsmensa['essen1']['p'][$weekday] . ")</p>\n";

        echo "<h3>Essen 2</h3>\n";
        echo "<p>" . $hsmensa['essen2']['v'][$weekday] . " (" . $hsmensa['essen2']['p'][$weekday] . ")</p>\n";

        echo "<h3>Front-Cooking</h3>\n";
        echo "<p>" . $hsmensa['frontcooking']['v'][$weekday] . " (" . $hsmensa['frontcooking']['p'][$weekday] . ")</p>\n";

        echo "<h3>Bio-Men&uuml;</h3>\n";
        echo "<p>" . $hsmensa['biomenue']['v'][$weekday] . " (" . $hsmensa['biomenue']['p'][$weekday] . ")</p>\n";

        echo "<h3>Beilagen</h3>\n";
        echo "<ul>\n";
        $beilagen = explode(" |,| ", $hsmensa['beilagen']['v'][$weekday]);
        foreach($beilagen as $beilage)
          echo "<li>" . $beilage . "</li>\n";
        echo "</ul>\n";
      }
    }
  }

  echo "</div>\n";
?>
