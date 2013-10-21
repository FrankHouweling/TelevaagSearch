<?php

require_once "../searchengine/Elasticsearch/Connection.php";

class ElasticsearchQuery{
   
   function __construct(){
      
   }
   
   function insert( $dataType, $jsonData ){
      $con = ElasticsearchConnection::getInstance();
      $return = $con->send( "POST", $dataType, "", $jsonData );
      return $return;
   }
   
   function search( $query, $dataType = false ){
      $con = ElasticsearchConnection::getInstance();
      $return = $con->send( "GET", "_search", array( "query" => "marx'" ) );
      return $return;
   }
   
}

?>