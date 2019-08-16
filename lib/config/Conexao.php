<?php

abstract class Conexao {

  private $con;

  public function __constructor(
      $serverHostName, $attachDBFileName, $database, $user, $pass
  ){
    $local = 
        "sqlsrv:Server={$serverHostName}; 
         AttachDBFileName={$attachDBFileName}; 
         Database={$database}; 
         ConnectionPooling=0";

    if($this->con != null){ return $con; }

    $this->con = new PDO($local, $user, $pass);
    $this->con->setAttribute(PDO::SQLSRV_ATTR_DIRECT_QUERY, true);
    $this->con->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
    $this->con->setAttribute(PDO::SQLSRV_ATTR_ENCODING, PDO::SQLSRV_ENCODING_UTF8);
    $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $this->con;
  }

}