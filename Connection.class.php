<?php


  require 'credentials.php';

  class Connection {


    private $_server;
    private $_db;
    private $_user;
    private $_password;
    private static $_conn; 


    public function __construct( $server='', $db='', $user='', $password='') {
      global $DB_PRMS;
      $this->_server = empty($server) ? $DB_PRMS['server'] : $server ;
      $this->_db = empty($db) ? $DB_PRMS['db_name'] : $db;
      $this->_user = empty($user) ? $DB_PRMS['db_user'] : $user;
      $this->_password = empty($password) ? $DB_PRMS['password'] : $password;
      self::$_conn = null; 
    }

    public function getConnection() {

      if (!self::$_conn) {
        self::$_conn = new mysqli($this->_server, $this->_user, $this->_password, $this->_db);
        // Check connection
        if (mysqli_connect_errno()) {
            die("Connection failed: ");
        }
        mysqli_set_charset ( self::$_conn , 'utf8' );
      }

      return self::$_conn;

    }
  }


?>