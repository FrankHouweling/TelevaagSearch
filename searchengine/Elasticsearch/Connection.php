<?php

class UnknownSendMethodException extends Exception{ }

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
      
     // Compose querry
     $options = array(
       CURLOPT_URL => "http://localhost:9200/zoekmachine/".$uri.$qr,
       CURLOPT_CUSTOMREQUEST => $method, // GET POST PUT PATCH DELETE HEAD OPTIONS 
       CURLOPT_POSTFIELDS => $json,
     ); 
     
     curl_setopt_array($this->curl, $options); 
   
     // send request and wait for responce
     $responce =  json_decode(curl_exec($this->curl),true);
     
     var_dump( $responce );
     exit;
     
     return($responce);
   }
   
   /**
    *
    * @param $method string PUT|GET|DELETE|POST|HEAD|OPTIONS
    * @param $json string|array Valid json-string or array (which whill be json_encoded)
    *
    */
   
   public function send( $method, $uri, $query, $json ){
      $method = strtoupper($method);
      if( !in_array($method, array("PUT", "GET", "DELETE", "POST", "HEAD", "OPTIONS")) )
         throw new UnknownSendMethodException();
      
      if(is_array($json))
         $json = json_encode($json);
      
      if( $query !== NULL && !is_array($query) ){
         $qr = "?".$query;
      }
      else if($query !== NULL && is_array($query)){
         $qr = "?".$query[0];
         
         $query = array_shift($query);
            
         $qr = $qr . implode('&', $query);
      }
      else{
         $qr = "";   // Empty
      }
      
      return $this->restRequest($method, $uri, $qr, $json);
   }
   
}

?>