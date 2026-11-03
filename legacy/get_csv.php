<?php
  // Turnstile gate: challenge unverified visitors before opening any DB connection.
  require_once __DIR__ . '/../includes/turnstile_gate.php';

  include_once(__DIR__ . '/includes/functions.php');

  $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

  // Ensure ID isn't blank or negative
  getResultsCSV(($id !== '' && $id > 0) ? [$id] : []);
?>
