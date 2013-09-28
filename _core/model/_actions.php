<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
abstract class MJaxBaseAction{
    protected $objEvent = null;
    
    public function SetEvent($objEvent){
        $this->objEvent = $objEvent;
    }
    public function __toArray(){
        $arrData = array();
        $arrData['Type'] = get_class($this);
        return $arrData;
    }

}

class MJaxServerControlAction extends MJaxBaseAction{
    protected $strTargetControlId = null;
    protected $strFunction = null;
    protected $blnUseForm = false;

    public function __construct($objTargetControl, $strFunction){
    	if($objTargetControl instanceof MJaxForm){
    		$this->blnUseForm = true;
    	}else{
        	$this->strTargetControlId = $objTargetControl->ControlId;
    	}
        $this->strFunction = $strFunction;
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
            default:
                return parent::__set($strName, $mixValue);

        }
    }
    public function __toArray(){
        $arrData = parent::__toArray();
        $arrData['TargetControlId'] = $this->strTargetControlId;
        $arrData['Function'] = $this->strFunction;
        $arrData['UseForm'] = $this->blnUseForm;
        return $arrData;
    }
}
class MJaxJavascriptAction extends MJaxBaseAction{
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
}
class MJaxPluginAction extends MJaxJavascriptAction{
    
    public function __construct($objPlugin){
        $strRendered = $objPlugin->Render(false);
        //$strCommand = substr($strRendered, 0, strlen($strRendered) - 1);
        $strCommand = sprintf("function(){%s}", $strRendered);
        parent::__construct($strCommand);
    }
}

?>