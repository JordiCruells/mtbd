<?php
 
class CustomerDAO {
 
    private $_conn;

    public function __construct($conn) {
     
        $this->_conn = $conn;
         
    }

    public function create($customer) {
      
        $query = 'INSERT INTO wp_musicteach_customer (name, email, phone, participants, observations) values';
        $query .= '(?,?,?,?,?)';  
        $stmt = $this->_conn->prepare($query);
        $stmt->bind_param('sssss', 
                          $customer['name'], 
                          $customer['email'],
                          $customer['phone'],
                          $customer['participants'],
                          $customer['observations']                      
                        );
        $stmt->execute();
        $insert_id = $stmt->insert_id;
        $stmt->close();        
        return $insert_id;
     }

     public function update($customer) {
        
        $query = 'UPDATE wp_musicteach_customer SET name = ?, email = ?, phone = ?, participants = ?, observations = ? ';
        $query .= ' WHERE id = ?';

        $stmt = $this->_conn->prepare($query);
        $stmt->bind_param('sssssi', 
                          $customer['name'], 
                          $customer['email'],
                          $customer['phone'],
                          $customer['participants'],
                          $customer['observations'],
                          $customer['id']
                        );

        $stmt->execute();
        $stmt->close();
        
     }

      public function select($id) {
        
        $query = 'SELECT id, name, email, phone, participants, observations FROM wp_musicteach_customer ' ;
        $query .= 'WHERE id = ?';

        $stmt = $this->_conn->stmt_init();
        if (!$stmt->prepare($query)) {
          $customer = array();
        } else {

          $stmt->bind_param('i', $id);
          $stmt->execute();
          $stmt->bind_result($sel_id, $sel_name, $sel_email, $sel_phone, $sel_participants, $sel_observations);
          
          if ($row = $stmt->fetch()) {
            $customer = array('id' => $sel_id,
                              'name' => $sel_name,
                              'email' => $sel_email,
                              'phone' => $sel_phone,
                              'participants' => $sel_participants,
                              'observations' => $sel_observations                       
              );
          } else {
            $customer = array();
          }

          $stmt->close();                 
        }

        return $customer;
        
     }

     public function delete($id) {

        $query = 'DELETE FROM wp_musicteach_customer WHERE id = ?';
        $stmt = $this->_conn->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();        

      }



}

?>
