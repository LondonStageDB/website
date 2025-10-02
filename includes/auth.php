<?php
  include_once('functions.php');
  global $sphinx_conn;

  $searchTerm = $_GET['term'];
  $searchTerm = cleanStr($searchTerm);
  $searchTerm = mysqli_real_escape_string($sphinx_conn, $searchTerm);

  // If search string is over 3 chars, full wildcard search,
  // Else exact match beginning of string with wildcard at the end
  $search = strlen($searchTerm) > 3 ?
    "select authname from author where MATCH('$searchTerm*') LIMIT 10" :
    "select authname from author where MATCH('*$searchTerm*')";

  $result = $spihx_conn->query($search);

  $data = array();
  while ($row = $result->fetch_assoc()) {
    $data[] = trim($row['AuthName']);
  }

  echo json_encode(array_unique($data));
?>
