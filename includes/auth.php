<?php
  include_once("db.php");
  include_once('functions.php');
  global $conn;

  $searchTerm = $_GET['term'];
  $searchTerm = cleanStr($searchTerm);
  $searchTerm = mysqli_real_escape_string($conn, $searchTerm);

  // If search string is over 3 chars, full wildcard search,
  // Else exact match beginning of string with wildcard at the end
  $search = strlen($searchTerm) > 3 ?
    "SELECT *, (MATCH(AuthNameClean) AGAINST ('\"$searchTerm\" @4' IN BOOLEAN MODE) + case when AuthNameClean LIKE '$searchTerm' then 50 when AuthNameClean LIKE '$searchTerm%' then 15 end) as relevance FROM `Author` WHERE (MATCH(AuthNameClean) AGAINST ('\"$searchTerm\" @4' IN BOOLEAN MODE) OR AuthNameClean LIKE '%$searchTerm%') GROUP BY AuthName ORDER BY relevance DESC, AuthName LIMIT 10" :
    "SELECT * FROM `Author` WHERE AuthNameClean LIKE '$searchTerm%' GROUP BY AuthName ORDER BY AuthName LIMIT 10";

  $result = $conn->query($search);

  $data = array();
  while ($row = $result->fetch_assoc()) {
    $data[] = $row['AuthName'];
  }

  echo json_encode(array_unique($data));
?>
