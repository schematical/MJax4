<?php
/**
 * Created by JetBrains PhpStorm.
 * User: user1a
 * Date: 9/27/13
 * Time: 3:39 PM
 * To change this template use File | Settings | File Templates.
 */

class MJaxFormBase extends MLCObjectBase{
    protected $arrData = array();
    protected $arrControls = array();
    protected $intNextControlId = 1;
    /////////////////////////
    // Public Properties: GET
    /////////////////////////
    public function __get($strName) {
        switch ($strName) {
            case "FormId": return $this->strFormId;
            case "ActiveEvent": return $this->objActiveEvent;
            case "Controls" : return $this->arrControls;
            case "AssetMode": return $this->strAssetMode;
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
        if(array_key_exists('action', $_POST)){
            $objForm = _munserialzie($_POST);
            _dv($objForm);
        }else{
            $objForm = new $strFormClass();
            $objForm->Form_Create();

            $objForm->Render();
        }


    }
    public static function ListAssetDir($strDir, $strPackage = 'MJax4'){
        $arrReturn = array();

        $strUrl = MLCApplication::GetAssetUrl('', $strPackage) .  str_replace(MLCApplication::FindPackageDir($strPackage) . '/_core/assets', '', $strDir);

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
    public function __construct(){
        $this->InitHead();

        $this->arrData['body'] = array();
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
        $this->arrData['head'] = array();
        $this->arrData['head']['template'] = __ASSETS_URL__ . '/tpl/' . get_class($this) . '.tpl.html';
        $this->arrData['head']['require'] = array();
        $this->arrData['head']['require']['baseUrl'] = __MJAX_CORE_ASSET_URL__;
        $arrRequirePaths = array(
            'jquery' => __MJAX_CORE_ASSET_URL__ . '/lib/jquery/jquery',
            'mustache' => __MJAX_CORE_ASSET_URL__ . '/lib/mustache/mustache',
            'require_text' => __MJAX_CORE_ASSET_URL__ . '/lib/require.js/text',


            //TODO: Move ... somewhere
            'MJaxAjaxConn' => __MJAX_CORE_ASSET_URL__ . '/js/conn/MJaxAjaxConn',


            //TODO: Move MJaxTouch2 dependencies else where:
            'jquery_mobile' => __MJAX_TOUCH_2_CORE_ASSET_URL__ . '/lib/jquery-mobile/jquery.mobile-1.3.2.min'

        );
        $arrControlScripts = self::ListAssetDir(
            __MJAX_CORE_ASSETS__ .'/js/controls'
        );
        $this->arrData['head']['controls'] = array_keys($arrControlScripts);
        $this->arrData['head']['controls_tpls'] = array();
        foreach($this->arrData['head']['controls'] as $strControl){
            $strTemplate = __MJAX_CORE_ASSETS__ . '/tpl/controls/' . $strControl .'.tpl.php';
            if(file_exists($strTemplate)){
                $this->arrData['head']['controls_tpls'][$strControl] = file_get_contents($strTemplate);
            }
        }

        $arrRequirePaths = array_merge(
            $arrRequirePaths,
            $arrControlScripts

        );
        $this->arrData['head']['require']['paths'] = $arrRequirePaths;

        $this->arrData['head']['template_loc'] = __ASSETS_ACTIVE_APP_DIR__ . '/tpl/' . get_class($this) . '.tpl.html';
        $this->arrData['head']['template_html'] = file_get_contents($this->arrData['head']['template_loc']);
    }
    public function Render(){



        //If this is html do something here
        require_once(__MJAX_CORE_VIEW__ . '/MJaxForm.tpl.php');
    }
    public function __MSerialize(){
        $arrData = parent::__MSerialize();
        $this->arrData['_mclass'] = 'MJaxForm';
        $this->arrData['body'] = $this->arrControls;
        $arrData = array_merge($arrData, _mserialzie($this->arrData));
        return $arrData;
    }
    public function __MUnserialize($arrData){
        $this->arrData = _munserialzie($arrData);
    }

}