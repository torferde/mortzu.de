<?php
  // define constant to prevent
  // call of sites/*.php directly
  define("INCLUDE", time());

  // include functions
  require_once "simplepie/simplepie.inc";
  require_once "functions/parse_feed.php";
  require_once "functions/br2nl.php";

  // redirect to https
  if($_SERVER['HTTPS'] != "on") {
    $redirect = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location: " . $redirect);
  }

  // for fucking personensuchmaschinen redirect away
  if(preg_match("/^.*yasni\.de.*$/", $_SERVER['HTTP_REFERER']) ||
     preg_match("/^.*pipl\.com.*$/", $_SERVER['HTTP_REFERER']) ||
     preg_match("/^.*123people\.de.*$/", $_SERVER['HTTP_REFERER']) ||
     preg_match("/^.*radaris\.de.*$/", $_SERVER['HTTP_REFERER']))
    header("Location: /personensuchmaschine.html");

  // different modes of the page
  if($_SERVER['HTTP_HOST'] == "helios.planetcyborg.de")
    $mode = "helios";
  else
    $mode = "mr";

  // config
  $config['absolute_path'] = "/";
  $config['art_folder_rel'] = $config['absolute_path'] . 'images/';
  $config['art_folder'] = $_SERVER['DOCUMENT_ROOT'] . $config['art_folder_rel'];
  $config['art_default_inc'] = array();
  $config['art_excludes'] = array('.', '..', '.ds_store', '.svn', 'thumbs', '.not_this', '.git');
  $config['striptags_feed'] = "<a><img><p><strong><b><i><em><br><code><ol><ul><li>";
  $config['striptags'] = $config['striptags_feed'] . "<object><embed><param>";
  $config['maintitle'] = "helios, chaoticbilly, momo, moe, Moritz Rudert - names are different";

  // require sites
  if(preg_match('/^\/unibremen/', $_SERVER['REQUEST_URI'])) {
    require_once "sites/header.php";
    require_once "sites/unibremen.php";
    require_once "sites/footer.php";
    die();
  } elseif(preg_match('/^\/quotes/', $_SERVER['REQUEST_URI'])) {
    require_once "sites/header.php";
    require_once "sites/quotes.php";
    require_once "sites/footer.php";
    die();
  } elseif(preg_match('/^\/feed\/atom/', $_SERVER['REQUEST_URI'])) {
    require_once "sites/feed_atom.php";
    die();
  } elseif(preg_match('/^\/feed\/rss/', $_SERVER['REQUEST_URI'])) {
    require_once "sites/feed_rss.php";
    die();
  }

  // filter of the art section
  if(is_dir($config['art_folder'])) {
    if($dh = opendir($config['art_folder'])) {
      while(($file = readdir($dh)) !== false) {
        if(is_dir($config['art_folder'] . $file) && !in_array(strtolower($file), $config['art_excludes']))
          array_push($config['art_default_inc'], $file);
      }

      closedir($dh);
    }
  }

  if(isset($_REQUEST['m']) && $_REQUEST['m'] == 'all')
    $inc = $config['art_default_inc'];
  elseif(isset($_REQUEST['m']) && in_array($_REQUEST['m'], $config['art_default_inc']))
    $inc = array($_REQUEST['m']);
  else {
    $inc = array();
    foreach($config['art_default_inc'] as $value) {
      if(!file_exists($config['art_folder'] . $value . "/.not_this"))
        array_push($inc, $value);
    }
  }

  // calculate semester and age
  $semester = intval(((date("Y") - 2008 - 1) * 2) + (((date("n") + 2) % 12) / 6 + 1));

  $age = (date("Y") - 1988 - 1);
  if((date("n") == 2 && date("j") >= 2) || date("n") > 2) $age++;

  // include the header file
  require_once "sites/header.php";
?>

<h2><a name="wo_lebe_ich">Wo lebe ich?</a></h2>
<ul>
  <li>seit meiner Geburt in Bremen</li>
  <li>kurze Intermezzi in <a href="https://de.wikipedia.org/wiki/Hasbergen_%28Delmenhorst%29">Hasbergen</a> und <a href="https://de.wikipedia.org/wiki/Delmenhorst">Delmenhorst/Heidkrug</a></li>
  <li>momentan in einer <a href="https://mw.vc">Informatiker/Soziologinnen-WG</a> in der Neustadt</li>
  <li>zeitweise anzutreffen: <a href="https://de.wikipedia.org/w/index.php?title=Datei:Mzh_uni_bremen-3.jpg">MZH</a>, <a href="http://www.uni-bremen.de/">Uni-Bremen</a></li>
</ul>

<h2><a name="finde_mich">Finde mich</a></h2>
<ul>
  <li>XMPP: <img src="https://planetcyborg.de/index.php?p=indicator&amp;jid=helios@planetcyborg.de&amp;theme=0" alt="" /> <a href="xmpp:helios@planetcyborg.de">helios@planetcyborg.de</a></li>
  <li>IRC: helios im <a href="http://hackint.eu/">hackint</a></li>
  <li>blog: <a href="https://mw.vc/author/helios/">milliways-blog</a></li>
  <li>E-Mail: <a href="mailto:helios@planetcyborg.de">helios@planetcyborg.de</a></li>
</ul>

<h2><a name="namen">Namen sind nur Schall und Rauch</a></h2>
<ul>
  <li><a href="https://de.wikipedia.org/wiki/Helios">helios</a></li>
  <li>chaoticbilly</li>
  <li><a href="https://de.wikipedia.org/wiki/Momo">momo</a></li>
  <li><a href="https://de.wikipedia.org/wiki/Figuren_aus_Die_Simpsons#Moe_Szyslak">moe</a></li>
  <li>Moritz (Kaspar) Rudert</li>
</ul>

<h2><a name="glaube">An was glaube ich?</a></h2>
<ul>
  <li><a href="https://de.wikipedia.org/wiki/Cyborg">Cyborgizm</a></li>
  <li><a href="https://de.wikipedia.org/wiki/Diskordianismus">Diskordianismus</a></li>
</ul>

<h2><a name="taten">Was tue ich?</a></h2>

<h3><a name="taten_aktuell">aktuelles Leben</a></h3>
<ul>
  <li><?php echo $age; ?> Jahre alt</li>
  <li>Student an der <a href="http://uni-bremen.de/">Uni-Bremen</a> (<a href="http://informatik.uni-bremen.de/">Informatik</a> dipl.) im <?php echo $semester; ?>. Semester</li>
  <li>hochschulpolitisches Engagement an der Uni-Bremen:<ul>
    <li>Mitglied im <a href="http://inf.stugen.de/">StugA Informatik</a></li>
    <li>Stugen-Administrator</li>
    <li>Gremienarbeit: IT-Lk (IT-Lenkungskreis)</li>
    <li>Gremienarbeit: Stugenkonferenz</li>
  </ul></li>
  <li><a href="http://www.wlan.uni-bremen.de/">Campus-WLAN</a>-Betreuung als studentische Hilfskraft<ul>
    <li>Betreuung der AccessPoints der Uni Bremen (Cisco 1131 und 1142 mit Cisco Controllern)</li>
    <li>Administration von Linuxservern der WLAN-Dienste (DHCP, DNS, Webseite, <a href="https://www.icinga.org/">icinga</a>)</li>
    <li>First level support via E-Mail, Telefon und pers&ouml;nlich</li>
  </ul></li>
  <li>eigene Firma seit 2007 (<a href="https://planetcyborg.de/">planet cyborg</a> vormals Quasar-Net)</li>
  <li><a href="http://wybt.net/">wybt</a>-Administrator</li>
  <li>Vereinsmitglied im <a href="http://fbmi.de/">FBMI</a> (F&ouml;rderverein der Bereichsstudierendenschaften Mathematik und Informatik an der Universit&auml;t Bremen e.V.)</li>
  <li>Vereinsmitglied im <a href="http://ccc.de/">CCC</a></li>
  <li>verschiedene <a href="http://ccc.de/">CCC</a>-nahe Aktivit&auml;ten<ul>
    <li><a href="https://dn42.net/">dn42</a>-Gr&uuml;nder, Eigent&uuml;mer der Domain und Administrator des tracs</li>
    <li>Developer und Administrator vom <a href="https://engelsystem.de/">engelsystem</a> (source: <a href="https://planetcyborg.de/git/projects/engelsystem.git">planetcyborg.de/git/projects/engelsystem.git</a>)</li>
  </ul></li>
  <li>Vereinsmitglied im <a href="http://hackerspace-bremen.de/">Hackerspace Bremen</a><ul>
    <li>Administrator des Bremer Hackerspace Servers</li>
  </ul></li>
  <li>Webmaster vom <a href="http://hackint.eu/">hackint</a></li>
  <li>Initiator und Administrator des <a href="http://jabber.uni-bremen.de/">Jabberservers</a> der Uni-Bremen</li>
</ul>

<h3><a name="taten_vergangen">vergangenes Leben</a></h3>
<ul>
  <li>NOC und Stromorga f&uuml;r den <a href="http://www.informatik.uni-bremen.de/projekttag/2012/">Projekttag Informatik 2012</a> an der Uni-Bremen</li>
  <li>Webmaster f&uuml;r den <a href="http://www.informatik.uni-bremen.de/projekttag/2012/">Projekttag Informatik 2012</a> an der Uni-Bremen</li>
  <li>Mitorganisator der <a href="http://kif.fsinf.de/wiki/KIF395:Hauptseite">KIF 39.5</a>/KoMa 69 in Bremen</li>
  <li>zweimaliger Weltmeister im <a href="http://www.robocupgermanopen.de/">Robocup</a> <a href="http://junior.robocupgermanopen.de/junior/rescue">junior rescue</a> 2008/2009 
    (einmal als Teammitglied, einmal als Betreuer im Team <a href="/hoppus/">hoppuS</a>)</li>
  <li>verschiedenes politisches Engagement in Bremen (OL (Ortsleitung) der <a href="http://www.naturfreundejugend.de/">NFJ</a>-Bremen)</li>
  <li>Sch&uuml;ler am SZ Walle Lange Reihe Sek. 2<ul>
    <li>SV-Vorstand/Schulsprecher des SZ Walle Lange Reihe Sek. 2</li>
    <li>Erstellung der Schulhomepage SZ Walle Lange Reihe Sek. 2</li>
  </ul></li>
  <li>Sch&uuml;ler des SZ Findorff<ul>
    <li>Betreuung der Schulrechner des SZ Findorff</li>
    <li>Sch&uuml;lervertreter am SZ Findorff</li>
  </ul></li>
  <li>Teilnehmer/Teamer von Computerkinderfreizeiten</li>
  <li>Sch&uuml;ler der Grundschule SZ Findorff Admiralstr.</li>
</ul>

<h3><a name="taten_zukuenftig">zuk&uuml;nftiges Leben</a></h3>
<ul>
  <li>Studium abschlie&szlig;en</li>
  <li>technischer Mitarbeiter an der Uni-Bremen</li>
  <li><a href="https://de.wikipedia.org/wiki/Das_Haus_am_See">Haus</a> am <a href="https://de.wikipedia.org/wiki/Stadtaffe">See</a></li>
</ul>

<?php
if($mode != "mr") {
?>

<h2><a name="andere">andere &uuml;ber mich</a></h2>
<small>Willst du, dass deine Aussage auch hier steht? Dann schick mir eine E-Mail an <a href="mailto:helios@planetcyborg.de">helios@planetcyborg.de</a>.</small><br />
<ul>
  <li>von den Leuten, die ich bisher getroffen hab, mit die meiste Ahnung von Servern, Netzen und &Auml;hnlichem.</li>
  <li>Mit ihm arbeiten macht Spa&szlig;, ist am Anfang aber ungewohnt, denn er sagt, was er denkt; manchmal auf eine Art, an die man sich erst gew&ouml;hnen muss.</li>
  <li>es ist seine Aufrichtigkeit und Ehrlichkeit, die ihn auszeichnet, weshalb ich seine ehrliche Kritik immer sch&auml;tze.</li>
  <li>ein Mensch auf den man sich immer verlassen kann, der immer einfach da ist und auch bei unverst&auml;ndlichen Problemen hilft</li>
  <li>hat eine v&ouml;llig verrueckte Art, die einfach liebenswert ist, wenn man ihn n&auml;her kennenlernt und am Anfang einfach spannend wirkt.</li>
  <li>wei&szlig; sich ein- und durchzusetzten und f&uuml;r das einzustehen was er haben will.</li>
  <li>er ist interessant, weil man nie wei&szlig; welchen Quatsch er jetzt grad wieder im Kopf hat.</li>
  <li>neben seinen technischen Hobbies verliert er aber nie den Blick f&uuml;r seine Umwelt und vor allem seine n&auml;chsten Mitmenschen.</li>
  <li>freundlicher und immer hilfsbereiter CyberHippie.</li>
  <li>spricht auch mal unbequeme Wahrheiten aus.</li>
  <li>sieht, wie Hippies eben sind, dem Leben gelassen engegen.</li>
  <li>Cooler Nerd mit lustigen Ideen und Interessen, bei dem es manchmal etwas schwierig ist, seinen Sarkasmus von Ernst zu unterscheiden.</li>
  <li>Hat mehr Dinge im Kopf, als ich K&ouml;pfe habe.</li>
</ul>

<h2><a name="blog">blog</a></h2>
<?php
  // parse the blog feed
  $blog = parse_rss_feed("http://systemfehler.org/feed/");
  foreach($blog as $key => $post) {
    echo "<h3><a href=\"#" . md5($key . $post['title']) . "\" name=\"" . md5($key . $post['title']) . "\">" . $post['title'] . "</a></h3>\n";
    echo "<small>" . date('d.m.Y H:i', $key) . "</small>\n";
    echo str_replace("http://systemfehler.org/files/", "/secureimage.php?url=http://systemfehler.org/files/", $post['description']);
  }
?>

<h2><a name="art">art</a></h2>
<div id="gallery">
<?php
echo "<ul id=\"art\">";
foreach($inc as $file) {
  if ($dh2 = opendir($config['art_folder'] . $file)) {
    while (($file2 = readdir($dh2)) !== false) {
      if(!in_array(strtolower($file2), $config['art_excludes']) && is_file($config['art_folder'] . $file . "/" . $file2)) {
        if(!file_exists($config['art_folder'] . "thumbs/" . $file . "_" . $file2))
          system("convert -resize 120x120 \"" . $config['art_folder'] . $file . "/" . $file2 . "\" \"" . $config['art_folder'] . "thumbs/" . $file . "_" . $file2 . "\"");

        echo "<li><a href=\"" . $config['art_folder_rel'] . $file . "/" . $file2 . "\"><img src=\"" . $config['art_folder_rel'] . "thumbs/" . $file . "_" . $file2 . "\" alt=\"\" title=\"" . basename($file2) . "\"/></a></li>\n";
      }
    }

    closedir($dh2);
  }
}
?>
</ul>
</div>

<h2><a name="lese">hier lese ich</a></h2>
<ul>
  <li><a href="http://weltheit.eu/">mieke</a></li>
  <li><a href="http://tahlly.planetcyb.org/">tahlly</a></li>
  <li><a href="http://salissa.planetcyb.org/">salissa</a></li>
  <li><a href="http://notrademark.de/">msquare</a></li>
  <li><a href="https://blog.cvigano.de/">kritter</a></li>
  <li><a href="https://blog.jplitza.de/">jplitza</a></li>
</ul>
<?php
}

require_once "sites/footer.php";
?>
