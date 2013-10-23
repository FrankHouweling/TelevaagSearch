<?php

require_once "searchengine/Elasticsearch/Query.php";
require_once "searchengine/ResultList.php";
require_once "searchengine/Document.php";
require_once "searchengine/Author.php";

function render( $vars ){
   $templatename = func_get_arg(0);
   
   if( func_num_args() == 2 && is_array(func_get_arg(1)) ){
      $args = func_get_arg(1);
   }
   else{
      $args = func_get_args();
      array_shift($args);
   }
   
   foreach( $args as $key => $val ){
      $$key = $val;
   }
   
   include( $templatename );
}

if( $_SERVER['PATH_INFO'] == NULL ){
   if( isset( $_GET['q'] ) ){
   
      $query = new ElasticsearchQuery();
      $data  = $query->search( $_GET['q'] );
      $display = array();
      $display['result_total_num'] = $data->hits->total;
      $display['processing_time'] = 0.01;
      
      // Moving this to a seperate function would be better.. but for now i'm lazy
      
      $resultlist =  new ResultList();
      foreach( $data->hits->hits as $data ){
         $resultlist->add(
               new Document(
                  $data->_id, $data->_source->title, NULL, 
                 $data->_source->link, $data->highlight->text[0],
                 $data->_source->date
               )
            );
      }
      
      $display['resultset'] = $resultlist;
   
      render( "searchengine/site/results.php", $display );
   }
   else{
      render( "searchengine/site/index.php" );
   }
}
else{
   
}

?>