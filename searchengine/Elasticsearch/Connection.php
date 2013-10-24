<?php

class UnknownSendMethodException extends Exception{ }

function fixBadUnicodeForJson($str) {
    $str = preg_replace("/\\\\u00([0-9a-f]{2})\\\\u00([0-9a-f]{2})\\\\u00([0-9a-f]{2})\\\\u00([0-9a-f]{2})/e", 'chr(hexdec("$1")).chr(hexdec("$2")).chr(hexdec("$3")).chr(hexdec("$4"))', $str);
    $str = preg_replace("/\\\\u00([0-9a-f]{2})\\\\u00([0-9a-f]{2})\\\\u00([0-9a-f]{2})/e", 'chr(hexdec("$1")).chr(hexdec("$2")).chr(hexdec("$3"))', $str);
    $str = preg_replace("/\\\\u00([0-9a-f]{2})\\\\u00([0-9a-f]{2})/e", 'chr(hexdec("$1")).chr(hexdec("$2"))', $str);
    $str = preg_replace("/\\\\u00([0-9a-f]{2})/e", 'chr(hexdec("$1"))', $str);
    return $str;
}

class ElasticsearchConnection{
   static $connection;
   private $curl;
   
   public function __construct(){
      $this->curl = curl_init();
   }
   
   static function getInstance(){
      if( !isset( self::$connection ) ){
         self::$connection = new ElasticsearchConnection();
      }
      return self::$connection;
   }
   
   /*
    * Please rewrite, copy-pasted this from the internet. It works.
    */
   
   private function restRequest($method,$uri,$query=NULL,$json=NULL,$options=NULL){
      
     if( $query == "?" ){
        $query = "";
     }
      
     // Compose querry
     $options = array(
       CURLOPT_URL => "http://86.93.138.176:9200/zoekmachine/".$uri.$query,
       CURLOPT_CUSTOMREQUEST => $method, // GET POST PUT PATCH DELETE HEAD OPTIONS 
       CURLOPT_POSTFIELDS => $json,
       CURLOPT_RETURNTRANSFER => true
     ); 
     
     //exit;
     
     curl_setopt_array($this->curl, $options);
   
     // send request and wait for responce
     $response = curl_exec($this->curl);
     
     $timeinfo =  curl_getinfo($this->curl, CURLINFO_TOTAL_TIME);
     
     $returnAr =  json_decode($response);
     $returnAr->tooktime = (string) $timeinfo;
     
     return $returnAr;
   }
   
   private static function queryBuilder( $query ){
      if( $query !== NULL && !is_array($query) ){
         return "?".$query;
      }
      else if($query !== NULL && is_array($query)){
         $qr = "?".$query[0];
         
         array_shift($query);
         
         // Fix for arrays that consist of one part...
         if( count($query) == 0 )
            return $qr;
            
         return $qr . implode('&', $query);
      }
      return $qr = "";   // Empty
   }
   
   /**
    *
    * @param $method string PUT|GET|DELETE|POST|HEAD|OPTIONS
    * @param $json string|array Valid json-string or array (which whill be json_encoded)
    *
    */
   
   public function send( $method, $uri, $query = NULL, $json = NULL ){
      $method = strtoupper($method);
      if( !in_array($method, array("PUT", "GET", "DELETE", "POST", "HEAD", "OPTIONS")) )
         throw new UnknownSendMethodException();
      
      if(is_array($json))
         $json = json_encode($json);
      
      $qr = $this->queryBuilder( $query );
      
      return $this->restRequest($method, $uri, $qr, $json);
   }
   
}

?>