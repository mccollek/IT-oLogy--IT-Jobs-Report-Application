<?php
/** 
  * Occupation State Stats Class MySQL database script 
  * $password variable must be changed prior to deployment
  */
class OccStateStats { 
  var $username = "root"; 
  var $password = ""; 
  var $server = "localhost"; 
  var $port = "3306"; 
  var $databasename = "itology"; 
  var $tablename = "occStats"; 
  
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

  public function getStats() {
     $stmt = mysqli_prepare($this->connection,
          "SELECT
              occStats.pk_occ,
              occStats.fk_occ,
              occStats.jobs,
              occStats.salary,
              occStats.year
           FROM occStats");     
         
      $this->throwExceptionOnError();

      mysqli_stmt_execute($stmt);
      $this->throwExceptionOnError();

      $rows = array();
      mysqli_stmt_bind_result($stmt, $row->pk_occ, $row->fk_occ,
                    $row->jobs, $row->salary, $row->year);

      while (mysqli_stmt_fetch($stmt)) {
          $rows[] = $row;
          $row = new stdClass();
          mysqli_stmt_bind_result($stmt, $row->pk_occ, $row->fk_occ,
                    $row->jobs, $row->salary, $row->year);
      }

      mysqli_stmt_free_result($stmt);
      mysqli_close($this->connection);

      return $rows;
  }  

	public function getStateDataByOccupation($fk_occ, $fk_reg, $year) {
     $stmt = mysqli_prepare($this->connection,
          "SELECT
              occStats.pk_occ,
              occStats.jobs,
              occStats.salary,
              occStats.year
           FROM occStats WHERE fk_occ=? AND fk_reg=? AND year=?");     
         
      $this->throwExceptionOnError();
      
      mysqli_stmt_bind_param($stmt, 'iii', $fk_occ, $fk_reg, $year);
      $this->throwExceptionOnError();

      mysqli_stmt_execute($stmt);
      $this->throwExceptionOnError();

      $rows = array();
      mysqli_stmt_bind_result($stmt, $row->pk_occ, $row->jobs, $row->salary, $row->year);

      while (mysqli_stmt_fetch($stmt)) {
          $rows[] = $row;
          $row = new stdClass();
          mysqli_stmt_bind_result($stmt, $row->pk_occ, $row->jobs, $row->salary, $row->year);
      }

      mysqli_stmt_free_result($stmt);
      mysqli_close($this->connection);

      return $rows;
  }
  
  /** 
  * Utitity function to throw an exception if an error occurs 
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
