<?php
  include_once('includes/functions.php');
?>
<!doctype html>
<html class="no-js" lang="en">

<head>
  <?php include_once('common/header.php'); ?>
  <title>Search</title>
</head>

<body id="mainSearch">
  <?php include_once('common/nav.php'); ?>
  <div id="main" class="main grid-container">
    <h1>Advanced Search</h1>
    <form id="searchForm" class="form-accordion search-form grid-x grid-margin-x" method="get" action="results.php">
      <input type="hidden" name="sortBy" value="relevance">
      <div class="cell small-12 section-wrap">
        <h2>Event</h2>
        <div class="grid-x acc-section">
          <div class="small-12 medium-8 large-6 cell form-section">
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
          <div class="small-12 medium-8 large-6 cell form-section">
            <div class="form-group field-dates">
          <fieldset class="grid-x">
            <legend>Date Range</legend>
            <div class="date-type-label">
            <label class="hidden date-type hide" for="dateType">Date Type</label>
            <select id="dateType" class="date-type hide" name="date-type">
              <option value="1" <?php getSticky(2, 'date-type', '1'); ?>>Between</option>
              <option value="2" <?php getSticky(2, 'date-type', '2'); ?>>Before</option>
              <option value="3" <?php getSticky(2, 'date-type', '3'); ?>>On</option>
              <option value="4" <?php getSticky(2, 'date-type', '4'); ?>>After</option>
            </select>
            </div>
            <div class="small-12 cell">
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
            </div>
              </fieldset>
            </div>
          </div>
        </div>
      </div> <!-- End event wrap -->

      <div class="cell small-12 section-wrap">
        <h2>Performance</h2>
        <div class="grid-x acc-section">
          <div class="small-12 medium-8 large-6 cell form-section">
            <div class="form-group field-performance">
              <label for="performance" class="fb-text-label">Title</label>
              <input type="text" class="performance" name="performance" id="performance" onKeyPress="checkEnter(event)">
            </div>
            <div class="form-group field-author inline-label">
              <label for="author" class="fb-select-label">Author</label>
              <span data-tooltip class="top l-tooltip" tabindex="2" title="Searches not only for performances of plays known to be by this author, but also performances of associated titles, including adaptations.">?</span>
              <input type="text" class="author" name="author" id="author" value="<?php getSticky(1, 'author'); ?>" onKeyPress="checkEnter(event)">
            </div>
          </div>
          <div class="small-12 medium-8 large-6 cell form-section">
            <div class="form-group field-ptype">
              <fieldset class="ptype-contain">
                <div class="ptype-legend"><legend>Filter by Performance Type</legend></div>
                <div class="ptype">
                <span class="a-ptype"><input type="checkbox" name="ptype[]" value="p" id="mainpiece" <?php getSticky(3, 'ptype', 'p'); ?>><label for="mainpiece">Mainpiece</label></span>
                <span class="a-ptype"><input type="checkbox" name="ptype[]" value="a" id="afterpiece" <?php getSticky(3, 'ptype', 'a'); ?>><label for="afterpiece">Afterpiece</label></span>
                <span class="a-ptype"><input type="checkbox" name="ptype[]" value="m" id="music" <?php getSticky(3, 'ptype', 'm'); ?>><label for="music">Music</label></span>
                <span class="a-ptype"><input type="checkbox" name="ptype[]" value="d" id="dance" <?php getSticky(3, 'ptype', 'd'); ?>><label for="dance">Dance</label></span>
                <span class="a-ptype"><input type="checkbox" name="ptype[]" value="e" id="entertainment" <?php getSticky(3, 'ptype', 'e'); ?>><label for="entertainment">Entertainment</label></span>
                <span class="a-ptype"><input type="checkbox" name="ptype[]" value="s" id="song" <?php getSticky(3, 'ptype', 's'); ?>><label for="song">Song</label></span>
                <span class="a-ptype"><input type="checkbox" name="ptype[]" value="b" id="ballet" <?php getSticky(3, 'ptype', 'b'); ?>><label for="ballet">Ballet</label></span>
                <span class="a-ptype"><input type="checkbox" name="ptype[]" value="i" id="instrumental" <?php getSticky(3, 'ptype', 'i'); ?>><label for="instrumental">Instrumental</label></span>
                <span class="a-ptype"><input type="checkbox" name="ptype[]" value="o" id="opera" <?php getSticky(3, 'ptype', 'o'); ?>><label for="opera">Opera</label></span>
                <span class="a-ptype"><input type="checkbox" name="ptype[]" value="u" id="monologue" <?php getSticky(3, 'ptype', 'u'); ?>><label for="monologue">Monologue</label></span>
                <span class="a-ptype"><input type="checkbox" name="ptype[]" value="t" id="trick" <?php getSticky(3, 'ptype', 't'); ?>><label for="trick">Trick</label></span>
                </div>
              </fieldset>
            </div>
          </div>
        </div>
      </div> <!-- End Perf wrap -->

      <div class="cell small-12 section-wrap">
        <h2>Cast</h2>
        <div class="grid-x acc-section">
          <div class="small-12 medium-8 large-6 cell form-section">
            <div class="form-group field-actor inline-label">
              <label for="actor" class="fb-text-label">Actor</label>
              <span data-tooltip class="top l-tooltip" tabindex="2" title="We recommend searching by last name (for example, instead of 'Dorothy Jordan,' search 'Jordan' to return instances where she is listed as 'Mrs Jordan')">?</span>
              <span class="cast-switch">
                <label for="actSwitch" class="show-for-sr">Select 'AND' or 'OR' search on multiple actors.</label>
                <select name="actSwitch" id="actSwitch" title="Select 'AND' or 'OR' search on multiple actors." disabled="disabled">
                  <option value="and">AND</option>
                  <option value="or">OR</option>
                </select>
              </span>
              <span id="actors">
                <input type="text" class="actor actor-search" name="actor[]" id="actor" onKeyPress="checkEnter(event)">
              </span>
              <div class="addActor"><a id="addActor" class="addCast" title="Add an actor">+</a></div>
            </div>
          </div>
          <div class="small-12 medium-8 large-6 cell form-section">
            <div class="form-group field-role inline-label">
              <label for="role" class="fb-text-label">Role</label>
              <span class="cast-switch">
                <label for="roleSwitch" class="show-for-sr">Select 'AND' or 'OR' search on multiple roles.</label>
                <select name="roleSwitch" id="roleSwitch" title="Select 'AND' or 'OR' search on multiple roles." disabled="disabled">
                  <option value="and">AND</option>
                  <option value="or">OR</option>
                </select>
              </span>
              <span id="roles">
                <input type="text" class="role role-search" name="role[]" id="role" onKeyPress="checkEnter(event)">
              </span>
              <div class="addRole"><a id="addRole" class="addCast" title="Add a role">+</a></div>
            </div>
          </div>
        </div>
      </div> <!-- End cast wrap -->

      <div class="cell small-12 section-wrap keyword-wrap">
        <h2>Keyword</h2>
        <div class="grid-x acc-section">
          <div class="small-12 medium-8 large-6 cell form-section">
            <div class="form-group field-keyword inline-label">
              <label for="keyword" class="fb-text-label">Keyword </label>
              <span data-tooltip class="top l-tooltip" tabindex="2" title="Keyword searches event comments, performance titles, performance comments, roles, actors, and author names.">?</span>
              <input type="text" class="keyword" name="keyword" id="keyword" onKeyPress="checkEnter(event)">
            </div>
          </div>
        </div>
      </div>
      <div class="cell small-12 submit-wrap">
        <input type="submit" class="search-submit button" value="Search">
      </div>
    </form>
  </div>
  <?php include_once('common/footer.php'); ?>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="/js/search.js"></script>
</body>
</html>
