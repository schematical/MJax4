<?php
/* 
 * This is a simple button class for use with the mjax framework
 */
class MJaxLinkButton extends MJaxControl{
    
    public function __construct($objParentControl,$strControlId = null) {
        parent::__construct($objParentControl,$strControlId);

    }

    /////////////////////////
    // Public Properties: GET
    /////////////////////////
    public function __get($strName) {
        switch ($strName) {
            case "Href": return $this->Attr('href');
            default:
                return parent::__get($strName);
               
        }
    }

    /////////////////////////
    // Public Properties: SET
    /////////////////////////
    public function __set($strName, $mixValue) {
        switch ($strName) {
            case "Href":
                return ($this->Attr('href', $mixValue));                
            default:
                return parent::__set($strName, $mixValue);
                
        }
    }

    public function __MSerialize(){
        $arrData = parent::__MSerialize();
        return $arrData;
    }

}
?>
