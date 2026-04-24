<?php
  include_once('functions.php');
  global $sphinx_conn;

  $searchTerm = $_GET['term'];
  $searchTerm = cleanStr($searchTerm);
  $searchTerm = mysqli_real_escape_string($sphinx_conn, $searchTerm);

  // If search string is over 3 chars, full wildcard search,
  // Else exact match beginning of string with wildcard at the end
  $search = strlen($searchTerm) > 3 ?
    "select authname, authnameclean from author 
                               where MATCH('@(authname,authnameclean) $searchTerm*') LIMIT 10" :
    "select authname, authnameclean from author 
                               where MATCH('@(authname,authnameclean) *$searchTerm*') LIMIT 10";

  $result = $sphinx_conn->query($search);

  $names = array();
  while ($row = $result->fetch_assoc()) {
    $names[] = trim($row['authname']);
    if (!empty($row['authnameclean'])) {
      $names[] = $row['authnameclean'];
    }
  }
  echo json_encode(array_unique($names));
?>
