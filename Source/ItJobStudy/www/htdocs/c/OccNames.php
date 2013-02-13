<?php 
class OccNames { 
  var $username = "root"; 
  var $password = "connect2it"; 
  var $server = "localhost"; 
  var $port = "3306"; 
  var $databasename = "itology"; 
  var $tablename = "occNames"; 
  
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

  public function getNames() {
     $stmt = mysqli_prepare($this->connection,
          "SELECT
              occNames.pk_occ,
              occNames.occ,
              occNames.occName
           FROM occNames");     
         
      $this->throwExceptionOnError();

      mysqli_stmt_execute($stmt);
      $this->throwExceptionOnError();

      $rows = array();
      mysqli_stmt_bind_result($stmt, $row->pk_occ, $row->occ,
                    $row->occName);

      while (mysqli_stmt_fetch($stmt)) {
          $rows[] = $row;
          $row = new stdClass();
          mysqli_stmt_bind_result($stmt, $row->pk_occ, $row->occ,
                    $row->occName);
      }

      mysqli_stmt_free_result($stmt);
      mysqli_close($this->connection);

      return $rows;
  }  

	public function getITNames() {
     $stmt = mysqli_prepare($this->connection,
          "SELECT
              occNames.pk_occ,
              occNames.occ,
              occNames.occName
           FROM occNames ORDER BY occName
           ");     
         /*WHERE 
           occ='11-3021' OR
           occ='15-1021' OR
           occ='13-1081' OR
           occ='15-1011' OR
           occ='15-1031' OR
           occ='15-1032' OR
           occ='15-1041' OR
           occ='15-1051' OR
           occ='15-1061' OR
           occ='15-1071' OR
           occ='15-1081' OR
           occ='15-1099' OR
           occ='15-2031' OR
           occ='17-2061' OR
           occ='25-1021' OR
           occ='41-4011' OR
           occ='43-9011' OR
           occ='43-9021' OR
           occ='43-9031' OR
           occ='49-2011' OR
           occ='13-1111' OR
           occ='17-2112' OR
           occ='17-3026' OR
           occ='27-1014' OR
           occ='27-1024' OR
           occ='27-3042' OR
           occ='29-2071' OR
           occ='51-4011' OR
           occ='51-4012' OR
           occ='13-0000' OR
           occ='17-0000' OR
           occ='19-0000' OR
           occ='27-0000' OR
           occ='15-1133' OR
           occ='15-1151' OR
           occ='15-1143' OR
           occ='15-1121' OR
           occ='15-1141' OR
           occ='15-1142' OR
           occ='15-1122' OR
           occ='15-1134' OR
           occ='15-1152' OR
           occ='15-1199'*/
      $this->throwExceptionOnError();

      mysqli_stmt_execute($stmt);
      $this->throwExceptionOnError();

      $rows = array();
      mysqli_stmt_bind_result($stmt, $row->pk_occ, $row->occ,
                    $row->occName);

      while (mysqli_stmt_fetch($stmt)) {
          $rows[] = $row;
          $row = new stdClass();
          mysqli_stmt_bind_result($stmt, $row->pk_occ, $row->occ,
                    $row->occName);
      }

      mysqli_stmt_free_result($stmt);
      mysqli_close($this->connection);

      return $rows;
  }

  public function getNameByCode($itemID) {
     $stmt = mysqli_prepare($this->connection,
          "SELECT
              occNames.occName
           FROM occNames Where occ=?");
      $this->throwExceptionOnError();
          
      mysqli_stmt_bind_param($stmt, 'i', $itemID);
      $this->throwExceptionOnError();

      mysqli_stmt_execute($stmt);
      $this->throwExceptionOnError();

      $rows = array();
      mysqli_stmt_bind_result($stmt, $row->occName);

      if (mysqli_stmt_fetch($stmt)) {
                  return $row;
      } else {
                  return null;
          }

      mysqli_stmt_free_result($stmt);
      mysqli_close($this->connection);

  }
  
  public function getNameByKey($itemID) {
     $stmt = mysqli_prepare($this->connection,
          "SELECT
              occNames.occName
           FROM occNames Where pk_occ=?");
      $this->throwExceptionOnError();
          
      mysqli_stmt_bind_param($stmt, 'i', $itemID);
      $this->throwExceptionOnError();

      mysqli_stmt_execute($stmt);
      $this->throwExceptionOnError();

      $rows = array();
      mysqli_stmt_bind_result($stmt, $row->occName);

      if (mysqli_stmt_fetch($stmt)) {
                  return $row;
      } else {
                  return null;
          }

      mysqli_stmt_free_result($stmt);
      mysqli_close($this->connection);

  }
  
  public function getCodeByKey($itemID) {
     $stmt = mysqli_prepare($this->connection,
          "SELECT
              occNames.occ
           FROM occNames Where pk_occ=?");
      $this->throwExceptionOnError();
          
      mysqli_stmt_bind_param($stmt, 'i', $itemID);
      $this->throwExceptionOnError();

      mysqli_stmt_execute($stmt);
      $this->throwExceptionOnError();

      $rows = array();
      mysqli_stmt_bind_result($stmt, $row->occName);

      if (mysqli_stmt_fetch($stmt)) {
                  return $row;
      } else {
                  return null;
          }

      mysqli_stmt_free_result($stmt);
      mysqli_close($this->connection);

  }
  
  public function getKeyByCode($itemID) {
     $stmt = mysqli_prepare($this->connection,
          "SELECT
              occNames.pk_occ
           FROM occNames Where occ=?");
      $this->throwExceptionOnError();
          
      mysqli_stmt_bind_param($stmt, 'i', $itemID);
      $this->throwExceptionOnError();

      mysqli_stmt_execute($stmt);
      $this->throwExceptionOnError();

      $rows = array();
      mysqli_stmt_bind_result($stmt, $row->occName);

      if (mysqli_stmt_fetch($stmt)) {
                  return $row;
      } else {
                  return null;
          }

      mysqli_stmt_free_result($stmt);
      mysqli_close($this->connection);

  }

  
  public function getCodeByName($itemID) {
     $stmt = mysqli_prepare($this->connection,
          "SELECT
              occNames.occ
           FROM occNames Where occName=?");
      $this->throwExceptionOnError();
          
      mysqli_stmt_bind_param($stmt, 'i', $itemID);
      $this->throwExceptionOnError();

      mysqli_stmt_execute($stmt);
      $this->throwExceptionOnError();

      $rows = array();
      mysqli_stmt_bind_result($stmt, $row->occName);

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
