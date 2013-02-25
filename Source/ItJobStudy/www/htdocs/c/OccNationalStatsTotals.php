<?php
/** 
  * Occupation National Stats Total MySQL database script 
  * $password variable must be changed prior to deployment
  */ 
class OccNationalStats { 
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

	public function getNationalJobs() {
     $stmt = mysqli_prepare($this->connection,
          "SELECT
           sum(jobs),
           year 
          FROM itology.occStateITStats 
          GROUP BY year ASC");     
         
      $this->throwExceptionOnError();

      mysqli_stmt_execute($stmt);
      $this->throwExceptionOnError();

      $rows = array();
      mysqli_stmt_bind_result($stmt, $row->jobs, $row->year);

      while (mysqli_stmt_fetch($stmt)) {
          $rows[] = $row;
          $row = new stdClass();
          mysqli_stmt_bind_result($stmt, $row->jobs, $row->year);
      }

      mysqli_stmt_free_result($stmt);
      mysqli_close($this->connection);

      return $rows;
  }  


  public function getNationalSalary() {
     $stmt = mysqli_prepare($this->connection,
          "SELECT
           avg(salary),
           year 
          FROM itology.occStateITStats 
          GROUP BY year ASC");     
         
      $this->throwExceptionOnError();

      mysqli_stmt_execute($stmt);
      $this->throwExceptionOnError();

      $rows = array();
      mysqli_stmt_bind_result($stmt, $row->salary, $row->year);

      while (mysqli_stmt_fetch($stmt)) {
          $rows[] = $row;
          $row = new stdClass();
          mysqli_stmt_bind_result($stmt, $row->salary, $row->year);
      }

      mysqli_stmt_free_result($stmt);
      mysqli_close($this->connection);

      return $rows;
  }  


  public function getNationalSalaryByID($itemID) {
     $stmt = mysqli_prepare($this->connection,
          "SELECT
              occStats.salary
           FROM occStats where occStats.pk_occ=?");
      $this->throwExceptionOnError();
          
      mysqli_stmt_bind_param($stmt, 'i', $itemID);
      $this->throwExceptionOnError();

      mysqli_stmt_execute($stmt);
      $this->throwExceptionOnError();

      $rows = array();
      mysqli_stmt_bind_result($stmt, $row->salary);

      if (mysqli_stmt_fetch($stmt)) {
                  return $row;
      } else {
                  return null;
          }

      mysqli_stmt_free_result($stmt);
      mysqli_close($this->connection);

  }
  
  public function getNationalJobsByID($itemID) {
     $stmt = mysqli_prepare($this->connection,
          "SELECT
              occStats.jobs
           FROM occStats where occStats.pk_occ=?");
      $this->throwExceptionOnError();
          
      mysqli_stmt_bind_param($stmt, 'i', $itemID);
      $this->throwExceptionOnError();

      mysqli_stmt_execute($stmt);
      $this->throwExceptionOnError();

      $rows = array();
      mysqli_stmt_bind_result($stmt, $row->jobs);

      if (mysqli_stmt_fetch($stmt)) {
                  return $row;
      } else {
                  return null;
          }

      mysqli_stmt_free_result($stmt);
      mysqli_close($this->connection);

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
