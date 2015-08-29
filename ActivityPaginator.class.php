<?php
 
class ActivityPaginator {
 
     private $_conn;
     private $_limit;
     private $_page;
     
     private $_query;
     private $_prepared_values;

     private $_total;

     private $_search_string;
     private $_search_order;
     private $_search_types;
     private $_search_song_themes;
     private $_search_ages;

     private $_search_data_types;
     private $_search_data_song_themes;
     private $_search_data_ages;
  
     public function __construct( $conn, $query, $group='', $search_string='', $search_order='', $search_types=array(), $search_song_themes=array(), $search_ages=array()  ) {
     
        $this->_conn = $conn;
        
        $this->_search_string=$search_string;
        $this->_search_order=$search_order;
        $this->_search_types=$search_types;
        $this->_search_song_themes=$search_song_themes;
        $this->_search_ages=$search_ages;

        $this->_search_data_types = array();
        $this->_search_data_song_themes = array(); 
        $this->_search_data_ages = array(); 

        $prepared_types = '';
        $prepared_values = array();
        $and = '';

        if ($this->_isSearch()) {

            $query = $query . ' WHERE ';
            

            if ($this->_search_string !== '') {

                if (strlen($this->_search_string) === 3) { 
                  $s = $this->_search_string;
                  $start = $s.' %';
                  $middle = '% '. $s.' %';
                  $end = '% '. $s;
                  $query .= ' ( ';
                  $query .= ' A.activity_name LIKE ? OR A.activity_name LIKE ?  OR A.activity_name LIKE ? ';
                  $query .= ' OR A.description LIKE ? OR A.description LIKE ? OR A.description LIKE ? ';
                  $query .= ' OR A.materials LIKE ? OR A.materials LIKE ? OR A.materials LIKE ? ';
                  $query .= ' OR A.observations LIKE ? OR A.observations LIKE ? OR A.observations LIKE ? ';
                  $query .= ' OR A.keywords LIKE ? OR A.keywords LIKE ? OR A.keywords LIKE ? ';  
                  $query .= ' OR C.name LIKE ? OR C.name LIKE ? OR C.name LIKE ? ';  
                  $query .= ' ) ';
                  $prepared_types .= 'ssssssssssssssssss';
                  for ($i=0; $i <6 ; $i++) { 
                      $prepared_values[] = &$start;  
                      $prepared_values[] = &$middle;
                      $prepared_values[] = &$end;  
                  }
                 } else {

                  $query .= ' ( MATCH (A.activity_name,A.description,A.materials,A.observations,A.keywords) AGAINST (?) OR MATCH (C.name) AGAINST (?) )';
                   $prepared_types .= 'ss';
                   $prepared_values[] = &$this->_search_string;  
                   $prepared_values[] = &$this->_search_string;
                }
               
            
                $and = ' AND ';
            }

           

            if (count($this->_search_types) > 0) {

                  $query .= $and . ' ( ';

                  $or = '';

                  for ($i=0; $i < count($this->_search_types); $i++) { 

                    $query .= $or . "CONCAT(',',TRIM(A.types),',') LIKE ? ";
                      
                    $prepared_types .= 's';
                    $this->_search_data_types[$i] = '%,' . $this->_search_types[$i] . ',%';
                    $prepared_values[] = &$this->_search_data_types[$i];
                    

                    $or = ' OR ';    
                  }
                  $query .= ' ) ';
                  $and = ' AND ';             

            }

            if (count($this->_search_song_themes) > 0) {

                  $query .= $and . ' ( ';
                  $or = '';

                  for ($i=0; $i < count($this->_search_song_themes); $i++) { 

                    $query .= $or . "CONCAT(',',TRIM(A.song_themes),',') LIKE ? ";
                      
                    $prepared_types .= 's';
                    $this->_search_data_song_themes[$i] = '%,' . $this->_search_song_themes[$i] . ',%';
                    $prepared_values[] = &$this->_search_data_song_themes[$i];
                    
                    $or = ' OR ';    
                  }
                  $query .= ' ) ';  
                  $and = ' AND ';           

            }

            if (count($this->_search_ages) > 0) {

                  $query .= $and . ' ( ';
                  $or = '';

                  for ($i=0; $i < count($this->_search_ages); $i++) { 

                    $query .= $or . "CONCAT(',',TRIM(A.ages),',') LIKE ? ";
                      
                    $prepared_types .= 's';
                    $this->_search_data_ages[$i] = '%,' . $this->_search_ages[$i] . ',%';
                    $prepared_values[] = &$this->_search_data_ages[$i];
                    
                    $or = ' OR ';    
                  }
                  $query .= ' ) ';  
                  $and = ' AND ';           

            }


        }

        $query .= $group;        
        $this->_query = $query;

        /*echo '<br>'.$query;
        echo '<br>'.$prepared_types;
        echo '<br>';
        print_r($prepared_values);*/



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
            $stmt->bind_result($fetch_id, $fetch_activity_name, $fetch_description, $fetch_goals, $fetch_materials, $fetch_observations, $fetch_assesment, $fetch_comments, $fetch_keywords, $fetch_types, $fetch_song_themes, $fetch_ages, $fetch_timestamp, $fetch_song_name);


            while ( $stmt->fetch()) {
                $results[]  = array('id' => $fetch_id,
                                'activity_name' => $fetch_activity_name,                               
                                'description' => $fetch_description,
                                'goals' => $fetch_goals,
                                'materials' => $fetch_materials,
                                'observations' => $fetch_observations,
                                'assesment' => $fetch_assesment,
                                'comments' => $fetch_comments,
                                'keywords' => $fetch_keywords,
                                'types' => $fetch_types,
                                'song_themes' => $fetch_song_themes,
                                'ages' => $fetch_ages,
                                'timestamp' => $fetch_timestamp,
                                'song_name' => $fetch_song_name
                              );
            }

            $stmt->close();  
        }

        $this->_prepared_values = isset($param_arr) ? $param_arr : array();

        //echo 'PREPARED VALUES:';
        //print_r($this->_prepared_values);

        //echo '<br> TOTAL: ' . count($results);
        $this->_total = count($results);

         
    }


    private function _isSearch() {
      return $this->_search_string !== '' || count($this->_search_types) > 0 || count($this->_search_song_themes) > 0 || count($this->_search_ages) > 0;
    }


    public function getData( $limit = 10, $page = 1) {
     
     
     
      $this->_limit   = $limit;
      $this->_page    = $page;

      $query = $this->_query;

      switch ($this->_search_order) {
        case '':
        case 'changed':
          $query .= ' ORDER BY A.timestamp DESC ';
          break;
        case 'name':
          $query .= ' ORDER BY A.activity_name ASC ';
          break;
        default:
          break;
      }


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

      /*echo 'QUERY:' . $query;
      echo '$prepared_values =>';
      print_r($prepared_values);*/

     

      $stmt = $this->_conn->stmt_init();

      $results = array();

      if (!$stmt->prepare($query)) {

         die ('wrong query: ' . $query);

      } else {
 //      echo '-A-';
 //       print_r($prepared_values);
          
          if (count($prepared_values) > 0) {
             call_user_func_array(array($stmt, 'bind_param'), $prepared_values);
          }

//echo '-b-';
          
          $stmt->execute();
          $stmt->bind_result($fetch_id, $fetch_activity_name, $fetch_description, $fetch_goals, $fetch_materials, $fetch_observations, $fetch_assesment, $fetch_comments, $fetch_keywords, $fetch_types, $fetch_song_themes, $fetch_ages, $fetch_timestamp, $fetch_song_name);

//echo '-C-';

          while ( $stmt->fetch()) {

              //echo 'APEEND RESULT ';

              $results[]  = array('id' => $fetch_id,
                              'activity_name' => $fetch_activity_name,                              
                              'description' => $fetch_description,
                              'goals' => $fetch_goals,
                              'materials' => $fetch_materials,
                              'observations' => $fetch_observations,
                              'assesment' => $fetch_assesment,
                              'comments' => $fetch_comments,
                              'keywords' => $fetch_keywords,
                              'types' => $fetch_types,
                              'song_themes' => $fetch_song_themes,
                              'ages' => $fetch_ages,
                              'timestamp' => $fetch_timestamp,
                              'song_name' => $fetch_song_name
                            );
          }

          $stmt->close();  
      }

//echo '-D-';

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

      
      if ($this->_search_string !== '') {
        $param_search .= '&search_string=' . $this->_search_string;
      }
      if ($this->_search_order !== '') {
        $param_search .= '&search_order=' . $this->_search_order;
      }
      if (count($this->_search_types > 0)) {
        for ($i=0; $i < count($this->_search_types); $i++) { 
          $param_search .= '&search_types[' . $i . ']=' . $this->_search_types[$i];
        }  
      }
      if (count($this->_search_song_themes > 0)) {
        for ($i=0; $i < count($this->_search_song_themes); $i++) { 
          $param_search .= '&search_song_themes[' . $i . ']=' . $this->_search_song_themes[$i];
        }  
      }
      if (count($this->_search_ages > 0)) {
        for ($i=0; $i < count($this->_search_ages); $i++) { 
          $param_search .= '&search_ages[' . $i . ']=' . $this->_search_ages[$i];
        }  
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