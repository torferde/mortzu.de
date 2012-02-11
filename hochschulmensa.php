<?php
ini_set("default_socket_timeout", 2);

  class tableExtractor {
    var $source = NULL;
    var $anchor = NULL;
    var $anchorWithin = false;
    var $headerRow = true;
    var $startRow = 1;
    var $maxRows = 0;
    var $startCol = 1;
    var $maxCols = 0;
    var $stripTags = false;
    var $extraCols = array();
    var $rowCount = 0;
    var $dropRows = NULL;
    var $cleanHTML = NULL;
    var $rawArray = NULL;
    var $finalArray = NULL;

    function extractTable() {
      $this->cleanHTML();
      $this->prepareArray();
      return $this->createArray();
    }

    function cleanHTML() {
      if(!function_exists('stripos')) {
        function stripos($haystack,$needle,$offset = 0) {
          return(strpos(strtolower($haystack),strtolower($needle),$offset));
        }
      }

      if ($this->anchorWithin) {
        $anchorPos = stripos($this->source, $this->anchor) + strlen($this->anchor);
        $sourceSnippet = strrev(substr($this->source, 0, $anchorPos));
        $tablePos = stripos($sourceSnippet, strrev(("<table"))) + 6;
        $startSearch = strlen($sourceSnippet) - $tablePos;
      } else
        $startSearch = stripos($this->source, $this->anchor);

      $startTable = stripos($this->source, '<table', $startSearch);
      $endTable = stripos($this->source, '</table>', $startTable) + 8;
      $table = substr($this->source, $startTable, $endTable - $startTable);

      if(!function_exists('lcase_tags')) {
        function lcase_tags($input) {
          return strtolower($input[0]);
        }
      }

      $table = preg_replace_callback('/<(\/?)(table|tr|th|td)/is', 'lcase_tags', $table);
      $table = preg_replace('/<\/?(thead|tbody).*?>/is', '', $table);
      $table = preg_replace('/<(\/?)th(.*?)>/is', '<$1td$2>', $table);
      $table = trim($table);
      $table = str_replace("\r\n", "", $table); 
      $this->cleanHTML = $table;
    }
    
    function prepareArray() {
      $pattern = '/(<\/?(?:tr|td).*?>)/is';
      $table = preg_split($pattern, $this->cleanHTML, -1, PREG_SPLIT_DELIM_CAPTURE);    
      $tableCleaned = array();
      $rowCount = 0;
      $colCount = 1;
      $trOpen = false;
      $tdOpen = false;

      foreach($table as $item) {
        $item = str_replace('&nbsp;', '', $item);
        $item = trim($item);

        $itemUnedited = $item;
        $item = preg_replace('/<(\/?)(table|tr|td).*?>/is', '<$1$2>', $item);

        switch ($item) {
          case '<tr>':
            $rowCount++;
            $colCount = 1;
            $trOpen = true;
            break;
          case '<td>':
            $tdTag = $itemUnedited;
            $tdOpen = true;
            break;
          case '</td>':
            $tdOpen = false;
            break;
          case '</tr>':
            $trOpen = false;
            break;
          default :
            if($tdOpen) {
              if(preg_match('/<td [^>]*colspan\s*=\s*(?:\'|")?\s*([0-9]+)[^>]*>/is', $tdTag, $matches))
                $colspan = $matches[1];
              else
                $colspan = 1;

              if(preg_match('/<td [^>]*rowspan\s*=\s*(?:\'|")?\s*([0-9]+)[^>]*>/is', $tdTag, $matches))
                $rowspan = $matches[1];
              else
                $rowspan = 0;

              for($c = 0; $c < $colspan; $c++) {
                if(!isset($tableCleaned[$rowCount][$colCount]))
                  $tableCleaned[$rowCount][$colCount] = $item;
                else
                  $tableCleaned[$rowCount][$colCount + 1] = $item;

                $futureRows = $rowCount;

                for($r = 1; $r < $rowspan; $r++) {
                  $futureRows++;

                  if($colspan > 1)
                    $tableCleaned[$futureRows][$colCount + 1] = $item;
                  else
                    $tableCleaned[$futureRows][$colCount] = $item;
                }

                $colCount++;
              }

              ksort($tableCleaned[$rowCount]);
            }
            break;
         }
      }

      if($this->headerRow)
        $this->rowCount = count($tableCleaned) - 1;
      else
        $this->rowCount = count($tableCleaned);

      $this->rawArray = $tableCleaned;
    }

    function createArray() {
      $tableData = array();
      if($this->headerRow) {
        $row = $this->rawArray[$this->headerRow];
        $columnNames = array();
        $uniqueNames = array();
        $colCount = 0;

        foreach($row as $cell) {
          $colCount++;
          $cell = strip_tags($cell);
          $cell = trim($cell);

          if($cell) {
            if(isset($uniqueNames[$cell])) {
              $uniqueNames[$cell]++;
              $cell .= ' ('.($uniqueNames[$cell] + 1).')';      
            } else {
              $uniqueNames[$cell] = 0;
            }

            $columnNames[$colCount] = $cell;
          } else
            $columnNames[$colCount] = $colCount;
        }

        unset($this->rawArray[$this->headerRow]);
      }

      foreach(explode(',', $this->dropRows) as $key => $value) {
        unset($this->rawArray[$value]);
      }

      if($this->maxRows)
        $endRow = $this->startRow + $this->maxRows - 1;
      else
        $endRow = count($this->rawArray);

      $rowCount = 0;
      $newRowCount = 0;

      foreach($this->rawArray as $row) {
        $rowCount++;
        if($rowCount >= $this->startRow && $rowCount <= $endRow) {
          $newRowCount++;
          $tableData[$newRowCount] = array();
          $tableData[$newRowCount] = array();

          if($this->maxCols)
            $endCol = $this->startCol + $this->maxCols - 1;
          else
            $endCol = count($row);

          $colCount = 0;
          $newColCount = 0;

          foreach($row as $cell) {
            $colCount++;

            if($colCount >= $this->startCol && $colCount <= $endCol) {
              $newColCount++;
              if($this->extraCols) {
                foreach($this->extraCols as $extraColumn) {
                  if($extraColumn['column'] == $colCount) {
                    if(preg_match($extraColumn['regex'], $cell, $matches)) {
                      if(is_array($extraColumn['names'])) {
                        $this->extraColsCount = 0;

                        foreach($extraColumn['names'] as $extraColumnSub) {
                          $this->extraColsCount++;
                          $tableData[$newRowCount][$extraColumnSub] = $matches[$this->extraColsCount];
                        }
                      } else {
                        $tableData[$newRowCount][$extraColumn['names']] = $matches[1];
                      }
                    } else {
                      $this->extraColsCount = 0;
                      if(is_array($extraColumn['names'])) {
                        $this->extraColsCount = 0;
                        foreach($extraColumn['names'] as $extraColumnSub) {
                          $this->extraColsCount++;
                          $tableData[$newRowCount][$extraColumnSub] = '';
                        }
                      } else {
                        $tableData[$newRowCount][$extraColumn['names']] = '';
                      }
                    }
                  }
                }
              }

              if($this->stripTags)
                $cell = strip_tags($cell);
                $colKey = $newColCount;

                if($this->headerRow)
                  if(isset($columnNames[$colCount]))
                    $colKey = $columnNames[$colCount];
                    $tableData[$newRowCount][$colKey] = $cell;
            }
          }
        }
      }

      $this->finalArray = $tableData;
      return $tableData;
    }
  }

  function replace_day($tomorrow = false, $donot = false) {
    $tmp_day = date("w");
    $time = date("G");

    $weekday = array ("So.", "Mo.", "Di.", "Mi.", "Do.", "Fr.", "Sa." );

    if(($time >= 14 || $tomorrow) && !$donot)
      $tmp_day = $tmp_day +1;

    return $weekday[$tmp_day];
  }

  function clean_output($string) {
    $string = str_replace("<br>", " ", $string);
    $string = str_replace(">>", "", $string);
    $string = str_replace("<<", "", $string);
    $string = strip_tags(utf8_encode($string));
    $string = str_replace(">", "", $string);
    $string = str_replace("<", "", $string);
    $string = str_replace("  ", " ", $string);
    $string = str_replace("________", "<br />", $string);
    $string = str_replace(" -", "-", $string);
    $string = str_replace("- ", "-", $string);
    $string = html_entity_decode($string, ENT_QUOTES, "UTF-8");
    $string = htmlentities($string, ENT_QUOTES, "UTF-8");
    $string = trim($string);

    return $string;
  }

  function beilagen($string) {
    return explode("<br>", $string);
  }

  $input = "";

  if(isset($_GET["when"]))
    $when = $_GET["when"];

  $url = "http://www.studentenwerk.bremen.de/files/main_info/essen/plaene/hsbessen.php";
  $tmpfile = "/tmp/hochschulmensa.tmp";

  if(@filemtime($tmpfile) <= time() - 900 || @filesize($tmpfile) == 0) {
    $input = @file_get_contents($url);

    if($input != "") {
      $tmpfiles = @fopen($tmpfile, w);
      flock($tmpfiles, LOCK_EX);
      fwrite($tmpfiles, $input);
      flock($tmpfiles, LOCK_UN);
      fclose($tmpfiles);
    }
  }

  $content = file_get_contents($tmpfile);
  $msg = "<p><a href=\"" . htmlentities("?when=week", ENT_QUOTES, "UTF-8") . "\">week</a> | <a href=\"" . htmlentities("?when=tomorrow", ENT_QUOTES, "UTF-8") . "\">tomorrow</a> | <a href=\"" . htmlentities("?", ENT_QUOTES, "UTF-8") . "\">today</a></p><hr />\n";
  $msg .= "<div id=\"mensadata\">\n";

  // Create class tableextractor
  $tableext = new tableExtractor;

  $curr_day = date("D");

  // Get content
  $tableext->source= $content;
  $tableext->anchorWithin= false;
  $tableext->anchor= '<basefont face="Arial" Size=1>';
  $tableext->headerRow= false;
  $extracted= $tableext->extractTable();

  $i = 0;

  foreach($extracted[4] as $key => $row) {
    $i++;
    $extracted[2][$key] = $extracted[2][$key] . ".";

    if($i % 4 == 0) {
      if(isset($when) && $when == "week") {
        $msg .= "<h3>" . clean_output($extracted[2][$key]) . "</h3>\n";
        $msg .= "<h4>Essen 1</h4><p>" . clean_output($extracted[4][$key]) . "</p>\n";
                                $msg .= "<h4>Essen 2</h4>\n<p>" . clean_output($extracted[6][$key]) . "</p>\n";
                                $msg .= "<h4>Front-Cooking</h4>\n<p>" . clean_output($extracted[8][$key]) . (clean_output($extracted[9][$key-1]) != ""?" (" . clean_output($extracted[9][$key-1]) . " &euro;)":"") . "</p>\n";
                                $msg .= "<h4>Bio-Menue</h4>\n<p>" . clean_output($extracted[10][$key]) . (clean_output($extracted[11][$key-1]) != ""?" (" . clean_output($extracted[11][$key-1]) . " &euro;)":"") . "</p>\n";
                                $msg .= "<h4>Beilagen</h4>\n<p>" . clean_output($extracted[12][$key]) . "</p>\n";
        $msg .= "<hr />\n";
      } else {
        if(isset($when) && $when == "tomorrow")
          $day = replace_day(true);
        else
                $day = replace_day(false, true);

        if(($day == "Sa.") && ($curr_day == "Fri")) {
          $msg .= "<p>it's friday, after 14:00; no mensa data available</p>\n";
          break;
        } elseif($curr_day == "Sat") {
          $msg .= "<p>it's saturday; no mensa data available</p>\n";
          break;
        } elseif($curr_day == "Sun") {
          $msg .= "<p>it's sunday; no mensa data available</p>\n";
          break;
        } elseif($day == clean_output($extracted[2][$key])) {
          $msg .= "<h3>Essen 1</h3>\n<p>" . clean_output($extracted[4][$key]) . "</p>\n";
          $msg .= "<h3>Essen 2</h3>\n<p>" . clean_output($extracted[6][$key]) . "</p>\n";
          $msg .= "<h3>Front-Cooking</h3>\n<p>" . clean_output($extracted[8][$key]) . (clean_output($extracted[9][$key-1]) != ""?" (" . clean_output($extracted[9][$key-1]) . " &euro;)":"") . "</p>\n";
          $msg .= "<h3>Bio-Menue</h3>\n<p>" . clean_output($extracted[10][$key]) . (clean_output($extracted[11][$key-1]) != ""?" (" . clean_output($extracted[11][$key-1]) . " &euro;)":"") . "</p>\n";
          $msg .= "<h3>Beilagen</h3>\n<p>" . clean_output($extracted[12][$key]) . "</p>\n";
          break;
        }
      }
    }

    if($msg == "")
      $msg = "<p>error fetching data.</p>\n";
  }

  $msg .= "</div>\n";

  echo $msg;
?>
