<?php
require_once "lib/XMLSerializer.php";

class ResultList implements Iterator{
 
   $list = [];
   
   function __construct(){
      // Initializes empty one
   }
   
   public function add( Document $document ){
      $this->list[] = $document
   }
   
   public function addFromJson( $jsonText ){
      $this->list[] = new Document( $jsonText );
   }
   
   /**
    * 
    * Return the result list as an XML-document so it could be used
    * by XSLT to create the result page.
    *
    */
   
   public function asXML(){
      $serializer = new XMLSerializer();
      $serializer->serialize( $this->list );
      return $serializer->asXML();
   }
   
   // @todo Do we need this?
   
   public function asJSON(){
      return json_encode( $this->list );  
   }
   
   // @todo The iterator stuff
   
}

?>