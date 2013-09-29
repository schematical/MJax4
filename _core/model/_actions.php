<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
abstract class MJaxBaseAction extends MLCObjectBase{
    protected $objEvent = null;
    
    public function SetEvent($objEvent){
        $this->objEvent = $objEvent;
    }

}

class MJaxServerControlAction extends MJaxBaseAction{
    protected $strTargetControlId = null;
    protected $strFunction = null;
    protected $blnUseForm = false;

    public function __construct($objTargetControl, $strFunction = null){
        if($objTargetControl instanceof MData){
            $this->__MUnserialize($objTargetControl);
            //_dv($objTargetControl);
        }else{
            if($objTargetControl instanceof MJaxForm){
                $this->blnUseForm = true;
            }else{
                $this->strTargetControlId = $objTargetControl->ControlId;
            }
            $this->strFunction = $strFunction;
        }
    }

    public function Exicute($objForm, $strControlId, $strParameter){
        $strFunction = $this->strFunction;
        if($this->blnUseForm){
        	$objForm->$strFunction($objForm->FormId, $strControlId, $strParameter);
        }else{
        	$objForm->Controls[$this->strTargetControlId]->$strFunction($objForm->FormId, $strControlId, $strParameter);
        }
    }
   
    
    public function __get($strName) {
        switch ($strName) {
            //case "KeyCode": return $this->strKeyCode;
            default:
                return parent::__get($strName);

        }
    }

    /////////////////////////
    // Public Properties: SET
    /////////////////////////
    public function __set($strName, $mixValue) {
        switch ($strName) {
            case("UseForm"):
                $this->blnUseForm = $mixValue;
            case("TargetControlId"):
                $this->strTargetControlId = $mixValue;
            case("Function"):
                $this->strFunction = $mixValue;
            default:
                return parent::__set($strName, $mixValue);

        }
    }
    public function __MUnserialize($arrData){

        $this->strTargetControlId = $arrData['TargetControlId'];
        $this->strFunction = $arrData['Function'];
        $this->blnUseForm = $arrData['UseForm'];
        return $arrData;
    }
    public function __MSerialize(){
        $arrData = parent::__MSerialize();
        $arrData['TargetControlId'] = $this->strTargetControlId;
        $arrData['Function'] = $this->strFunction;
        $arrData['UseForm'] = $this->blnUseForm;
        return $arrData;
    }
}
/*class MJaxJavascriptAction extends MJaxBaseAction{
    protected $strCode = null;
    public function __construct($strCode){
        $this->strCode = $strCode;
    }
    public function Render(){
        $strRendered = '';
        $strRendered .= $this->strCode;
        //The following wont render anything unless blnOnce is set to true
        $strRendered .= $this->objEvent->RenderUnbind();
        $strRendered .= '';
        return $strRendered;
    }
    public function Exicute(){}
}*/


?>