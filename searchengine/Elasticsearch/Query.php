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
   
   function advanced( $raw , $dataType ){
   	$con = ElasticsearchConnection::getInstance();

      if( $dataType !== "" )
         $dataType = $dataType . "/";
         
    $return = $con->send( "GET",  $dataType . "_search", NULL, json_encode($raw));
         
    return $return;
   }
   function search( $query, $dataType = "", $van = 0, $tot = false ){
      $con = ElasticsearchConnection::getInstance();
      
      if( $dataType !== "" ){
         $dataType = $dataType . "/";
         $filter = '';
      }
      else{
         $filter = '"filter" : { 
                        "not" : { 
                          "or" : [ 
                             { 
                                "type" : { 
                                   "value" : "kamervraag-bm25" 
                                } 
                             }, 
                             { 
                                "term" : { 
                                   "_type" : "kamervraag-bm25" 
                                } 
                             } 
                          ] 
                        } 
                     }, ';
      }
      
      if( $tot == false ){
         $tot = ( $van+20 );
      }
      
      $return = $con->send( "GET",  $dataType . "_search", NULL, 
        '{
           "from" : ' . $van . ', "size" : ' . $tot . ',
           "query": {
                  "filtered" : { 
                     ' . $filter . '
                     "query" : { 
                        "query_string": {
                     "query": "' . $query . '"
                 }
                     } 
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
   
   function searchByPerson( $query, $person, $dataType = "", $van = 0, $tot = false ){
       $con = ElasticsearchConnection::getInstance();
      
      if( $dataType !== "" )
         $dataType = $dataType . "/";
      
      if( $tot == false ){
         $tot = ( $van+20 );
      }
      
      $json = '{
           "from" : ' . $van . ', "size" : ' . $tot . ',
           "query": {
                  "bool": {
                     "must": [
                        {
                           "query_string": {
                              "query": "' . $query . '"
                           }
                        },
                        {"query_string":{"default_field":"kamervraag.persons","query":"' . $person . '"}}
                     ] 
                  }
             },
             "highlight" : {
                 "fields" : {
                     "text" : {"fragment_size" : 150, "number_of_fragments" : 3}
                 }
             }
         }';
         
      $return = $con->send( "GET",  $dataType . "_search", NULL, 
        $json );
      
      foreach( $return->hits->hits as $id => $item ){
         if( !in_array( $person, $item->_source->persons ) ){
            unset( $return->hits->hits[$id] );
            $return->hits->total--;
         }
      }
        
      return $return;
   }
   
   function persons( $query, $dataType ){
      $con = ElasticsearchConnection::getInstance();
      
      if( $dataType !== "" )
         $dataType = $dataType . "/";
      
      if( $tot == false ){
         $tot = ( $van+20 );
      }
      
      $return = $con->send( "GET",  $dataType . "_search", NULL, 
        '{
           "from" : 0, "size" : 2500,
           "fields" : ["persons"],
           "query": {
                 "query_string": {
                     "query": "' . $query . '"
                 }
             }
         }' );
         
      return $return;
   }
   
   function id( $givenId ){
      $con = ElasticsearchConnection::getInstance();
      
      if( $dataType !== "" )
         $dataType = $dataType . "/";
         
      $return = $con->send( "GET",  $dataType . "_search", array("q=_id:" . $givenId) );
      
      return $return;
   }
   
}

?>