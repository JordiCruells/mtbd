<?php
 
class SchedulingDAO {
 
    private $_conn;

    public function __construct($conn) {
     
        $this->_conn = $conn;
         
    }

    public function create($scheduling) {

        
        $query = 'INSERT INTO wp_musicteach_scheduling (scheduling_date_start , scheduling_date_end, observations, comments, age) values';
        $query .= '(?,?,?,?,?)';  
        $stmt = $this->_conn->prepare($query);
        $stmt->bind_param('sssss', 
                          $scheduling['scheduling_date_start'], 
                          $scheduling['scheduling_date_end'], 
                          $scheduling['observations'],
                          $scheduling['comments'],
                          $scheduling['age']                        
                        );

        $stmt->execute();


        $insert_id = $stmt->insert_id;
        $stmt->close();        
        return $insert_id;
     }

      public function update($scheduling) {
        
        $query = 'UPDATE wp_musicteach_scheduling SET scheduling_date_start  = ?, scheduling_date_end  = ?, observations = ?, comments = ? , age = ? ';
        $query .= 'WHERE id = ?';

        $stmt = $this->_conn->prepare($query);
        $stmt->bind_param('sssssi', 
                          $scheduling['scheduling_date_start'], 
                          $scheduling['scheduling_date_end'], 
                          $scheduling['observations'],
                          $scheduling['comments'],
                          $scheduling['age'],
                          $scheduling['id']
                        );

        $stmt->execute();
        $stmt->close();
        
     }

      public function select($id) {
        
        $query = 'SELECT A.id, A.scheduling_date_start , A.scheduling_date_end, A.observations, A.comments, A.age FROM wp_musicteach_scheduling A ' ;
        $query .= ' WHERE A.id = ?';

        $stmt = $this->_conn->stmt_init();
        if (!$stmt->prepare($query)) {
          $scheduling = array();
        } else {

          $stmt->bind_param('i', $id);
          $stmt->execute();
          $stmt->bind_result($sel_id, $sel_scheduling_date_start , $sel_scheduling_date_end, $sel_observations, $sel_comments, $sel_age);
                 
          if ($row = $stmt->fetch()) {

            $scheduling = array('id' => $sel_id,
                              'scheduling_date_start' => $sel_scheduling_date_start ,
                              'scheduling_date_end' => $sel_scheduling_date_end ,                   
                              'observations' => $sel_observations,
                              'comments' => $sel_comments,                           
                              'age' => $sel_age
            );

          } else {
            
            $scheduling = array();
          }

          $stmt->close();                 
        }

        $scheduling['activity'] = $this->get_activities($id);

        return $scheduling;
        
     }


     private function get_activities($id) {

        //$query_activities = 'SELECT A.id, A.scheduling_id , A.scheduling_block, A.activity_id, B.activity_name, B.description, B.goals, B.materials, B.observations, B.assesment, B.comments, B.keywords, B.types, B.song_themes, B.ages FROM wp_musicteach_scheduling_activity A JOIN wp_musicteach_activity B ON A.activity_id = B.id WHERE A.scheduling_id = ? order by A.id' ;
        $query_activities = "SELECT  A.id, A.scheduling_id , A.scheduling_block, A.activity_id, B.activity_name, B.description, B.goals, B.materials, B.observations, B.assesment, B.comments, B.keywords, B.types, B.song_themes, B.ages, GROUP_CONCAT(D.name, ' ,') as songs FROM wp_musicteach_scheduling_activity A JOIN wp_musicteach_activity B ON A.activity_id = B.id JOIN wp_musicteach_activity_song C ON B.id = C.activity_id JOIN wp_musicteach_song D ON C.song_id = D.id WHERE A.scheduling_id = ? GROUP BY  A.id, A.activity_id order by A.id, A.activity_id, C.song_id ";


        $stmt = $this->_conn->stmt_init();

        if (!$stmt->prepare($query_activities)) {
          $activities = array();
        } else {
          $stmt->bind_param('i', $id);
          $stmt->execute();
          $stmt->bind_result($sel_id, $sel_scheduling_id, $sel_scheduling_block, $sel_activity_id,
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
             $activities[$sel_scheduling_block][$sel_activity_id] = $activity;
          } 
          $stmt->close();                 
        }
        return $activities;
     }

      public function delete($id) {

        $query = 'DELETE FROM wp_musicteach_scheduling WHERE id = ?';
        $stmt = $this->_conn->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();        

      }

      public function linkActivities($scheduling_id, $activities) {

        $query = 'INSERT INTO wp_musicteach_scheduling_activity (scheduling_id, scheduling_block, activity_id) values (?,?,?)';  
  
        foreach ($activities as $block_id => $block_activities){ 
          foreach ($block_activities as $activity_id => $value) {
            //Insert relation into wp_musicteach_worksop_activity
            $stmt = $this->_conn->prepare($query);
            $stmt->bind_param('iii', 
                             $scheduling_id,
                             $block_id,
                             $activity_id);
            $stmt->execute();
            $stmt->close();             
          }
        }           
      }

      public function unlinkActivities($scheduling_id) {

        $query = 'DELETE FROM wp_musicteach_scheduling_activity WHERE scheduling_id = ?';
        $stmt = $this->_conn->prepare($query);
        $stmt->bind_param('i', $scheduling_id);
        $stmt->execute();
        $stmt->close();        

      }

}

?>
