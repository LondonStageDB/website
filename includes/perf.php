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
    "SELECT *, (MATCH(PerfTitleClean) AGAINST ('\"$searchTerm\" @4' IN BOOLEAN MODE) + case when PerfTitleClean LIKE '$searchTerm' then 50 when PerfTitleClean LIKE '$searchTerm%' then 15 end) as relevance FROM `Performances` WHERE (MATCH(PerfTitleClean) AGAINST ('\"$searchTerm\" @4' IN BOOLEAN MODE) OR PerfTitleClean LIKE '%$searchTerm%') GROUP BY PerformanceTitle ORDER BY relevance DESC, PerformanceTitle LIMIT 10" :
    "SELECT * FROM `Performances` WHERE PerfTitleClean LIKE '$searchTerm%' GROUP BY PerformanceTitle ORDER BY PerformanceTitle LIMIT 10";

  $result = $conn->query($search);

  $data = array();
  if ($result) {
    while ($row = $result->fetch_assoc()) {
      $data[] = trim($row['PerfTitleClean']);
    }
  }

  echo json_encode(array_unique($data));
?>
