<?php
/* 
 * This control will have be the base for all MJax controls
 */
abstract class MJaxControlBase extends MLCObjectBase{
	protected static $arrExtensions = array();
    protected $strControlId = null;
    protected $arrEvents = array();
    protected $objForm = null;
    protected $objParentControl = null;
    protected $strActionParameter = null;
    protected $strText = '';
    protected $strName = null;
    protected $blnAutoRenderChildren = false;
    protected $arrChildControls = array();
    protected $arrPlugins = array();

    protected $strTemplate = null;

    protected $blnModified = false;
    protected $blnRenderClearActions = false;
    public function  __construct($objParentControl, $strControlId = null, $arrAttrs = null) {
        if(is_null($objParentControl)){
        	throw new Exception("This is a null parent control");
        }

       $this->blnModified = true;
        if($objParentControl instanceof MData){
            $this->__MUnserialize($objParentControl);
        }else{
            if($objParentControl instanceof MJaxForm){
                $this->objForm = $objParentControl;
                if(!is_null($strControlId)){
                    $this->strControlId = $strControlId;
                }else{
                    $this->strControlId = $this->objForm->GenerateControlId();
                }

            }else{
                $this->objParentControl = $objParentControl;
                $this->objForm = $this->objParentControl->Form;

                if(!is_null($strControlId)){
                    $this->strControlId = $strControlId;
                }else{
                    if(
                        (!is_null($arrAttrs)) &&
                        (array_key_exists('id', $arrAttrs))
                    ){
                        $this->strControlId = $arrAttrs['id'];
                    }else{
                        $this->strControlId = $this->objForm->GenerateControlId();
                    }
                }

                $this->objParentControl->AddChildControl($this);
            }


            $this->objForm->RegisterControl($this);

        }
        foreach(self::$arrExtensions as $objExtension){
            $objExtension->InitControl($this);
        }
    }

    public function SetForm($objForm) {
        $this->objForm = $objForm;
        if(is_null($this->objForm)){
        	$objControl = null;
        }else{
        	$objControl = $this;	
        }        
		
        foreach($this->arrEvents as $arrEvent){
        	foreach($arrEvent as $objEvent){
        		$objEvent->SetControl($objControl);
        	}
        }
    }
    public function AddAction($objEvent, $objAction){

    	if(
    		(
				($objEvent instanceof MJaxControlBase) ||
				($objEvent instanceof MJaxFormBase)
			) &&
			(is_string($objAction))
		){
			$ctlAction = $objEvent;
			$strFunction = $objAction;
			$objAction = new MJaxServerControlAction($ctlAction, $strFunction);
			$objEvent = new MJaxClickEvent();
			
		}
        $objEvent->Init($this, $objAction);
        if(!array_key_exists($objEvent->EventName, $this->arrEvents)){
            $this->arrEvents[$objEvent->EventName] = array();
        }
        $this->arrEvents[$objEvent->EventName][] = $objEvent;
    }
    public function RemoveAllActions($mixEvent){
        if($mixEvent instanceof MJaxEventBase){
            $strEvent= $mixEvent->EventName;
        }elseif(is_string($mixEvent)){
            $strEvent = $mixEvent;
        }else{
            throw new Exception("RemoveAllActions method must take either a string or a MJaxEvent for the 1st parameter");
        }
        $this->blnModified = true;
        $this->arrEvents[$strEvent] = array();
        $this->blnRenderClearActions = true;
    }
    public function TriggerEvent($strEvent){
        foreach($this->arrEvents as $arrSubEvents){

            foreach($arrSubEvents as $objEvent){
                if($objEvent->GetEventName() == $strEvent){
                    $objEvent->Exicute();
                }
            }
        }
    }
    public function AddChildControl($objControl){
        $this->arrChildControls[$objControl->ControlId] = $objControl;
    }

    public function Render(){
        $this->blnModified = false;
       	if(is_null($this->objForm)){
       		throw new Exception($this->ControlId);
       	}
        if($this->objForm->CallType == MJaxCallType::None){
            return '';//$this->RenderJSCalls();
        }else{
            return '';
        }
    }

    public function ParsePostData(){ }
    /*-----Events -----*/
    public function Create_Controls(){ }
    /*-----Events End------*/

    public function Remove(){


        if(!is_null($this->objParentControl)){
            unset($this->objParentControl->arrChildControls[$this->strControlId]);
        }
    	$this->objForm->RemoveControl($this->strControlId);
    }
    public function RemoveAllChildControls(){
        foreach($this->arrChildControls as $strControlId => $objChildControl){
            $objChildControl->Remove();
        }
    }
    /////////////////////////
    // Public Properties: GET
    /////////////////////////
    public function __get($strName) {
    	
		
        switch ($strName) {
            case "ControlId": return $this->strControlId;
            case "ParentControl": return $this->objParentControl;
            case "Form": return $this->objForm;
            case "ActionParameter": return $this->strActionParameter;
            case "Text": return $this->strText;
            case "AutoRenderChildren": return $this->blnAutoRenderChildren;
            case "Template": return $this->strTemplate;
            case "Modified": return $this->blnModified;
            case "ChildControls": return $this->arrChildControls;
            case "Name": return $this->strName;
            case "Events": return $this->arrEvents;
            case "Value": return $this->GetValue();
            default:
               throw new Exception("No property with name '" . $strName . "'");
        }
    }

    /////////////////////////
    // Public Properties: SET
    /////////////////////////
    public function __set($strName, $mixValue) {
        $this->blnModified = true;
		
        switch ($strName) {
            case "ControlId":
                return ($this->strControlId = $mixValue);
            case "ActionParameter":
				return ($this->strActionParameter = $mixValue);
               
            case "Text":           
                return ($this->strText = $mixValue);
               
          	case "Name":
                return ($this->strName = $mixValue);
            case "AutoRenderChildren":
                return ($this->blnAutoRenderChildren = $mixValue);

            case "Template":
                return ($this->strTemplate = $mixValue);
                
            case "Modified":
               return ($this->blnModified = $mixValue);
            case "Value":
                return $this->SetValue($mixValue);
            default:			   
               throw new Exception("No property '" . $strName . "'");
        }
    }

	public function __call($strName, $arrArguments){
		
		foreach(self::$arrExtensions as $intIndex => $objExtension){
			//_dp($objExtension);
			if(method_exists($objExtension, $strName)){
				
				$objExtension->SetControl($this);
				return  call_user_func_array(array($objExtension, $strName), $arrArguments); 
				//return $objExtension->__call($strName, $arrArguments);
			}
		}
	    if(
	    	(method_exists($this, $strName)) 
		){ 
	        return call_user_func_array(
	          	array($this, $strName),
	            $arrArguments
			); 
        }else{ 
        	throw new MJaxExtensionMissingPropertyException(
	          	sprintf(
	          		'The required method "%s" does not exist for %s',
	          		$strName,
	          		get_class($this)
				)
			); 
        } 
	}
    public function After($mixHtml){
        $this->objForm->After(
            '#' . $this->strControlId,
            $mixHtml
        );
    }
    public function Before($mixHtml){
        $this->objForm->Before(
            '#' . $this->strControlId,
            $mixHtml
        );
    }
    public function Append($mixHtml){
        $this->objForm->Append(
            '#' . $this->strControlId,
            $mixHtml
        );
    }
    public function Prepend($mixHtml){
        $this->objForm->Prepend(
            '#' . $this->strControlId,
            $mixHtml
        );
    }
    public function ReplaceWith($mixControl, $mixHtml){
        $this->objForm->ReplaceWith(
            '#' . $this->strControlId,
            $mixHtml
        );
    }
    public function __MSerialize(){
        $arrData = parent::__MSerialize();
        $arrData['ControlId'] = $this->strControlId;
        $arrData['Text'] = $this->strText;
        $arrData['Value'] = $this->GetValue();
        $arrData['Name'] = $this->strName;
        $arrData['Template'] = $this->strTemplate;
        $arrData['Modified'] = $this->blnModified;

        $arrData['Events'] = array();
        foreach($this->arrEvents as $strKey => $arrEvents){
            $arrData['Events'][$strKey] = array();
            foreach($arrEvents as $intIndex => $objEvent){
                $arrData['Events'][$strKey][$intIndex] = $objEvent->__MSerialize();
            }
        }
        if(file_exists($this->strTemplate)){
            $arrData['TemplateHtml'] = file_get_contents($this->strTemplate);
        }
        //TODO: Consider attaching to entity

        return $arrData;
    }
    public function __MUnserialize($arrData){

        $this->strControlId = $arrData['ControlId'];
        $this->strText = 'Text';
        $this->SetValue($arrData['Value']);


        $this->strName = $arrData['Name'];
        $this->strTemplate = $arrData['Template'];
        $this->blnModified = $arrData['Modified'];

        if($arrData->offsetExists('Events')){

            foreach($arrData['Events'] as $strKey => $arrEvents){
                //_dv($arrEvents);
                $this->arrEvents[$strKey] = array();
                foreach($arrEvents as $intIndex => $objEvent){

                    $this->arrEvents[$strKey][$intIndex] = _munserialzie($objEvent);

                }
            }
        }else{

        }
        /*if($this->strControlId == 'lnkTest'){
            _dv($this->arrEvents);
        }*/
        /*if(file_exists($this->strTemplate)){
            $arrData['TemplateHtml'] = file_get_contents($this->strTemplate);
        }*/
    }
    public function SetValue($mixValue){
        return false;
    }
    public function GetValue(){
        return null;
    }
	public static function AddExtension($objExtension){
		self::$arrExtensions[] = $objExtension;
	}

}
?>
