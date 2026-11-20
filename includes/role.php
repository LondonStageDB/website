<?php
  include_once('functions.php');
  global $search_conn;

  $searchTerm = $_GET['term'];
  $searchTerm = cleanStr($searchTerm);
  $searchTerm = mysqli_real_escape_string($search_conn, $searchTerm);

  // Algorithm: prioritize exact matches
  $ranker = "OPTION ranker = expr('sum(exact_hit*50 + (min_hit_pos==1)*15 + hit_count + role_freq)')";

  $match = strlen($searchTerm) < 4 ?
    "@roleclean[3] (^$searchTerm*)" :
    "@roleclean[3] (^$searchTerm$ | ^$searchTerm* | *$searchTerm*)";

  $search = "select roleclean, weight() w
             from role
             where MATCH('$match')
             order by w desc
             limit 10
             $ranker;";

  $result = $search_conn->query($search);

  $data = array();
  while ($row = $result->fetch_assoc()) {
    $data[] = trim($row['roleclean']);
  }
  $data = array_values(array_unique($data));

  // Makes sure exact matches come first
  usort($data, function ($a, $b) use ($searchTerm) {
    return (strcasecmp($b, $searchTerm) === 0) <=> (strcasecmp($a, $searchTerm) === 0);
  });

  echo json_encode($data);
?>
