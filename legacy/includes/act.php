<?php
  include_once('functions.php');
  global $conn;

  $searchTerm = $_GET['term'];
  $searchTerm = cleanStr($searchTerm);
  $searchTerm = mysqli_real_escape_string($conn, $searchTerm);

  // If search string is over 3 chars, full wildcard search,
  // Else exact match beginning of string with wildcard at the end
  $search = strlen($searchTerm) > 3 ?
    "SELECT *, (MATCH(PerformerClean) AGAINST ('\"$searchTerm\" @4' IN BOOLEAN MODE) + case when PerformerClean LIKE '$searchTerm' then 50 when PerformerClean LIKE '$searchTerm%' then 15 end) as relevance FROM `Cast` WHERE (MATCH(PerformerClean) AGAINST ('\"$searchTerm\" @4' IN BOOLEAN MODE) OR PerformerClean LIKE '%$searchTerm%') GROUP BY Performer ORDER BY relevance DESC, Performer LIMIT 10" :
    "SELECT * FROM `Cast` WHERE PerformerClean LIKE '$searchTerm%' GROUP BY Performer ORDER BY Performer LIMIT 10";

  $result = $conn->query($search);

  $data = array();
  while ($row = $result->fetch_assoc()) {
    $data[] = trim($row['PerformerClean']);
  }

  echo json_encode(array_unique($data));
?>
