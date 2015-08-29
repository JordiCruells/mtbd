<?php
 
class ActivityDAO {
 
    private $_conn;

    public function __construct($conn) {
     
        $this->_conn = $conn;
         
    }

    public function create($activity) {
      
        $query = 'INSERT INTO wp_musicteach_activity (activity_name, description, goals, materials, observations, assesment, comments, keywords, types, song_themes, ages) values';
        $query .= '(?,?,?,?,?,?,?,?,?,?,?)';  

        $stmt = $this->_conn->prepare($query);
        $stmt->bind_param('sssssssssss', 
                          $activity['activity_name'], 
                          $activity['description'],
                          $activity['goals'],
                          $activity['materials'],
                          $activity['observations'],
                          $activity['assesment'],
                          $activity['comments'],
                          $activity['keywords'],
                          $activity['types'],
                          $activity['song_themes'],
                          $activity['ages']
                        );

        $stmt->execute();
        $insert_id = $stmt->insert_id;
        $stmt->close();        
        return $insert_id;
     }

      public function update($activity) {
        
        $query = 'UPDATE wp_musicteach_activity SET activity_name = ?, description = ?, goals = ?, materials = ?, observations = ?, assesment = ?, comments = ?, keywords = ?, types = ?, song_themes = ?, ages = ? ';
        $query .= 'WHERE id = ?';

        $stmt = $this->_conn->prepare($query);
        $stmt->bind_param('sssssssssssi', 
                          $activity['activity_name'], 
                          $activity['description'],
                          $activity['goals'],
                          $activity['materials'],
                          $activity['observations'],
                          $activity['assesment'],
                          $activity['comments'],
                          $activity['keywords'],
                          $activity['types'],
                          $activity['song_themes'],
                          $activity['ages'],
                          $activity['id']
                        );

        $stmt->execute();
        $stmt->close();
        
     }

      public function select($id) {
        
        $query = 'SELECT id, activity_name, description, goals, materials, observations, assesment, comments, keywords, types, song_themes, ages FROM wp_musicteach_activity ' ;
        $query .= 'WHERE id = ?';

        $stmt = $this->_conn->stmt_init();
        if (!$stmt->prepare($query)) {
          $activity = array();
        } else {

          $stmt->bind_param('i', $id);
          $stmt->execute();
          $stmt->bind_result($sel_id, $sel_activity_name, $sel_description, $sel_goals, $sel_materials, $sel_observations, $sel_assesment, $sel_comments, $sel_keywords, $sel_types, $sel_song_themes, $sel_ages);
          
          

          if ($row = $stmt->fetch()) {
            $activity = array('id' => $sel_id,
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
                              'ages' => $sel_ages
              );
          } else {
            $activity = array();
          }

          $stmt->close();                 
        }

        return $activity;
        
     }

      public function delete($id) {

        $query = 'DELETE FROM wp_musicteach_activity WHERE id = ?';
        $stmt = $this->_conn->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();        

      }

}

?>
