<?php
  include_once('includes/functions.php');

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recaptcha_token'])) {
    // Build POST request:
    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_secret = ReCaptcha::GOOGLE_RECAPTCHA_SECRET_KEY;
    $recaptcha_response = $_POST['recaptcha_token'];

    // Make and decode POST request:
    $response = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
    $responseData  = json_decode($response);

    // Take action based on the score returned:
    if ($responseData->success && $responseData->score >= 0.5) {
      // Verified, start downloading

      global $sphinx_conn;

      $qResults = [];
      $ids = [];

      // Use current $_GET variables to build the same query used on results page, minus the LIMIT
      $sql = buildSphinxQuery();
      $sql .= ' LIMIT 99999 OPTION max_matches=99999';
      $result = $sphinx_conn->query($sql);

      while ($row = $result->fetch_assoc()) {
        $qResults[] = $row;
      }

      // Only care about EventIds
      $ids = array_column($qResults, 'eventid');

      // Send IDs array to get all event info, returns CSV Doc
      getResultsCSV($ids);

      die();
    }
  }

?>
<html>
<head>
<title>Download CSV</title>

<script
  src="https://www.google.com/recaptcha/api.js?render=<?php echo ReCaptcha::GOOGLE_RECAPTCHA_SITE_KEY; ?>">
</script>
<script>
  grecaptcha.ready(function() {
    grecaptcha.execute("<?php echo ReCaptcha::GOOGLE_RECAPTCHA_SITE_KEY; ?>", {action: 'download'}).then(function(token) {
      // Send the token to your server
      document.getElementById('recaptcha-token').value = token;
    });
  });
</script>
<style>
  .backdrop {
    position: fixed; /* Positions the backdrop relative to the viewport */
    top: 0;
    left: 0;
    width: 100vw; /* Full viewport width */
    height: 100vh; /* Full viewport height */
    background-color: rgba(41, 33, 18); /* Semi-transparent black backdrop */
    display: flex; /* Enables flexbox for centering */
    justify-content: center; /* Horizontally centers content */
    align-items: center; /* Vertically centers content */
    z-index: 999; /* Ensures the backdrop is on top of other content */
  }

  .centered-button {
    /* Basic button styling */
    padding: 15px 30px;
    font-size: 1.2em;
    background-color: #6e2424;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}
</style>
</head>
<body class="backdrop">
<form method="POST" id="recaptcha-form">
  <input type="hidden" name="recaptcha_token" id="recaptcha-token"/>
  <button type="submit" class="centered-button">I'm human.</button>
</form>
</body>
</html>