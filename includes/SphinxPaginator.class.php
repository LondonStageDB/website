<?php

class SphinxPaginator {

     private $_conn;
     private $_limit;
     private $_page;
     private $_query;
     private $_total;

public function __construct( $conn, $query ) {
    $this->_conn  = $conn;
    $this->_query = $query;

    $this->_conn->query( $this->_query ); // Execute the query.
    // To find the total number of results with sphinx, a follow up query
    // should be done to look up the previous query's meta info.
    $rs = $this->_conn->query( 'SHOW META;' );

    // Search through the rows and find the total_found variable.
    while ( $row = $rs->fetch_assoc() ) {
        if ($row['Variable_name'] == 'total_found') {
            $this->_total = $row['Value'];
            break;
        }
    }
}

  /**
   * Gets the field weights string to use in the Sphinx query.
   *
   * These weights replace the functionality of the perfscore and keyscore
   * columns used in the original SQL query to rank the matching records.
   *
   * Returns false if the field weights are not needed. This occurs when the
   * search does not include the performance title or keyword filters.
   *
   * @return false|string
   *   A field weights string to add to the OPTION statement for sphinx.
   */
public function getFieldWeights() {
  $keywordFilter  = ($_GET['keyword'] && $_GET['keyword'] !== '');
  $perfFilter     = ($_GET['performance'] && $_GET['performance'] !== '');
  if ($keywordFilter && $perfFilter) {
    return "field_weights=(perftitleclean=3,performanceTitle=2," .
        "commentpclean=1,commentcclean=1,roleclean=2,performerclean=2," .
        "authnameclean=2)";
  } elseif ($keywordFilter) {
    return "field_weights=(perftitleclean=10,performanceTitle=10," .
        "commentpclean=7,commentcclean=7,roleclean=10,performerclean=10," .
        "authnameclean=10)";
  } elseif ($perfFilter) {
    return "field_weights=(perftitleclean=2,performanceTitle=1," .
        "commentpclean=0,commentcclean=0,roleclean=0,performerclean=0," .
        "authnameclean=0)";
  }
  return FALSE;
}

public function getData( $limit = 25, $page = 1 ) {
    $this->_limit   = $limit;
    $this->_page    = $page;
    // Add the rank function, and the field weights if the keyword or
    //   or performance title filters are set.
    $option         = [];
    $weights        = $this->getFieldWeights();
    if ($weights) {
      $option[]     = "ranker=expr('sum((lcs*hit_count+bm25)*user_weight)')";
      $option[]     = $weights;
    }

    // In Sphinx must specify a limit and max-matches if interested in > 1000
    //   results queries (regardless of what is specified in the LIMIT).
    if ( $this->_limit == 'all' ) {
        // Set limits to some arbitrary, high number.
        $option[]   = 'max_matches=99999';
        $query      = $this->_query . 'LIMIT 99999' ;
    } else {
        // Keep the max_matches as small as we need it to be because memory on
        // Sphinx server is allocated for this size prior to running the query.
        $offset     = ( $this->_page - 1 ) * $this->_limit;
        $query      = $this->_query . "\nLIMIT $offset, $this->_limit";
        $option[]   = "max_matches=" . $this->_page * $this->_limit;
    }
    // Build and append the option statement which is a comma separated list.
    $query         .= "\nOPTION " . implode(', ', $option);
    // echo "<p>Query: <code>$query</code></p>";
    // Perform the query.
    $rs             = $this->_conn->query( $query );
    $results        = [];
    // Only process through the results if there are any to process through.
    if ( $rs ) {
      while ( $row = $rs->fetch_assoc() ) {
        $results[] = $row;
      }
    }
    $result         = new stdClass();
    $result->page   = $this->_page;
    $result->limit  = $this->_limit;
    $result->total  = $this->_total;
    $result->data   = $results;

    return $result;
}


public function createLinks( $links, $list_class ) {
    if ( $this->_limit == 'all' ) {
        return '';
    }

    $get_params = $_SERVER['QUERY_STRING'];
    if (isset($_GET['p']) && $_GET['p'] !== "") {
      $get_params = str_replace("&p=" . $_GET['p'], "", $get_params);
    }
    if (isset($_GET['limit']) && $_GET['limit'] !== "") {
      $get_params = str_replace("&limit=" . $_GET['limit'], "", $get_params);
    }

    $last       = ceil( $this->_total / $this->_limit );

    $start      = ( ( $this->_page - $links ) > 0 ) ? $this->_page - $links : 1;
    $end        = ( ( $this->_page + $links ) < $last ) ? $this->_page + $links : $last;

    $html       = '<ul class="' . $list_class . '">';

    $class      = ( $this->_page == 1 ) ? "disabled" : "";
    $p_link     = ( $this->_page == 1 ) ? 'Previous <span class="show-for-sr">page</span>' : '<a href="sphinx-results.php?' . $get_params . '&limit=' . $this->_limit . '&p=' . ( $this->_page - 1 ) . '" aria-label="Previous page">Previous <span class="show-for-sr">page</span></a>';
    $html      .= '<li class="pagination-previous ' . $class . '">' . $p_link . '</li>';

    if ( $start > 1 ) {
        $html  .= '<li><a href="sphinx-results.php?' . $get_params . '&limit=' . $this->_limit . '&p=1" aria-label="Page 1">1</a></li>';
        $html  .= '<li class="ellipsis" aria-hidden="true"></li>';
    }

    for ( $i = $start ; $i <= $end; $i++ ) {
        $class  = ( $this->_page == $i ) ? "current" : "";
        $c_link = ( $this->_page == $i ) ? '<span class="show-for-sr">You\'re on page</span> ' . $i : '<a href="sphinx-results.php?' . $get_params . '&limit=' . $this->_limit . '&p=' . $i . '" aria-label="' . $i . '">' . $i . '</a>';
        $html  .= '<li class="' . $class . '">' . $c_link . '</li>';
    }

    if ( $end < $last ) {
        $html  .= '<li class="ellipsis" aria-hidden="true"></li>';
        $html  .= '<li><a href="sphinx-results.php?' . $get_params . '&limit=' . $this->_limit . '&p=' . $last . '" aria-label="Page ' . $last .'">' . $last . '</a></li>';
    }

    $class      = ( $this->_page == $last ) ? "disabled" : "";
    $n_link     = ( $this->_page == $last ) ? 'Next <span class="show-for-sr">page</span>' : '<a href="sphinx-results.php?' . $get_params . '&limit=' . $this->_limit . '&p=' . ( $this->_page + 1 ) . '" aria-label="Next page">Next <span class="show-for-sr">page</span></a>';
    $html      .= '<li class="pagination-next ' . $class . '">' . $n_link . '</li>';

    $html      .= '</ul>';

    return $html;
}

}
