<?php

/**
 * 
 * Represents a document found with the query
 *
 */

class Document{
   private $id, $title, $author, $url;
   
   public function __construct( $docId, $docTitle, $docAuthor, $docUrl, $docContent ){
      $this->id = $docId;
      $this->title = $docTitle;
      $this->author = $docAuthor;
      $this->url = $url;
      $this->content = $docContent;
   }
   
   // Getters
   
   public function getId(){
      return $this->id;
   }
   
   public function getTitle(){
      if( strlen($this->title) > 0 )
         return $this->title;
      return $this->generateTitle();
   }
   
   public function getAuthor(){
      return $this->author;  
   }
   
   public function getContent(){
      return $this->content;
   }
   
   public function getUrl(){
      return $this->url;  
   }
   
   public function getLink(){
      return $this->url;
   }
   
   public function hasAuthor(){
      if( $this->author instanceof Author ){
         return true;
      }
      else{
         return false;
      }
   }
   
   public function getPreview(){
      return $this->content;
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
                          new Author($content['author']), $content['url'], $content['content']);
   }
   
}

?>