<?php

require_once "Connection.php";

class ElasticsearchQuery{
   
   function __construct(){
      
   }
   
   function insert( $dataType, $jsonData ){
      $con = ElasticsearchConnection::getInstance();
      $return = $con->send( "POST", $dataType, "", $jsonData );
      return $return;
   }
   
   function search( $query, $dataType = "", $van = 0, $tot = false ){
      $con = ElasticsearchConnection::getInstance();
      
      if( $dataType !== "" )
         $dataType = $dataType . "/";
      
      if( $tot == false ){
         $tot = ( $van+20 );
      }
      
      $return = $con->send( "GET",  $dataType . "_search", NULL, 
        '{
           "from" : ' . $van . ', "size" : ' . $tot . ',
           "query": {
                 "query_string": {
                     "query": "' . $query . '"
                 }
             },
             "highlight" : {
                 "fields" : {
                     "text" : {"fragment_size" : 150, "number_of_fragments" : 3}
                 }
             }
         }' );
         
      return $return;
   }
   
}

?>