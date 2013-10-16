<?php

require_once "../searchengine/Elasticsearch/Connection.php";

class ElasticsearchQuery{
   
   function __construct(){
      
   }
   
   function insert( $dataType, $jsonData ){
      $con = ElasticsearchConnection::getInstance();
      $return = $con->send( "PUT", $dataType, "", $jsonData );
      return $return;
   }
   
}

?>