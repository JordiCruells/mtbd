<?php
 
class SchedulingPaginator {
 
     private $_conn;
     private $_limit;
     private $_page;
     
     private $_query;
     private $_prepared_values;

     private $_total;

     private $_search_date;
     private $_search_age;

     public function __construct( $conn, $query, $group='', $_search_date='', $_search_age='') {
     
        $this->_conn = $conn;        
        
        $this->_search_date = $_search_date;
        $this->_search_age = $_search_age;

        $this->_search_data_types = array();

        $prepared_types = '';
        $prepared_values = array();
        
        if ($this->_isSearch()) {
            $query = $query . ' WHERE ';  
            $and = '';
        }         


        if (!empty($this->_search_date)) {
          $query .= $and . '  A.scheduling_date_start <= ? AND A.scheduling_date_end >= ? ';            
          $prepared_types .= 'ss';
          $prepared_values[] = &$this->_search_date;            
          $prepared_values[] = &$this->_search_date;            
          $and = ' AND ';  
        }
        
        if (!empty($this->_search_age)) {
  
          $query .= $and . '  A.scheduling_age = ? ';            
          $prepared_types .= 's';
          $prepared_values[] = &$this->_search_age;            
          $and = ' AND ';  

        }  


        $query .= $group;        
        $this->_query = $query;

        /*echo $query; 
        echo '<br>';
        echo $prepared_types;
        echo '<br>';
        print_r($prepared_values);*/
        //exit;

        $stmt = $this->_conn->stmt_init();

        $results = array();

        if (!$stmt->prepare($query)) {

           die ('wrong query: ' . $query);

        } else {
            
            if (!empty($prepared_types)) {
              
              $param_arr[] = &$prepared_types;
              for ($i=0; $i < count($prepared_values) ; $i++) { 
                $param_arr[] = &$prepared_values[$i];
              }
              call_user_func_array(array($stmt, 'bind_param'), $param_arr);
            }

            
            $stmt->execute();
            $stmt->bind_result($fetch_id, $fetch_scheduling_date_start, $fetch_scheduling_date_end, $fetch_observations, $fetch_comments, $fetch_age, $fetch_timestamp);


            while ( $stmt->fetch()) {
                $results[]  = array('id' => $fetch_id,
                                'scheduling_date_start' => $fetch_scheduling_date_start,
                                'scheduling_date_end' => $fetch_scheduling_date_end,  
                                'observations' => $fetch_observations,
                                'comments' => $fetch_comments,
                                'age' => $fetch_age,
                                'timestamp' => $fetch_timestamp
                              );
            }

            $stmt->close();  
        }

        $this->_prepared_values = isset($param_arr) ? $param_arr : array();

        //echo 'PREPARED VALUES:';
        //print_r($this->_prepared_values);

        $this->_total = count($results);

    }


    private function _isSearch() {
      $str= $this->_search_date . $this->_search_age;
      return !empty($str);
    }


    public function getData( $limit = 10, $page = 1) {
     
      $this->_limit   = $limit;
      $this->_page    = $page;

      $query = $this->_query;

      //if ($this->_isSearch()) {
        $query .= ' ORDER BY A.scheduling_date_start DESC ';
      /*} else {
        $query .= ' ORDER BY A.timestamp DESC ';
      }*/


      //echo '<br>GET DATA query ' . $query;


      $prepared_values = $this->_prepared_values;

      //echo '<br> Prepared alues';
      //print_r($prepared_values);
        
      if ( $this->_limit !== 'all' ) {
          //$query      .= " LIMIT " . ( ( $this->_page - 1 ) * $this->_limit ) . ", $this->_limit";
          $query      .= " LIMIT ? , ?";
          $prepared_types = isset($prepared_values[0]) ? $prepared_values[0].'ii' : 'ii';
          $prepared_values[0] = &$prepared_types;
          $val1 = ($this->_page - 1 ) * $this->_limit;
          $val2 = $this->_limit;
          $prepared_values[]=&$val1;
          $prepared_values[]=&$val2;
      }


      $stmt = $this->_conn->stmt_init();

      $results = array();

      if (!$stmt->prepare($query)) {

         die ('wrong query: ' . $query);

      } else {
          
          if (count($prepared_values) > 0) {
             call_user_func_array(array($stmt, 'bind_param'), $prepared_values);
          }

          $stmt->execute();

          $stmt->bind_result($fetch_id, $fetch_scheduling_date_start, $fetch_scheduling_date_end, $fetch_observations, $fetch_comments, $fetch_age, $fetch_timestamp);

          while ( $stmt->fetch()) {
               $results[]  = array( 'id' => $fetch_id,
                                    'scheduling_date_start' => $fetch_scheduling_date_start,
                                    'scheduling_date_end' => $fetch_scheduling_date_end,
                                    'observations' => $fetch_observations,
                                    'comments' => $fetch_comments,
                                    'age' => $fetch_age,
                                    'timestamp' => $fetch_timestamp
                                  );
          }

          $stmt->close();  
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

      $param_search = '';

      
      if ($this->_search_date !== '') {
        $param_search .= '&search_date=' . $this->_search_date;
      }
      
      if ($this->_search_age !== '') {
        $param_search .= '&search_age=' . $this->_search_age;
      }    


      $param_search .= '&r= ' . mt_rand(0, 9999999); // avoid caching
      
      $last       = ceil( $this->_total / $this->_limit );
   
      $start      = ( ( $this->_page - $links ) > 0 ) ? $this->_page - $links : 1;                   
      $end        = ( ( $this->_page + $links ) < $last ) ? $this->_page + $links : $last;
   
      $html       = '<ul class="' . $list_class . '">';
   
      $class      = ( $this->_page == 1 ) ? "disabled" : "";
      $html       .= '<li class="' . $class . '"><a href="?limit=' . $this->_limit . '&page=' . ( $this->_page - 1 ) . $param_search . '">&laquo;</a></li>';
   
      if ( $start > 1 ) {
          $html   .= '<li><a href="?limit=' . $this->_limit . '&page=1' . $param_search . '">1</a></li>';
          $html   .= '<li class="disabled"><span>...</span></li>';
      }
   
      for ( $i = $start ; $i <= $end; $i++ ) {
          $class  = ( $this->_page == $i ) ? "active" : "";
          $html   .= '<li class="' . $class . '"><a href="?limit=' . $this->_limit . '&page=' . $i . $param_search . '">' . $i . '</a></li>';
      }
   
      if ( $end < $last ) {
          $html   .= '<li class="disabled"><span>...</span></li>';
          $html   .= '<li><a href="?limit=' . $this->_limit . '&page=' . $last . $param_search . '">' . $last . '</a></li>';
      }
   
      $class      = ( $this->_page == $last ) ? "disabled" : "";
      $html       .= '<li class="' . $class . '"><a href="?limit=' . $this->_limit . '&page=' . ( $this->_page + 1 ) .  $param_search . '">&raquo;</a></li>';
   
      $html       .= '</ul>';
   
      $start = (($this->_page - 1) * $this->_limit) + 1;
      $end = min($start + $this->_limit - 1, $this->_total);

      if ($this->_total > 0) {
        $html .= "<span class='pagination text-primary'> " . $this->_total . " ocurrences (mostrant de la " . $start . " fins la " . $end . ")</span>";
      } else {
        $html .= "<span class='pagination text-primary'> No s'ha trobat cap ocurrència </span>";
      }
      return $html;
  }
 
}