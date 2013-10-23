<?php

/**
 * 
 * Represents a document found with the query
 *
 */

class Document{
   private $id, $title, $author, $link, $date;
   
   public function __construct( $docId, $docTitle, $docAuthor, $docUrl, $docContent, $date ){
      $this->id = $docId;
      $this->title = $docTitle;
      $this->author = $docAuthor;
      $this->link = $docUrl;
      $this->content = $docContent;
      $this->date = $date;
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
   
   public function generateTitle(){
      $content = $this->content;
      $split = explode(".", $content);
      
      $val = "";
      $i = 0;
      while( strlen($val) <= 100 && isset($split[$i]) ){
         $val = $val . $split[$i];
         $i++;
      }
      
      if( strlen($val) > 100 ){
         return substr($val,0,95) . "...";
      }
      else{
         return $val;
      }
   }
   
   public function getAuthor(){
      return $this->author;  
   }
   
   public function getContent(){
      return $this->content;
   }
   
   public function getLink(){
      return $this->link;
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
   
   public function getDate(){
      return $this->date;
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