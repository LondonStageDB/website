<?php
  include_once('functions.php');
  global $manticore_conn;

  $searchTerm = $_GET['term'];
  $searchTerm = cleanStr($searchTerm);
  $searchTerm = mysqli_real_escape_string($manticore_conn, $searchTerm);

  // Algorithm: prioritize exact matches
  $ranker = "OPTION ranker = expr('sum(exact_hit*75 + (min_hit_pos==1)*15 + hit_count + performer_freq)')";

  $match = strlen($searchTerm) < 4 ?
    "@performerclean[3] (^$searchTerm*)" :
    "@performerclean[3] (^$searchTerm$ | ^$searchTerm* | *$searchTerm*)";

  $search = "select performerclean, weight() w
             from performer
             where MATCH('$match')
             order by w desc
             limit 10
             $ranker;";

  $result = $manticore_conn->query($search);

  $data = array();
  while ($row = $result->fetch_assoc()) {
    $data[] = trim($row['performerclean']);
  }
  $data = array_values(array_unique($data));

  // Makes sure exact matches come first
  usort($data, function ($a, $b) use ($searchTerm) {
    return (strcasecmp($b, $searchTerm) === 0) <=> (strcasecmp($a, $searchTerm) === 0);
  });

  echo json_encode($data);
?>