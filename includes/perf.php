<?php
  include_once('functions.php');
  global $sphinx_conn;

  $searchTerm = $_GET['term'];
  $searchTerm = cleanStr($searchTerm);
  $searchTerm = mysqli_real_escape_string($sphinx_conn, $searchTerm);

  // If search string is over 3 chars, full wildcard search,
  // Else exact match beginning of string with wildcard at the end

  $search = strlen($searchTerm) > 3 ?
      "select perftitleclean from london_stages 
                       where MATCH('@(perftitleclean) $searchTerm*') group by perftitleclean 
                    ORDER BY perftitleclean ASC LIMIT 10":
      "select perftitleclean from london_stages 
                       where MATCH('@(perftitleclean) *$searchTerm*') group by perftitleclean
                    ORDER BY perftitleclean ASC LIMIT 10";

  $result = $sphinx_conn->query($search);

  $data = array();
  if ($result) {
    while ($row = $result->fetch_assoc()) {
      $data[] = trim($row['perftitleclean']);
    }
  }

  echo json_encode(array_unique($data));
?>
