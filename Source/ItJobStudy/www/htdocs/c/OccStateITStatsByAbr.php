<?php 
class OccStateITStatsByAbr { 
  var $username = "root"; 
  var $password = "connect2it"; 
  var $server = "localhost"; 
  var $port = "3306"; 
  var $databasename = "itology"; 
  var $tablename = "occStateItStats"; 
  
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

  public function getStateITStatsByAbr($stateAbr) {
     $stmt = mysqli_prepare($this->connection,
          "SELECT 
          	year, 
          	sum(jobs),
          	avg(salary)
          FROM `itology`.`occStateITStats` 
          WHERE stateAbr = ? 
          GROUP BY year ASC;
          ");     
         
      $this->throwExceptionOnError();
      
      mysqli_stmt_bind_param($stmt, 's', $stateAbr);
      $this->throwExceptionOnError();

      mysqli_stmt_execute($stmt);
      $this->throwExceptionOnError();

      $rows = array();
      mysqli_stmt_bind_result($stmt, $row->year, $row->jobs,
                    $row->salary);

      while (mysqli_stmt_fetch($stmt)) {
          $rows[] = $row;
          $row = new stdClass();
          mysqli_stmt_bind_result($stmt, $row->year, $row->jobs,
                    $row->salary);
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
