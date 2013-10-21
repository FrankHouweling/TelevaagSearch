<?php

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
   
      require_once "searchengine/site/results.php";
   }
   else{
      require_once "searchengine/site/index.php";
   }
}
else{
   
}

?>