<?php
  $art_folder_rel = 'images/';
  $art_folder = $_SERVER['DOCUMENT_ROOT'] . $art_folder_rel;
  $default_inc = array();
  $excludes = array('.', '..', '.ds_store', '.svn', 'thumbs', '.not_this', '.git');

  if(is_dir($art_folder)) {
    if($dh = opendir($art_folder)) {
      while(($file = readdir($dh)) !== false) {
        if(is_dir($art_folder . $file) && !in_array(strtolower($file), $excludes) && !file_exists($art_folder . $file . "/.not_this"))
          array_push($default_inc, $file);
      }

      closedir($dh);
    }
  }

  $semester = (date("Y") - 2008) * 2;
  if(date("n") >= 10) $semester++;

  $alter = (date("Y") - 1988 - 1);
  if((date("n") == 2 && date("j") >= 2) || date("n") > 2) $alter++;

  if($_SERVER['HTTPS'] != "on") {
    $redirect = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location: " . $redirect);
  }

  if(preg_match("/^.*yasni\.de.*$/", $_SERVER['HTTP_REFERER']) ||
     preg_match("/^.*pipl\.com.*$/", $_SERVER['HTTP_REFERER']) ||
     preg_match("/^.*123people\.de.*$/", $_SERVER['HTTP_REFERER']) ||
     preg_match("/^.*radaris\.de.*$/", $_SERVER['HTTP_REFERER']))
    header("Location: /personensuchmaschine.html");

  if($_SERVER['HTTP_HOST'] == "helios.planetcyborg.de")
    $mode = "helios";
  else
    $mode = "mr";

if(isset($_REQUEST['m']) && !empty($_REQUEST['m'])) {
  switch($_REQUEST['m']) {
    case "all":
      $inc = "";
      break;

    default:
      $inc = array($_REQUEST['m']);
      break;
  }
} else
  $inc = $default_inc;

echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
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
    <link rel="stylesheet" href="style.css" />

<?php if(!isset($_REQUEST['nojs'])) { ?>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="galleria/galleria-1.2.6.js"></script>
    <script type="text/javascript" src="galleria/themes/classic/galleria.classic.min.js"></script>
<?php } ?>

  </head>

  <body>
    <div id="main">
      <div id="header">
        <img src="images/logo.png" alt="" />

        <div id="headertwo">
          <img src="images/me.png" alt="" />
        </div>
      </div>

      <div id="content">

<h2><a name="wo_lebe_ich">Wo lebe ich?</a></h2>
<ul>
  <li>seit meiner Geburt in Bremen</li>
  <li>kurze Intermezzi in Hasbergen und Delmenhorst/Heidkrug</li>
  <li>momentan in einer <a href="http://mw.vc">Informatiker/Soziologinnen-WG</a> in der Neustadt</li>
  <li>zeitweise anzutreffen: <a href="https://de.wikipedia.org/w/index.php?title=Datei:Mzh_uni_bremen-3.jpg">MZH</a>, Uni-Bremen</li>
</ul>

<h2><a name="finde_mich">Finde mich</a></h2>
<ul>
  <li>XMPP: <img src="https://planetcyborg.de/index.php?p=indicator&amp;jid=helios@planetcyborg.de&amp;theme=0" alt="" /> <a href="xmpp:helios@planetcyborg.de">helios@planetcyborg.de</a></li>
  <li>IRC: helios im <a href="http://hackint.eu/">hackint</a></li>
  <li>blog: <a href="http://mw.vc/author/helios/">milliways-blog</a></li>
  <li>E-Mail: <a href="mailto:helios@planetcyborg.de">helios@planetcyborg.de</a></li>
</ul>

<h2><a name="namen">Namen sind nur Schall und Rauch</a></h2>
<ul>
  <li><a href="http://de.wikipedia.org/wiki/Helios">helios</a></li>
  <li>chaoticbilly</li>
  <li><a href="http://de.wikipedia.org/wiki/Momo">momo</a></li>
  <li><a href="http://de.wikipedia.org/wiki/Figuren_aus_Die_Simpsons#Moe_Szyslak">moe</a></li>
  <li>Moritz (Kaspar) Rudert</li>
</ul>

<h2><a name="glaube">An was glaube ich?</a></h2>
<ul>
  <li><a href="http://de.wikipedia.org/wiki/Rastafari">Rastafarian</a></li>
  <li><a href="http://de.wikipedia.org/wiki/Cyborg">Cyborgizm</a></li>
</ul>

<h2><a name="taten">Was tue ich?</a></h2>

<h3><a name="taten_aktuell">aktuelles Leben</a></h3>
<ul>
  <li><?php echo $alter; ?> Jahre alt</li>
  <li>Student an der <a href="http://uni-bremen.de/">Uni-Bremen</a> (<a href="http://informatik.uni-bremen.de/">Informatik</a> dipl.) im <?php echo $semester; ?>. Semester</li>
  <li>Versuch als <a href="https://de.wikipedia.org/wiki/Pescetarismus">Ovo-Lacto-Pesco-Vegetarier</a> zu leben<ul>
    <li>Versuch Tiermilchprodukte durch Sojamilchprodukte zu ersetzen</li>
  </ul></li>
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
  <li>eigene Firma seit 2007 (<a href="http://planetcyborg.de/">planet cyborg</a> vormals Quasar-Net)</li>
  <li><a href="http://wybt.net/">wybt</a>-Administrator</li>
  <li>Vereinsmitglied im <a href="http://fbmi.de/">FBMI</a> (F&ouml;rderverein der Bereichsstudierendenschaften Mathematik und Informatik an der Universit&auml;t Bremen e.V.)</li>
  <li>Vereinsmitglied im <a href="http://ccc.de/">CCC</a></li>
  <li>verschiedene <a href="http://ccc.de/">CCC</a>-nahe Aktivit&auml;ten<ul>
    <li><a href="http://dn42.net/">dn42</a>-Gr&uuml;nder, Eigent&uuml;mer der Domain und Administrator des tracs</li>
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
  <li><a href="http://de.wikipedia.org/wiki/Das_Haus_am_See">Haus</a> am <a href="http://de.wikipedia.org/wiki/Stadtaffe">See</a></li>
</ul>

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

<?php
if($mode != "mr") {
?>

<h2><a name="art">art</a></h2>
<div id="gallery">
<?php
echo "<ul id=\"art\">";
if (is_dir($art_folder)) {
  if ($dh = opendir($art_folder)) {
    while (($file = readdir($dh)) !== false) {
      if(!in_array(strtolower($file), $excludes) && is_dir($art_folder . $file)) {
        if (!is_array($inc) || in_array($file, $inc)) {
          if ($dh2 = opendir($art_folder . $file)) {
            while (($file2 = readdir($dh2)) !== false) {
              if(!in_array(strtolower($file2), $excludes) && is_file($art_folder . $file . "/" . $file2)) {
                if(!file_exists($art_folder . "thumbs/" . $file . "_" . $file2))
                  system("convert -resize 120x120 \"" . $art_folder . $file . "/" . $file2 . "\" \"" . $art_folder . "thumbs/" . $file . "_" . $file2 . "\"");

                echo "<li><a href=\"" . $art_folder_rel . $file . "/" . $file2 . "\"><img src=\"" . $art_folder_rel . "thumbs/" . $file . "_" . $file2 . "\" alt=\"\" title=\"" . basename($file2) . "\"/></a></li>\n";
              }
            }

            closedir($dh2);
          }
        }
      }
    }

    closedir($dh);
  }
}
?>
</ul>
</div>

<h2><a name="lese">hier lese ich</a></h2>
<ul>
  <li><a href="http://mieke.planetcyb.org/">mieke</a></li>
  <li><a href="http://tahlly.planetcyb.org/">tahlly</a></li>
  <li><a href="http://themesh.de/">sicarius</a></li>
  <li><a href="http://salissa.planetcyb.org/">salissa</a></li>
  <li><a href="http://xhala.planetcyb.org/">xhala</a></li>
  <li><a href="http://notrademark.de/">msquare</a></li>
  <li><a href="https://blog.cvigano.de/">kritter</a></li>
  <li><a href="https://blog.jplitza.de/">jplitza</a></li>
</ul>
<?php
}
?>

<h2><a name="tracking">Tracking</a></h2>
<p><iframe width="560px" height="250px" src="http://planetcyborg.de/piwik/index.php?module=CoreAdminHome&#038;action=optOut&#038;language=de" style="background: #fff;"></iframe></p>

<h2><a name="impressum">Impressum</a></h2>
<p>Alleinverantwortlich für diese Internetseite ist:</p>
<p>Moritz Kaspar Rudert<br />
Hohentorsheerstr. 172<br />
D &#8211; 28199 Bremen<br />
Telefon: 0421 &#8211; 16 76 136<br />
E-Mail: <a href="mailto:mr@planetcyborg.de">mr@planetcyborg.de</a></p>

      <div id="happy-worker"><img src="images/we-are-happy-workers.png" alt="" /></div>
      </div>
      </div>

<!-- Piwik -->
<script type="text/javascript">
var pkBaseURL = (("https:" == document.location.protocol) ? "https://planetcyborg.de/piwik/" : "http://planetcyborg.de/piwik/");
document.write(unescape("%3Cscript src='" + pkBaseURL + "piwik.js' type='text/javascript'%3E%3C/script%3E"));
</script><script type="text/javascript">
try {
var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", 8);
piwikTracker.trackPageView();
piwikTracker.enableLinkTracking();
} catch( err ) {}
</script><noscript><p><img src="http://planetcyborg.de/piwik/piwik.php?idsite=8" style="border:0" alt="" /></p></noscript>
<!-- End Piwik Tracking Code -->

<script type="text/javascript">
  Galleria.loadTheme('galleria/themes/classic/galleria.classic.min.js');
  $("#gallery").galleria({
    width: 880,
    height: 500
  });
</script>

  </body>
</html>
