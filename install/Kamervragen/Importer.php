<?php

ini_set('memory_limit', '1000M');

require_once "Importer.php";
require_once "../searchengine/Elasticsearch/Query.php";

class ImportDirNotWritableException extends Exception{}

class KamervragenImporter implements Importer{
   
   public function runImport($dataSrc = "Kamervragen/Data/"){
      $handle = opendir($dataSrc);
      if (!$handle) {
         throw new ImportDirNotWritableException();
      }
      
      /* This is the correct way to loop over the directory. */
      while (false !== ($entry = readdir($handle))) {
          if( !in_array($entry, array('.', '..', '.DS_Store')) && str_replace(".xml", "", $entry) !== $entry ){
              $this->loadDataFromXml( $dataSrc . $entry );
          }
      }
   
      closedir($handle);

   }
   
   private function removeallattrs(){
      
   }
   
    private function loadDataFromXml( $srcUrl ){
      $content = file_get_contents( $srcUrl );
      
      if( !$content )
         throw new ImportDirNotWritabelException();
      
      // Create SimpleXMLElement and pass it through to the preprocessor
      // to make a valid JSON representation
      
      $document = new \DomDocument('1.0', 'UTF-8');
      @$document->loadXML($content);

      $element = $document->getElementsByTagName("kvr")->item(0);
         $jar = array();
         
         // Get title & permalink
         
         foreach ($element->getElementsByTagName("meta")->item(0)->getElementsByTagName("metadata")->item(0)->getElementsByTagName('item') as $ding)
         {
            if ($ding->getAttribute('attribuut') == "Bibliografische_omschrijving")
            {
              $jar['title'] = $ding->nodeValue;
            }
            if ($ding->getAttribute('attribuut') == "Permalink")
            {
              $jar['link'] = $ding->nodeValue;
            }
         }
         
         // Get some important persons..
         $jar['persons'] = array();
         foreach( $element->getElementsByTagName("vraagdata")->item(0)->getElementsByTagName("vrager") as $person ){
            $jar['persons'][] = $person->nodeValue . " (" . $person->getAttribute("partij") . ") (" . $person->getAttribute("oorsprong") . ")" ;
         }
         
         foreach( $element->getElementsByTagName("antwoorddata")->item(0)->getElementsByTagName("antwoorder") as $person ){
            $jar['persons'][] = $person->nodeValue . " (" . $person->getAttribute("functie") . ") (" . $person->getAttribute("ministerie") . ")" ;
         }
         
         // Get date
         
         $jar['date'] = $element->getElementsByTagName("meta")->item(0)->getElementsByTagName("vraagdata")->item(0)->getAttribute("indiendatum");
         
         // Get vragen
         foreach($element->getElementsByTagName("vragen")->item(0)->getElementsByTagName("vraag") as $vraag){
            $jar["vragen"][] = $vraag->nodeValue;
         }
         
         // Get antwoorden
         foreach($element->getElementsByTagName("antwoorden")->item(0)->getElementsByTagName("antwoord") as $antwoord){
            $jar["antwoorden"][] = $antwoord->nodeValue;
         }
         
         $jar["text"] = "Vragen:\n\n" . implode($jar["vragen"], "\n") . "\n\nAntwoorden:\n\n" . implode($jar['antwoorden'], "\n");
         
         
         $q = new ElasticsearchQuery();
         $q->insert( "kamervraag", json_encode($jar) );
      
   }
   
}

?>