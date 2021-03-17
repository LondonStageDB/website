<?php

class SphinxPaginator {

     private $_conn;
     private $_limit;
     private $_page;
     private $_query;
     private $_total;

public function __construct( $conn, $query ) {

    $this->_conn = $conn;
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


public function getDataIds() {

    $results = [];
    $query   = $this->_query;

    $rs      = $this->_conn->query( $query );

    while ( $row = $rs->fetch_assoc() ) {
        $results[]  = $row;
    }

    return array_column($results, 'EventId');

}


public function getData( $limit = 25, $page = 1 ) {

    $this->_limit   = $limit;
    $this->_page    = $page;
    $results = [];

    if ( $this->_limit == 'all' ) {
        $query      = $this->_query;
    } else {
        $query      = $this->_query . " LIMIT " . ( ( $this->_page - 1 ) * $this->_limit ) . ", $this->_limit";
    }
    $rs             = $this->_conn->query( $query );

    while ( $row = $rs->fetch_assoc() ) {
        $results[]  = $row;
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
    $html       .= '<li class="pagination-previous ' . $class . '">' . $p_link . '</li>';

    if ( $start > 1 ) {
        $html   .= '<li><a href="sphinx-results.php?' . $get_params . '&limit=' . $this->_limit . '&p=1" aria-label="Page 1">1</a></li>';
        $html   .= '<li class="ellipsis" aria-hidden="true"></li>';
    }

    for ( $i = $start ; $i <= $end; $i++ ) {
        $class  = ( $this->_page == $i ) ? "current" : "";
        $c_link = ( $this->_page == $i ) ? '<span class="show-for-sr">You\'re on page</span> ' . $i : '<a href="sphinx-results.php?' . $get_params . '&limit=' . $this->_limit . '&p=' . $i . '" aria-label="' . $i . '">' . $i . '</a>';
        $html   .= '<li class="' . $class . '">' . $c_link . '</li>';
    }

    if ( $end < $last ) {
        $html   .= '<li class="ellipsis" aria-hidden="true"></li>';
        $html   .= '<li><a href="sphinx-results.php?' . $get_params . '&limit=' . $this->_limit . '&p=' . $last . '" aria-label="Page ' . $last .'">' . $last . '</a></li>';
    }

    $class      = ( $this->_page == $last ) ? "disabled" : "";
    $n_link     = ( $this->_page == $last ) ? 'Next <span class="show-for-sr">page</span>' : '<a href="sphinx-results.php?' . $get_params . '&limit=' . $this->_limit . '&p=' . ( $this->_page + 1 ) . '" aria-label="Next page">Next <span class="show-for-sr">page</span></a>';
    $html       .= '<li class="pagination-next ' . $class . '">' . $n_link . '</li>';

    $html       .= '</ul>';

    return $html;
}
}
