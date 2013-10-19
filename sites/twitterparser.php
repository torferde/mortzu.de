<?php
  if(!defined("INCLUDE")) die();

  switch($twitter_what) {
    case "london":
      $title = "Dennis und Mieke in London";
      $twitter_users = array('allspark23', 'miekemuecke');
      $start_ts = "04.08.2013 18:00:00";
      $end_ts = "10.08.2013 06:00:00";
      break;
    case "borkum":
      $title = "Mieke und Moritz auf Borkum";
      $twitter_users = array('mortzu', 'miekemuecke');
      $start_ts = "10.09.2013 00:00:00";
      $end_ts = "14.09.2013 23:59:00";
      break;
    default:
      die();
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <title><?php echo $title; ?></title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />

    <style type="text/css">
      body {
        background-color: #fff;
        color: #000;
        font-family: Geneva, "Lucida Sans", "Lucida Grande",
          "Lucida Sans Unicode", Verdana, sans-serif;
          font-size: 12px;
      }

      a, a:visited {
        color: blue;
      }
    </style>

  </head>
  <body>

  <div style="position: absolute; right: 10px; top: 10px;"><a href="https://mortzu.de/impressum/">Impressum</a></div>

  <h2><?php echo $title; ?></h2>

<?php
require_once('extlib/TwitterAPIExchange/TwitterAPIExchange.php');

$tweets = array();

$settings = array(
  'oauth_access_token' => '1243027093-MFbA6nqHcLqqE0dufch78JyoTNRz9Vih3wh0Sss',
  'oauth_access_token_secret' => 'IS7sb03TP4rCZdDcaMeeUy8uO6jHEWRj37dj88KJpoA',
  'consumer_key' => '71jpzwwo2LdsHtIvS1EdGg',
  'consumer_secret' => 'klNRyP054WjmAMhOWWhfCEyGoaR8rhCdoLsdbX1kNk'
);

$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
$requestMethod = 'GET';

foreach($twitter_users as $twitter_user) {
  $getfield = '?screen_name=' . $twitter_user . '&count=1500';

  $twitter = new TwitterAPIExchange($settings);
  $json_content = $twitter->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest();

  $array_content = json_decode($json_content, true);

  foreach($array_content as $tweet) {
    $hash_tags = array();

    foreach($tweet['entities']['hashtags'] as $hashtags)
      array_push($hash_tags, strtolower($hashtags['text']));

    $tweets[strtotime($tweet['created_at'])] = array('username' => $twitter_user, 'user' => $tweet['user']['name'], 'text' => $tweet['text'], 'hashtags' => $hash_tags);

    unset($hash_tags);
  }
}

krsort($tweets);

foreach($tweets as $date => $tweet) {
  if(isset($start_ts) && $date < strtotime($start_ts))
    continue;

  if(isset($end_ts) && $date > strtotime($end_ts))
    continue;

  if(isset($hashtag) && !in_array($hashtag, $tweet['hashtags']))
    continue;

  echo "<p>" . date("d.m.y H:i:s", $date) . " (<a href=\"https://twitter.com/" . $tweet['username'] . "\">" . $tweet['user'] . "</a>)<br />\n";
  echo make_links($tweet['text']) . "<br /></p>\n";
}

function make_links($text) {
  $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";

  if(preg_match($reg_exUrl, $text, $url))
    return preg_replace($reg_exUrl, "<a href=\"" . $url[0] . "\">" . $url[0] . "</a> ", $text);
  else
    return $text;
}
?>

  </body>
</html>
