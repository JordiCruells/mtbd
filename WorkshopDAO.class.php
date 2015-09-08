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
                          $workshop['workshop_date'], 
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
        
        $query = 'SELECT A.id, A.workshop_date , A.group_id, A.observations, A.comments, A.favourite, A.age, B.name FROM wp_musicteach_workshop A JOIN wp_musicteach_group B ON A.group_id = B.id ' ;
        $query .= ' WHERE A.id = ?';

        $stmt = $this->_conn->stmt_init();
        if (!$stmt->prepare($query)) {
          $workshop = array();
        } else {

          $stmt->bind_param('i', $id);
          $stmt->execute();
          $stmt->bind_result($sel_id, $sel_workshop_date , $sel_group_id, $sel_observations, $sel_comments, $sel_favourite, $sel_age, $sel_group_name);
          
       
          if ($row = $stmt->fetch()) {

            $workshop = array('id' => $sel_id,
                              'workshop_date' => $sel_workshop_date ,
                              'group_id' => $sel_group_id,                              
                              'observations' => $sel_observations,
                              'comments' => $sel_comments,                           
                              'favourite' => $sel_favourite,        
                              'age' => $sel_age,
                              'group_name' => $sel_group_name
            );

          } else {
            
            $workshop = array();
          }

          $stmt->close();                 
        }

        $workshop['activity'] = $this->get_activities($id);

        return $workshop;
        
     }


     private function get_activities($id) {

        //$query_activities = 'SELECT A.id, A.workshop_id , A.workshop_block, A.activity_id, B.activity_name, B.description, B.goals, B.materials, B.observations, B.assesment, B.comments, B.keywords, B.types, B.song_themes, B.ages FROM wp_musicteach_workshop_activity A JOIN wp_musicteach_activity B ON A.activity_id = B.id WHERE A.workshop_id = ? order by A.id' ;

        $query_activities = "SELECT A.id, A.workshop_id , A.workshop_block, A.activity_id, B.activity_name, B.description, B.goals, B.materials, B.observations, B.assesment, B.comments, B.keywords, B.types, B.song_themes, B.ages, GROUP_CONCAT(D.name, ' ,') as songs FROM wp_musicteach_workshop_activity A JOIN wp_musicteach_activity B ON A.activity_id = B.id JOIN wp_musicteach_activity_song C ON B.id = C.activity_id JOIN wp_musicteach_song D ON C.song_id = D.id WHERE A.workshop_id = ? GROUP BY  A.id, A.activity_id order by A.id, A.activity_id, C.song_id ";

        $stmt = $this->_conn->stmt_init();

        if (!$stmt->prepare($query_activities)) {
          $activities = array();
        } else {
          $stmt->bind_param('i', $id);
          $stmt->execute();
          $stmt->bind_result($sel_id, $sel_workshop_id, $sel_workshop_block, $sel_activity_id,
                             $sel_activity_name, $sel_description, $sel_goals, $sel_materials, $sel_observations, $sel_assesment, $sel_comments, $sel_keywords, $sel_types, $sel_song_themes, $sel_ages, $sel_songs
                             );                 
          $activities = array();
          while ($row = $stmt->fetch()) {
             $activity = array('id' => $sel_activity_id,
                              'activity_name' => $sel_activity_name,
                              'description' => $sel_description,
                              'goals' => $sel_goals,
                              'materials' => $sel_materials,
                              'observations' => $sel_observations,
                              'assesment' => $sel_assesment,
                              'comments' => $sel_comments,
                              'keywords' => $sel_keywords,
                              'types' => $sel_types,
                              'song_themes' => $sel_song_themes,
                              'ages' => $sel_ages,
                              'songs' => $sel_songs
             );
             $activities[$sel_workshop_block][$sel_activity_id] = $activity;
          } 
          $stmt->close();                 
        }
        return $activities;
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
