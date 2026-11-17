<?php
  include_once('functions.php');
  global $manticore_conn;

  $searchTerm = $_GET['term'];
  $searchTerm = cleanStr($searchTerm);
  $searchTerm = mysqli_real_escape_string($manticore_conn, $searchTerm);

  // Relevance function (prioritize close matches and frequent roles)
  $ranker = "OPTION ranker = expr('sum(exact_hit*10 + (min_hit_pos==1)*5 + exact_order*3)*10 + role_freq')";
  
  // If search string is over 3 chars, full wildcard search,
  // Else exact match beginning of string with wildcard at the end
  $match = strlen($searchTerm) < 4 ?
    "@(role,roleclean) ^$searchTerm*" :
    "@(role,roleclean) (^$searchTerm* | *$searchTerm*)";

  // Build search query from MATCH and ranker
  $search = "select roleclean
            from role
            where MATCH('$match')
            limit 10
            $ranker;";

  $result = $manticore_conn->query($search);

  $data = array();
  while ($row = $result->fetch_assoc()) {
    $data[] = trim($row['roleclean']);
  }

  echo json_encode(array_unique($data));
?>
