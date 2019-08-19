<?php

class Conexao {

  static $con;

  static function con(
      $serverHostName, $attachDBFileName, $database, $user, $pass
  ){
    $local = 
      "sqlsrv:Server={$serverHostName}; 
       AttachDBFileName={$attachDBFileName}; 
       Database={$database}; 
       ConnectionPooling=0";

    if(self::$con != null){ return self::$con; }

    self::$con = new PDO($local, $user, $pass);
    self::$con->nextRow = false;
    self::$con->setAttribute(PDO::SQLSRV_ATTR_DIRECT_QUERY, true);
    self::$con->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
    self::$con->setAttribute(PDO::SQLSRV_ATTR_ENCODING, PDO::SQLSRV_ENCODING_UTF8);
    self::$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    self::$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return self::$con;
  }

}