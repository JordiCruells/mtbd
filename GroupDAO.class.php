<?php
 
class GroupDAO {
 
    private $_conn;

    public function __construct($conn) {
     
        $this->_conn = $conn;
         
    }

    public function create($group) {
      
        $query = 'INSERT INTO wp_musicteach_group (name, age, date_start, date_end, location, observations, comments) values';
        $query .= '(?,?,?,?,?,?,?)';  
        $stmt = $this->_conn->prepare($query);
        $stmt->bind_param('sssssss', 
                          $group['name'], 
                          $group['age'],
                          $group['date_start'],
                          $group['date_end'],
                          $group['location'],
                          $group['observations'],
                          $group['comments']                          
                        );

        $stmt->execute();
        $insert_id = $stmt->insert_id;
        $stmt->close();        
        return $insert_id;
     }

      public function update($group) {
        
        $query = 'UPDATE wp_musicteach_group SET name = ?, age = ?, date_start = ?, date_end = ?, location = ?, observations = ?, comments = ? ';
        $query .= 'WHERE id = ?';

        $stmt = $this->_conn->prepare($query);
        $stmt->bind_param('sssssssi', 
                          $group['name'], 
                          $group['age'],
                          $group['date_start'],
                          $group['date_end'],
                          $group['location'],
                          $group['observations'],
                          $group['comments'],
                          $group['id']
                        );

        $stmt->execute();
        $stmt->close();
        
     }

      public function select($id) {
        
        $query = 'SELECT id, name, age, date_start, date_end, location, observations, comments FROM wp_musicteach_group ' ;
        $query .= 'WHERE id = ?';

        $stmt = $this->_conn->stmt_init();
        if (!$stmt->prepare($query)) {
          $group = array();
        } else {

          $stmt->bind_param('i', $id);
          $stmt->execute();
          $stmt->bind_result($sel_id, $sel_name, $sel_age, $sel_date_start, $sel_date_end, $sel_location, $sel_observations, $sel_comments);
          
          if ($row = $stmt->fetch()) {
            $group = array('id' => $sel_id,
                           'name' => $sel_name,
                           'age' => $sel_age,
                           'date_start' => $sel_date_start,
                           'date_end' => $sel_date_end,
                           'location' => $sel_location,
                           'observations' => $sel_observations,
                           'comments' => $sel_comments                           
              );
          } else {
            $group = array();
          }

          $stmt->close();                 
        }

        return $group;
        
     }

     public function delete($id) {

        $query = 'DELETE FROM wp_musicteach_group WHERE id = ?';
        $stmt = $this->_conn->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();        

      }


      public function getCurrentGroups() {


        $query = 'SELECT id, name, age, date_start, date_end, location, observations, comments FROM wp_musicteach_group ' ;
        $query .= 'WHERE CURDATE() BETWEEN date_start AND date_end ';

        $stmt = $this->_conn->stmt_init();
        $groups = array();

        if ($stmt->prepare($query)) {

          //$stmt->bind_param('i', $id);
          $stmt->execute();
          $stmt->bind_result($sel_id, $sel_name, $sel_age, $sel_date_start, $sel_date_end, $sel_location, $sel_observations, $sel_comments);
          
          while ($row = $stmt->fetch()) {
            $groups[] = array('id' => $sel_id,
                              'name' => $sel_name,
                              'age' => $sel_age,
                              'date_start' => $sel_date_start,
                              'date_end' => $sel_date_end,
                              'location' => $sel_location,
                              'observations' => $sel_observations,
                              'comments' => $sel_comments                           
              );
          } 

          $stmt->close();                 
        }

        return $groups;

      }

      public function getAllGroups() {


        $query = 'SELECT id, name, age, date_start, date_end, location, observations, comments FROM wp_musicteach_group ' ;

        $stmt = $this->_conn->stmt_init();
        $groups = array();

        if ($stmt->prepare($query)) {

          //$stmt->bind_param('i', $id);
          $stmt->execute();
          $stmt->bind_result($sel_id, $sel_name, $sel_age, $sel_date_start, $sel_date_end, $sel_location, $sel_observations, $sel_comments);
          
          while ($row = $stmt->fetch()) {
            $groups[] = array('id' => $sel_id,
                              'name' => $sel_name,
                              'age' => $sel_age,
                              'date_start' => $sel_date_start,
                              'date_end' => $sel_date_end,
                              'location' => $sel_location,
                              'observations' => $sel_observations,
                              'comments' => $sel_comments                           
              );
          } 

          $stmt->close();                 
        }

        return $groups;

      }


      public function getCurrentGroupsKeysAndNames() {
        
        $groups = $this->getCurrentGroups();

        

        $result = array();

        for ($i=0; $i < count($groups) ; $i++) { 
        
          $result[$groups[$i]['id']] = $groups[$i]['name'];

        }

        return $result;

      }

      public function getAllGroupsKeysAndNames() {
        
        $groups = $this->getAllGroups();
        $result = array();

        for ($i=0; $i < count($groups) ; $i++) { 
            $result[$groups[$i]['id']] = $groups[$i]['name'];
        }

        return $result;

      }


}

?>
