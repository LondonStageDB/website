<?php
  include_once('includes/functions.php');

  $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

  // Ensure ID isn't blank or negative
  getJSON(($id !== '' && $id > 0) ? $id : null);

  if (isset($conn) && $conn instanceof mysqli) { $conn->close(); }
  if (isset($sphinx_conn) && $sphinx_conn instanceof mysqli) { $sphinx_conn->close(); }
?>
