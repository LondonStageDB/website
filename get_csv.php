<?php
  include_once('includes/functions.php');

  $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

  // Ensure ID isn't blank or negative
  getResultsCSV(($id !== '' && $id > 0) ? [$id] : []);
?>
