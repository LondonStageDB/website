<!doctype html>
<html class="no-js yes-flex" lang="en">

<head>
  <?php include_once('common/header.php'); ?>
  <title>Search</title>
</head>

<body id="home" class="home-page">
  <?php include_once('common/nav.php'); ?>
  <div id="main" class="main">
    <div class="form-wrap">
      <h1>London Stage Database</h1>
      <div>
        <p><strong>Try our new faster search!</strong></p>
      </div>
      <form id="searchForm" class="search-form grid-x grid-margin-x" method="get" action="sphinx-results.php">
        <label for="keyword" class="fb-text-label show-for-sr">Keyword</label>
        <input type="text" class="keyword input-group-field" name="keyword" id="keyword" placeholder='Search (e.g., Behn, Macbeth, harlequin, or "riot")' autofocus>
        <input type="submit" class="button input-group-button" value="Search" />
        <a href="search.php" class="adv-search">Advanced Search</a>
      </form>
    </div>
  </div>

  <?php include_once('common/footer.php'); ?>
</body>

</html>
