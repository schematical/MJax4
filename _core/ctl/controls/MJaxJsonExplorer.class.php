<?php
/* 
 * This is a basic ajax text box
 */
class MJaxJsonExplorer extends MJaxControl{
	protected $strPath = '';
    protected $mixData = array();
    public function __MSerialize(){
        $arrData = parent::__MSerialize();
        $arrData['Path'] = $this->strPath;
        return $arrData;
    }
    public function SetValue($mixData){
        $this->mixData = $mixData;
    }
    public function GetValue(){
        return $this->mixData;//TODO: Apply path to it
    }
    /////////////////////////
    // Public Properties: GET
    /////////////////////////
    public function __get($strName)
    {
        switch ($strName) {
            case "Path":
                return $this->strPath;
            default:
                return parent::__get($strName);
            //throw new Exception("Not porperty exists with name '" . $strName . "' in class " . __CLASS__);
        }
    }

    /////////////////////////
    // Public Properties: SET
    /////////////////////////
    public function __set($strName, $mixValue)
    {
        switch ($strName) {
            case "Path":
                return $this->strPath = $mixValue;
            default:
                return parent::__set($strName, $mixValue);
            //throw new Exception("Not porperty exists with name '" . $strName . "' in class " . __CLASS__);
        }
    }

    
}
?>
