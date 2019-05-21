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
    "SELECT *, (MATCH(RoleClean) AGAINST ('\"$searchTerm\" @4' IN BOOLEAN MODE) + case when RoleClean LIKE '$searchTerm' then 50 when RoleClean LIKE '$searchTerm%' then 15 end)  as relevance FROM `Cast` WHERE (MATCH(RoleClean) AGAINST ('\"$searchTerm\" @4' IN BOOLEAN MODE) OR RoleClean LIKE '%$searchTerm%') GROUP BY Role ORDER BY relevance DESC, Role LIMIT 10" :
    "SELECT * FROM `Cast` WHERE RoleClean LIKE '$searchTerm%' GROUP BY Role ORDER BY Role LIMIT 10";

  $result = $conn->query($search);

  $data = array();
  while ($row = $result->fetch_assoc()) {
    $data[] = $row['RoleClean'];
  }

  echo json_encode($data);
?>
