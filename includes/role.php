<?php
  include_once('functions.php');
  global $sphinx_conn;

  $searchTerm = $_GET['term'];
  $searchTerm = cleanStr($searchTerm);
  $searchTerm = mysqli_real_escape_string($sphinx_conn, $searchTerm);

  // If search string is over 3 chars, full wildcard search,
  // Else exact match beginning of string with wildcard at the end
  $search = strlen($searchTerm) > 3 ?
    "select roleclean from london_stages 
                       where MATCH('@(role,roleclean) $searchTerm*') group by roleclean 
                       LIMIT 10 OPTION ranker=sph04":
    "select roleclean from london_stages 
                       where MATCH('@(role,roleclean) *$searchTerm*') group by roleclean 
                       LIMIT 10 OPTION ranker=sph04 ";

  $result = $sphinx_conn->query($search);

  $data = array();
  while ($row = $result->fetch_assoc()) {
    $data[] = trim($row['roleclean']);
  }

  echo json_encode(array_unique($data));
?>
