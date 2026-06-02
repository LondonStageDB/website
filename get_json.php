<?php
  // Turnstile gate: challenge unverified visitors before opening any DB connection.
  require_once 'includes/turnstile_gate.php';

  include_once('includes/functions.php');

  $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

  // Ensure ID isn't blank or negative
  getJSON(($id !== '' && $id > 0) ? $id : null);
?>
