<!doctype html>
<html class="no-js yes-flex" lang="en">

<head>
  <?php include_once('common/header.php'); ?>
  <title>Search</title>
</head>

<body id="home" class="home-page">
  <?php include_once('common/nav.php'); ?>
  <main class="main">
    <div class="form-wrap">
      <h1>London Stage Database</h1>
      <form id="searchForm" class="search-form grid-x grid-margin-x" method="get" action="sphinx-results.php">
        <label for="keyword" class="fb-text-label show-for-sr">Keyword</label>
        <input type="text" class="keyword input-group-field" name="keyword" id="keyword" placeholder='Search (e.g., Behn, Macbeth, harlequin, or "riot")' autofocus>
        <input type="submit" class="button input-group-button" value="Search" />
        <a href="search.php" class="adv-search">Advanced Search</a>
      </form>
       <div class="margin-top-1rem">
            
          <p>This Legacy site allows users to reproduce results obtained before 
            <a href="https://blogs.uoregon.edu/londonstage/2026/11/12/manticore/">a major system upgrade</a> on November 19, 2026. 
            To begin a new search, please visit <a href="https://londonstagedatabase.uoregon.edu">our main website</a>.</p>
      </div>
    </div>
   
</main>

  <?php include_once('common/footer.php'); ?>
</body>

</html>
