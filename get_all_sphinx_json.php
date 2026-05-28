<?php
  // Turnstile gate: challenge unverified visitors before opening any DB connection.
  require_once 'includes/turnstile_gate.php';

  include_once('includes/functions.php');

  global $sphinx_conn;

  // Use current $_GET variables to build the same query used on results page, minus the LIMIT
  $sql  = buildSphinxQuery();
  $sql .= ' LIMIT 99999 OPTION max_matches=99999';
  $result = $sphinx_conn->query($sql);

  $qResults = [];
  while ($row = $result->fetch_assoc()) {
    $qResults[] = $row;
  }

  // Only care about EventIds
  $ids = array_column($qResults, 'eventid');

  // Send IDs array to get all event info, returns JSON Doc
  getResultsJSON($ids);
