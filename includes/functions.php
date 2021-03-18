<?php
  include_once("db.php");

  /**
  * Build search query off of $_GET data
  *
  * @return string the SQL query used for the search, minus pagination parameters
  */
  function buildQuery() {
    global $conn;

    $getters = array(); // Contains all search parameters
    $queries = array(); // Contains all 'WHERE' parameters
    $orders = array(); // Contains SORT BY parameters
    $ptypes = array(); // List of ptypes from $_GET['ptype']
    $keywrd = array();
    $sortBy = (!empty($_GET['sortBy']) && in_array($_GET['sortBy'], ['relevance', 'datea', 'dated'])) ? $_GET['sortBy'] : 'relevance';
    $volume = (isset($_GET['vol'])) ? $_GET['vol'] : '1, 2, 3, 4, 5';
    $roleSwtch = (isset($_GET['roleSwitch']) && $_GET['roleSwitch'] === 'or') ? 'OR' : 'AND';
    $actSwtch = (isset($_GET['actSwitch']) && $_GET['actSwitch'] === 'or') ? 'OR' : 'AND';

    foreach($_GET as $key => $value) {
      $temp = is_array($value) ? $value : trim($value);
      if (!empty($temp)) {
        // Create ptype array to later implode to string
        if ($key === 'ptype') {
          foreach ($_GET['ptype'] as $type) {
            if (in_array($type, ['p', 'a', 'm', 'd', 'e', 's', 'b', 'i', 'o', 'u', 't'])) array_push($ptypes, "'".$type."'");
          }
        }
        // If it's a keyword, add to keyword array, otherwise add to $getters
        if ($key === 'keyword') {
          $keywrd[$key] = cleanStr($value);
        } elseif (!in_array($key, $getters)) {
          if ($key === 'performance' || $key === 'actor' || $key === 'role' || $key === 'keyword') {
            $getters[$key] = cleanStr($value);
          } else {
            $getters[$key] = $value;
          }
        }
      }
    }

    // Then add the keyword search last to minimize query time a bit
    if (!empty($keywrd)) {
      $getters['keyword'] = $keywrd['keyword'];
    }

    // Create ptypes string to be used in 'WHERE IN()' later
    $ptype_qry = '';
    if (!empty($ptypes)) {
      $ptype_qry = implode(",", $ptypes);
    }

    // Start our base query
    $sql = "SELECT Events.EventId, Events.EventDate, Events.Season, Events.Hathi, Events.CommentC, Events.TheatreId,
            Performances.PerformanceId, Performances.PerformanceOrder, Performances.PType, Performances.PerformanceTitle, Performances.CommentP, Performances.CastAsListed, Performances.DetailedComment,
            Cast.CastId, Cast.Role, Cast.Performer,
            Theatre.Volume, Theatre.TheatreName ";

    // Tack on Performance related parameters for relevance sorting
    if (!empty($_GET['performance'])) {
      $perfTemp = mysqli_real_escape_string($conn, $getters['performance']);  // Retains double quotes
      $perfClean = mysqli_real_escape_string($conn, cleanQuotes($getters['performance'], true)); // No double quotes for 'LIKE' search
      $perfCleanExact = mysqli_real_escape_string($conn, cleanQuotes($getters['performance'], false)); // No space wrap for exact search
      $sql .= ", (MATCH(PerfTitleClean) AGAINST ('$perfTemp' IN BOOLEAN MODE) + ";
      $sql .= " case when PerformanceTitle LIKE '$perfCleanExact' then 50 "; // Exact orig column gets highest rating
      $sql .= " when PerfTitleClean LIKE '$perfCleanExact' then 30 "; // Exact clean column match

      // Only really needed if the search has more than one word
      if (str_word_count(preg_replace("/[^A-Za-z0-9 ]/", ' ', $getters['performance'])) > 1) {
        $sql .= " when PerformanceTitle LIKE '%$perfCleanExact%' then 40 "; // Partial orig column wildcard
        $sql .= " when PerfTitleClean LIKE '%$perfCleanExact%' then 20 "; // Partial clean col wildcard
        $sql .= "when PerfTitleClean LIKE '%$perfClean%' then 10 "; // Partial clean col w/ surrounding spaces match gets lowest rating
      }
      $sql .= "else 0 end) as PerfScore ";

      // TODO case when MATCH(PerformanceTitle) AGAINST ('+love a la +mode' IN BOOLEAN MODE) then 100 end as PerfBetterScore";
    }

    $sql .= " FROM Events
              LEFT JOIN Performances ON Performances.EventId = Events.EventId
              LEFT JOIN Cast ON Cast.PerformanceId = Performances.PerformanceId
              LEFT JOIN Theatre ON Theatre.TheatreId = Events.TheatreId";

    // If author or keyword search, need diff SELECT values
    if (!empty($_GET['author']) || !empty($_GET['keyword'])) {
      $sql = "SELECT Events.EventId, Events.EventDate, Events.Season, Events.Hathi, Events.CommentC, Events.TheatreId,
              Performances.PerformanceId, Performances.PerformanceOrder, Performances.PType, Performances.PerformanceTitle, Performances.CommentP, Performances.CastAsListed, Performances.DetailedComment,
              Cast.CastId, Cast.Role, Cast.Performer,
              Theatre.Volume, Theatre.TheatreName,
              Works.WorkId,
              Author.AuthId, Author.AuthName";

      // Tack on Keyword related parameters for relevance sorting
      if (!empty($_GET['keyword'])) {
        $keyTemp = mysqli_real_escape_string($conn, $keywrd['keyword']);
        $sql .=", max(((MATCH(PerfTitleClean) AGAINST ('$keyTemp' IN NATURAL LANGUAGE MODE) + case when PerfTitleClean LIKE '%$keyTemp%' then 20 else 0.3 end)) +
                ((MATCH(CommentPClean) AGAINST ('$keyTemp' IN NATURAL LANGUAGE MODE) + case when CommentPClean LIKE '%$keyTemp%' then 20 else 0.3 end) * .75) +
                ((MATCH(CommentCClean) AGAINST ('$keyTemp' IN NATURAL LANGUAGE MODE) + case when CommentCClean LIKE '%$keyTemp%' then 20 else 0.3 end) * .75) +
                ((MATCH(RoleClean) AGAINST ('$keyTemp' IN NATURAL LANGUAGE MODE) + case when RoleClean LIKE '%$keyTemp%' then 20 else 0.3 end) +
                (MATCH(PerformerClean) AGAINST ('$keyTemp' IN NATURAL LANGUAGE MODE) + case when PerformerClean LIKE '%$keyTemp%' then 20 else 0.3 end)) +
                (MATCH(AuthNameClean) AGAINST ('$keyTemp' IN NATURAL LANGUAGE MODE) + case when AuthNameClean LIKE '%$keyTemp%' then 20 else 0.3 end)) as keyScore ";
      }

      // Tack on Performance related parameters for relevance sorting
      if (!empty($_GET['performance'])) {
        $perfTemp = mysqli_real_escape_string($conn, $getters['performance']);  // Retains double quotes
        $perfClean = mysqli_real_escape_string($conn, cleanQuotes($getters['performance'], true));  // No double quotes for 'LIKE' search
        $perfCleanExact = mysqli_real_escape_string($conn, cleanQuotes($getters['performance'], false)); // No space wrap for exact search
        $sql .= ", (MATCH(PerfTitleClean) AGAINST ('$perfTemp' IN BOOLEAN MODE) + ";
        $sql .= " case when PerformanceTitle LIKE '$perfCleanExact' then 50 "; // Exact orig column gets highest rating
        $sql .= " when PerfTitleClean LIKE '$perfCleanExact' then 30 "; // Exact clean column match

        // Only really needed if the search has more than one word
        if (str_word_count(preg_replace("/[^A-Za-z0-9 ]/", ' ', $getters['performance'])) > 1) {
          $sql .= " when PerformanceTitle LIKE '%$perfCleanExact%' then 40 "; // Partial orig column wildcard
          $sql .= " when PerfTitleClean LIKE '%$perfCleanExact%' then 10 "; // Partial clean col wildcard
          $sql .= "when PerfTitleClean LIKE '%$perfClean%' then 20 "; // Partial clean col w/ surrounding spaces match gets lowest rating
        }
        $sql .= "else 0 end) as PerfScore ";

        // TODO case when MATCH(PerformanceTitle) AGAINST ('+love a la +mode' IN BOOLEAN MODE) then 100 end as PerfBetterScore";
      }

      $sql .= " FROM Events
                LEFT JOIN Performances ON Performances.EventId = Events.EventId
                LEFT JOIN Cast ON Cast.PerformanceId = Performances.PerformanceId
                LEFT JOIN Theatre ON Theatre.TheatreId = Events.TheatreId
                LEFT JOIN Works ON Works.WorkId = Performances.WorkId
                LEFT JOIN WorkAuthMaster on WorkAuthMaster.WorkId = Works.WorkId
                LEFT JOIN Author on Author.AuthId = WorkAuthMaster.AuthId";
      }

      // Get our WHERE parameter for any selected date and add to $queries
      $dateQuery = getDateQuery();
      if ($dateQuery !== '') {
        array_push($queries, $dateQuery);
      }

      // Add $queries entry for each value in $getters
      if (!empty($getters)) {
        foreach ($getters as $key => $value) {
          ${$key} = $value;
          switch($key) {
            case 'theatre':
              if ($theatre !== 'all') {
                if(substr($theatre, 0, 3) === '111') {
                  $theatre = preg_replace('/[0-9;"`\~\!\@\#\$\%\^\&\*\<\>\[\]]/', '', $theatre); // Remove any numbers and special chars
                  $theatre = mysqli_real_escape_string($conn, $theatre);
                  array_push($queries, "Theatre.TheatreName LIKE '%$theatre%'");
                } else {
                  $theatre = preg_replace('/[0-9;"`\~\!\@\#\$\%\^\&\*\<\>\[\]]/', '', $theatre); // Remove any numbres and special chars
                  $theatre = mysqli_real_escape_string($conn, $theatre);
                  array_push($queries, "Theatre.TheatreName = '$theatre'");
                }
              }
            break;
            case 'volume':

              $volume = mysqli_real_escape_string($conn, $volume);
              if ($volume !== 'all' && in_array($volume, [1, 2, 3, 4, 5])) {
                array_push($queries, "Theatre.Volume = '$volume'");
              }
            break;
            case 'actor':
              $actQry = "(";
              $a = 1;
              $actor = array_filter($actor, 'strlen');
              if (count($actor) > 1 && $actSwtch === "AND") {
                $actQry .= getCastQuery('actor', $actor);
              } else {
                foreach($actor as $act) {
                  if ($act !== '') {
                    $actorClean = mysqli_real_escape_string($conn, cleanQuotes($act, true));
                    $act = mysqli_real_escape_string($conn, $act);
                    if ($a < count($actor)) {
                      $actQry .= "(MATCH(Cast.PerformerClean) AGAINST ('$act' IN NATURAL LANGUAGE MODE) OR Cast.PerformerClean LIKE '%$actorClean%') " . $actSwtch . " ";
                    } else {
                      $actQry .= "(MATCH(Cast.PerformerClean) AGAINST ('$act' IN NATURAL LANGUAGE MODE) OR Cast.PerformerClean LIKE '%$actorClean%')";
                    }
                  }
                  $a++;
                }
              }
              $actQry .= ")";
              if ($actQry !== "()") array_push($queries, $actQry);
            break;
            case 'role':
              $roleQry = "(";
              $r = 1;
              $role = array_filter($role, 'strlen');
              if (count($role) > 1 && $roleSwtch === "AND") {
                $roleQry .= getCastQuery('role', $role);
              } else {
                foreach($role as $rle) {
                  if ($rle !== '') {
                    $roleClean = mysqli_real_escape_string($conn, cleanQuotes($rle, true));
                    $rle = mysqli_real_escape_string($conn, $rle);
                    if ($r < count($role)) {
                      $roleQry .= "(MATCH(Cast.RoleClean) AGAINST ('$rle' IN NATURAL LANGUAGE MODE) OR Cast.RoleClean LIKE '%$roleClean%') " . $roleSwtch . " ";
                    } else {
                      $roleQry .= "(MATCH(Cast.RoleClean) AGAINST ('$rle' IN NATURAL LANGUAGE MODE) OR Cast.RoleClean LIKE '%$roleClean%')";
                    }
                  }
                  $r++;
                }
              }
              $roleQry .= ")";
              if($roleQry !== "()") array_push($queries, $roleQry);
            break;
            case 'performance':
              // Include ptype parameter if exists
              $typeStr = '';
              if (!empty($ptypes)) $typeStr = " AND Performances.PType IN ($ptype_qry)";
              $performanceClean = mysqli_real_escape_string($conn, cleanQuotes($performance, true));
              $performance = mysqli_real_escape_string($conn, $performance);
              array_push($queries, "((MATCH(PerfTitleClean) AGAINST ('$performance' IN BOOLEAN MODE) OR PerfTitleClean LIKE '%$performanceClean%') $typeStr)");
              array_push($orders, "PerfScore DESC");
            break;
            case 'ptype':
              // If 'performance title' or 'author' search, ptype parameter will be included in those queries, so exclude here
              if ((!array_key_exists('performance', $getters) || $getters['performance'] === '') && (!array_key_exists('author', $getters) || $getters['author'] === '')) {
                array_push($queries, "Events.EventId IN (SELECT Events.EventId from Events JOIN Performances on Performances.EventId = Events.EventId WHERE Performances.PType IN ($ptype_qry))");
              }
            break;
            case 'author':
              array_push($queries, getAuthorQuery($author, $ptype_qry));
            break;
            case 'keyword':
              $keywordClean = mysqli_real_escape_string($conn, cleanQuotes($keyword, true));
              $keyword = mysqli_real_escape_string($conn, $keyword);
              array_push($queries, " (MATCH(CommentCClean) AGAINST ('$keyword' IN NATURAL LANGUAGE MODE) OR CommentCClean LIKE '%$keywordClean%'
                  OR MATCH(PerfTitleClean) AGAINST ('$keyword' IN NATURAL LANGUAGE MODE) OR PerfTitleClean LIKE '%$keywordClean%'
                  OR MATCH(CommentPClean) AGAINST ('$keyword' IN NATURAL LANGUAGE MODE) OR CommentPClean LIKE '%$keywordClean%'
                  OR MATCH(RoleClean, PerformerClean) AGAINST ('$keyword' IN NATURAL LANGUAGE MODE) OR RoleClean LIKE '%$keywordClean%' OR PerformerClean LIKE '%$keywordClean%'
                  OR MATCH(AuthNameClean) AGAINST ('$keyword' IN NATURAL LANGUAGE MODE) OR AuthNameClean LIKE '%$keywordClean%') ");

              // Promote matches on Performance Titles and demote matches on Performance or Event Comments
              array_push($orders, " keyScore DESC");
            break;
          }
        }
      }

      // Add our WHERE statements to $sql
      if (!empty($queries)) {
      $sql .= " WHERE ";
      $i = 1;
      foreach($queries as $query) {
        if ($i < count($queries)) {
          $sql .= $query . ' AND ';
        } else {
          $sql .= $query;
        }
        $i++;
      }
    }

    // The results need to be grouped by Event to avoid redundancy
    $sql .= " GROUP BY Events.EventId ORDER BY ";

    // If sort by 'relevance', add SORT BYs for each $orders. Tack on Events.EventDate as secondary/default sort
    $sortOrder = ($sortBy === 'datea') ? 'ASC' : 'DESC';
    if ($sortBy === 'relevance') {
      if (!empty($orders)) {
        $cnt = 1;
        foreach($orders as $order) {
          if ($cnt < count($orders)) {
            $sql .= $order . ', ';
          } else {
            $sql .= $order . ', Events.EventDate';
          }
          $cnt++;
        }
      } else {
        $sql .= " Events.EventDate";
      }
    } else {
      $sql .= " Events.EventDate ";
      $sql .= $sortOrder;
    }

    return $sql;
  }

/**
 * Build search query off of $_GET data
 *
 * @param boolean $outputOnly
 *   If true, will only echo the generated query instead of run it.
 *
 * @return string the SQL query used for the search, minus pagination parameters
 */
  function buildSphinxQuery($outputOnly = false) {
    global $sphinx_conn;

    $getters = array(); // Contains all search parameters
    $queries = array(); // Contains all 'WHERE' parameters
    $orders = array(); // Contains SORT BY parameters
    $ptypes = array(); // List of ptypes from $_GET['ptype']
    $keywrd = array();
    $sortBy = (!empty($_GET['sortBy']) && in_array($_GET['sortBy'], ['relevance', 'datea', 'dated'])) ? $_GET['sortBy'] : 'relevance';
    $volume = (isset($_GET['vol'])) ? $_GET['vol'] : '1, 2, 3, 4, 5';
    $roleSwtch = (isset($_GET['roleSwitch']) && $_GET['roleSwitch'] === 'or') ? 'OR' : 'AND';
    $actSwtch = (isset($_GET['actSwitch']) && $_GET['actSwitch'] === 'or') ? 'OR' : 'AND';

    foreach($_GET as $key => $value) {
      $temp = is_array($value) ? $value : trim($value);
      if (!empty($temp)) {
        // Create ptype array to later implode to string
        if ($key === 'ptype') {
          foreach ($_GET['ptype'] as $type) {
            if (in_array($type, ['p', 'a', 'm', 'd', 'e', 's', 'b', 'i', 'o', 'u', 't'])) array_push($ptypes, "'".$type."'");
          }
        }
        // If it's a keyword, add to keyword array, otherwise add to $getters
        if ($key === 'keyword') {
          $keywrd[$key] = cleanStr($value);
        } elseif (!in_array($key, $getters)) {
          if ($key === 'performance' || $key === 'actor' || $key === 'role' || $key === 'keyword') {
            $getters[$key] = cleanStr($value);
          } else {
            $getters[$key] = $value;
          }
        }
      }
    }

    // Then add the keyword search last to minimize query time a bit
    if (!empty($keywrd)) {
      $getters['keyword'] = $keywrd['keyword'];
    }

    // Create ptypes string to be used in 'WHERE IN()' later
    $ptype_qry = '';
    if (!empty($ptypes)) {
      $ptype_qry = implode(",", $ptypes);
    }

    /*
     * The SELECT columns of a Sphinx query must be accompanied by an alias.
     * The identifiers of the index's columns match the mysql column names all-
     * lower cased.
     *
     * The original value of the select column list was written for MySQL's as
     * "TableName.ColumnName". The code below likely expects the original
     * column list's camelcasing for values lookup. So the original column list
     * was used to generate the map between index columnname and table
     * ColumnName: "SELECT Events.EventId, ... , Theatre.TheatreName" is now
     * "SELECT eventid AS EventId, ... , theatrename AS TheatreName".
     */
    $sql = "SELECT eventid AS EventId, eventdate AS EventDate, season AS Season, hathi AS Hathi, commentc AS CommentC, theatreid AS TheatreId,
            performanceid AS PerformanceId, performanceorder AS PerformanceOrder, ptype AS PType, performancetitle AS PerformanceTitle, commentp AS CommentP, castaslisted AS CastAsListed, detailedcomment AS DetailedComment,
            castid AS CastId, role AS Role, performer AS Performer,
            volume AS Volume, theatrename AS TheatreName ";

    /*
     * The logic for the original MySQL query related to the calculated value
     * "PerfScore" or "keyScore" are no longer needed because the way that
     * Sphinx does matching is more effective and the calculated match is not
     * needed any longer.
     *
     * The FROM statement for MySQL becomes the single index identifier in
     * Sphinx.
     */
    $sql .= " FROM london_stages";

    // If author or keyword search, need diff SELECT values
    if (!empty($_GET['author']) || !empty($_GET['keyword'])) {
      // See the mapping process from the old columnlist to index column list
      // which was used to generate the list below, in a comment above.
      $sql = "SELECT eventid AS EventId, eventdate AS EventDate, season AS Season, hathi AS Hathi, commentc AS CommentC, theatreid AS TheatreId,
              performanceid AS PerformanceId, performanceorder AS PerformanceOrder, ptype AS PType, performancetitle AS PerformanceTitle, commentp AS CommentP, castaslisted AS CastAsListed, detailedcomment AS DetailedComment,
              castid AS CastId, role AS Role, performer AS Performer,
              volume AS Volume, theatrename AS TheatreName,
              workid AS WorkId,
              authid AS AuthId, authname AS AuthName";

      /*
       * Because the keyScore and PerfScore relevance scores are no longer used
       * to sort the results, they have been dropped from the column list.
       *
       * The FROM table list is now just one index name.
       */
      $sql .= " FROM london_stages";

      // Get our WHERE parameter for any selected date and add to $queries
      $dateQuery = getDateQuery();
      if ($dateQuery !== '') {
        array_push($queries, $dateQuery);
      }

      // Add $queries entry for each value in $getters
      if (!empty($getters)) {
        foreach ($getters as $key => $value) {
          ${$key} = $value;
          switch ($key) {
            case 'theatre':
              if ($theatre !== 'all') {
                if (substr($theatre, 0, 3) === '111') {
                  $theatre = preg_replace('/[0-9;"`\~\!\@\#\$\%\^\&\*\<\>\[\]]/', '', $theatre); // Remove any numbers and special chars
                  $theatre = mysqli_real_escape_string($sphinx_conn, $theatre);
                  array_push($queries, "theatrename LIKE '%$theatre%'");
                } else {
                  $theatre = preg_replace('/[0-9;"`\~\!\@\#\$\%\^\&\*\<\>\[\]]/', '', $theatre); // Remove any numbres and special chars
                  $theatre = mysqli_real_escape_string($sphinx_conn, $theatre);
                  array_push($queries, "theatrename = '$theatre'");
                }
              }
              break;
            case 'volume':
              $volume = mysqli_real_escape_string($sphinx_conn, $volume);
              if ($volume !== 'all' && in_array($volume, [1, 2, 3, 4, 5])) {
                array_push($queries, "Theatre.Volume = '$volume'");
              }
              break;
            case 'actor':
              $actQry = "(";
              $a = 1;
              $actor = array_filter($actor, 'strlen');
              if (count($actor) > 1 && $actSwtch === "AND") {
                $actQry .= getCastQuery('actor', $actor);
              } else {
                foreach ($actor as $act) {
                  if ($act !== '') {
                    $actorClean = mysqli_real_escape_string($sphinx_conn, cleanQuotes($act, true));
                    $act = mysqli_real_escape_string($sphinx_conn, $act);
                    if ($a < count($actor)) {
                      $actQry .= "(MATCH(Cast.PerformerClean) AGAINST ('$act' IN NATURAL LANGUAGE MODE) OR Cast.PerformerClean LIKE '%$actorClean%') " . $actSwtch . " ";
                    } else {
                      $actQry .= "(MATCH(Cast.PerformerClean) AGAINST ('$act' IN NATURAL LANGUAGE MODE) OR Cast.PerformerClean LIKE '%$actorClean%')";
                    }
                  }
                  $a++;
                }
              }
              $actQry .= ")";
              if ($actQry !== "()") array_push($queries, $actQry);
              break;
            case 'role':
              $roleQry = "(";
              $r = 1;
              $role = array_filter($role, 'strlen');
              if (count($role) > 1 && $roleSwtch === "AND") {
                $roleQry .= getCastQuery('role', $role);
              } else {
                foreach ($role as $rle) {
                  if ($rle !== '') {
                    $roleClean = mysqli_real_escape_string($sphinx_conn, cleanQuotes($rle, true));
                    $rle = mysqli_real_escape_string($sphinx_conn, $rle);
                    if ($r < count($role)) {
                      $roleQry .= "(MATCH(Cast.RoleClean) AGAINST ('$rle' IN NATURAL LANGUAGE MODE) OR Cast.RoleClean LIKE '%$roleClean%') " . $roleSwtch . " ";
                    } else {
                      $roleQry .= "(MATCH(Cast.RoleClean) AGAINST ('$rle' IN NATURAL LANGUAGE MODE) OR Cast.RoleClean LIKE '%$roleClean%')";
                    }
                  }
                  $r++;
                }
              }
              $roleQry .= ")";
              if ($roleQry !== "()") array_push($queries, $roleQry);
              break;
            case 'performance':
              // Include ptype parameter if exists
              $typeStr = '';
              if (!empty($ptypes)) $typeStr = " AND ptype IN ($ptype_qry)";
              $performanceClean = mysqli_real_escape_string($sphinx_conn, cleanQuotes($performance, true));
              $performance = mysqli_real_escape_string($sphinx_conn, $performance);
              array_push($queries, "MATCH(\'@perftitleclean ' . $performance .'\') $typeStr')");
              // array_push($queries, "((MATCH(PerfTitleClean) AGAINST ('$performance' IN BOOLEAN MODE) OR PerfTitleClean LIKE '%$performanceClean%') $typeStr)");
              array_push($orders, "PerfScore DESC");
              break;
            case 'ptype':
              // If 'performance title' or 'author' search, ptype parameter will be included in those queries, so exclude here
              if ((!array_key_exists('performance', $getters) || $getters['performance'] === '') && (!array_key_exists('author', $getters) || $getters['author'] === '')) {
                array_push($queries, "Events.EventId IN (SELECT Events.EventId from Events JOIN Performances on Performances.EventId = Events.EventId WHERE Performances.PType IN ($ptype_qry))");
              }
              break;
            case 'author':
              array_push($queries, getAuthorQuery($author, $ptype_qry));
              break;
            case 'keyword':
              // $keywordClean = mysqli_real_escape_string($sphinx_conn, cleanQuotes($keyword, true));
              $keyword = mysqli_real_escape_string($sphinx_conn, $keyword);
              array_push($queries, ' MATCH(\'' . $keyword . '\') ');
              /*
               * Old keyword search clause.
                array_push($queries, " (MATCH(CommentCClean) AGAINST ('$keyword' IN NATURAL LANGUAGE MODE) OR CommentCClean LIKE '%$keywordClean%'
                  OR MATCH(PerfTitleClean) AGAINST ('$keyword' IN NATURAL LANGUAGE MODE) OR PerfTitleClean LIKE '%$keywordClean%'
                  OR MATCH(CommentPClean) AGAINST ('$keyword' IN NATURAL LANGUAGE MODE) OR CommentPClean LIKE '%$keywordClean%'
                  OR MATCH(RoleClean, PerformerClean) AGAINST ('$keyword' IN NATURAL LANGUAGE MODE) OR RoleClean LIKE '%$keywordClean%' OR PerformerClean LIKE '%$keywordClean%'
                  OR MATCH(AuthNameClean) AGAINST ('$keyword' IN NATURAL LANGUAGE MODE) OR AuthNameClean LIKE '%$keywordClean%') ");
              */

              // Promote matches on Performance Titles and demote matches on Performance or Event Comments
              // array_push($orders, " keyScore DESC");
              break;
          }
        }
      }

      // Add our WHERE statements to $sql
      if (!empty($queries)) {
        $sql .= " WHERE ";
        $i = 1;
        foreach ($queries as $query) {
          if ($i < count($queries)) {
            $sql .= $query . ' AND ';
          } else {
            $sql .= $query;
          }
          $i++;
        }
      }

    }
    // The results need to be grouped by Event to avoid redundancy
    $sql .= " GROUP BY eventid";

    // If sort by 'relevance', add SORT BYs for each $orders.
    // Tack on eventdate as secondary/default sort
    $sortOrder = ($sortBy === 'datea') ? 'ASC' : 'DESC';
    if ($sortBy !== 'relevance') {
      $sql .= " ORDER BY eventdate " . $sortOrder;
    }

    return $sql;
  }


  /**
  * Returns match counts for keyword searches. Columns are PerfCleanTitle,
  *  AuthNameClean, CommentPClean, CommentCClean, and RoleClean/PerformerClean.
  *
  * @param string $keyword Sanitizes keyword from $_GET
  *
  * @return array Array of match counts by column
  */
  function getResultsByColumn($keyword) {
    global $conn;
    $keywordClean = mysqli_real_escape_string($conn, cleanQuotes($keyword, true));
    $keywrd = mysqli_real_escape_string($conn, $keyword);
    $pcount = [];
    $acount = [];
    $pccount = [];
    $eccount = [];
    $ccount = [];
    $counts = [];

    $psql = "SELECT COUNT(*) AS count FROM Performances WHERE MATCH(PerfTitleClean) AGAINST ('$keywrd' IN NATURAL LANGUAGE MODE) OR PerfTitleClean LIKE '%$keywordClean%'";
    $asql = "SELECT COUNT(*) AS count FROM (SELECT Events.EventId FROM Events LEFT JOIN Performances ON Performances.EventId = Events.EventId LEFT JOIN Works ON Works.WorkId = Performances.WorkId LEFT JOIN WorkAuthMaster on WorkAuthMaster.WorkId = Works.WorkId LEFT JOIN Author on Author.AuthId = WorkAuthMaster.AuthId WHERE MATCH(AuthNameClean) AGAINST ('$keywrd' IN NATURAL LANGUAGE MODE) OR AuthNameClean LIKE '%$keywordClean%' GROUP BY Events.EventId) as qry";
    $pcsql = "SELECT COUNT(*) AS count FROM Performances WHERE MATCH(CommentPClean) AGAINST ('$keywrd' IN NATURAL LANGUAGE MODE) OR CommentPClean LIKE '%$keywordClean%'";
    $ecsql = "SELECT COUNT(*) AS count FROM Events WHERE MATCH(CommentCClean) AGAINST ('$keywrd' IN NATURAL LANGUAGE MODE) OR CommentCClean LIKE '%$keywordClean%'";
    $csql = "SELECT COUNT(*) AS count FROM Cast WHERE MATCH(RoleClean, PerformerClean) AGAINST ('$keywrd' IN NATURAL LANGUAGE MODE) OR RoleClean LIKE '%$keywordClean%' OR PerformerClean LIKE '%$keywordClean%'";

    $presult = $conn->query($psql);
    $aresult = $conn->query($asql);
    $pcresult = $conn->query($pcsql);
    $ecresult = $conn->query($ecsql);
    $cresult = $conn->query($csql);

    while ($row = $presult->fetch_assoc()) {
      $pcount[] = $row;
    }
    while ($row = $aresult->fetch_assoc()) {
      $acount[] = $row;
    }
    while ($row = $pcresult->fetch_assoc()) {
      $pccount[] = $row;
    }
    while ($row = $ecresult->fetch_assoc()) {
      $eccount[] = $row;
    }
    while ($row = $cresult->fetch_assoc()) {
      $ccount[] = $row;
    }

    $counts[] = array('col' => 'pcount', 'count' => $pcount[0]['count']);
    $counts[] = array('col' => 'acount', 'count' => $acount[0]['count']);
    $counts[] = array('col' => 'pccount', 'count' => $pccount[0]['count']);
    $counts[] = array('col' => 'eccount', 'count' => $eccount[0]['count']);
    $counts[] = array('col' => 'ccount', 'count' => $ccount[0]['count']);

    function cmp($a, $b)
    {
      return $a['count'] < $b['count'];
    }

    usort($counts, 'cmp');

    return $counts;
  }


  /**
  * Checks if only 'keyword' is filled out. Only show Results by Column if it's the only field.
  *
  * @return boolean
  */
  function onlyKeyword() {
    if (!isset($_GET['keyword']) || $_GET['keyword'] === '') return false;
    $actors = (isset($_GET['actor']) && array_filter($_GET['actor'], 'strlen')) ? array_filter($_GET['actor'], 'strlen') : [];
    $roles = (isset($_GET['role']) && array_filter($_GET['role'], 'strlen')) ? array_filter($_GET['role'], 'strlen') : [];
    $ptypes = (isset($_GET['ptype']) && array_filter($_GET['ptype'], 'strlen')) ? array_filter($_GET['ptype'], 'strlen') : [];
    if (isset($_GET['author']) && trim($_GET['author']) !== '') return false;
    if (isset($_GET['performance']) && trim($_GET['performance']) !== '') return false;
    if (isset($_GET['theatre']) && trim($_GET['theatre']) !== '' && $_GET['theatre'] !== 'all') return false;
    if (isset($_GET['volume']) && trim($_GET['volume']) !== '' && $_GET['volume'] !== 'all') return false;
    if (isset($_GET['start-year']) && trim($_GET['start-year']) !== '') return false;
    if (isset($_GET['end-year']) && trim($_GET['end-year']) !== '') return false;
    if (isset($_GET['actor']) && count($actors) > 0) return false;
    if (isset($_GET['role']) && count($roles) > 0) return false;
    if (isset($_GET['ptype']) && count($ptypes) > 0) return false;
    return true;
  }


  /**
  * Cleans string of all special chars except semicolons, spaces, and double quotes.
  *
  * Replaces '-' and '_' with a space. Replaces ” and “ with ". Then removes all
  *  chars not alphanumeric, space, double quotes, or semicolon.
  *
  * @param string $str Unsanitized string from $_GET
  *
  * @return string The cleaned string.
  */
  function cleanStr($str) {
    $string = str_replace('-', ' ', $str);
    $string = str_replace('_', ' ', $string);
    $string = str_replace('”', '"', $string);
    $string = str_replace('“', '"', $string);

    return preg_replace('/[^A-Za-z0-9 ;"]/', '', $string);
  }


  /**
  * Removes all double quotes from string
  *
  * @param string $str Cleaned string containing double quotes
  * @param boolean $wrap Denotes whether or not to wrap the returned
  *  string in spaces. For use in LIKE '% word %'.
  *
  * @return string The cleaned string with double quotes removed
  */
  function cleanQuotes($str, $wrap = false) {
    $hasQuotes = false;
    if (strpos($str, '"') !== false) $hasQuotes = true;

    $string = str_replace('"', '', $str);

    if ($wrap && $hasQuotes) {
      $string = " " . $string . " ";
    }
    return $string;
  }


  /**
  * Retrieves theatres from database and generates HTML for Theatre Select options
  */
  function getTheatres() {
    global $conn;

    $sql = 'SELECT * from Theatre GROUP BY TheatreName ORDER BY TheatreName';
    $result = $conn->query($sql);
    $output = [];

    echo '<optgroup label="Common Theatres">';
    echo '<option value="111Covent Garden"';
      getSticky(2, 'theatre', "111Covent Garden");
      echo '>Covent Garden (All)</option>';
    echo '<option value="111Drury Lane"';
      getSticky(2, 'theatre', "111Drury Lane");
      echo '>Drury Lane (All)</option>';
    echo '<option value="111Haymarket"';
      getSticky(2, 'theatre', "111Harmarket");
      echo '>Haymarket (All)</option>';
    echo '<option value="111Lincoln\'s Inn"';
      getSticky(2, 'theatre', "111Lincoln\'s Inn");
      echo '>Lincoln\'s Inn (All)</option>';
    echo '</optgroup>';
    echo '<option disabled>_________</option>';

    while ($row = $result->fetch_assoc()) {
      echo '<option value="' . $row['TheatreName'] . '"';
      getSticky(2, 'theatre', $row['TheatreName']);
      echo '>' . $row['TheatreName'] . '</option>';
    }

  }


  /**
  * Generates HTML for Year Select options
  */
  function getYears($yearType = 'start') {
    $start = 1659;

    for ($i=$start; $i <= 1800; $i++) {
      echo '<option value="' . $i . '"';
      getSticky(2, $yearType . '-year', $i);
      echo '>' . $i . '</option>';
    }
  }


  /**
  * Generates HTML for Months Select options
  */
  function getMonths($monthType = 'start') {
    for ($i=0; $i <= 12; $i++) {
      // substr('0' . $i, -2)
      echo '<option value="' . $i . '"';
      getSticky(2, $monthType . '-month', $i);
      echo '>' . $i . '</option>';
    }
  }


  /**
  * Generates HTML for Day Select options
  */
  function getDays($dayType = 'start') {
    for ($i=0; $i <= 31; $i++) {
      echo '<option value="' . $i . '"';
      getSticky(2, $dayType . '-day', $i);
      echo '>' . $i . '</option>';
    }
  }


  /**
  * Returns Theatre Name given an ID
  *
  * @param int $theatreId Theatre ID
  *
  * @return string Theatre Name
  */
  function getTheatreName($theatreId = '') {
    global $conn;
    if ($theatreId === '') return 'None';

    $sql = 'SELECT * FROM Theatre
            WHERE TheatreId = ' . $theatreId;

    $result = $conn->query($sql);
    $theatres = [];
    while ($row = mysqli_fetch_assoc($result)) {
      $theatres[] = $row;
    }
    return $theatres[0]['TheatreName'];
  }


  /**
  * Retrieves performances related to a given event
  *
  * Returns array of performances for a given event, including cast lists and author info
  *
  * @param int $eventId Event ID
  *
  * @return array Event Performances
  */
  function getPerformances($eventId = '') {
    global $conn;

    if ($eventId !== '') {
      $sql = 'SELECT * FROM Performances
              WHERE Performances.EventId = '. $eventId
              . ' ORDER BY Performances.PerformanceOrder';

      $result = $conn->query($sql);
      $perfs = [];
      while ($row = mysqli_fetch_assoc($result)) {
        $row['cast'] = getShortCastList($row['PerformanceId']);
        //$row['author'] = getAuthorInfo($row['WorkId']);
        $perfs[] = $row;
      }
      return $perfs;
    }

    return array();
  }


  /**
  * Returns array of works related to a given performance title
  *
  * Takes a given performance title [PerfTitleClean], splits it by semicolon,
  *  and performs wildcard searches for similar titles in Performances, Works,
  *  and WorksVariant tables
  *
  * @param string $perfTitle Cleaned performance title from [PerfTitleClean] column
  *
  * @return array Related Works
  */
  function getRelatedWorks($perfTitle = '') {
    // Return without looking up Related Works
    return '';
    global $conn;
    $prefix = "or ";
    $stopwords = ['[c|C]oncert[s]?', '[e|E]ntertainment[s]?'];
    $perfTitle =  preg_replace('/\b(' . implode('|', $stopwords) . ')\b/', '', $perfTitle);

    if ($perfTitle !== '') {
      $titles = array_map('trim', preg_split("[;|,]", $perfTitle));
      $sql = 'SELECT Works.*, WorksVariant.VariantName, WorkAuthMaster.Title as TheTitle, Performances.PerformanceTitle
        FROM Works LEFT JOIN WorksVariant ON WorksVariant.WorkId = Works.WorkId JOIN WorkAuthMaster ON WorkAuthMaster.WorkId = Works.WorkId LEFT JOIN Performances ON Performances.WorkId = Works.WorkId WHERE';

      $i = 1;
      foreach($titles as $perf) {
        $perf = cleanStr($perf);
        if (strtolower(substr($perf, 0, strlen($prefix))) == $prefix) {
          $perf = substr($perf, strlen($prefix));
        }
        if ($i < count($titles)) {
          $sql .= ' Works.TitleClean LIKE "' . $perf . '" OR Performances.PerfTitleClean LIKE "' . $perf . '" OR WorksVariant.NameClean LIKE "' . $perf . '" OR Works.Source1 LIKE "' . $perf . '" OR Works.Source2 LIKE "' . $perf . '" OR Works.SourceResearched LIKE "' . $perf . '" OR ';
        } else {
          $sql .= ' Works.TitleClean LIKE "' . $perf . '" OR Performances.PerfTitleClean LIKE "' . $perf . '" OR WorksVariant.NameClean LIKE "' . $perf . '" OR Works.Source1 LIKE "' . $perf . '" OR Works.Source2 LIKE "' . $perf . '" OR Works.SourceResearched LIKE "' . $perf . '" ';
        }
        $i++;
      }

      // Only want to show unique works, not all iterations of a given work title
      $sql .= ' GROUP BY Works.WorkId';

      $result = $conn->query($sql);
      $works = array();
      $sources = array();
      $workIds = array();
      while ($row = mysqli_fetch_assoc($result)) {
        $sources[] = $row['SourceResearched'];
        $sources[] = $row['Source1'];
        $sources[] = $row['Source2'];
        $row['author'] = getAuthorInfo($row['WorkId']);
        $works[] = $row;
        $workIds[] = $row['WorkId'];
      }

      $sources = array_filter($sources, 'strlen');

      // Get Work Sources and perform same search on them
      $sources = array_filter($sources, 'strlen');
      if (!empty($sources)) {
        $ssql = 'SELECT Works.*, WorksVariant.VariantName, WorkAuthMaster.Title as TheTitle, Performances.PerformanceTitle
          FROM Works LEFT JOIN WorksVariant ON WorksVariant.WorkId = Works.WorkId JOIN WorkAuthMaster ON WorkAuthMaster.WorkId = Works.WorkId LEFT JOIN Performances ON Performances.WorkId = Works.WorkId WHERE';

        $i = 1;
        foreach($sources as $source) {
          if ($i < count($sources)) {
            $ssql .= ' Works.TitleClean LIKE "' . $source . '" OR Performances.PerfTitleClean LIKE "' . $source . '" OR WorksVariant.NameClean LIKE "' . $perf . '" OR ';
          } else {
            $ssql .= ' Works.TitleClean LIKE "' . $source . '" OR Performances.PerfTitleClean LIKE "' . $source . '" OR WorksVariant.NameClean LIKE "' . $perf . '" ';
          }
          $i++;
        }
        $ssql .= ' GROUP BY Works.WorkId';
        $sresult = $conn->query($ssql);

        while ($srow = mysqli_fetch_assoc($sresult)) {
          if (!in_array($srow['WorkId'], $workIds)) {
            $srow['author'] = getAuthorInfo($srow['WorkId']);
            $works[] = $srow;
          }
        }
      }

      return $works;
    }
  }


  /**
  * Returns author information for a given work
  *
  * @param int $workId Work ID
  *
  * @return array Array of author info
  */
  function getAuthorInfo($workId = '') {
    global $conn;

    if ($workId !== '') {
      $sql = 'SELECT Author.*, WorkAuthMaster.AuthType FROM Author JOIN WorkAuthMaster ON WorkAuthMaster.AuthId = Author.AuthId
              WHERE WorkAuthMaster.WorkId = ' . $workId;

      $result = $conn->query($sql);
      $auths = [];
      while ($row = mysqli_fetch_assoc($result)) {
        $auths[] = $row;
      }

      return $auths;
    }
    return array();
  }


  /**
  * Determines type of date for a given author date
  *
  * @param string $type Author date type
  *
  * @return string
  */
  function authDateType($type = '') {
    $dateType = '';
    if ($type !== '') {
      switch($type) {
        case 'birth':
          $dateType = 'Birth:';
          break;
        case 'baptism':
          $dateType = 'Baptism:';
          break;
        case 'flourish':
          $dateType = 'Flourish:';
          break;
        case 'death':
          $dateType = 'Death:';
          break;
        default:
          $dateType = '';
      }
      return $dateType;
    }
    return '';
  }


  /**
  * Returns cast list for a given performance
  *
  * @param int $perfId Performance ID
  *
  * @return array Array of cast entries
  */
  function getShortCastList($perfId = '') {
    global $conn;

    if ($perfId !== '') {
      $sql = 'SELECT * FROM Cast
              WHERE Cast.PerformanceId = '. $perfId;

      $result = $conn->query($sql);
      $cast = [];
      while ($row = mysqli_fetch_assoc($result)) {
        $cast[] = $row;
      }
      return $cast;
    }
    return array();
  }


  /**
  * Generates sticky form values
  *
  * A sticky form is one that remembers how you filled it out. This function
  *  uses $_GET values to fill in the form so the user doesn't have to refill everything
  *
  * @param int $case Field type. 1 = text, 2 = select, 3 = checkbox, 4 = radio buttons,
  *  5 = text arrays.
  * @param string $par The $_GET parameter.
  * @param string $value Specific value for multi-option fields like checkboxes, etc.
  * @param string $initial Used to check an initial radio button.
  */
  function getSticky($case, $par, $value="", $initial="") {
    switch($case) {
      case 1: // text
        if (isset($_GET[$par]) && $_GET[$par] != "") {
          echo htmlentities(stripslashes($_GET[$par]));
        }
      break;
      case 2: // select
        if (isset($_GET[$par]) && $_GET[$par] === "") {} // Do nothing if empty
        else if (isset($_GET[$par]) && $_GET[$par] == $value) {
          echo ' selected="selected"';
        }
      break;
      case 3: // checkboxes
        if (isset($_GET[$par]) && $_GET[$par] !== '' && in_array($value, $_GET[$par])) {
          echo ' checked="checked"';
        }
      break;
      case 4: // radio buttons
        if (isset($_GET[$par]) && $_GET[$par] == $value) {
          echo ' checked="checked"';
        } else {
          if ($initial !="") {
            echo ' checked="checked"';
          }
        }
      break;
      case 5: // text arrays
        if (isset($_GET[$par]) && $_GET[$par] !== '' && in_array($value, $_GET[$par])) {
          return htmlentities(stripslashes($value));
        }
      break;
    }
  }


  /**
  * Takes a date and formats into human-readable 'd F Y' or 'F Y' if no day given
  *
  * @param int $theDate Date from Events table.
  * @param boolean $plain Include HTML elements or not.
  *
  * @return string Formatted date and surrounding HTML
  */
  function formatDate($theDate = '', $plain = false) {
    if ($theDate !== '') {
      $formatted_date = preg_replace("/^(\d{4})(\d{2})(\d{2})$/", "$1-$2-$3", $theDate);

      // If is a 'zero-date', only return month and year
      if (substr($formatted_date, -2) === '00') {
        $formatted_date = substr($formatted_date, 0, -2) . '01';
        $newTime = strtotime($formatted_date);
        $newDate = date('F Y', $newTime);
        if ($plain) {
          $newFormatted = substr($newDate, 0, -4) . substr($newDate, -4);
        } else {
          $newFormatted = '<span class="evt-date">' . substr($newDate, 0, -4) . substr($newDate, -4) . '</span>';
        }
        return $newFormatted;
      } else {
        $newTime = strtotime($formatted_date);
        $newDate = date('d F Y', $newTime);
        if ($plain) {
          $newFormatted = substr($newDate, 0, -4) . substr($newDate, -4);
        } else {
          $newFormatted = '<span class="evt-date">' . substr($newDate, 0, -4) . substr($newDate, -4) . '</span>';
        }
        return $newFormatted;
      }
    }
    return '';
  }


  /**
  * Generates date WHERE statement.
  *
  * @return string Date WHERE query.
  */
  function getDateQuery() {
    global $conn;
    $sql = "";
    $monSet = true;
    $daySet = true;
    $dateTp = filter_input(INPUT_GET, 'date-type', FILTER_SANITIZE_NUMBER_INT);
    $startYr = filter_input(INPUT_GET, 'start-year', FILTER_SANITIZE_NUMBER_INT);
    $startMon = filter_input(INPUT_GET, 'start-month', FILTER_SANITIZE_NUMBER_INT);
    $startDay = filter_input(INPUT_GET, 'start-day', FILTER_SANITIZE_NUMBER_INT);
    $endYr = filter_input(INPUT_GET, 'end-year', FILTER_SANITIZE_NUMBER_INT);
    $endMon = filter_input(INPUT_GET, 'end-month', FILTER_SANITIZE_NUMBER_INT);
    $endDay = filter_input(INPUT_GET, 'end-day', FILTER_SANITIZE_NUMBER_INT);

    // 1=Between, 2=Before, 3=On, 4=After
    if (!isset($dateTp) || !in_array($dateTp, [1,2,3,4])) {
      $dateTp = 1;
    }

    // If no date defined, don't return a query
    if (in_array($dateTp, [2,3,4]) && !isset($startYr)) return '';
    if ((!isset($startYr) || $startYr === '') && (!isset($endYr) || $endYr === '')) {
      return '';
    }

    // Set defaults if not in $_GET info or outside limits
    if (!isset($startYr) || $startYr < 1659 || $startYr > 1800) $startYr = 1659;
    if (!isset($endYr) || $endYr < 1659 || $endYr > 1800) $endYr = 1800;
    if (!isset($startMon) || $startMon === '' || ($startMon < 0 || $startMon > 12)) {$startMon = 0; $monSet = false;}
    if (!isset($startDay) || $startDay === '' || ($startDay < 0 || $startDay > 31)) {$startDay = 0; $daySet = false;}
    if (!isset($endMon) || $endMon === '' || ($endMon < 0 || $endMon > 12)) $endMon = 12;
    if (!isset($endDay) || $endDay === '' || ($endDay < 0 || $endDay > 31)) $endDay = 31;

    $startStr = $startYr . substr('0' . $startMon, -2) . substr('0' . $startDay, -2);
    $endStr = $endYr . substr('0' . $endMon, -2) . substr('0' . $endDay, -2);

    switch($dateTp) {
      case 1: // Between
        $sql = "Events.EventDate BETWEEN $startStr AND $endStr";
        break;
      case 2: // Before
        $sql = "Events.EventDate <= $startStr";
        break;
      case 3: // On
        // If zero date (e.g. 17161100), run LIKE '171611%'
        if ($daySet === false) {
          // If zero month (e.g. 17160000), run LIKE '1716%'
          if ($monSet === false) {
            $sql = "Events.EventDate LIKE '" . $startYr . "%'";
          } else {
            $sql = "Events.EventDate LIKE '" . $startYr . substr('0' . $startMon, -2) . "%'";
          }
        } else { // Else exact match
          $sql = "Events.EventDate = $startStr";
        }
        break;
      case 4: // After
        $sql = "Events.EventDate >= $startStr";
        break;
    }

    return $sql;
  }


  /**
  * Takes an author's name and generates WHERE statement from all related titles.
  *
  * Takes an author's name and performes two preliminary queries to get all work
  *  titles associated with that author, as well as any other work title that
  *  is similar or has a variant title that is similar. The list of found titles
  *  is then returned as a bunch of WHERE LIKE '%<title>%' statements to get any
  *  performance with a similar title. The idea is to introduce ambiguity and
  *  cast as wide a net as possible because we don't know for sure who authored
  *  the version of the play that was performed on any given night.
  *
  * @param string $author Cleaned author name from $_GET.
  * @param string $ptype_qry List of selected ptypes to be used in WHERE IN ().
  *
  * @return string Author WHERE statement.
  */
  function getAuthorQuery($author, $ptype_qry = '') {
    global $conn;

    if ($author === '') return '';

    $author = cleanStr($author);
    $authorClean = mysqli_real_escape_string($conn, cleanQuotes($author));
    $author = mysqli_real_escape_string($conn, $author);

    // If there are ptypes, generate statement
    $typeStr = '';
    if ($ptype_qry !== '') $typeStr = " Performances.PType IN ($ptype_qry) AND ";

    // Look for related titles in the Works table
    $workIdSql = "SELECT Works.TitleClean FROM Works WHERE Works.WorkId IN (
          SELECT WorkAuthMaster.WorkId FROM WorkAuthMaster
          JOIN Works ON Works.WorkId = WorkAuthMaster.WorkId
          LEFT JOIN WorksVariant ON WorksVariant.WorkId = Works.WorkId
          WHERE WorkAuthMaster.TitleClean IN (
            SELECT WorkAuthMaster.TitleClean FROM WorkAuthMaster
            LEFT JOIN Author ON Author.AuthId = WorkAuthMaster.AuthId
            WHERE (Author.AuthNameClean LIKE '%$authorClean%') ) ";
            $workIdSql .= " OR WorksVariant.NameClean IN (
              SELECT WorkAuthMaster.TitleClean FROM WorkAuthMaster
              LEFT JOIN Author ON Author.AuthId = WorkAuthMaster.AuthId
              WHERE (Author.AuthNameClean LIKE '%$authorClean%')
            )
          )";

    // look for related titles in the WorksVariant table
    $varIdSql = "SELECT WorksVariant.NameClean FROM WorksVariant WHERE WorksVariant.WorkId IN (
          SELECT WorkAuthMaster.WorkId FROM WorkAuthMaster
          JOIN Works ON Works.WorkId = WorkAuthMaster.WorkId
          LEFT JOIN WorksVariant ON WorksVariant.WorkId = Works.WorkId
          WHERE WorkAuthMaster.TitleClean IN (
            SELECT WorkAuthMaster.TitleClean FROM WorkAuthMaster
            LEFT JOIN Author ON Author.AuthId = WorkAuthMaster.AuthId
            WHERE (Author.AuthNameClean LIKE '%$authorClean%') ) ";
            $varIdSql .= " OR WorksVariant.NameClean IN (
              SELECT WorkAuthMaster.TitleClean FROM WorkAuthMaster
              LEFT JOIN Author ON Author.AuthId = WorkAuthMaster.AuthId
              WHERE (Author.AuthNameClean LIKE '%$authorClean%')
            )
          )";

    $workIdResult = $conn->query($workIdSql);

    $workIds = [];
    while ($row = mysqli_fetch_assoc($workIdResult)) {
      $workIds[] = $row['TitleClean'];
    }

    $varIdResult = $conn->query($varIdSql);
    while ($row = mysqli_fetch_assoc($varIdResult)) {
      $workIds[] = $row['NameClean'];
    }

    // Unique list of titles from both queries
    $titles = array_unique($workIds);

    if (empty($workIds)) return '0';

    $sql = " ";

    // Include ptype statement
    $sql .= "$typeStr (";

    // Some titles actually contain multiple titles, separated by a semicolon or
    //  '; or '. We'll trim and explode out the title on the semicolon, and $prefix is used
    //  to strip out the 'or '
    $prefix = "or ";

    $i = 1;
    foreach ($titles as $title) {
      $titleArr = array_map('trim', explode(';', $title));
      if ($i < count($titles)) {
        foreach ($titleArr as $titl) {
          // Check for 'or ' prefix and strip it
          if (strtolower(substr($titl, 0, strlen($prefix))) == $prefix) {
            $titl = substr($titl, strlen($prefix));
          }
          $sql .= " Performances.PerfTitleClean LIKE \"%$titl%\" OR ";
        }
      } else {
        $j = 1;
        foreach ($titleArr as $titl) {
          // Check for 'or ' prefix and strip it
          if (strtolower(substr($titl, 0, strlen($prefix))) == $prefix) {
            $titl = substr($titl, strlen($prefix));
          }
          if ($j < count($titleArr)) {
            $sql .= " Performances.PerfTitleClean LIKE \"%$titl%\" OR ";
          } else {
            $sql .= " Performances.PerfTitleClean LIKE \"%$titl%\" ";
          }
          $j++;
        }
      }
      $i++;
    }

    $sql .= ") ";

    return $sql;
  }


  /**
  * Takes arrays of actors or roles and generates the necessary WHERE statement for AND searches.
  *
  * Takes an array of actors or roles and runs queries to find events that have both.
  *
  * @param string $castType Either Role or Actor.
  * @param array $casts Array of actors or roles to search on.
  *
  * @return string Cast WHERE statement (Where IN() list of Event Ids).
  */
  function getCastQuery($castType, $casts = array()) {
    global $conn;

    if (empty($casts)) return '';

    $allIds = array();
    $intersectIds = array();

    $type = ($castType === 'role') ? 'Role' : 'Performer';

    $sql = "SELECT Events.EventId FROM Events
      JOIN Performances ON Performances.EventId = Events.EventId
      JOIN Cast ON Cast.PerformanceId = Performances.PerformanceId
      WHERE ";

    $i = 1;
    foreach ($casts as $cast) {
      if ($cast !== '') {
        $qry = $sql;
        $castClean = mysqli_real_escape_string($conn, cleanQuotes($cast, true));
        $cast = mysqli_real_escape_string($conn, $cast);
        $qry .= "MATCH(Cast." . $type . "Clean) AGAINST ('\"$cast\" @4' IN BOOLEAN MODE) OR Cast." . $type . "Clean LIKE '%$castClean%' GROUP BY Events.EventId";

        $castResult = $conn->query($qry);

        $eventIds = array();
        while ($row = mysqli_fetch_assoc($castResult)) {
          $eventIds[] = $row['EventId'];
        }
        $allIds[] = $eventIds;
      }
      $i++;
    }

    $initial = $allIds[0];
    for($a = 1; $a < count($allIds); $a++) {
      foreach($allIds[$a] as $id) {
        if (in_array($id, $initial)) $intersectIds[] = $id;
      }
    }

    if (count($intersectIds) <= 0) return '';

    $finQry = " Events.EventId IN (" . implode(',', $intersectIds) . ") ";
    return $finQry;
  }


  /**
  * Takes a ptype code and returns the full type string.
  *
  * @param string $ptype Ptype code.
  *
  * @return string Full ptype string.
  */
  function getPType($ptype = '') {
    if ($ptype !== '') {
      switch($ptype) {
        case 'p':
          return 'Mainpiece';
        break;
        case 'a':
          return 'Afterpiece';
        break;
        case 'm':
          return 'Music';
        break;
        case 'd':
          return 'Dance';
        break;
        case 'e':
          return 'Entertainment';
        break;
        case 's':
          return 'Song';
        break;
        case 'b':
          return 'Ballet';
        break;
        case 'i':
          return 'Instrumental';
        break;
        case 'o':
          return 'Opera';
        break;
        case 'u':
          return 'Monologue';
        break;
        case 't':
          return 'Trick';
        break;
      }
    }
    return 'Performance';
  }


  /**
  * Returns 'Your Search' info for results page
  *
  * @return string
  */
  function yourSearch() {
    $yourSearch = '';
    if (!empty($_GET['keyword'])) $yourSearch .= '<span class="your-search-item">Keyword - ' . htmlentities($_GET['keyword']) . '</span>';
    if (!empty($_GET['performance'])) $yourSearch .= '<span class="your-search-item">Title - ' . htmlentities($_GET['performance']) . '</span>';
    if (!empty(array_filter($_GET['actor'], 'strlen'))) $yourSearch .= '<span class="your-search-item">Actors - ' . htmlentities(implode(', ', array_filter($_GET['actor'], 'strlen'))) . '</span>';
    if (!empty(array_filter($_GET['role'], 'strlen'))) $yourSearch .= '<span class="your-search-item">Roles - ' . htmlentities(implode(', ', array_filter($_GET['role'], 'strlen'))) . '</span>';
    if (!empty($_GET['author'])) $yourSearch .= '<span class="your-search-item">Author - ' . htmlentities($_GET['author']) . '</span>';

    return (!empty($yourSearch)) ? '<span class="your-search-for"> for: </span><span class="your-search-items">' . $yourSearch . '</span>' : '';
  }


  /**
  * Takes an eventId and returns an Event
  *
  * @param int $eventId Event ID.
  *
  * @return object Event.
  */
  function getEvent($eventId = 1) {
    global $conn;

    $sql = "SELECT * FROM Events WHERE EventId=" . $eventId;
    $result = $conn->query($sql);
    $event = [];

    while ($row = mysqli_fetch_assoc($result)) {
      $event[] = $row;
    }
    if (count($event) > 0) {
      return $event[0];
    } else {
      return $event;
    }

  }


  /**
  * Takes an eventId and returns phase III data.
  *
  * @param int $eventId Event ID.
  *
  * @return array Phase III Event data.
  */
  function getPhaseIII($eventId = 0) {
    global $conn;

    if ($eventId === 0) return array();

    $phaseIII = array();
    $phaseIII['event'] = '';
    $phaseIII['perfs'] = array();
    $eventSql = "SELECT EventId, EventDate, TheatreCode, Hathi, CommentC FROM Events WHERE EventId=" . $eventId;
    $perfSql = "SELECT PerformanceId, EventId, PType, PerformanceTitle, CommentP FROM Performances WHERE EventId=" . $eventId;
    $asSeeSql = "SELECT * FROM AsSeeDate WHERE PerformanceId=";
    $castSql = "SELECT CastId, PerformanceId, Role, Performer FROM Cast WHERE PerformanceId=";

    $eResult = $conn->query($eventSql);
    while ($row = mysqli_fetch_assoc($eResult)) {
      $phaseIII['event'] = implode(' | ', array_filter($row, 'strlen'));
    }

    if (count($phaseIII) > 0) {
      $pResult = $conn->query($perfSql);
      while ($row = mysqli_fetch_assoc($pResult)) {
        $tempId = $row['PerformanceId'];
        $tempPerf = array();
        $tempPerf['info'] = implode(' | ', array_filter($row, 'strlen'));

        $asSeeResult = $conn->query($asSeeSql . $tempId);
        $tempPerf['asSee'] = array();
        while ($asRow = mysqli_fetch_assoc($asSeeResult)) {
          array_push($tempPerf['asSee'], implode(' | ', array_filter($asRow, 'strlen')));
        }

        $castResult = $conn->query($castSql . $tempId);
        $tempPerf['cast'] = array();
        while ($castRow = mysqli_fetch_assoc($castResult)) {
          array_push($tempPerf['cast'], implode(' | ', array_filter($castRow, 'strlen')));
        }

        array_push($phaseIII['perfs'], $tempPerf);
      }
    } else {
      return array();
    }

    return $phaseIII;
  }


  /**
  * Takes some HTML text and some words, highlights the words in the text
  *
  * Searches through a block of HTML text for a set of words and surrounds those words
  *  with HTML that causes them to be highlighted on the page. A text block could
  *  be anything from a performance title to cast name to event comment. The words
  *  are a pipe-delimited string of terms the user searched for.
  *
  * @param string $text Text block that may contains words that need highlighting.
  * @param string $words Pipe delimited string of words that need highlighting.
  *  Words have quotes removed.
  *
  * @return string Text block with highlighted words.
  */
  function highlight($text, $words) {
    $words = trim($words);
    if ($words === '') return $text;

    preg_match_all('~[^|]+~', $words, $m); // Array of | delimited phrases
    preg_match_all('~[^| ]+~', $words, $n); // Array of each word in each phrase
    $cleaned = array_map('cleanStr', $n);
    $allWords = array_unique(array_merge($m[0], $n[0], $cleaned[0])); // Combine unique
    if(!$allWords || count($allWords) === 0) {
        return $text;
    }

    // We only want to highlight words or parts of words longer than 2 characters
    $allWords = array_filter($allWords, function($werd) {
      return strlen($werd) > 2;
    });

    // Only match outside of angle brackets <>. Don't want HTML in our hrefs!
    $re = '/<[^>]*>(*SKIP)(*F)|' . implode('|', $allWords) . '/i';
    return preg_replace($re, '<span class="highlight">$0</span>', $text);
  }


  /**
  * Takes some text and some words, determines if words are found in text
  *
  * @param string $text Text block that may contains words.
  * @param string $words Pipe delimited string of words to search for.
  *  Words have quotes removed.
  *
  * @return string
  */
  function isFoundIn($text, $words) {
    $words = trim($words);
    if ($words === '' || $text === '') { return false; }

    preg_match_all('~[^|]+~', $words, $m); // Array of | delimited phrases
    preg_match_all('~[^| ]+~', $words, $n); // Array of ' ' delimited phrases
    $allWords = array_unique(array_merge($m[0], $n[0])); // Combine unique
    if(!$allWords || count($allWords) === 0) {
        return false;
    }

    // We only want to find words or parts of words longer than 2 characters
    $allWords = array_filter($allWords, function($werd) {
      return strlen($werd) > 2;
    });

    $re = '/\\w*?' . implode('|', $allWords) . '\\w*/i';
    if(!preg_match($re, $text)) {
      return false;
    }
    else {
      return true;
    }
  }


  /**
  * Determines if any of the related search terms are found in cast list.
  *
  * On the results page, we only want to show the cast members that match the
  *  keyword, actor, or role search terms. This function finds the matches and
  *  returns a cast list array to output.
  *
  * @param string $actorSearch Pipe-delimited string containing keyword and actor
  *  search terms. Words have quotes removed.
  * @param string $roleSearch Pipe-delimited string containing keyword and role
  *  search terms. Words have quotes removed.
  * @param array $cast Array of all cast members associated with an event.
  *
  * @return array Array of cast members that match either they keyword, actor, or
  *  role search terms.
  */
  function isInCast($actorSearch, $roleSearch, $cast) {
    $castMatch = [];
    $actorSearch = trim($actorSearch);
    $roleSearch = trim($roleSearch);
    if ((str_replace('|', '', $actorSearch) === '' || $actorSearch === '|') && (str_replace('|', '', $roleSearch) === '' || $roleSearch === '|')) { return false; }
    if (count($cast) <= 0) { return false; }

    preg_match_all('~[^|]+~', $actorSearch, $am); // Actor search terms | delimited array
    preg_match_all('~[^| ]+~', $actorSearch, $an); // Actor search terms ' ' delimited array
    preg_match_all('~[^|]+~', $roleSearch, $rm); // Role search terms | delimited array
    preg_match_all('~[^| ]+~', $roleSearch, $rn); // Role search terms ' ' delimited array
    $allActors = array_unique(array_merge($am[0], $an[0])); // Combine unique
    $allRoles = array_unique(array_merge($rm[0], $rn[0])); // Combine unique
    if((!$allActors || count($allActors) === 0) && (!$allRoles || count($allRoles === 0))) {
        return false;
    }

    // We only want to find words or parts of words longer than 2 characters
    $allActors = array_filter($allActors, function($werd) {
      return strlen($werd) > 2;
    });
    $allRoles = array_filter($allRoles, function($werd) {
      return strlen($werd) > 2;
    });

    $re_a = '/\\w*?' . implode('|', $allActors) . '\\w*/i'; // Actor regex
    $re_r = '/\\w*?' . implode('|', $allRoles) . '\\w*/i'; // Role regex

    foreach ($cast as $mem) {
      if (($actorSearch !== '' && $actorSearch !== '|' && preg_match($re_a, $mem['Performer'])) || ($roleSearch !== '' && $roleSearch !== '|' && preg_match($re_r, $mem['Role']))) {
        $castMatch[] = $mem;
      }
    }
    if (count($castMatch) > 0) {
      return $castMatch;
    }
    return false;
  }

  /**
  * Takes a string and removes words not near a highlighted word
  *
  * @param string $text String to be cut down
  *
  * @return string
  */
  function cutString($string) {
    if (strpos($string, 'highlight') === false) return $string;

    $numChars = 25; // Number of characters around the highlighted word to keep
    $needle = 'highlight';
    $lastPos = 0;
    $positions = array();
    $finalString = '';
    $startDisregard = 13; // Num chars to subtract from positions before counting the 25. (for <span class=" )

  //https://stackoverflow.com/questions/1193500/truncate-text-containing-html-ignoring-tags

    // Make array of tag objects
    // arr[0] = {tag: 'a', tagStart: 12, tagEnd: 33};
    // Then, if 'highlight' is found between a tag start and end, +/- $numChars from its tag values unless it reaches another tagEnd first.
    // So find all tags that 'highlight' is inside. Take $numChars from the outside tag unless run into another tag first.

/*    $html = mb_convert_encoding($string, "HTML-ENTITIES", 'UTF-8');

 $dom = new domDocument;
 $dom->preserveWhiteSpace = false;
 $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

 $contents = $dom->getElementsByTagName('a');*/ // Array of Content
 // https://www.techfry.com/php-tutorial/html-basic-tags

    while (($lastPos = strpos($string, $needle, $lastPos))!== false) {
      $positions[] = $lastPos;
      $lastPos = $lastPos + strlen($needle);
    }

    $i = 1;
    foreach ($positions as $pos) {
      $startPos = (($pos - $startDisregard) - $numChars < 0) ? 0 : ($pos - $startDisregard) - $numChars;
      $finalPos = (($pos + $startDisregard) + $numChars > strlen($string)) ? strlen($string) : ($pos + $startDisregard) + $numChars;
      if ($i === 1 && $startPos !== 0) $finalString .= '... ';
      $finalString .= substr($string, $startPos, $finalPos-$startPos);
      if ($i < count($positions) && $finalPos !== strlen($string)) $finalString .= ' ... ';
      $i++;
    }

    return $string . ' END ' . $finalString;
  }


/**
 * Takes some text and creates links for any instances of $name=
 *
 * In the 1970s database, they wrapped many names in $=. We want to find these and
 *  create links that take the user to a keyword search for that person.
 *
 * @param string $text Text block that may contains named entities ($name=)
 * @param bool $sphinx_results When set to true, will like to sphinx results.
 *
 * @return string HTML Text block with named entities linked out to keyword searches
 */
  function namedEntityLinks($text, $sphinx_results = false) {
    $text = trim($text);
    if ($text === "") return '';

    $re = '/(\$)([\s\S]+)(=)([^\"]*)/U'; // Matches $name=

    return $sphinx_results ?
        preg_replace($re, '<a href="/sphinx-results.php?keyword=$2">$2$4</a>', $text) :
        preg_replace($re, '<a href="/results.php?keyword=$2">$2$4</a>', $text);
  }


/**
 * Creates a link out to a new search for a given key.
 *
 * Creates crosslinks for a given key out to a search for that key. So, a key of
 *  'actor' will take the value and link to a search for ?actor=value.
 *
 * @param string $key The key for which we will create a search link.
 * @param string $value The value for which to be searched.
 * @param bool $sphinx_results When set to true, will like to sphinx results.
 *
 * @return string HTML Text block with values linked out to relevant searches.
 */
  function linkedSearches($key, $value, $sphinx_results = false) {
    $value = trim($value);
    if ($value === '') return '';

    // Clean the value string up a bit. Remove '$, |, =, *'. Change brackets to HTML entities.
    $value = preg_replace('/[\[\]]/', '&rbrack;', strip_tags(preg_replace('/[\$|=\*\/]/', '', $value)));
    preg_match_all('~[^,]+~', $value, $m);

    foreach($m[0] as $k => $val) {
      $m[0][$k] = trim($val);
    }

    $re = '/' . implode('|', $m[0]) . '/i';
    //return preg_replace($re, '<a href="results.php?'.preg_replace('/[\[\]]/', '&rbrack;', $key).'=$0">$0</a>', $value);
    return $sphinx_results ?
        preg_replace($re, '<a href="sphinx-results.php?'.$key.'=$0">$0</a>', $value) :
        preg_replace($re, '<a href="results.php?'.$key.'=$0">$0</a>', $value);
  }


  /**
  * Generates href for performance title link
  *
  * @param string $value The title to be linked.
  * @param bool $sphinx_results When set to true, will like to sphinx results.
  *
  * @return string href value.
  */
  function linkedTitles($value, $sphinx_results = false) {
    $value = trim($value);
    if ($value === '') return '';

    $value = strip_tags(htmlentities($value));

    return $sphinx_results ?
        '/sphinx-results.php?performance=' . $value :
        '/results.php?performance=' . $value;
  }


  /**
  * Takes title and removes '$' and '='.
  *
  * Removes '$' and '=' from Titles, as they are only used for links,
  *  but we can't place a namedEntity link within a Title link.
  *
  * @param string $title Title to be cleaned.
  *
  * @return string Cleaned title.
  */
  function cleanTitle($title) {
    if ($title === '') return '';

    $cleaned = str_replace(array('$', '='), '', $title);

    return $cleaned;
  }


  /**
  * Takes text block and repairs any faulty HTML
  *
  * In the data, there are many opening italics HTML tags that didn't have a
  *  closing tag. This fixes that.
  *
  * @param string $title Title HTML to be fixed.
  *
  * @return string
  */
  function cleanItalics($title) {
    if ($title === '') return '';

    $tidy = new tidy();
    $clean = $tidy->repairString($title, array('show-body-only' => true));
    return $clean;
  }


  /**
  * Creates XML download for a single event
  *
  * @param int $id ID of Event to be XML'ed.
  */
  function getXML($id) {
    $event = getEvent($id);
    if (empty($event)) {
      $event['error'] = 'No such event.';
      $filename = 'error';
    } else {
      $filename = $id;
      $event['Performances'] = getPerformances($event['EventId']);
    }

    $xml_data = new SimpleXMLElement('<?xml version="1.0"?><data></data>');

    function array_to_xml( $data, &$xml_data, $pkey = '' ) {
      foreach ( $data as $key => $value ) {
          if( is_numeric($key) ){
              if ($pkey === 'performances') $key = 'performance-'.$key;
              else if ($pkey === 'cast') $key = 'cast-'.$key;
              else
                $key = 'item-'.$key; //dealing with <0/>..<n/> issues
          }
          $key = strtolower($key);
          if( is_array($value) ) {
              $subnode = $xml_data->addChild($key);
              array_to_xml($value, $subnode, $key);
          } else {
              $xml_data->addChild("$key",htmlspecialchars(utf8_for_xml("$value")));
          }
       }
     }

    array_to_xml($event,$xml_data);
    $xml = $xml_data->asXML();

    // Set headers so file automatically downloads
    header('Content-disposition: attachment; filename=' . $filename . '.xml');
    header('Content-type: text/xml');
    echo $xml;
  }


  /**
  * Creates XML download for all search results
  *
  * @param array $ids Array of IDs of Events to be XML'ed.
  */
  function getResultsXML($ids = []) {
    global $conn;
    $results = [];
    $events = [];
    $filename = 'results_XML';

    // Get Event info
    foreach ($ids as $id) {
      $event = getEvent($id);
      $event['Performances'] = getPerformances($id);
      $events[] = $event;
    }

    $xml_data = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><data></data>');

    function array_to_xml( $data, &$xml_data, $pkey = '' ) {
      foreach ( $data as $key => $value ) {
          if( is_numeric($key) ){
              if ($pkey === 'performances') $key = 'performance-'.$key;
              else if ($pkey === 'cast') $key = 'cast-'.$key;
              else
                $key = 'item-'.$key; //dealing with <0/>..<n/> issues
          }
          $key = strtolower($key);
          if( is_array($value) ) {
              $subnode = $xml_data->addChild($key);
              array_to_xml($value, $subnode, $key);
          } else {
              $xml_data->addChild("$key",htmlspecialchars(utf8_for_xml("$value")));
          }
       }
     }

    array_to_xml($events, $xml_data);
    $xml = $xml_data->asXML();

    // Set headers so file automatically downloads
    header('Content-disposition: attachment; filename=' . $filename . '.xml');
    header('Content-type: text/xml');
    echo $xml;
  }


  /**
  * Removes unsupported UTF8 chars from string
  *
  * @param string $string String that needs to be prepared for XML conversion.
  *
  * @return string
  */
  function utf8_for_xml($string) {
    return preg_replace ('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $string);
  }


  /**
  * Creates CSV download for all search results
  *
  * @param array $ids Array of IDs of Events to be CSV'ed.
  */
  function getResultsCSV($ids = []) {
    global $conn;
    $results = array();
    $events = array();
    $colArr = array();
    $headers = array();
    $cols = '';
    $filename = (count($ids) === 1) ? $ids[0] : 'results_CSV';
    $resultQry = "SELECT Events.EventId AS e_EventId,
        Events.EventDate AS e_EventDate,
        Events.TheatreCode AS e_TheatreCode,
        Events.Season AS e_Season,
        Events.Volume AS e_Volume,
        Events.Hathi AS e_Hathi,
        Events.CommentC AS e_CommentC,
        Events.TheatreId AS e_TheatreId,
        Events.Phase1 AS e_Phase1,
        Events.Phase2 AS e_Phase2,
        Events.CommentCClean AS e_CommentCClean,
        Events.BookPDF AS e_BookPDF,
        Theatre.TheatreId AS t_TheatreId,
        Theatre.Volume AS t_Volume,
        Theatre.TheatreCode AS t_TheatreCode,
        Theatre.TheatreName AS t_TheatreName,
        Performances.PerformanceId AS p_PerformanceId,
        Performances.EventId AS p_EventId,
        Performances.PerformanceOrder AS p_PerformanceOrder,
        Performances.PType AS p_PType,
        Performances.PerformanceTitle AS p_PerformanceTitle,
        Performances.CommentP AS p_CommentP,
        Performances.CastAsListed AS p_CastAsListed,
        Performances.DetailedComment AS p_DetailedComment,
        Performances.WorkId AS p_WorkId,
        Performances.PerfTitleClean AS p_PerfTitleClean,
        Performances.CommentPClean AS p_CommentPClean,
        Cast.CastId AS c_CastId,
        Cast.PerformanceId AS c_PerformanceId,
        Cast.Role AS c_Role,
        Cast.Performer AS c_Performer,
        Cast.RoleClean AS c_RoleClean,
        Cast.PerformerClean AS c_PerformerClean
        FROM Events 
	LEFT JOIN Theatre ON Theatre.TheatreId = Events.TheatreId
	LEFT JOIN Performances ON Performances.EventId = Events.EventId
	LEFT JOIN Cast ON Cast.PerformanceId = Performances.PerformanceId
	WHERE Events.EventId IN(" . implode(',', $ids) . ")";

    // https://stackoverflow.com/questions/125113/php-code-to-convert-a-mysql-query-to-csv
    // https://stackoverflow.com/questions/13108157/php-array-to-csv
    // https://stackoverflow.com/questions/2539217/how-to-get-database-table-header-information-into-an-csv-file

    $fp = fopen('php://output', 'w');
    // Set headers so file automatically downloads
    header('Content-disposition: attachment; filename=' . $filename . '.csv');
    header('Content-type: text/csv; charset=utf-8');
    if(empty($ids)) {
      fputcsv($fp, 'No Events Found');
    } else {
      $qryResults = $conn->query($resultQry);
      while ($row = mysqli_fetch_assoc($qryResults)) {
        if(empty($headers)) {
          $headers = array_keys($row);
          fputcsv($fp, $headers);
        }
        foreach($row as $key => $value) {
          $row[$key] = utf8_for_xml($value);
        }
        fputcsv($fp, $row);
      }
    }

    die;
  }


  /**
  * Creates JSON download for a single event
  *
  * @param int $id ID of Event to be JSON'ed.
  */
  function getJSON($id) {
    $event = getEvent($id);
    if (empty($event)) {
      $event['error'] = 'No such event.';
      $filename = 'error';
    } else {
      $filename = $id;
      $event['Performances'] = array();
      //$event['Performances'] = getPerformances($event['EventId']);
      $perfs = getPerformances($event['EventId']);

      foreach ($perfs as $perf) {
        $perf['RelatedWorks'] = getRelatedWorks($perf['PerformanceTitle']);
        $event['Performances'][] = $perf;
      }
    }

    $json = json_encode(utf8ize($event));

    // Set headers so file automatically downloads
    header('Content-disposition: attachment; filename=' . $filename . '.json');
    header('Content-type: application/json');
    echo $json;
  }


  /**
  * Creates JSON download for all search results
  *
  * @param array $ids Array of IDs of Events to be JSON'ed.
  */
  function getResultsJSON($ids = []) {
    global $conn;
    $results = [];
    $events = [];
    $filename = 'results_JSON';

    foreach ($ids as $id) {
      $event = getEvent($id);
      $event['Performances'] = getPerformances($id);
      $events[] = $event;
    }

    if (count($ids) < 2000) {
      $json = json_encode(utf8ize($events));
    } else {
      $json = encodeLargeArray($events);
    }

    // Set headers so file automatically downloads
    header('Content-disposition: attachment; filename=' . $filename . '.json');
    header('Content-type: application/json');
    echo $json;

  }


  /**
  * JSON encodes Event arrays larger than 5000.
  *
  * Kept hitting memory limits with larger arrays. This splits the array up and
  *  encodes it in chunks.
  *
  * @param array $events Full array of events to be encoded.
  * @param int $threshold Splits up array into chunks of this size.
  *
  * @return string JSON encoded events.
  */
  function encodeLargeArray($events, $threshold = 2000) {
    $json = array();
    while (count($events) > 0) {
        $partial_array = array_slice($events, 0, $threshold);
        $json[] = ltrim(rtrim(json_encode(utf8ize($partial_array)), "]"), "[");
        $events = array_slice($events, $threshold);
    }

    $jsonStr = "";
    for ($i = 0; $i < sizeof($json); $i++) {
      $jsonStr .= $json[$i];
      if ($i != sizeof($json) - 1) {
        $jsonStr .= ", ";
      }
    }
    $jsonStr2 = '[' . $jsonStr . ']';
    return $jsonStr2;
  }




  function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
  }

?>
