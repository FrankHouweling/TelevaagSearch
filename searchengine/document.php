<?php

/**
 * 
 * Represents a document found with the query
 *
 */

class Document{
   
   public function __construct( $docId, $docTitle, $docAuthor, $docUrl, $docContent ){
   }
   
   // Getters
   
   public function getId(){
      return $this->id;
   }
   
   public function getTitle(){
      return $this->title;
   }
   
   public function getAuthor(){
      return $this->author;  
   }
   
   public function getUrl(){
      return $this->url;  
   }
   
   // Setters
   
   /**
    * 
    * Called after each setter to save data to ElasticSearch
    *
    */
   private function save(){
      // @todo Implement ElasticSearch
   }
   
   /**
    * 
    * Construct a new Document instance from a JSON result-document.
    *
    */
   public static function buildFromJson( $jsonText ){
      $content = json_decode( $jsonText );
      return new Document($content['id'], $content['title'], 
                          $content['author'], $content['url'], $content['content']);
   }
   
}

?>