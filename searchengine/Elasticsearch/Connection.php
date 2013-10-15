<?php

class UnknownSendMethodException extends Exception{ }

class ElasticsearchConnection{
   static $connection;
   
   public function __construct(){
      // Do things..
   }
   
   public function getInstance(){
      if( !isset( self::connection ) ){
         self::connection = new ElasticsearchConnection();
      }
      return self::connection;
   }
   
   public function send( $method, $json ){
      $method = strtolower($method);
      if( !in_array($method, array("put", "get", "delete", "set")) )
         throw new UnknownSendMethodException();
      
      curl();  //  Curl stuf, you know how..
   }
   
}

?>