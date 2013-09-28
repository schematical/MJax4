<?php

define('__MJAX__', dirname(__FILE__));
define('__MJAX_CORE__', __MJAX__ . '/_core');
define('__MJAX_CORE_CTL__', __MJAX_CORE__ . '/ctl');
define('__MJAX_CORE_MODEL__', __MJAX_CORE__ . '/model');
define('__MJAX_CORE_VIEW__', __MJAX_CORE__ . '/view');
define('__MJAX_CORE_ASSETS__', __MJAX_CORE__ . '/assets');
define("__MJAX_CORE_ASSET_URL__", MLCApplication::GetAssetUrl('', 'MJax4'));
MLCApplicationBase::$arrClassFiles['MJaxFormBase'] = __MJAX_CORE_CTL__ . '/MJaxFormBase.class.php';
MLCApplicationBase::$arrClassFiles['MJaxForm'] = __MJAX_CORE_CTL__ . '/MJaxForm.class.php';

MLCApplicationBase::$arrClassFiles['MJaxControlBase'] = __MJAX_CORE_CTL__ . '/controls/MJaxControlBase.class.php';
MLCApplicationBase::$arrClassFiles['MJaxControl'] = __MJAX_CORE_CTL__ . '/controls/MJaxControl.class.php';
MLCApplicationBase::$arrClassFiles['MJaxTextBox'] = __MJAX_CORE_CTL__ . '/controls/MJaxTextBox.class.php';
MLCApplicationBase::$arrClassFiles['MJaxLinkButton'] = __MJAX_CORE_CTL__ . '/controls/MJaxLinkButton.class.php';

require_once(__MJAX_CORE_CTL__ . '/_enum.inc.php');

require_once(__MJAX_CORE_MODEL__ . '/_actions.php');
require_once(__MJAX_CORE_MODEL__ . '/_events.php');
require_once(__MJAX_CORE_MODEL__ . '/_exceptions.inc.php');
require_once(__MJAX_CORE_MODEL__ . '/_functions.inc.php');



