<?php
  if($_SERVER['HTTPS'] != "on") {
    $redirect = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location: " . $redirect);
  }

  if(preg_match("/^.*yasni\.de.*$/", $_SERVER['HTTP_REFERER']) ||
     preg_match("/^.*pipl\.com.*$/", $_SERVER['HTTP_REFERER']) ||
     preg_match("/^.*123people\.de.*$/", $_SERVER['HTTP_REFERER']) ||
     preg_match("/^.*radaris\.de.*$/", $_SERVER['HTTP_REFERER']))
    header("Location: /personensuchmaschine.html");

switch($_REQUEST['m']) {
  case "all":
    $inc = "";
    break;

  default:
    $inc = array("camp2011", "freimarkt2009", "persons", "indoorart", "office", "streetart");
    break;
}

echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>wiresphere - helios, chaoticbilly, momo, moe, Moritz Rudert - names are different</title>
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

<h2>Wo lebe ich?</h2>
<ul>
  <li>seit meiner Geburt in Bremen</li>
  <li>kurze Intermezzi in Hasbergen und Delmenhorst/Heidkrug</li>
  <li>momentan in einer Informatiker/Mathematiker-WG in der Neustadt</li>
  <li>zeitweise anzutreffen: MZH; Uni-Bremen</li>
</ul>

<h2>Finde mich</h2>
<ul>
  <li>XMPP: <img src="https://planetcyborg.de/index.php?p=indicator&amp;jid=helios@wiresphere.de&amp;theme=0" alt="" /> <a href="xmpp:helios@wiresphere.de">helios@wiresphere.de</a></li>
  <li>IRC: helios im <a href="http://hackint.eu/">hackint</a></li>
  <li>blog: <a href="http://mw.vc/author/helios/">milliways-blog</a></li>
</ul>

<h2>Namen sind nur Schall und Rauch</h2>
<ul>
  <li><a href="http://de.wikipedia.org/wiki/Helios">helios</a></li>
  <li>chaoticbilly</li>
  <li><a href="http://de.wikipedia.org/wiki/Momo">momo</a></li>
  <li><a href="http://de.wikipedia.org/wiki/Figuren_aus_Die_Simpsons#Moe_Szyslak">moe</a></li>
  <li>Moritz (Kaspar) Rudert</li>
</ul>

<h2>An was glaube ich?</h2>
<ul>
  <li><a href="http://de.wikipedia.org/wiki/Rastafari">Rastafarian</a></li>
  <li><a href="http://de.wikipedia.org/wiki/Cyborg">Cyborgizm</a></li>
</ul>

<h2>Was tue ich?</h2>

<h3>aktuelles Leben</h3>
<ul>
  <li>Student an der <a href="http://uni-bremen.de/">Uni-Bremen</a> (<a href="http://informatik.uni-bremen.de/">Informatik</a> dipl.) im 7. Semester</li>
  <li>hochschulpolitisches Engagement an der Uni-Bremen (Mitglied im StugA Informatik, verschiedene Gremienarbeit (u.A. IT-Lk (IT-Lenkungskreis)))</li>
  <li><a href="http://www.wlan.uni-bremen.de/">Campus-WLAN</a>-Betreuung als studentische Hilfskraft</li>
  <li>eigene Firma seit 2007 (<a href="http://planetcyborg.de/">planet cyborg</a> vormals Quasar-Net)</li>
  <li><a href="http://wybt.net/">wybt</a></li>
  <li>verschiedene <a href="http://ccc.de/">CCC</a>-nahe Aktivit&auml;ten (u.A. <a href="http://dn42.net/">dn42</a>)</li>
  <li>Vereinsmitglied im <a href="http://ccc.de/">CCC</a></li>
  <li>Vereinsmitglied im FBMI (F&ouml;rderverein der Bereichsstudierendenschaften Mathematik und Informatik an der Universit&auml;t Bremen e.V.)</li>
  <li>Betreuer diverse Archlinux-<a href="<?php echo htmlentities("http://aur.archlinux.org/packages.php?K=helios&SeB=m"); ?>">AUR-Pakete</a></li>
  <li>Webmaster vom <a href="http://hackint.eu/">hackint</a></li>
  <li>Initiator des <a href="http://jabber.uni-bremen.de/">Jabberservers</a> der Uni-Bremen</li>
</ul>

<h2>art</h2>
<div id="gallery">
<?php

$dir = "./images/";
$excludes = array('.', '..', '.ds_store', '.svn', 'thumbs');

// Open a known directory, and proceed to read its contents
echo "<ul id=\"art\">";
if (is_dir($dir)) {
  if ($dh = opendir($dir)) {
    while (($file = readdir($dh)) !== false) {
      if(!in_array(strtolower($file), $excludes) && is_dir($dir . $file)) {
        if (is_dir($dir . $file) && (!is_array($inc) || in_array($file, $inc))) {
          if ($dh2 = opendir($dir . $file)) {
            while (($file2 = readdir($dh2)) !== false) {
              if(!in_array(strtolower($dir . $file . "/" . $file2), $excludes) && is_file($dir . $file . "/" . $file2)) {
                if(!file_exists($dir . "thumbs/" . $file . "_" . $file2))
                  system("convert -resize 120x120 \"" . $dir . $file . "/" . $file2 . "\" \"" . $dir . "thumbs/" . $file . "_" . $file2 . "\"");

                echo "<li><a href=\"" . $dir . $file . "/" . $file2 . "\"><img src=\"" . $dir . "thumbs/" . $file . "_" . $file2 . "\" alt=\"\" title=\"" . basename($file2) . "\"/></a></li>\n";
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

<h3>vergangenes Leben</h3>
<ul>
  <li>Mitorganisator der KIF 39.5/KoMa 69 in Bremen</li>
  <li>zweimaliger Weltmeister im <a href="http://www.robocupgermanopen.de/">Robocup</a> <a href="http://www.robocupgermanopen.de/en/junior/rescue">junior rescue</a> 2008/2009 
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

<h3>zuk&uuml;nftiges Leben</h3>
<ul>
  <li>Studium abschlie&szlig;en</li>
  <li>technischer Mitarbeiter an der Uni-Bremen</li>
  <li><a href="http://de.wikipedia.org/wiki/Das_Haus_am_See">Haus</a> am <a href="http://de.wikipedia.org/wiki/Stadtaffe">See</a></li>
</ul>

<h2>andere &uuml;ber mich</h2>
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

<h2>hier lese ich</h2>
<ul>
  <li><a href="http://mieke.planetcyb.org/">mieke</a></li>
  <li><a href="http://tahlly.planetcyb.org/">tahlly</a></li>
  <li><a href="http://themesh.de/">sicarius</a></li>
  <li><a href="http://salissa.planetcyborg.de/">salissa</a></li>
  <li><a href="http://xhala.planetcyb.org/">xhala</a></li>
  <li><a href="http://notrademark.de/">msquare</a></li>
  <li><a href="https://blog.cvigano.de/">kritter</a></li>
  <li><a href="https://blog.jplitza.de/">jplitza</a></li>
</ul>

<h2>Tracking</h2>
<p><iframe width="560px" height="250px" src="http://planetcyborg.de/piwik/index.php?module=CoreAdminHome&#038;action=optOut&#038;language=de" style="background: #fff;"></iframe></p>

<h2>Impressum</h2>
<p>Alleinverantwortlich für diese Internetseite ist:</p>
<p>Moritz Kaspar Rudert<br />
Hohentorsheerstr. 172<br />
D &#8211; 28199 Bremen<br />
Telefon: 0421 &#8211; 16 76 136<br />
E-Mail: <a href="mailto:mr@planetcyborg.de">mr@planetcyborg.de</a></p>

      <div style="text-align: center;"><img src="images/we-are-happy-workers.png" alt="" /></div>
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
    width: 500,
    height: 500
  });
</script>

  </body>
</html>
