<?php
 
class WorkshopDAO {
 
    private $_conn;

    public function __construct($conn) {
     
        $this->_conn = $conn;
         
    }

    public function create($workshop) {
       
        $query = 'INSERT INTO wp_musicteach_workshop (workshop_date , group_id, observations, comments, favourite, age) values';
        $query .= '(?,?,?,?,?,?)';  
        $stmt = $this->_conn->prepare($query);
        $stmt->bind_param('sissss', 
                          $workshop['workshop_date'], 
                          $workshop['group_id'], 
                          $workshop['observations'],
                          $workshop['comments'],
                          $workshop['favourite'],
                          $workshop['age']                        
                        );

        $stmt->execute();

        $insert_id = $stmt->insert_id;
        $stmt->close();        
        return $insert_id;
     }

      public function update($workshop) {
        
        $query = 'UPDATE wp_musicteach_workshop SET workshop_date  = ?, group_id = ?, observations = ?, comments = ? , favourite = ? , age = ? ';
        $query .= 'WHERE id = ?';

        $stmt = $this->_conn->prepare($query);
        $stmt->bind_param('sissssi', 
                          $workshop['workshop_date '], 
                          $workshop['group_id'], 
                          $workshop['observations'],
                          $workshop['comments'],
                          $workshop['favourite'],
                          $workshop['age'],
                          $workshop['id']
                        );

        $stmt->execute();
        $stmt->close();
        
     }

      public function select($id) {
        
        $query = 'SELECT id, workshop_date , group_id, observations, comments, favourite, age FROM wp_musicteach_workshop ' ;
        $query .= 'WHERE id = ?';

        $stmt = $this->_conn->stmt_init();
        if (!$stmt->prepare($query)) {
          $workshop = array();
        } else {

          $stmt->bind_param('i', $id);
          $stmt->execute();
          $stmt->bind_result($sel_id, $sel_workshop_date , $sel_group_id, $sel_observations, $sel_comments, $sel_favourite, $sel_age);
          
          if ($row = $stmt->fetch()) {
            $workshop = array('id' => $sel_id,
                              'workshop_date ' => $sel_workshop_date ,
                              'group_id' => $sel_group_id,                              
                              'observations' => $sel_observations,
                              'comments' => $sel_comments,                           
                              'favourite' => $sel_favourite,        
                              'age' => $sel_age
              );
          } else {
            $workshop = array();
          }

          $stmt->close();                 
        }

        return $workshop;
        
     }

      public function delete($id) {

        $query = 'DELETE FROM wp_musicteach_workshop WHERE id = ?';
        $stmt = $this->_conn->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();        

      }

      public function linkActivities($workshop_id, $activities) {

        $query = 'INSERT INTO wp_musicteach_workshop_activity (workshop_id, workshop_block, activity_id) values (?,?,?)';  
  
        foreach ($activities as $block_id => $block_activities){ 
          foreach ($block_activities as $activity_id => $value) {
            //Insert relation into wp_musicteach_worksop_activity
            $stmt = $this->_conn->prepare($query);
            $stmt->bind_param('iii', 
                             $workshop_id,
                             $block_id,
                             $activity_id);
            $stmt->execute();
            $stmt->close();             
          }
        }           
      }

      public function unlinkActivities($workshop_id) {

        $query = 'DELETE FROM wp_musicteach_workshop_activity WHERE workshop_id = ?';
        $stmt = $this->_conn->prepare($query);
        $stmt->bind_param('i', $workshop_id);
        $stmt->execute();
        $stmt->close();        

      }

}

?>
