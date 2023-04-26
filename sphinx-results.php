<?php
  $time = microtime();
  $time = explode(' ', $time);
  $time = $time[1] + $time[0];
  $start = $time;

  include_once('includes/functions.php');
  require_once 'includes/SphinxPaginator.class.php';

  global $sphinx_conn;

  // Build query with $_GET values
  $sql = buildSphinxQuery();

  // Get and sanitize 'limit' and 'p' for pagination
  $g_lim = filter_input(INPUT_GET, 'limit', FILTER_SANITIZE_NUMBER_INT);
  $g_p = filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT);
  $limit      = ( $g_lim !== '' && $g_lim > 0 ) ? $g_lim : 25;
  $page       = ( $g_p !== '' && $g_p > 0 ) ? $g_p : 1;
  $links      = 3;
  $Paginator  = new SphinxPaginator( $sphinx_conn, $sql );

  // Get paginated results
  $results    = $Paginator->getData( $limit, $page );

  // Add the ranker OPTION statements for sphinx to be output in Toggle SQL.
  $sql       .= "\nOPTION " . $Paginator->getFieldWeights();

  // Cleaned, pipe delimited strings from 'actor' and 'role' arrays
  if (!empty($_GET['actor'])) {
    $getActors = array_map(function ($act) {
      return cleanStr($act);
    }, $_GET['actor']);
  }
  if (!empty($_GET['role'])) {
    $getRoles = array_map(function ($rle) {
      return cleanStr($rle);
    }, $_GET['role']);
  }
  $cleanedActors = (isset($_GET['actor'])) ? implode('|', $getActors) : '';
  $cleanedRoles  = (isset($_GET['role'])) ? implode('|', $getRoles) : '';

  $search_filters_empty = TRUE;
  foreach ($_GET as $key => $value) {
    if ($key == 'date-type') break;
    if ($key == 'sortBy') break;
    if ($_GET[$key] == 'all') break;
    if (!empty($value)) {
      $search_filters_empty = FALSE;
    }
  }
?>

<!doctype html>
<html class="no-js" lang="en">

<head>
  <?php include_once('common/header.php'); ?>
  <title>Search</title>
</head>

<body id="results">
  <?php include_once('common/nav.php'); ?>
  <div id="main" class="main grid-container">
    <div class="sql-query-wrap">
      <div class="toggle-query"><a id="toggle">Toggle Sphinx Query</a></div>
      <div class="sql-query">
        <?php echo $sql; ?>
      </div>
    </div>
    <form id="searchForm" class="form-accordion search-form grid-x" method="get" data-abide novalidate>
      <div class="grid-container grid-x form-wrapper">
        <div class="hide-for-large">
          <button type="button" class="button hide-for-large open-filter-btn" data-toggle="filterMenu">
            Open Search Filters
          </button>
        </div>
        <div class="filter-menu form-section cell medium-4 large-3 off-canvas in-canvas-for-large position-left" id="filterMenu" data-off-canvas>
          <button class="close-button" aria-label="Close menu" type="button" data-close>
            <span aria-hidden="true">&times;</span>
          </button>
          <div class="download-btns">
            <h2>Result Options</h2>
            Download: <br>
            <a href="get_all_sphinx_json.php?<?php echo $_SERVER['QUERY_STRING']; ?>" class="button dwnld-btn">JSON</a>
            <!--<a href="get_all_xml.php?ids=<?php //echo htmlspecialchars(json_encode($allResultIds), ENT_QUOTES); ?>" class="button dwnld-btn">Download XML</a>-->
            <a href="get_all_sphinx_xml.php?<?php echo $_SERVER['QUERY_STRING']; ?>" class="button dwnld-btn">XML</a>
            <a href="get_all_sphinx_csv.php?<?php echo $_SERVER['QUERY_STRING']; ?>" class="button dwnld-btn">CSV</a>
          </div>
          <h2>Search Filters</h2>
          <div class="grid-x filter-sections-wrap form-accordion">
            <div class="cell small-12 section-wrap">
              <h2 class="active">Event</h2>
              <div class="grid-x acc-section">
                <div class="small-12 cell">
                  <div class="form-group field-theatre">
                    <label for="theatre" class="fb-select-label">Theatre</label>
                    <select class="theatre" name="theatre" id="theatre">
                      <option disabled <?php if ((!$_GET['theatre']) || ($_GET['theatre'] && $_GET['theatre'] !== '')) echo 'selected="selected"'; ?>>
                          Select a Theatre</option>
                      <option value="all">Any Theatre...</option>
                      <option disabled>_________</option>
                      <?php getTheatres(); ?>
                    </select>
                  </div>
                  <div class="form-group field-volume">
                    <label for="volume" class="fb-select-label">Volume</label>
                    <select class="volume" name="volume" id="volume">
                      <option value="" disabled selected>Select a Volume</option>
                      <option value="all">Any Volume...</option>
                      <option disabled>_________</option>
                      <option value="1" <?php getSticky(2, 'volume', '1'); ?>>1 (1659-1700)</option>
                      <option value="2" <?php getSticky(2, 'volume', '2'); ?>>2 (1700-1729)</option>
                      <option value="3" <?php getSticky(2, 'volume', '3'); ?>>3 (1729-1747)</option>
                      <option value="4" <?php getSticky(2, 'volume', '4'); ?>>4 (1747-1776)</option>
                      <option value="5" <?php getSticky(2, 'volume', '5'); ?>>5 (1776-1800)</option>
                    </select>
                  </div>
                </div>
                <div class="small-12 cell">
                  <div class="form-group field-dates">
                    <fieldset>
                      <legend>Date Range</legend>
                      <label class="hidden date-type hide" for="dateType">Date Type</label>
                      <select id="dateType" class="date-type hide" name="date-type">
                        <option value="1" <?php getSticky(2, 'date-type', '1'); ?>>Between</option>
                        <option value="2" <?php getSticky(2, 'date-type', '2'); ?>>Before</option>
                        <option value="3" <?php getSticky(2, 'date-type', '3'); ?>>On</option>
                        <option value="4" <?php getSticky(2, 'date-type', '4'); ?>>After</option>
                      </select>
                      <div class="year start-year">
                        <span class="year-title">Start</span>
                        <label class="hidden" for="startYear">Start Year</label>
                        <select class="date-year" id="startYear" name="start-year">
                          <option disabled selected>Year</option>
                          <option value="">All</option>
                          <?php getYears('start'); ?>
                        </select>
                        <label class="hidden" for="startMonth">Start Month</label>
                        <select class="date-month" id="startMonth" name="start-month" disabled>
                          <option disabled selected>Mon</option>
                          <option value="">All</option>
                          <?php getMonths('start'); ?>
                        </select>
                        <label class="hidden" for="startDay">Start Day</label>
                        <select class="date-day" id="startDay" name="start-day" disabled>
                          <option disabled selected>Day</option>
                          <option value="">All</option>
                          <?php getDays('start'); ?>
                        </select>
                      </div>
                      <div class="year end-year">
                        <span class="year-title">End</span>
                        <label class="hidden" for="endYear">End Year</label>
                        <select class="date-year" id="endYear" name="end-year">
                          <option disabled selected>Year</option>
                          <option value="">All</option>
                          <?php getYears('end'); ?>
                        </select>
                        <label class="hidden" for="endMonth">End Month</label>
                        <select class="date-month" id="endMonth" name="end-month" disabled>
                          <option disabled selected>Mon</option>
                          <option value="">All</option>
                          <?php getMonths('end'); ?>
                        </select>
                        <label class="hidden" for="endDay">End Day</label>
                        <select class="date-day" id="endDay" name="end-day" disabled>
                          <option disabled selected>Day</option>
                          <option value="">All</option>
                          <?php getDays('end'); ?>
                        </select>
                      </div>
                    </fieldset>
                  </div>
                </div>
              </div>
            </div>
            <!-- end event wrap -->
            <div class="cell small-12 section-wrap">
              <h2 class="active">Performance</h2>
              <div class="grid-x acc-section">
                <div class="small-12 cell">
                  <div class="form-group field-performance">
                    <label for="performance" class="fb-text-label">Title</label>
                    <input type="text" class="performance ui-autocomplete-input" name="performance" id="performance" value="<?php getSticky(1, 'performance'); ?>" onKeyPress="checkEnter(event)">
                  </div>
                  <div class="form-group field-author inline-label">
                    <label for="author" class="fb-select-label">Author</label>
                    <span data-tooltip class="top l-tooltip" tabindex="2" title="Searches not only for performances of plays known to be by this author, but also performances of associated titles, including adaptations.">?</span>
                    <input type="text" class="author" name="author" id="author" value="<?php getSticky(1, 'author'); ?>" onKeyPress="checkEnter(event)">
                  </div>
                </div>
                <div class="small-12 cell">
                  <div class="form-group field-ptype">
                    <fieldset class="ptype-contain">
                      <div class="ptype-legend">
                        <legend>Filter by Performance Type</legend>
                      </div>
                      <div class="ptype">
                        <input type="checkbox" name="ptype[]" value="p" id="mainpiece" <?php getSticky(3, 'ptype', 'p'); ?>><label for="mainpiece">Mainpiece</label><br>
                        <input type="checkbox" name="ptype[]" value="a" id="afterpiece" <?php getSticky(3, 'ptype', 'a'); ?>><label for="afterpiece">Afterpiece</label><br>
                        <input type="checkbox" name="ptype[]" value="m" id="music" <?php getSticky(3, 'ptype', 'm'); ?>><label for="music">Music</label><br>
                        <input type="checkbox" name="ptype[]" value="d" id="dance" <?php getSticky(3, 'ptype', 'd'); ?>><label for="dance">Dance</label><br>
                        <input type="checkbox" name="ptype[]" value="e" id="entertainment" <?php getSticky(3, 'ptype', 'e'); ?>><label for="entertainment">Entertainment</label><br>
                        <input type="checkbox" name="ptype[]" value="s" id="song" <?php getSticky(3, 'ptype', 's'); ?>><label for="song">Song</label><br>
                        <input type="checkbox" name="ptype[]" value="b" id="ballet" <?php getSticky(3, 'ptype', 'b'); ?>><label for="ballet">Ballet</label><br>
                        <input type="checkbox" name="ptype[]" value="i" id="instrumental" <?php getSticky(3, 'ptype', 'i'); ?>><label for="instrumental">Instrumental</label><br>
                        <input type="checkbox" name="ptype[]" value="o" id="opera" <?php getSticky(3, 'ptype', 'o'); ?>><label for="opera">Opera</label><br>
                        <input type="checkbox" name="ptype[]" value="u" id="monologue" <?php getSticky(3, 'ptype', 'u'); ?>><label for="monologue">Monologue</label><br>
                        <input type="checkbox" name="ptype[]" value="t" id="trick" <?php getSticky(3, 'ptype', 't'); ?>><label for="trick">Trick</label>
                      </div>
                    </fieldset>
                  </div>
                </div>
              </div>
            </div>
            <!-- end perf wrap -->
            <div class="cell small-12 section-wrap">
              <h2 class="active">Cast</h2>
              <div class="grid-x acc-section">
                <div class="small-12 cell">
                  <div class="form-group field-actor inline-label">
                    <label for="actor" class="fb-text-label">Actor</label>
                    <span data-tooltip class="top l-tooltip" tabindex="2" title="We recommend searching by last name (for example, instead of 'Dorothy Jordan,' search 'Jordan' to return instances where she is listed as 'Mrs Jordan')">?</span>
                    <span class="cast-switch">
                      <label for="actSwitch" class="show-for-sr">Select 'AND' or 'OR' search on multiple actors.</label>
                      <select name="actSwitch" id="actSwitch" title="Select 'AND' or 'OR' search on multiple actors." <?php echo (isset($_GET['actor']) && count(array_filter($_GET['actor'], 'strlen')) > 1) ? '' : 'disabled="disabled"'; ?>>
                        <option value="and" <?php getSticky(2, 'actSwitch', 'and'); ?>>AND</option>
                        <option value="or" <?php getSticky(2, 'actSwitch', 'or'); ?>>OR</option>
                      </select>
                    </span>
                    <span id="actors">
                      <?php
                        $actorArr = (isset($_GET['actor'])) ? array_filter($_GET['actor'], 'strlen') : [];
                        if (count($actorArr) > 0) {
                          $i = 0;
                          foreach($actorArr as $act) {
                            echo '<input type="text" class="actor actor-search" name="actor[]" id="actor" value="' . getSticky(5, 'actor', $actorArr[$i]) . '" onKeyPress="checkEnter(event)">';
                            $i++;
                          }
                        } else {
                          echo '<input type="text" class="actor actor-search" name="actor[]" id="actor" value="" onKeyPress="checkEnter(event)">';
                        }
                      ?>
                    </span>
                    <div class="addActor"><a id="addActor" class="addCast" title="Add an actor">+</a></div>
                  </div>
                </div>
                <div class="small-12 cell">
                  <div class="form-group field-role inline-label">
                    <label for="role" class="fb-text-label">Role</label>
                    <span class="cast-switch">
                      <label for="roleSwitch" class="show-for-sr">Select 'AND' or 'OR' search on multiple roles.</label>
                      <select name="roleSwitch" id="roleSwitch" title="Select 'AND' or 'OR' search on multiple roles." <?php echo (isset($_GET['role']) && count(array_filter($_GET['role'], 'strlen')) > 1) ? '' : 'disabled="disabled"'; ?>>
                        <option value="and" <?php getSticky(2, 'roleSwitch', 'and'); ?>>AND</option>
                        <option value="or" <?php getSticky(2, 'roleSwitch', 'or'); ?>>OR</option>
                      </select>
                    </span>
                    <span id="roles">
                      <?php
                        $roleArr = (isset($_GET['role'])) ? array_filter($_GET['role'], 'strlen') : [];
                        if (count($roleArr) > 0) {
                          $i = 0;
                          foreach($roleArr as $act) {
                            echo '<input type="text" class="role role-search" name="role[]" id="role" value="' . getSticky(5, 'role', $roleArr[$i]) . '" onKeyPress="checkEnter(event)">';
                            $i++;
                          }
                        } else {
                          echo '<input type="text" class="role role-search" name="role[]" id="role" value="" onKeyPress="checkEnter(event)">';
                        }
                      ?>
                    </span>
                    <div class="addRole"><a id="addRole" class="addCast" title="Add a role">+</a></div>
                  </div>
                </div>
              </div>
            </div>
            <!-- end cast wrap -->
            <div class="cell small-12 section-wrap">
              <h2 class="active">Keyword</h2>
              <div class="grid-x acc-section">
                <div class="small-12 cell">
                  <div class="form-group field-keyword inline-label">
                    <label for="keyword" class="fb-text-label">Keyword</label>
                    <span data-tooltip class="top l-tooltip" tabindex="2" title="Keyword searches event comments, performance titles, performance comments, roles, actors, and author names.">?</span>
                    <input type="text" class="keyword" name="keyword" id="keyword" value="<?php getSticky(1, 'keyword'); ?>" onKeyPress="checkEnter(event)">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="cell small-12 button-wrap">
            <a href="/search.php" class="new-search show-for-large">New Search</a>
            <input type="submit" class="search-submit button" value="Update Results">
          </div>
        </div>
        <div class="form-results cell small-12 large-9">
          <div class="results-wrap">
            <div class="your-search">
              <span class="num-results"><?php echo $results->total . ' results'; ?></span><?php echo yourSearch(); ?>
            </div>
            <?php if(onlyKeyword()) {
              $resByCol = getSphinxResultsByColumn(cleanStr($_GET['keyword']));
              $msg = "We found ";
              $resCnt = 1;

              foreach($resByCol as $colKey => $col) {
                $colMsg = '';
                switch($col['col']) {
                  case 'pcount':
                    $colMsg = ' ' . $col['count'] . ' matches on Performance Title';
                  break;
                  case 'acount':
                    $colMsg = ' ' . $col['count'] . ' matches on Author';
                  break;
                  case 'pccount':
                    $colMsg = ' ' . $col['count'] . ' matches on Performance Comments';
                  break;
                  case 'eccount':
                    $colMsg = ' ' . $col['count'] . ' matches on Event Comments';
                  break;
                  case 'ccount':
                    $colMsg = ' ' . $col['count'] . ' matches on Roles/Actors';
                  break;
                }
                if ($resCnt < count($resByCol)) {
                  $msg .= $colMsg . ',';
                } else {
                  $msg .= ' and ' . $colMsg . '.';
                }
                $resCnt++;
              }
              echo '<div class="res-by-col">' . $msg . '</div>';
            } ?>
            <?php if ($results->total > 0) { ?>
            <div class="grid-x results-header">
              <div class="input-group relevance-menu-wrap">
                <label for="sortBy" class="input-group-label relevance-menu">Sort By
              </label>
                <select name="sortBy" id="sortBy" class="input-group-field">
                <option value="relevance" <?php getSticky(2, 'sortBy', 'relevance'); ?>>Relevance</option>
                <option value="datea" <?php getSticky(2, 'sortBy', 'datea'); ?>>Date (asc)</option>
                <option value="dated" <?php getSticky(2, 'sortBy', 'dated'); ?>>Date (desc)</option>
              </select>
                <div class="input-group-button">
                  <input type="submit" class="search-submit button" value="Update">
                </div>
              </div>
              <nav aria-label="Pagination" class="grid-x pag-wrap">
                <?php echo $Paginator->createLinks( $links, 'pagination pagination-sm' ); ?>
              </nav>
            </div>
            <div class="grid-x results-table">
              <div class="cell">
                <?php if (!empty($_GET['author'])) : ?>
                <div class="author-explain">
                  <span class="info-icon"></span>
                  <span>Results not only include performances of plays known to be by '<?php echo cleanQuotes(cleanStr($_GET['author'])); ?>', but also performances of associated titles, including adaptations.</span>
                </div>
                <?php endif; ?>
                <?php for( $i = 0; $i < count( $results->data ); $i++ ) : ?>
                <div class="event">
                  <?php $results->data[$i]['performances'] = getPerformances($results->data[$i]['eventid']); ?>
                  <div class="evt-head grid-x">
                    <h2><a href="event.php?id=<?php echo $results->data[$i]['eventid'] ?>">
                    <div class="evt-num"><?php echo (($limit * ($page - 1)) + ($i + 1)); ?></div>
		    <?php echo formatDate($results->data[$i]['eventdate']); ?>
                    <span class="evt-theatre"> @ <?php echo getTheatreName($results->data[$i]['theatreid']); ?></span>
                    </a>
                  </h2>
                </div>
                <div class="evt-body">
                  <?php if (isset($_GET['keyword']) && isFoundIn($results->data[$i]['commentc'], cleanQuotes(cleanStr($_GET['keyword'])))) echo '<div class="evt-info"><b>Event Comment: </b>' . highlight(namedEntityLinks($results->data[$i]['commentc'], true), cleanQuotes($_GET['keyword'])) . '</div>';?>
                  <div class="evt-other clearfix">
                    <div class="perfs">
                      <h3>Performances</h3>
                      <?php foreach ($results->data[$i]['performances'] as $perf) {
                        if ((isset($_GET['author']) && trim($_GET['author']) !== '') || (isset($_GET['keyword']) && trim($_GET['keyword']) !== '')) {
                            $perf['RelatedWorks'] = getSphinxRelatedWorks($perf['PerformanceTitle']);
                        }
                        echo '<div class="perf">';
                        echo '<h4><span class="info-heading">' . getPType($perf['PType']) . (in_array($perf['PType'], ['a', 'p']) ? ' Title' : '') . ': </span>';
                        $to_highlight = isset($_GET['keyword']) ? cleanQuotes($_GET['keyword']) : '';
                        $to_highlight .= (!empty($_GET['performance'])) ? '|' . cleanQuotes($_GET['performance']) : '';
                        if (in_array($perf['PType'], ['a', 'p'])) {
                            $to_highlight = isset($_GET['keyword']) ? cleanQuotes($_GET['keyword']) : '';
                            $to_highlight .= (!empty($_GET['performance'])) ? '|' . cleanQuotes($_GET['performance']) : '';
                            echo '<i>' . highlight(cleanItalics(cleanTitle($perf['PerformanceTitle'])), !empty($to_highlight) ? $to_highlight : NULL) . '</i>';
                        } else {
                            echo highlight(namedEntityLinks($perf['DetailedComment'], true), !empty($to_highlight) ? $to_highlight : NULL);
                        }
                        echo '</h4>';
                        if (isset($_GET['keyword']) && isFoundIn($perf['CommentP'], cleanQuotes(cleanStr($_GET['keyword']))) ) echo '<span class="perf-comm"><span class="smcp"><b>Performance Comment: </b></span>' . highlight(namedEntityLinks($perf['CommentP'], true), cleanQuotes($_GET['keyword'])) . '</span><br>';
                        echo '<div class="perf-body">';
                         $inCast = isInCast(cleanQuotes($_GET['keyword']) . '|' . cleanQuotes($cleanedActors), cleanQuotes(cleanStr($_GET['keyword'] || '')) . '|' . cleanQuotes($cleanedRoles), $perf['cast']);
                         if ($inCast !== false) {
                           echo '<div class="cast"><h5>Cast</h5>';
                           foreach ($inCast as $cast) {
                             echo '<span class="c-role"><span class="smcp"><b>Role</b></span>: ' . highlight(linkedSearches('role[]', $cast['Role'], true), cleanQuotes($_GET['keyword']) . '|' . cleanQuotes($cleanedRoles)) . '</span> <span class="c-act"><span class="smcp"><b>Actor</b></span>: ' . highlight(linkedSearches('actor[]', $cast['Performer'], true), cleanQuotes($_GET['keyword']) . '|' . cleanQuotes($cleanedActors)) . '</span><br>';
                           }
                           echo '</div>';
                         }
                         if ((isset($_GET['author']) && trim($_GET['author']) !== '') || (isset($_GET['keyword']) && trim($_GET['keyword']) !== '')) {
                           if (!empty($perf['RelatedWorks']) && count($perf['RelatedWorks']) > 0) {
                             $isFoundInRelated = false;
                            $isFoundUnique = array(); // Track unique work names
                            $isFoundArr = array();
                            foreach ($perf['RelatedWorks'] as $rltd) {
                              if (isset($rltd['author']) && count($rltd['author']) > 0) {
                                foreach ($rltd['author'] as $rltdAuth) {
                                  if (isFoundIn($rltdAuth['authname'], cleanQuotes(cleanStr($_GET['keyword'])) . '|' . cleanQuotes(cleanStr($_GET['author'])))) {
                                    $isFoundInRelated = true;
                                    if (!in_array($rltd['title'], $isFoundUnique)) {
                                      $isFoundUnique[] = $rltd['title'];
                                      $isFoundArr[] = $rltd;
                                    }
                                    //break 2;
                                  }
                                }
                              }
                            }
                            if ($isFoundInRelated) {
                              echo '<div class="rltd-wrks"><h5>Related Works</h5>';
                                foreach ($isFoundArr as $rltd2) {
                                  echo '<div class="rltd-auth"><span class="work-wrap"><span class="smcp"><b>Related Work:</b></span> ' . $rltd2['title'] . '</span> ';
                                  echo '<span class="auth-wrap"><span class="smcp"><b>Author(s):</b></span> ';
                                  foreach ($rltd2['author'] as $rltdAuth2) {
                                    if (isFoundIn($rltdAuth2['authname'], cleanQuotes(cleanStr($_GET['keyword'])) . '|' . cleanQuotes(cleanStr($_GET['author'])))) {
                                      echo '<span class="auth">' . highlight($rltdAuth2['authname'], cleanQuotes($_GET['keyword']) . '|' . cleanQuotes($_GET['author'])) . '</span>';
                                    }
                                  }
                                  echo '</span></div>';
                                }
                              echo '</div>'; // End rltd-wrks
                            }
                          }
                        }
                        echo '</div></div>'; // End perf-body and perf
                      } ?>
                    </div>
                  </div>
                </div>
              </div>
              <?php endfor; ?>
            </div>
          </div>
          <nav aria-label="Pagination" class="grid-x pag-wrap">
            <?php echo $Paginator->createLinks( $links, 'pagination pagination-sm pag-bottom' ); ?>
          </nav>
          <?php } else { ?>
            <div class="no-results">
              No results found. Modify the filters to the left or <a href="/index.php">try a new search</a>
            </div>
          <?php } ?>
        </div>
      </div>
  </div>
  </form>
  </div>
  <?php include_once('common/footer.php'); ?>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="/js/search.js"></script>

  <!--

  <?php
  $time = microtime();
  $time = explode(' ', $time);
  $time = $time[1] + $time[0];
  $finish = $time;
  $total_time = round(($finish - $start), 4);
  echo "Page generated in $total_time seconds.";
  ?>


  -->
</body>

</html>
