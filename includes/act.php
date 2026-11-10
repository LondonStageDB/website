<?php
  include_once('functions.php');
  global $mnanticore_conn;

  $searchTerm = $_GET['term'];
  $searchTerm = cleanStr($searchTerm);
  $searchTerm = mysqli_real_escape_string($manticore_conn, $searchTerm);

  // If search string is over 3 chars, full wildcard search,
  // Else exact match beginning of string with wildcard at the end
  $search = strlen($searchTerm) > 3 ?
    "select performer from london_stages 
                       where MATCH('@(performerclean,performer) ^$searchTerm*') group by performer 
                    LIMIT 10":
    "select performer from london_stages 
                       where MATCH('@(performerclean,performer) *$searchTerm*') group by performer
                    LIMIT 10";

  $result = $manticore_conn->query($search);

  $data = array();
  while ($row = $result->fetch_assoc()) {
    $data[] = trim($row['performer']);
  }
  echo json_encode(array_unique($data));
?>
