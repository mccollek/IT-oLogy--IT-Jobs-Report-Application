<?php 
class StatesData { 
  var $username = "root"; 
  var $password = "connect2it"; 
  var $server = "localhost"; 
  var $port = "3306"; 
  var $databasename = "itology"; 
  var $tablename = "states"; 
  
  var $connection; 
  public function __construct() { 
    $this->connection = mysqli_connect( 
                       $this->server,  
                       $this->username,  
                       $this->password, 
                       $this->databasename, 
                       $this->port 
                       ); 
    
    $this->throwExceptionOnError($this->connection); 
  } 

  public function getStates() {
     $stmt = mysqli_prepare($this->connection,
          "SELECT
              states.pk_state,
              states.stateNo,
              states.stateAbr,
              states.stateName
           FROM states");     
         
      $this->throwExceptionOnError();

      mysqli_stmt_execute($stmt);
      $this->throwExceptionOnError();

      $rows = array();
      mysqli_stmt_bind_result($stmt, $row->pk_state, $row->stateNo,
                    $row->stateAbr, $row->stateName);

      while (mysqli_stmt_fetch($stmt)) {
          $rows[] = $row;
          $row = new stdClass();
          mysqli_stmt_bind_result($stmt, $row->pk_state, $row->stateNo,
                    $row->stateAbr, $row->stateName);
      }

      mysqli_stmt_free_result($stmt);
      mysqli_close($this->connection);

      return $rows;
  }  

  public function getNameByKey($itemID) {
     $stmt = mysqli_prepare($this->connection,
          "SELECT
              states.stateName
           FROM states Where pk_state=?");
      $this->throwExceptionOnError();
          
      mysqli_stmt_bind_param($stmt, 'i', $itemID);
      $this->throwExceptionOnError();

      mysqli_stmt_execute($stmt);
      $this->throwExceptionOnError();

      $rows = array();
      mysqli_stmt_bind_result($stmt, $row->stateName);

      if (mysqli_stmt_fetch($stmt)) {
                  return $row;
      } else {
                  return null;
          }

      mysqli_stmt_free_result($stmt);
      mysqli_close($this->connection);

  }
  
  public function getNameByAbr($itemID) {
     $stmt = mysqli_prepare($this->connection,
          "SELECT
              states.stateName
           FROM states Where stateAbr=?");
      $this->throwExceptionOnError();
          
      mysqli_stmt_bind_param($stmt, 'i', $itemID);
      $this->throwExceptionOnError();

      mysqli_stmt_execute($stmt);
      $this->throwExceptionOnError();

      $rows = array();
      mysqli_stmt_bind_result($stmt, $row->stateName);

      if (mysqli_stmt_fetch($stmt)) {
                  return $row;
      } else {
                  return null;
          }

      mysqli_stmt_free_result($stmt);
      mysqli_close($this->connection);

  }
  
  public function getNameByNumber($itemID) {
     $stmt = mysqli_prepare($this->connection,
          "SELECT
              states.stateName
           FROM states Where stateNo=?");
      $this->throwExceptionOnError();
          
      mysqli_stmt_bind_param($stmt, 'i', $itemID);
      $this->throwExceptionOnError();

      mysqli_stmt_execute($stmt);
      $this->throwExceptionOnError();

      $rows = array();
      mysqli_stmt_bind_result($stmt, $row->stateName);

      if (mysqli_stmt_fetch($stmt)) {
                  return $row;
      } else {
                  return null;
          }

      mysqli_stmt_free_result($stmt);
      mysqli_close($this->connection);

  }
  
  public function getKeyByNumber($itemID) {
     $stmt = mysqli_prepare($this->connection,
          "SELECT
              states.pk_state
           FROM states Where stateNo=?");
      $this->throwExceptionOnError();
          
      mysqli_stmt_bind_param($stmt, 'i', $itemID);
      $this->throwExceptionOnError();

      mysqli_stmt_execute($stmt);
      $this->throwExceptionOnError();

      $rows = array();
      mysqli_stmt_bind_result($stmt, $row->pk_state);

      if (mysqli_stmt_fetch($stmt)) {
                  return $row;
      } else {
                  return null;
          }

      mysqli_stmt_free_result($stmt);
      mysqli_close($this->connection);

  }
  
  public function getKeyByName($itemID) {
     $stmt = mysqli_prepare($this->connection,
          "SELECT
              states.pk_state
           FROM states Where stateName=?");
      $this->throwExceptionOnError();
          
      mysqli_stmt_bind_param($stmt, 'i', $itemID);
      $this->throwExceptionOnError();

      mysqli_stmt_execute($stmt);
      $this->throwExceptionOnError();

      $rows = array();
      mysqli_stmt_bind_result($stmt, $row->pk_state);

      if (mysqli_stmt_fetch($stmt)) {
                  return $row;
      } else {
                  return null;
          }

      mysqli_stmt_free_result($stmt);
      mysqli_close($this->connection);

  }
  
  public function getKeyByAbr($itemID) {
     $stmt = mysqli_prepare($this->connection,
          "SELECT
              states.pk_state
           FROM states Where stateAbr=?");
      $this->throwExceptionOnError();
          
      mysqli_stmt_bind_param($stmt, 'i', $itemID);
      $this->throwExceptionOnError();

      mysqli_stmt_execute($stmt);
      $this->throwExceptionOnError();

      $rows = array();
      mysqli_stmt_bind_result($stmt, $row->pk_state);

      if (mysqli_stmt_fetch($stmt)) {
                  return $row;
      } else {
                  return null;
          }

      mysqli_stmt_free_result($stmt);
      mysqli_close($this->connection);

  }

	public function getNumberByKey($itemID) {
     $stmt = mysqli_prepare($this->connection,
          "SELECT
              states.stateNo
           FROM states Where pk_state=?");
      $this->throwExceptionOnError();
          
      mysqli_stmt_bind_param($stmt, 'i', $itemID);
      $this->throwExceptionOnError();

      mysqli_stmt_execute($stmt);
      $this->throwExceptionOnError();

      $rows = array();
      mysqli_stmt_bind_result($stmt, $row->stateNo);

      if (mysqli_stmt_fetch($stmt)) {
                  return $row;
      } else {
                  return null;
          }

      mysqli_stmt_free_result($stmt);
      mysqli_close($this->connection);

  }
  
  public function getNumberByAbr($itemID) {
     $stmt = mysqli_prepare($this->connection,
          "SELECT
              states.stateNo
           FROM states Where stateAbr=?");
      $this->throwExceptionOnError();
          
      mysqli_stmt_bind_param($stmt, 'i', $itemID);
      $this->throwExceptionOnError();

      mysqli_stmt_execute($stmt);
      $this->throwExceptionOnError();

      $rows = array();
      mysqli_stmt_bind_result($stmt, $row->stateNo);

      if (mysqli_stmt_fetch($stmt)) {
                  return $row;
      } else {
                  return null;
          }

      mysqli_stmt_free_result($stmt);
      mysqli_close($this->connection);

  }
  
  public function getNumberByName($itemID) {
     $stmt = mysqli_prepare($this->connection,
          "SELECT
              states.stateNo
           FROM states Where stateName=?");
      $this->throwExceptionOnError();
          
      mysqli_stmt_bind_param($stmt, 'i', $itemID);
      $this->throwExceptionOnError();

      mysqli_stmt_execute($stmt);
      $this->throwExceptionOnError();

      $rows = array();
      mysqli_stmt_bind_result($stmt, $row->stateNo);

      if (mysqli_stmt_fetch($stmt)) {
                  return $row;
      } else {
                  return null;
          }

      mysqli_stmt_free_result($stmt);
      mysqli_close($this->connection);

  }
  
  public function getAbrByKey($itemID) {
     $stmt = mysqli_prepare($this->connection,
          "SELECT
              states.stateAbr
           FROM states Where pk_state=?");
      $this->throwExceptionOnError();
          
      mysqli_stmt_bind_param($stmt, 'i', $itemID);
      $this->throwExceptionOnError();

      mysqli_stmt_execute($stmt);
      $this->throwExceptionOnError();

      $rows = array();
      mysqli_stmt_bind_result($stmt, $row->stateAbr);

      if (mysqli_stmt_fetch($stmt)) {
                  return $row;
      } else {
                  return null;
          }

      mysqli_stmt_free_result($stmt);
      mysqli_close($this->connection);

  }
  
  public function getAbrByNumber($itemID) {
     $stmt = mysqli_prepare($this->connection,
          "SELECT
              states.stateAbr
           FROM states Where stateNo=?");
      $this->throwExceptionOnError();
          
      mysqli_stmt_bind_param($stmt, 'i', $itemID);
      $this->throwExceptionOnError();

      mysqli_stmt_execute($stmt);
      $this->throwExceptionOnError();

      $rows = array();
      mysqli_stmt_bind_result($stmt, $row->stateAbr);

      if (mysqli_stmt_fetch($stmt)) {
                  return $row;
      } else {
                  return null;
          }

      mysqli_stmt_free_result($stmt);
      mysqli_close($this->connection);

  }
  
  public function getAbrByName($itemID) {
     $stmt = mysqli_prepare($this->connection,
          "SELECT
              states.stateAbr
           FROM states Where stateName=?");
      $this->throwExceptionOnError();
          
      mysqli_stmt_bind_param($stmt, 'i', $itemID);
      $this->throwExceptionOnError();

      mysqli_stmt_execute($stmt);
      $this->throwExceptionOnError();

      $rows = array();
      mysqli_stmt_bind_result($stmt, $row->stateAbr);

      if (mysqli_stmt_fetch($stmt)) {
                  return $row;
      } else {
                  return null;
          }

      mysqli_stmt_free_result($stmt);
      mysqli_close($this->connection);

  }

/** 
  * Utitity function to throw an exception if an error indurs 
  * while running a mysql command. 
  */ 
  private function throwExceptionOnError($link = null) { 
    if($link == null) { 
      $link = $this->connection; 
    } 
    if(mysqli_error($link)) { 
      $msg = mysqli_errno($link) . ": " . mysqli_error($link); 
      throw new Exception('MySQL Error - '. $msg); 
    }         
  } 
 
} 
?>
