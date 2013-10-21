<?php
require_once "lib/XMLSerializer.php";

class ResultList implements Iterator{
 
   private $list = array(), $position;
   
   function __construct(){
      // Initializes empty one
      $this->position = 0;
   }
   
   public function add( Document $document ){
      $this->list[] = $document;
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
   
   // The iterator stuff
   
   function rewind() {
       $this->position = 0;
   }

   function current() {
      return $this->list[$this->position];
   }

   function key() {
      return $this->position;
   }

   function next() {
      ++$this->position;
   }

   function valid() {
      return isset($this->list[$this->position]);
   }
   
}

?>