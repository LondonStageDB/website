<?php
  include_once('includes/functions.php');
  require_once 'includes/Paginator.class.php';

  global $conn;

  // Build query with $_GET values
  $sql = buildQuery();

  // Get and sanitize 'limit' and 'p' for pagination
  $g_lim = filter_input(INPUT_GET, 'limit', FILTER_SANITIZE_NUMBER_INT);
  $g_p = filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT);
  $limit      = ( $g_lim !== '' && $g_lim > 0 ) ? $g_lim : 25;
  $page       = ( $g_p !== '' && $g_p > 0 ) ? $g_p : 1;
  $links      = 3;
  $Paginator  = new Paginator( $conn, $sql );

  // Get paginated results
  $results    = $Paginator->getData( $limit, $page );
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
      <div class="toggle-query"><a id="toggle">Toggle Query</a></div>
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
            <a href="get_all_json.php?<?php echo $_SERVER['QUERY_STRING']; ?>" class="button dwnld-btn">Download JSON</a>
            <!--<a href="get_all_xml.php?ids=<?php //echo htmlspecialchars(json_encode($allResultIds), ENT_QUOTES); ?>" class="button dwnld-btn">Download XML</a>-->
            <a href="get_all_xml.php?<?php echo $_SERVER['QUERY_STRING']; ?>" class="button dwnld-btn">Download XML</a>
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
                      <option value="" disabled selected>Select a Theatre</option>
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
                          <?php getYears('start'); ?>
                        </select>
                        <label class="hidden" for="startMonth">Start Month</label>
                        <select class="date-month" id="startMonth" name="start-month" disabled>
                          <option disabled selected>Mon</option>
                          <?php getMonths('start'); ?>
                        </select>
                        <label class="hidden" for="startDay">Start Day</label>
                        <select class="date-day" id="startDay" name="start-day" disabled>
                          <option disabled selected>Day</option>
                          <?php getDays('start'); ?>
                        </select>
                      </div>
                      <div class="year end-year">
                        <span class="year-title">End</span>
                        <label class="hidden" for="endYear">End Year</label>
                        <select class="date-year" id="endYear" name="end-year">
                          <option disabled selected>Year</option>
                          <?php getYears('end'); ?>
                        </select>
                        <label class="hidden" for="endMonth">End Month</label>
                        <select class="date-month" id="endMonth" name="end-month" disabled>
                          <option disabled selected>Mon</option>
                          <?php getMonths('end'); ?>
                        </select>
                        <label class="hidden" for="endDay">End Day</label>
                        <select class="date-day" id="endDay" name="end-day" disabled>
                          <option disabled selected>Day</option>
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
                    <input type="text" class="actor" name="actor" id="actor" value="<?php getSticky(1, 'actor'); ?>" onKeyPress="checkEnter(event)">
                  </div>
                </div>
                <div class="small-12 cell">
                  <div class="form-group field-role">
                    <label for="role" class="fb-text-label">Role</label>
                    <input type="text" class="role" name="role" id="role" value="<?php getSticky(1, 'role'); ?>" onKeyPress="checkEnter(event)">
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
            <?php echo $results->total . ' results'; ?>
            <?php if ($results->total > 0) { ?>
            <div class="grid-x results-header">
              <div class="input-group relevance-menu-wrap">
                <label for="sortBy" class="input-group-label relevance-menu">Sort By
              </label>
                <select name="sortBy" id="sortBy" class="input-group-field">
                <option value="relevance" <?php getSticky(2, 'sortBy', 'relevance'); ?>>Relevance</option>
                <option value="date" <?php getSticky(2, 'sortBy', 'date'); ?>>Date</option>
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
                <?php for( $i = 0; $i < count( $results->data ); $i++ ) : ?>
                <div class="event">
                  <?php $results->data[$i]['Performances'] = getPerformances($results->data[$i]['EventId']); ?>
                  <?php echo '<div class="evt-head grid-x">
                  <h2><a href="event.php?id=' . $results->data[$i]['EventId'] . '">' . formatDate($results->data[$i]['EventDate']); ?>
                    <span class="evt-theatre"> @ <?php echo getTheatreName($results->data[$i]['TheatreId']); ?></span>
                    </a>
                  </h2>
                </div>
                <div class="evt-body">
                  <?php if (isFoundIn($results->data[$i]['CommentC'], cleanQuotes($_GET['keyword']))) echo '<div class="evt-info"><b>Event Comment: </b>' . highlight(namedEntityLinks($results->data[$i]['CommentC']), cleanQuotes($_GET['keyword'])) . '</div>';?>
                  <div class="evt-other clearfix">
                    <div class="perfs">
                      <h3>Performances</h3>
                      <?php foreach ($results->data[$i]['Performances'] as $perf) {
                        echo '<div class="perf">';
                        echo '<h4><span class="info-heading">' . getPType($perf['PType']) . ' Title: </span><i>' . highlight(cleanItalics($perf['PerformanceTitle']), cleanQuotes($_GET['keyword']) . '|' . cleanQuotes($_GET['performance'])) . '</i></h4>';
                        if (isFoundIn($perf['CommentP'], cleanQuotes($_GET['keyword'])) ) echo '<b>Performance Comment: </b>' . highlight(namedEntityLinks($perf['CommentP']), cleanQuotes($_GET['keyword'])) . '<br>';
                        $inCast = isInCast(cleanQuotes($_GET['keyword']) . '|' . cleanQuotes($_GET['actor']), cleanQuotes($_GET['keyword']) . '|' . cleanQuotes($_GET['role']), $perf['cast']);
                        if ($inCast !== false) {
                          echo '<div class="cast"><h5>Cast</h5>';
                          foreach ($inCast as $cast) {
                            echo '<b>Role</b>: ' . highlight(linkedSearches('role', $cast['Role']), cleanQuotes($_GET['keyword']) . '|' . cleanQuotes($_GET['role'])) . "\t\t <b>Actor</b>: " . highlight(linkedSearches('actor', $cast['Performer']), cleanQuotes($_GET['keyword']) . '|' . cleanQuotes($_GET['actor'])) . '<br>';
                          }
                          echo '</div>';
                        }
                        echo '</div>';
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
          <?php } ?>
        </div>
      </div>
  </div>
  </form>
  </div>
  <?php include_once('common/footer.php'); ?>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="/js/search.js"></script>
</body>

</html>
