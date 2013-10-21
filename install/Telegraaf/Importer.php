<?php

ini_set('memory_limit', '1000M');

require_once "Importer.php";
require_once "../searchengine/Elasticsearch/Query.php";

class ImportDirNotWritableException extends Exception{}

class TelegraafImporter implements Importer{
   
   public function runImport($dataSrc = "Telegraaf/Data/"){
      $handle = opendir($dataSrc);
      if (!$handle) {
         throw new ImportDirNotWritableException();
      }
      
      /* This is the correct way to loop over the directory. */
      while (false !== ($entry = readdir($handle))) {
          if( !in_array($entry, array('.', '..', '.DS_Store')) ){
              $this->loadDataFromXml( $dataSrc . $entry );
          }
      }
   
      closedir($handle);

   }
   
   private function loadDataFromXml( $srcUrl ){
      $content = file_get_contents( $srcUrl );
      
      if( !$content )
         throw new ImportDirNotWritabelException();
      
      // Create SimpleXMLElement and pass it through to the preprocessor
      // to make a valid JSON representation
      
      $document = new \DomDocument('1.0', 'UTF-8');
      @$document->loadXML($content);

      foreach( $document->getElementsByTagName("root") as $element ){
         $text = $element->getElementsByTagName("text");
         $title = $element->getElementsByTagName('title');
         
         
         $q = new ElasticsearchQuery();
         $q->insert( "telegraafarticle", json_encode(array( "text" => $text->item(0)->nodeValue, "title" => $title->item(0)->nodeValue)) );
      }
      
   }
   
}

?>