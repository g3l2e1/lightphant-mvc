<?php

class Conexao {

    static $con;
    
    static function phpSqlSrvWindows() {
        
    	$serverHostName = "localhost";
    	$attachDBFileName = "C:\Program Files\Microsoft SQL Server\MSSQL10_50.MSSQLSERVER\MSSQL\DATA\Banco.mdf";
    	$user = "sa"; $pass = "123456";
        
        //Definir driver de conexão.
        $local = "sqlsrv:Server={$serverHostName}; AttachDBFileName={$attachDBFileName}; Database=MEUBANCO; ConnectionPooling=0";
                
        //Definir objeto de conexão.
        self::$con = self::$con == null ? new PDO($local, $user, $pass) : self::$con;        
        self::$con->setAttribute(PDO::SQLSRV_ATTR_DIRECT_QUERY, true);
        self::$con->setAttribute(PDO::SQLSRV_ATTR_ENCODING, PDO::SQLSRV_ENCODING_UTF8);
        self::$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		self::$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        //Retornar objeto de conexão.
        return self::$con;
    }
    
    static function phpSqlSrvLinux() {

    	$serverIp = "127.0.0.1";
    	$user = "sa"; $pass = "123456";
    	
    	//Definir driver de conexão.
    	$local = "dblib:version=7.0; dbname=MEUBANCO; charset=UTF-8; host={$serverIp};";
    	
    	//Definir objeto de conexão.
    	self::$con = self::$con == null ? new PDO($local, $user, $pass) : self::$con;
    	
    	//Definições especificas para cada sistema operacional.
    	self::$con->setAttribute(PDO::SQLSRV_ATTR_DIRECT_QUERY, true);
    	self::$con->setAttribute(PDO::SQLSRV_ATTR_ENCODING, PDO::SQLSRV_ENCODING_UTF8);
    	self::$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    	self::$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    	//Retornar objeto de conexão.
    	return self::$con;
    }

}

?>