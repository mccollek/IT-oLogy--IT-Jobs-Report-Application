<?php 
class IndNationalStats { 
  var $username = "root"; 
  var $password = "connect2it"; 
  var $server = "localhost"; 
  var $port = "3306"; 
  var $databasename = "itology"; 
  var $tablename = "indStats"; 
  
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
              indStats.pk_indStat,
              indStats.fk_ind,
              indStats.fk_reg,
              indStats.ownership,
              indStats.aggregation,
              indStats.jobs,
              indStats.orgs,
              indStats.salary,
              indStats.year,
              indStats.quarter,
           FROM indStats");     
         
      $this->throwExceptionOnError();

      mysqli_stmt_execute($stmt);
      $this->throwExceptionOnError();

      $rows = array();
      mysqli_stmt_bind_result($stmt, $row->pk_indStat, $row->fk_ind,
                    $row->jobs, $row->salary, $row->year);

      while (mysqli_stmt_fetch($stmt)) {
          $rows[] = $row;
          $row = new stdClass();
          mysqli_stmt_bind_result($stmt, $row->pk_indStat, $row->fk_ind,
                    $row->jobs, $row->salary, $row->year);
      }

      mysqli_stmt_free_result($stmt);
      mysqli_close($this->connection);

      return $rows;
  }  

	public function getNationalIndJobs() {
     $stmt = mysqli_prepare($this->connection,
          "SELECT
              indStats.pk_indStat,
              indStats.jobs,
              indStats.year
           FROM indStats WHERE fk_reg = '1' AND quarter = '5' AND ownership = '0'");     
         
      $this->throwExceptionOnError();

      mysqli_stmt_execute($stmt);
      $this->throwExceptionOnError();

      $rows = array();
      mysqli_stmt_bind_result($stmt, $row->pk_indStat, $row->jobs, $row->year);

      while (mysqli_stmt_fetch($stmt)) {
          $rows[] = $row;
          $row = new stdClass();
          mysqli_stmt_bind_result($stmt, $row->pk_indStat, $row->jobs, $row->year);
      }

      mysqli_stmt_free_result($stmt);
      mysqli_close($this->connection);

      return $rows;
  }  


  public function getNationalIndSalary() {
     $stmt = mysqli_prepare($this->connection,
          "SELECT
              indStats.pk_indStat,
              indStats.salary,
              indStats.year
           FROM indStats WHERE fk_reg = '1' AND quarter = '5' AND ownership = '0'");     
         
      $this->throwExceptionOnError();

      mysqli_stmt_execute($stmt);
      $this->throwExceptionOnError();

      $rows = array();
      mysqli_stmt_bind_result($stmt, $row->pk_indStat, $row->salary, $row->year);

      while (mysqli_stmt_fetch($stmt)) {
          $rows[] = $row;
          $row = new stdClass();
          mysqli_stmt_bind_result($stmt, $row->pk_indStat, $row->salary, $row->year);
      }

      mysqli_stmt_free_result($stmt);
      mysqli_close($this->connection);

      return $rows;
  }  


  public function getNationalIndSalaryByID($itemID) {
     $stmt = mysqli_prepare($this->connection,
          "SELECT
              indStats.salary
           FROM indStats where indStats.pk_indStat=?");
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
  
  public function getNationalIndJobsByID($itemID) {
     $stmt = mysqli_prepare($this->connection,
          "SELECT
              indStats.jobs
           FROM indStats where indStats.pk_indStat=?");
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
