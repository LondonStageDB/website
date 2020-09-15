<?php
  include_once('includes/functions.php');
  ini_set('memory_limit', '1024M');
  ini_set('max_execution_time', '0');
  global $conn;

  $qResults = [];
  $ids = [];

  // Use current $_GET variables to build the same query used on results page, minus the LIMIT
  $sql = buildQuery();
  $result = $conn->query($sql);

  while ($row = $result->fetch_assoc()) {
    $qResults[] = $row;
  }

  // Only care about EventIds
  $ids = array_column($qResults, 'EventId');

  // Send IDs array to get all event info, returns XML Doc
  getResultsXML($ids);
?>
