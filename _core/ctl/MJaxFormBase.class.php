<?php
/**
 * Created by JetBrains PhpStorm.
 * User: user1a
 * Date: 9/27/13
 * Time: 3:39 PM
 * To change this template use File | Settings | File Templates.
 */

class MJaxFormBase extends MLCObjectBase{
    protected $strFormId = null;
    protected $objActiveEvent = null;
    protected $arrHeaderData = array();
    protected $arrControls = array();
    protected $intNextControlId = 1;
    protected $arrAssetDirs = array();
    /////////////////////////
    // Public Properties: GET
    /////////////////////////
    public function __get($strName) {
        switch ($strName) {
            case "FormId": return $this->strFormId;
            case "ActiveEvent": return $this->objActiveEvent;
            case "Controls" : return $this->arrControls;
            case "AssetMode": return $this->strAssetMode;
            case "head":
            case "Head":
                return $this->arrHeaderData;
            case "body":
            case "Body":
                return $this->arrControls;
            default:
                throw new MLCMissingPropertyException($this,$strName);

        }
    }
    public function Form_Create(){ }

    /////////////////////////
    // Public Properties: SET
    /////////////////////////
    public function __set($strName, $mixValue) {
        switch ($strName) {
            case "ActiveEvent":
                return $this->objActiveEvent = $mixValue;
            case "head":
            case "Head":
                return $this->arrHeaderData = $mixValue;
            case "body":
            case "Body":
                return $this->arrControls = $mixValue;
            default:
                throw new MLCMissingPropertyException($this,$strName);

        }
    }


    /////////////////////////
    // Helpers for ControlId Generation
    /////////////////////////
    public function GenerateControlId() {
        $strToReturn = sprintf('c%s', $this->intNextControlId);
        $this->intNextControlId++;
        return $strToReturn;
    }
    public static function Run($strFormClass){
        if(array_key_exists('head', $_POST)){
            $objForm = _munserialzie($_POST);

            if(array_key_exists('event', $objForm->head)){
                $arrEventData = $objForm->head['event'];
                $objForm->TriggerControlEvent(
                    $arrEventData['control_id'],
                    $arrEventData['event']
                );
            }
            $objForm->RenderJson();

        }else{
            $objForm = new $strFormClass();
            $objForm->strFormId = $strFormClass;
            $objForm->Form_Create();

            $objForm->Render();
        }


    }

    public static function ListAssetDir($strDir, $strPackage = 'MJax4'){
        $arrReturn = array();
        $strPackageDir = MLCApplication::FindPackageDir($strPackage);
        if(is_null($strPackageDir)){
            //Assume it is an app
            $strUrl = MLCApplication::GetAssetUrl('', $strPackage) .  str_replace(__APP_DIR__ . '/' . $strPackage . '/assets', '', $strDir);
        }else{
            $strUrl = MLCApplication::GetAssetUrl('', $strPackage) .  str_replace($strPackageDir . '/_core/assets', '', $strDir);
        }

        if ($handle = opendir($strDir)) {


            /* This is the correct way to loop over the directory. */
            while (false !== ($strEntry = readdir($handle))) {
                if(
                    ($strEntry != '..') &&
                    ($strEntry != '.')
                ){
                    $strName = pathinfo($strEntry, PATHINFO_FILENAME);
                    $strName = str_replace('.ctl', '', $strName);
                    $arrReturn[$strName] = $strUrl .'/' . pathinfo($strEntry, PATHINFO_FILENAME);
                }
            }
            closedir($handle);
        }
        return $arrReturn;
    }





    /*--------------------------------------------------------------------
    Instance Methods
    */
    public function __construct($objData = null){
        if(!is_null($objData)){
            $this->__MUnserialize($objData);
        }else{
            $this->AddAssetDir('MJax4', __MJAX_CORE_ASSETS__);
            $this->InitHead();

            $this->PreLoadControlTemplates();
        }

    }
    public function AddAssetDir($strPackage, $strDir){
        $this->arrAssetDirs[$strPackage] = $strDir;
    }
    public function RegisterControl($objControl){
        $strControlId = $objControl->ControlId;
        if(array_key_exists($strControlId, $this->arrControls)){
            throw new Exception("A control with the Id '" . $strControlId . "' already exists");
        }
        $this->arrControls[$strControlId] = $objControl;
    }
    public function RemoveControl($mixControl){
        if(is_string($mixControl)){
            $strControlId = $mixControl;
        }else{
            $strControlId = $mixControl->ControlId;
        }
        $arrChildControls = $this->arrControls[$strControlId]->ChildControls;
        foreach($arrChildControls as $objChildControl){
            $this->RemoveControl($objChildControl);
        }
        unset($this->arrControls[$strControlId]);
    }
    public function InitHead(){
        $this->arrHeaderData = array();
        $this->arrHeaderData['template'] = __ASSETS_URL__ . '/tpl/' . get_class($this) . '.tpl.html';
        $this->arrHeaderData['require'] = array();
        $this->arrHeaderData['require']['baseUrl'] = __MJAX_CORE_ASSET_URL__;
        $arrRequirePaths = array(
            'jquery' => __MJAX_CORE_ASSET_URL__ . '/lib/jquery/jquery',
            'mustache' => __MJAX_CORE_ASSET_URL__ . '/lib/mustache/mustache',
            'require_text' => __MJAX_CORE_ASSET_URL__ . '/lib/require.js/text',


            //TODO: Move ... somewhere
            'MJaxAjaxConn' => __MJAX_CORE_ASSET_URL__ . '/js/conn/MJaxAjaxConn',
        );
        $this->arrHeaderData['controls'] = array();
        foreach($this->arrAssetDirs as $strPackage => $strDir){
            $arrControlScripts = self::ListAssetDir(
                $strDir .'/js/controls',
                $strPackage
            );
            $this->arrHeaderData['controls'] = array_merge(
                $this->arrHeaderData['controls'],
                array_keys($arrControlScripts)
            );

            $arrRequirePaths = array_merge(
                $arrRequirePaths,
                $arrControlScripts

            );
        }
        $this->arrHeaderData['require']['paths'] = $arrRequirePaths;
        //$this->arrHeaderData['require_modules'] = array('jquery', 'mustache','MJaxAjaxConn');
        $this->arrHeaderData['template_loc'] = __ASSETS_ACTIVE_APP_DIR__ . '/tpl/' . get_class($this) . '.tpl.html';
        $this->arrHeaderData['template_html'] = file_get_contents($this->arrHeaderData['template_loc']);
    }
    public function PreLoadControlTemplates(){
        $this->arrHeaderData['controls_tpls'] = array();
        $arrPackageNames = array_keys($this->arrAssetDirs);
        foreach($this->arrHeaderData['controls'] as $strControl){
            $intIndex = count($this->arrAssetDirs);
            while(
                (!array_key_exists($strControl, $this->arrHeaderData['controls_tpls'])) &&
                ($intIndex > 0)
            ){
                $strTemplate = $this->arrAssetDirs[$arrPackageNames[$intIndex - 1]] . '/tpl/controls/' . $strControl .'.tpl.php';
                error_log("Trying: " . $strTemplate);
                if(file_exists($strTemplate)){
                    $this->arrHeaderData['controls_tpls'][$strControl] = file_get_contents($strTemplate);
                }

                $intIndex -= 1;
            }
        }

    }
    public function Render(){

        //If this is html do something here
        require_once(__MJAX_CORE_VIEW__ . '/MJaxForm.tpl.php');
    }
    public function RenderJson(){
        echo json_encode(_mserialzie($this));
    }
    public function TriggerControlEvent($strControlId, $strEvent){
        if(!array_key_exists($strControlId, $this->arrControls)){
            throw new Exception("Control '" . $strControlId . "' does not exist");
        }
        $this->arrControls[$strControlId]->__call('TriggerEvent',array($strEvent));

    }
    public function __MSerialize(){
        $arrData = parent::__MSerialize();
        $arrData['_mclass'] = 'index';
        $arrData['_m_clientside_class'] = 'MJaxForm';
        $arrData['body'] = array();
        foreach($this->arrControls as $strControlId => $objControl){
            if(is_null($objControl->ParentControl)){
                $arrData['body'][$strControlId] = $objControl;
            }
        }
        $arrData['head'] = $this->arrHeaderData;

        $arrData = array_merge($arrData, _mserialzie($arrData));
        return $arrData;
    }
    public function __MUnserialize($arrData){
        parent::__MUnserialize($arrData);


        foreach($this->arrControls as $objControl){
            $objControl->SetForm($this);
        }
    }


}