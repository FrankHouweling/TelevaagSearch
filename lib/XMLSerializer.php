<?php

class XmlSerializer{
    
    public $return, $domdoc;
    
    function __construct( $name = "serialized", $returnobject = false ){
        if( $returnobject !== false ){
            $this->return = $returnobject;
        }
        else{
            $this->domdoc = new DomDocument();
            $node = $this->domdoc->createElement( $name );
            $this->domdoc->appendChild( $node );
            $this->return  = $node;
        }
    }
    
    function asXML()
    {
        return $this->domdoc->saveXML();
    }
    
    function serialize( $element, $name = false, $toElement = false ){
        
        // Get type..
        $type = gettype( $element );
        
        if( $name !== false ){
            $name = $name;
        }
        else{
            $name = $type;
        }
        
        if( is_numeric($name) ){
            $name = "item";
        }
        
        if( $toElement == false ){
            $obj = $this->return;
        }
        else{
            $obj = $toElement;
        }
        
        switch( $type ){
            case "object":
                return $this->serializeObject($element, $name, $toElement);
            break;
            case "array":
                return $this->serializeArray( $element, $name , $toElement );
            break;
            case "string":
            case "boolean":
            case "integer":
            case "double":
                $node = $this->domdoc->createElement( $name, $element );
                $obj->appendChild( $node );
            break;
            default:
                throw new Exception( "Unsupported datatype." );
            break;
        }
        
    }
    
    function serializeArray( Array $ar, $name = false, $toElement = false ){
        
        if( $toElement == false ){
            $toElement = $this->return;
        }
        
        if( $name == false ){
            $name = "list";
        }
        
        $list = $this->domdoc->createElement( $name );
        $toElement->appendChild( $list );
        foreach( $ar as $name => $value ){
            $this->serialize( $value, $name, $list );
        }
    }
    
    function serializeObject( $object, $name, $toObj = false ){
        
        $attributes = (array) $object;
        
        if( $name == false ){
            $elementName = get_class($object);
        }
        elseif( $name == "object" ){
           
            $elementName = "object";
            
            foreach( $attributes as $tmpname => $tmpvalue ){
                if( trim( str_replace( get_class($object), "",  str_replace("\0", '', $tmpname) ) ) == "tablename" ){
                    $elementName = $tmpvalue;
                }
            }
            
        }
        else{
            $elementName = $name;
        }
        
        $ob = $this->domdoc->createElement( $elementName  );
        
        if( $toObj !== false ){
            $toObj->appendChild( $ob);
        }
        else{
           $this->return->appendChild( $ob );
        }
        
        foreach( $attributes as $name => $value ){
            $name = str_replace("\0", '', $name);
            $name = str_replace( get_class($object), "", $name );
            $this->serialize( $value, $name, $ob );
        }
    }
    
}

?>