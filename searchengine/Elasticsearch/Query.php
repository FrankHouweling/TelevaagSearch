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
   
<<<<<<< HEAD
   function search( $query, $dataType = "" ){
=======
   function search( $query, $dataType = "", $van = 0, $tot = false ){
>>>>>>> 110ba0b76accf0417a9281d16ed0f7fc3cbf84fd
      $con = ElasticsearchConnection::getInstance();
      
      if( $dataType !== "" )
         $dataType = $dataType . "/";
      
      if( $tot == false ){
         $tot = ( $van+20 );
      }
      
      $return = $con->send( "GET",  $dataType . "_search", NULL, 
        '{
<<<<<<< HEAD
=======
           "from" : ' . $van . ', "size" : ' . $tot . ',
>>>>>>> 110ba0b76accf0417a9281d16ed0f7fc3cbf84fd
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