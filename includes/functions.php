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
 * @return string the SQL query used for the search, minus pagination parameters
 */
  function buildSphinxQuery() {
    global $sphinx_conn;

    $getters = array(); // Contains all search parameters
    $queries = array(); // Contains all 'WHERE' parameters
    $matches = array(); // Contains all Sphinx MATCH() parameters
    $perfTitleMatches = array(); // Contains the list of perf titles to MATCH
    $eventIdQueries = array(); // Contains lists of EventIds to intersect
    $ptypes = array(); // List of ptypes from $_GET['ptype']
    $keywrd = array();
    $sortBy = 'relevance'; // Default. Will set to parameter if valid, below.
    $volume = (isset($_GET['vol'])) ? $_GET['vol'] : '1, 2, 3, 4, 5';
    $roleSwtch = (isset($_GET['roleSwitch']) && $_GET['roleSwitch'] === 'or') ? 'OR' : 'AND';
    $actSwtch = (isset($_GET['actSwitch']) && $_GET['actSwitch'] === 'or') ? 'OR' : 'AND';

    if (!empty($_GET['sortBy']) &&
        in_array($_GET['sortBy'], ['datea', 'dated'])) {
      $_GET['sortBy'];
    }

    foreach ($_GET as $key => $value) {
      $temp = is_array($value) ? $value : trim($value);
      if (!empty($temp)) {
        // Create ptype array to later implode to string
        if ($key === 'ptype') {
          foreach ($_GET['ptype'] as $type) {
            if (in_array($type, ['p', 'a', 'm', 'd', 'e', 's', 'b', 'i', 'o', 'u', 't'])) {
              array_push($ptypes, "'" . $type . "'");
            }
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

    /*
     * The SELECT fields (columns) of the Sphinx query can be simplified
     * because only the columns that were used in the original query populate
     * the index.
     */
    $sql = "SELECT *";
    /*
     * The logic for the original MySQL query related to the calculated value
     * "PerfScore" or "keyScore" are no longer needed because the way that
     * Sphinx does matching is more effective and the calculated match is not
     * needed any longer. Modifications to the field ranking can be done later.
     *
     * The FROM statement for MySQL translates to the index identifier in
     * Sphinx queries. No joins are needed in this function to search because
     * the index was built using the original MySQL query's Join statements.
     */
    $sql .= "\nFROM london_stages";

    // Get our WHERE parameter for any selected date and add to $queries
    $dateQuery = getDateQuery(true);
    if ($dateQuery !== '') {
      array_push($queries, $dateQuery);
    }

    // Add $queries entry for each value in $getters
    if (!empty($getters)) {
      foreach ($getters as $key => $value) {
        ${$key} = $value;
        switch ($key) {
          case 'theatre':
            if ($theatre === 'all') break;
            $theatres = getSphinxTheatreNamesQuery($theatre);
            if (is_array($theatres) && count($theatres) > 0) {
              $theatres = implode(', ', $theatres);
              array_push($queries, "theatrename IN ($theatres)");
            }
            break;
          case 'volume':
            $volume = mysqli_real_escape_string($sphinx_conn, $volume);
            if ($volume !== 'all' && in_array($volume, [1, 2, 3, 4, 5])) {
              array_push($queries, 'volume=' . $volume);
            }
            break;
          case 'actor':
            $actor = array_filter($actor, 'strlen');
            if (count($actor) < 1) break;
            $actQry = (count($actor) > 1 && $actSwtch === "AND") ?
              getSphinxCastQuery('actor', $actor) :
              getSphinxCastQuery('actor', $actor, 'OR');
            array_push($eventIdQueries, $actQry);
            break;
          case 'role':
            $role = array_filter($role, 'strlen');
            if (count($role) < 1) break;
            $roleQry = (count($role) > 1 && $roleSwtch === "AND") ?
              getSphinxCastQuery('role', $role) :
              getSphinxCastQuery('role', $role, 'OR');
            array_push($eventIdQueries, $roleQry);
            break;
          case 'performance':
            $performance = mysqli_real_escape_string($sphinx_conn, $performance);
            if (!empty($performance) && $performance !== '') {
              array_push($perfTitleMatches, '"' . $performance . '"/1');
            }
            break;
          case 'ptype':
            if (!empty($ptypes)) {
              $ptype_qry = implode(",", $ptypes);
              array_push($queries, "ptype IN ($ptype_qry)");
            }
            break;
          case 'author':
            // The author filter does a lookup of all of the author's works,
            //   then adds the full list of titles and related works titles to
            //   the MATCH statement with an OR operator between each title.
            $author = trim(mysqli_real_escape_string($sphinx_conn, $author));
            $authorMatch = getSphinxAuthorQuery($author);
            // If the query generation encountered an error it will be false.
            if (is_bool($authorMatch)) {
              // When the author query returns nothing useful, there should be
              //   no matches in the main query, to match the legacy behavior.
              array_push($queries, '0'); // Returns an empty set.
            }
            else {
              // Include the returned list of perf titles in the MATCH statement.
              array_push($perfTitleMatches, $authorMatch);
            }
            break;
          case 'keyword':
            $keyword = mysqli_real_escape_string($sphinx_conn, $keyword);
            // The role and performer should not use the quorum number ('/1')
            // on the entered keyword to better match the way that keyword
            // search worked in the legacy search. Use it for the other fields.
            array_push(
              $matches,
              "(@(authnameclean,perftitleclean,commentcclean,commentpclean) \"$keyword\"/1) | (@(roleclean,performerclean) \"$keyword\")"
            );
            break;
        }
      }
    }

    // Build the perfTitleClean field MATCH parameter and add to that array.
    if (!empty($perfTitleMatches)) {
      // The operator between parameters should be "AND", which is a space.
      $perfTitleMatches = implode(' ', $perfTitleMatches);
      // Place the new string at the beginning of the array because the MAYBE
      //   parameter added when the title filter is set should come after.
      array_unshift($matches, "(@(perftitleclean,performancetitle) $perfTitleMatches)");
    }
    // Build eventid IN() statement with intersect of $eventIdQueries items.
    if (!empty($eventIdQueries)) {
      if (is_array($eventIdQueries) && count($eventIdQueries) === 1 && is_array($eventIdQueries[0]))
        $eventIdQueries = $eventIdQueries[0];
      elseif (is_array($eventIdQueries[0]))
        $eventIdQueries = call_user_func_array('array_intersect', $eventIdQueries);

      $eventIdQueries = 'eventid IN (' . implode(', ', $eventIdQueries) . ')';
      array_push($queries, $eventIdQueries);
    }
    // Build the MATCH statement and add it to the list of queries.
    if (!empty($matches)) {
      $matches = 'MATCH(\'' . implode(' ', $matches) . '\')';
      array_push($queries, $matches);
    }
    // Build the WHERE clause.
    if (!empty($queries) && count($queries) > 0) {
      $sql .= "\nWHERE " . implode(' AND ', $queries);
    }
    // The results need to be grouped by Event to avoid redundancy
    $sql .= "\nGROUP BY eventid";
    // If sorting by event date, add an ORDER BY clause.
    // Determine if ascending or descending and then .
    if ($sortBy !== 'relevance' ||
        (empty($perfTitleMatches) && empty($getters['keyword']))) {
      $sortOrder = ($sortBy === 'dated') ? 'DESC' : 'ASC';
      $sql .= "\nORDER BY eventdate $sortOrder";
    } elseif ($sortBy === 'relevance') {
      $sql .= "\nORDER BY weight() desc, eventdate asc";
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
   * Finds match counts for keyword searches on specific columns using Sphinx.
   *
   * Columns are PerfCleanTitle, AuthNameClean, CommentPClean, CommentCClean,
   * and RoleClean/PerformerClean.
   *
   * @param string $keyword
   *   Keyword from $_GET to run search on.
   *
   * @return array
   *   Array of match counts by column.
   */
  function getSphinxResultsByColumn($keyword) {
    global $sphinx_conn;
    $keywrd   = mysqli_real_escape_string($sphinx_conn, $keyword);
    $psql     = "SELECT performanceid FROM london_stages WHERE MATCH('@perftitleclean \"$keywrd\"/1') GROUP BY performanceid";
    $asql     = "SELECT eventid FROM london_stages WHERE MATCH('@authnameclean \"$keywrd\"/1') GROUP BY eventid";
    $pcsql    = "SELECT performanceid FROM london_stages WHERE MATCH('@commentpclean \"$keywrd\"/1') GROUP BY performanceid";
    $ecsql    = "SELECT eventid FROM london_stages WHERE MATCH('@commentcclean \"$keywrd\"/1') GROUP BY eventid";
    $csql     = "SELECT castid FROM london_stages WHERE MATCH('@(roleclean,performerclean) \"$keywrd\"') GROUP BY castid";
    $metasql  = "SHOW meta";

    $result['p']  = $sphinx_conn->query($psql);
    $result['p_meta']  = $sphinx_conn->query($metasql);
    $result['a']  = $sphinx_conn->query($asql);
    $result['a_meta']  = $sphinx_conn->query($metasql);
    $result['pc'] = $sphinx_conn->query($pcsql);
    $result['pc_meta'] = $sphinx_conn->query($metasql);
    $result['ec'] = $sphinx_conn->query($ecsql);
    $result['ec_meta'] = $sphinx_conn->query($metasql);
    $result['c']  = $sphinx_conn->query($csql);
    $result['c_meta']  = $sphinx_conn->query($metasql);
    $all_counts   = [];

    if (!is_bool($result['p']) && !is_bool($result['p_meta']))
      while ($row = $result['p_meta']->fetch_assoc())
        if ($row['Variable_name'] === 'total_found')
          $all_counts[] = ['col' => 'pcount', 'count' => $row['Value']];

    if (!is_bool($result['a']) && !is_bool($result['a_meta']))
      while ($row = $result['a_meta']->fetch_assoc())
        if ($row['Variable_name'] === 'total_found')
          $all_counts[] = ['col' => 'acount', 'count' => $row['Value']];

    if (!is_bool($result['pc']) && !is_bool($result['pc_meta']))
      while ($row = $result['pc_meta']->fetch_assoc())
        if ($row['Variable_name'] === 'total_found')
          $all_counts[] = ['col' => 'pccount', 'count' => $row['Value']];

    if (!is_bool($result['ec']) && !is_bool($result['ec_meta']))
      while ($row = $result['ec_meta']->fetch_assoc())
        if ($row['Variable_name'] === 'total_found')
          $all_counts[] = ['col' => 'eccount', 'count' => $row['Value']];

    if (!is_bool($result['c_meta']) && !is_bool($result['c_meta']))
      while ($row = $result['c_meta']->fetch_assoc())
        if ($row['Variable_name'] === 'total_found')
          $all_counts[] = ['col' => 'ccount', 'count' => $row['Value']];

    function cmp($a, $b)
    {
      return $a['count'] < $b['count'];
    }

    usort($all_counts, 'cmp');

    return $all_counts;
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

    if (gettype($string) == "string") {
      $string = strip_tags($string);
    } elseif (gettype($string) == "array") {
      $string = array_map('strip_tags', $string);
    }

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

    $sql = 'SELECT * FROM Theatre GROUP BY TheatreName ORDER BY TheatreName';
    $result = $conn->query($sql);

    echo '<optgroup label="Common Theatres">';
    echo '<option value="111Covent Garden"';
      getSticky(2, 'theatre', "111Covent Garden");
      echo '>Covent Garden (All)</option>';
    echo '<option value="111Drury Lane"';
      getSticky(2, 'theatre', "111Drury Lane");
      echo '>Drury Lane (All)</option>';
    echo '<option value="111Haymarket"';
      getSticky(2, 'theatre', "111Haymarket");
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
   * Gets a list of Theatre Names to add to the Sphinx WHERE clause.
   *
   * @param int $theatreName
   *  Theatre ID
   *
   * @return array
   *   Array of Theatre Names with single quotes added for the query.
   */
  function getSphinxTheatreNamesQuery($theatreName = '') {
    global $conn;
    if ($theatreName === '') return [];
    $theatres = []; // Storage for return value.
    // Clean the filter input.
    $theatre  = preg_replace('/[0-9;"`\~\!\@\#\$\%\^\&\*\<\>\[\]]/', '', $theatreName); // Remove any numbers and special chars
    $theatre  = mysqli_real_escape_string($conn, $theatre);
    // If '111' is a prefix then look up names using 'LIKE' condition.
    if (substr($theatreName, 0, 3) === '111') {
      $sql = "SELECT * FROM Theatre
              WHERE TheatreName LIKE '%$theatre%'
              GROUP BY TheatreName";
      $result = $conn->query($sql);
      while ($row = mysqli_fetch_assoc($result)) {
        $theatre = preg_replace('/[0-9;"`\~\!\@\#\$\%\^\&\*\<\>\[\]]/', '', $row['TheatreName']); // Remove any numbers and special chars
        $theatre = mysqli_real_escape_string($conn, $theatre);
        $theatres[] = "'$theatre'";
      }
    }
    // An individual theatre was selected, so just add it and return.
    else {
      $theatres[] = "'$theatre'";
    }
    return $theatres;
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
      if ($result !== FALSE) {
        while ($row = mysqli_fetch_assoc($result)) {
          $sources[] = $row['SourceResearched'];
          $sources[] = $row['Source1'];
          $sources[] = $row['Source2'];
          $row['author'] = getAuthorInfo($row['WorkId']);
          $works[] = $row;
          $workIds[] = $row['WorkId'];
        }
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
  function getSphinxRelatedWorks($perfTitle = '') {
    // Return without looking up Related Works
    global $sphinx_conn;

    $prefix = "or ";
    $stopwords = ['[c|C]oncert[s]?', '[e|E]ntertainment[s]?'];
    $perfTitle =  preg_replace('/\b(' . implode('|', $stopwords) . ')\b/', '', $perfTitle);

    if ($perfTitle !== '') {
      $titles = array_map('trim', preg_split("[;|,]", $perfTitle));
      $sql = "SELECT *\nFROM related_work";
      $values = [];

      foreach($titles as $perf) {
        $perf = cleanStr($perf);
        if (strtolower(substr($perf, 0, strlen($prefix))) == $prefix) {
          $perf = substr($perf, strlen($prefix));
        }
        if (strtolower($perf) === 'or') {
          continue;
        }
        $values[] = '"' . $perf . '"';
      }
      $values = implode('|', $values);
      $sql .= "\nWHERE MATCH('" . $values . "')";

      // Only want to show unique works, not all iterations of a given work title
      $sql .= "\nGROUP BY WorkId";

      $result = $sphinx_conn->query($sql);
      $works = array();
      $sources = array();
      $workIds = array();
      while ($row = mysqli_fetch_assoc($result)) {
        $sources[] = $row['sourceresearched'];
        $sources[] = $row['source1'];
        $sources[] = $row['source2'];
        $row['author'] = getAuthorInfo($row['workid']);
        $works[] = $row;
        $workIds[] = $row['workid'];
      }

      // Get Work Sources and perform same search on them
      $sources = array_filter($sources, 'strlen');
      if (!empty($sources)) {
        $sources = wildCardQuotes($sources);
        $ssql = "SELECT WorkId, Title, Type1, Type2, Source1, Source2, SourceResearched, TitleClean, VariantName, TheTitle, PerformanceTitle \nFROM related_work";
        $ssql .= "\nWHERE MATCH('@TitleClean \"" . implode('"|"', $sources) . "\" @PerfTitleClean \"" . implode('"|"', $sources) . "\" @NameClean \"" . implode('"|"', $sources) . "\"')";
        $ssql .= ' GROUP BY WorkId';

        $sresult = $sphinx_conn->query($ssql);

        if ($sresult) {
          while ($srow = mysqli_fetch_assoc($sresult)) {
            if (!in_array($srow['workid'], $workIds)) {
              $srow['author'] = getAuthorInfo($srow['workid']);
              $works[] = $srow;
            }
          }
        }
      }

      return $works;
    }
  }

  /**
   * Removes all quotes from the string and replaces them with a wildcard char.
   *
   * @param $str string
   *   The string to remove quotes from.
   *
   * @return string
   *   A copy of the string with all quote characters removed.
   */
  function wildCardQuotes($str) {
    return str_replace(["'",'"'], '%', $str);
  }


  /**
  * Returns author information for a given work
  *
  * @param int $workId Work ID
  *
  * @return array Array of author info
  */
  function getAuthorInfo($workId = '') {
    global $sphinx_conn;

    if ($workId !== '') {
      $sql = "SELECT AuthId, AuthName, StartDate, StartType, EndDate, EndType, AuthType \nFROM related_work \nWHERE WorkId = " . $workId . " GROUP BY AuthId";

      $result = $sphinx_conn->query($sql);
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
 * Generates the date conditions for the WHERE statement.
 *
 * @param bool $forSphinx
 *   If TRUE, the return is compatible with Sphinx. Default is FALSE.
 *
 * @return string
 *   EventDate conditions to add to the WHERE statement of the query.
 */
  function getDateQuery($forSphinx = FALSE) {
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
        $sql = $forSphinx ?
            "eventdate BETWEEN $startStr AND $endStr" :
            "Events.EventDate BETWEEN $startStr AND $endStr";
        break;
      case 2: // Before
        $sql = $forSphinx ?
            "eventdate <= $startStr" :
            "Events.EventDate <= $startStr";
        break;
      case 3: // On
        // If zero date (e.g. 17161100), run LIKE '171611%'
        if ($daySet === false) {
          // If zero month (e.g. 17160000), run LIKE '1716%'
          if ($monSet === false) {
            $sql = $forSphinx ?
                "eventdate BETWEEN " . $startYr . "0000 AND " . $startYr . "1231" :
                "Events.EventDate LIKE '" . $startYr . "%'";
          } else {
            $yearEnd = $startYr;
            $monthEnd = $startMon + 1;
            if ($monthEnd > 12) {
              $yearEnd++;
              $monthEnd = 1;
            }
            $strEnd = $yearEnd . substr('0' . $monthEnd, -2) . '00';
            $sql = $forSphinx ?
                "eventdate BETWEEN " . $startStr . " AND " . $startYr . $startMon ."31" :
                "Events.EventDate LIKE '" . $startYr . substr('0' . $startMon, -2) . "%'";
          }
        } else { // Else exact match
          $sql = $forSphinx ?
              "eventdate = $startStr" :
              "Events.EventDate = $startStr";
        }
        break;
      case 4: // After
        $sql = $forSphinx ?
            "eventdate >= $startStr" :
            "Events.EventDate >= $startStr";
        break;
    }

    return $sql;
  }


  /**
  * Takes an author's name and generates WHERE statement from all related titles.
  *
  * Takes an author's name and performs two preliminary queries to get all work
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
    $workIdSql =
        "SELECT Works.TitleClean FROM Works WHERE Works.WorkId IN (
          SELECT WorkAuthMaster.WorkId FROM WorkAuthMaster
          JOIN Works ON Works.WorkId = WorkAuthMaster.WorkId
          LEFT JOIN WorksVariant ON WorksVariant.WorkId = Works.WorkId
          WHERE WorkAuthMaster.TitleClean IN (
            SELECT WorkAuthMaster.TitleClean FROM WorkAuthMaster
            LEFT JOIN Author ON Author.AuthId = WorkAuthMaster.AuthId
            WHERE (Author.AuthNameClean LIKE '%$authorClean%')
          ) OR WorksVariant.NameClean IN (
              SELECT WorkAuthMaster.TitleClean FROM WorkAuthMaster
              LEFT JOIN Author ON Author.AuthId = WorkAuthMaster.AuthId
              WHERE (Author.AuthNameClean LIKE '%$authorClean%')
          )
        )";

    // look for related titles in the WorksVariant table
    $varIdSql =
        "SELECT WorksVariant.NameClean FROM WorksVariant WHERE WorksVariant.WorkId IN (
          SELECT WorkAuthMaster.WorkId FROM WorkAuthMaster
          JOIN Works ON Works.WorkId = WorkAuthMaster.WorkId
          LEFT JOIN WorksVariant ON WorksVariant.WorkId = Works.WorkId
          WHERE WorkAuthMaster.TitleClean IN (
            SELECT WorkAuthMaster.TitleClean FROM WorkAuthMaster
            LEFT JOIN Author ON Author.AuthId = WorkAuthMaster.AuthId
            WHERE (Author.AuthNameClean LIKE '%$authorClean%')
          ) OR WorksVariant.NameClean IN (
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
   * Takes an author's name and generates MATCH fragment for related works.
   *
   * Takes an author's name and performs a preliminary query to get all work
   *  titles associated with that author, as well as any other work title that
   *  is similar or has a variant title that is similar. The list of found titles
   *  is then returned as a bunch of WHERE LIKE '%<title>%' statements to get any
   *  performance with a similar title. The idea is to introduce ambiguity and
   *  cast as wide a net as possible because we don't know for sure who authored
   *  the version of the play that was performed on any given night.
   *
   * @param string $author Cleaned author name from $_GET.
   *
   * @return string|bool
   *   MATCH statement fragments to include in the WHERE clause. Value is FALSE
   *     if the author is not found, there are no related works, or an error is
   *     encountered.
   */
  function getSphinxAuthorQuery($author) {
    if ($author === '') return FALSE;
    global $sphinx_conn;

    $author = cleanStr($author);
    $authorClean = mysqli_real_escape_string($sphinx_conn, cleanQuotes($author));
    // Find the author's works in the Related Works index.
    $authorWorksSql =
        "SELECT *
         FROM related_work
         WHERE MATCH('@authnameclean \"$authorClean\"')
         GROUP BY workid
         LIMIT 1000";
    // Run the query.
    $workResult = $sphinx_conn->query($authorWorksSql);
    // TRUE if there was an error returned by the query.
    if (is_bool($workResult)) return FALSE;
    // Process through the results, gather all works and similarly named works.
    $workTitles = [];
    while ($row = mysqli_fetch_assoc($workResult)) {
      // Add contents of whatever work name fields have a value in the record.
      if ($row['titleclean'] && $row['titleclean'] !== '')
        $workTitles[] = $row['titleclean'];
      if ($row['nameclean'] && $row['nameclean'] !== '')
        $workTitles[] = $row['nameclean'];
      if ($row['perftitleclean'] && $row['perftitleclean'] !== '')
        $workTitles[] = $row['perftitleclean'];
    }
    if (empty($workTitles)) return FALSE;
    // Unique list of all titles.
    $titles = array_unique($workTitles);
    // Some titles actually contain multiple titles, separated by a semicolon or
    //   '; or '. We'll trim and explode out the title on the semicolon, and
    //   $prefix is used to strip out the 'or '.
    $processedTitles = [];
    $prefix = "or ";
    foreach ($titles as $title) {
      $titleArr = array_map('trim', explode(';', $title));
      foreach ($titleArr as $titl) {
        if (strtolower(substr($titl, 0, strlen($prefix))) == $prefix) {
          $titl = substr($titl, strlen($prefix));
        }
        // Add each title within double quotes.
        $processedTitles[] = '"' . $titl . '"';
      }
    }
    // Return the MATCH statement for the performance title field with all
    //   titles in double quotes, separated by the OR operator.
    return implode(' | ' , $processedTitles);
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
   * Generates the necessary WHERE statement for the actor or role filter.
   *
   * Takes an array of actors or roles and runs queries to find an event list.
   *
   * If operator is set to 'AND' the EventIds will relate to all in the casts
   *   array. Otherwise, behaves as 'OR' with EventIds that relate to any in
   *   the casts array.
   *
   * @param string $castType
   *   Either Role or Actor.
   * @param array $casts
   *   Array of actors or roles to search on.
   * @param string $operator
   *   Operator to find the event list. Defaults to 'AND'.
   *
   * @return array
   *   EventId list for the WHERE statement.
   */
  function getSphinxCastQuery($castType, $casts = array(), $operator = 'AND') {
    global $conn;

    if (empty($casts)) return [];
    $allIds = array();
    $outputEventIds = array();
    $type = ($castType === 'role') ? 'Role' : 'Performer';
    $sql = "SELECT Events.EventId FROM Events
      JOIN Performances ON Performances.EventId = Events.EventId
      JOIN Cast ON Cast.PerformanceId = Performances.PerformanceId
      WHERE ";
    // Collect the EventIds associated with each role or performer.
    $i = 1;
    foreach ($casts as $cast) {
      if ($cast !== '') {
        // Finish putting the query together for this role or performer.
        $qry = $sql;
        $castClean = mysqli_real_escape_string($conn, cleanQuotes($cast, true));
        $cast = mysqli_real_escape_string($conn, $cast);
        $qry .= "MATCH(Cast." . $type . "Clean) AGAINST ('\"$cast\" @4' IN BOOLEAN MODE) OR Cast." . $type . "Clean LIKE '%$castClean%' GROUP BY Events.EventId";
        // Execute the query and add the list to the allIds list.
        $castResult = $conn->query($qry);
        $eventIds = array();
        while ($row = mysqli_fetch_assoc($castResult)) {
          $eventIds[] = $row['EventId'];
        }
        // Add this role or performer's eventId list to the allIds array.
        $allIds[] = $eventIds;
      }
      $i++;
    }

    if ($operator === 'AND') {
      // Add all common EventIds of the sub-arrays (intersect).
      $initial = $allIds[0];
      for ($a = 1; $a < count($allIds); $a++) {
        foreach ($allIds[$a] as $id) {
          if (in_array($id, $initial)) $outputEventIds[] = $id;
        }
      }
    }
    else { // 'OR'.
      // Add all EventIds from the sub-arrays together (union).
      foreach ($allIds as $castEventIds) {
        foreach ($castEventIds as $eventId) {
          $outputEventIds[$eventId] = $eventId; // Keeps the values unique.
        }
      }
    }
    // Now return the WHERE condition statement.
    return $outputEventIds;
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
    if (!empty($_GET['actor']) && !empty(array_filter($_GET['actor'], 'strlen'))) $yourSearch .= '<span class="your-search-item">Actors - ' . htmlentities(implode(', ', array_filter($_GET['actor'], 'strlen'))) . '</span>';
    if (!empty($_GET['role']) && !empty(array_filter($_GET['role'], 'strlen'))) $yourSearch .= '<span class="your-search-item">Roles - ' . htmlentities(implode(', ', array_filter($_GET['role'], 'strlen'))) . '</span>';
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
    $cleanedN = array_map('cleanStr', $n[0]);
    $cleanedM = array_map('cleanStr', $m[0]);
    $allWords = array_unique(array_merge($cleanedM, $cleanedN)); // Combine unique
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
  * @param string $actorSearch Pipe-delimited string containing keyword and
  *  actor search terms. Words have quotes removed.
  * @param string $roleSearch Pipe-delimited string containing keyword and role
  *  search terms. Words have quotes removed.
  * @param array $cast Array of all cast members associated with an event.
  *
  * @return array|bool Array of cast members that match either they keyword,
  *  actor, or role search terms. False if no matches.
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
    $allActors = array_unique(array_merge(cleanStr($am[0]), cleanStr($an[0]))); // Combine unique
    $allRoles = array_unique(array_merge(cleanStr($rm[0]), cleanStr($rn[0]))); // Combine unique
    if((!is_array($allActors) || count($allActors) === 0) && (!is_array($allRoles) || count($allRoles) === 0)) {
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
   * @param string $text
   *   String to be cut down
   *
   * @return string
   *   The cut down string.
   */
  function cutString($string): string {
    if (!str_contains($string, 'highlight')) return $string;

    $numChars = 25; // Number of characters around the highlighted word to keep
    $needle = 'highlight';
    $lastPos = 0;
    $positions = array();
    $finalString = '';
    $startDisregard = 13; // Num chars to subtract from positions before counting the 25. (for <span class=" )

    // https://stackoverflow.com/questions/1193500/truncate-text-containing-html-ignoring-tags

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

    // Clean the value string up a bit. Remove '$, |, ), =, *'. Change brackets to HTML entities.
    $value = preg_replace('/[\[\]]/', '&rbrack;', strip_tags(preg_replace('/[\$|=\)\*\/]/', '', $value)));
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
