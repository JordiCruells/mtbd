<?php
 
class SongDAO {
 
    private $_conn;

    public function __construct($conn) {
     
        $this->_conn = $conn;
         
    }

    public function createSongs($activity_id, $songs) {

      
        $query = 'INSERT INTO wp_musicteach_song (name, duration, file) values (?,?,?)';  
        $query_rel_activity = 'INSERT INTO wp_musicteach_activity_song (activity_id, song_id) values (?,?)';  

  

        for ($i=0; $i <count($songs) ; $i++) { 
        
  
          //Insert into wp_musicteach_songs if new song
          $search_name = $this->getByName($songs[$i]['name']);

  
          if (empty($search_name)) {
            $stmt = $this->_conn->prepare($query);

            $stmt->bind_param('sss', 
                               $songs[$i]['name'], 
                               $songs[$i]['duration'],
                               $songs[$i]['file']  );

            $stmt->execute();
            $song_id = $stmt->insert_id;
            $stmt->close();    
          } else {
            $song_id = $search_name['id'];
          }


          //Insert relation into wp_musicteach_activity_song  
          $stmt = $this->_conn->prepare($query_rel_activity);
          $stmt->bind_param('ss', 
                             $activity_id, 
                             $song_id);
          $stmt->execute();
          $stmt->close();             

        }

    }

    public function update($song) {
    
        
    }

    public function selectFromActivity($id) {

        $query = 'SELECT A.id, A.name, A.duration, A.file FROM wp_musicteach_song A JOIN wp_musicteach_activity_song B ON A.id = B.song_id WHERE B.activity_id = ?';
        $stmt = $this->_conn->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->bind_result($sel_id, $sel_name, $sel_duration, $sel_file);
        $songs = array();

        while ($row = $stmt->fetch()) {
            $songs[] = array('id' => $sel_id,
                           'name' => $sel_name,
                           'duration' => $sel_duration,
                           'file' => $sel_file );
        } 

        $stmt->close();  

        return $songs;      
    }


    private function getByName($name) {

        
        $query = 'SELECT id, name, duration, file FROM wp_musicteach_song WHERE LOWER(name) = ?';
        $stmt = $this->_conn->prepare($query);
        $stmt->bind_param('s', strtolower($name));
        $stmt->execute();
        $stmt->bind_result($sel_id, $sel_name, $sel_duration, $sel_file);

        if ($row = $stmt->fetch()) {
            $song = array('id' => $sel_id,
                          'name' => $sel_name,
                          'duration' => $sel_duration,
                          'file' => $sel_file );
        } else {
            $song = array();
        }

        $stmt->close();  

        return $song;      

    }

    public function unlinkFromActivity($activity_id) {

        $query = 'DELETE FROM wp_musicteach_activity_song WHERE activity_id = ?';
        $stmt = $this->_conn->prepare($query);
        $stmt->bind_param('i', $activity_id);
        $stmt->execute();
        $stmt->close();        

    }

}

?>
