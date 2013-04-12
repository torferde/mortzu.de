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

  $mensa_cache_file = __DIR__ . "/../cache/" . $mensa_cache_file;

  $mensajson = get_mensa_json($mensa_cache_file);

  if ($mensajson === false)
    $mensajson = refresh_mensa($mensa_url, $mensa_match, $mensa_cache_file);

  if($mensajson == "") $mensajson = get_mensa_json($mensa_cache_file, false);

  $mensa = json_decode($mensajson, true);

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
    foreach($mensa['datum']['v'] as $key => $value) {
      if($key == 0) continue;

      echo "<h3>" . $value . "</h3>\n";
      echo "<h4>Essen 1</h4>\n";
      echo "<p>" . $mensa['essen1']['v'][$key] . " (" . $mensa['essen1']['p'][$key] . ")</p>\n";

      echo "<h4>Essen 2</h4>\n";
      echo "<p>" . $mensa['essen2']['v'][$key] . " (" . $mensa['essen2']['p'][$key] . ")</p>\n";

      echo "<h4>Wok u. Pfanne</h4>\n";
      echo "<p>" . $mensa['wok']['v'][$key] . " (" . $mensa['wok']['p'][$key] . ")</p>\n";

      echo "<h4>Vegetarisch</h4>\n";
      echo "<p>" . $mensa['vegetarisch']['v'][$key] . " (" . $mensa['vegetarisch']['p'][$key] . ")</p>\n";

      echo "<h4>Beilagen</h4>\n";
      echo "<ul>\n";
      $beilagen = explode(" |,| ", $mensa['beilagen']['v'][$key]);
      foreach($beilagen as $beilage)
        echo "<li>" . $beilage . "</li>\n";
      echo "</ul>\n";

      echo "<h4>Aufl&auml;ufe</h4>\n";
      echo "<ul>\n";

      $auflaeufe = explode(" |,| ", $mensa['auflaeufe']['v'][$key]);

      foreach($auflaeufe as $auflauf)
        echo "<li>" . $auflauf . "</li>\n";

      echo "</ul>\n";

      echo "<hr />\n";
    }
  } else {
    if(date("N") == 5 && date("H") >= "14")
      echo "<p>it's friday, after 14:00; no mensa data available</p>\n";
    elseif($weekday == 6 || date("N") == 6)
      echo "<p>it's saturday; no mensa data available</p>\n";
    elseif($weekday == 7 || date("N") == 7)
      echo "<p>it's sunday; no mensa data available</p>\n";
    else {
      $comparator = trim($mensa['essen1']['v'][$weekday]);
      foreach(array('essen2', 'wok', 'vegetarisch', 'beilagen') as $essen) {
        if(trim($mensa[$essen]['v'][$weekday]) != $comparator) {
          $comparator = false;
          break;
        }
      }
      if($comparator)
        echo "<p> it's $comparator; no mensa data available</p>\n";
      else {
        echo "<h3>Essen 1</h3>\n";
        echo "<p>" . $mensa['essen1']['v'][$weekday] . " (" . $mensa['essen1']['p'][$weekday] . ")</p>\n";

        echo "<h3>Essen 2</h3>\n";
        echo "<p>" . $mensa['essen2']['v'][$weekday] . " (" . $mensa['essen2']['p'][$weekday] . ")</p>\n";

        echo "<h3>Wok u. Pfanne</h3>\n";
        echo "<p>" . $mensa['wok']['v'][$weekday] . " (" . $mensa['wok']['p'][$weekday] . ")</p>\n";

        echo "<h3>Vegetarisch</h3>\n";
        echo "<p>" . $mensa['vegetarisch']['v'][$weekday] . " (" . $mensa['vegetarisch']['p'][$weekday] . ")</p>\n";

        echo "<h3>Beilagen</h3>\n";
        echo "<ul>\n";
        $beilagen = explode(" |,| ", $mensa['beilagen']['v'][$weekday]);
        foreach($beilagen as $beilage)
          echo "<li>" . $beilage . "</li>\n";
        echo "</ul>\n";

        echo "<h3>Aufl&auml;ufe</h3>\n";
        echo "<ul>\n";

        $auflaeufe = explode(" |,| ", $mensa['auflaeufe']['v'][$weekday]);

        foreach($auflaeufe as $auflauf)
          echo "<li>" . $auflauf . "</li>\n";

        echo "</ul>\n";
      }
    }
  }

  echo "</div>\n";
?>
