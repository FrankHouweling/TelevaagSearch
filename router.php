<?php

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
      $$key = val;
   }
   
   include( $templatename );
}

if( $_SERVER['PATH_INFO'] == NULL ){
   if( isset( $_GET['q'] ) ){
   
      // Dummy data
      
      $resultset = new ResultList();
      
      $resultset->add( 
         new Document(  "arandomid", 
                        "This is a title", 
                        new Author("Frank Houweling"), 
                        "http://www.google.nl/", 
                        "This is the content." 
                     ) 
                     );
   
      render( "searchengine/site/results.php" );
   }
   else{
      render( "searchengine/site/index.php" );
   }
}
else{
   
}

?>