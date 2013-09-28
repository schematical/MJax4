<?php

define('__MJAX__', dirname(__FILE__));
define('__MJAX_CORE__', __MJAX__ . '/_core');
define('__MJAX_CORE_CTL__', __MJAX_CORE__ . '/ctl');
define('__MJAX_CORE_MODEL__', __MJAX_CORE__ . '/model');
define('__MJAX_CORE_VIEW__', __MJAX_CORE__ . '/view');
define('__MJAX_CORE_ASSETS__', __MJAX_CORE__ . '/assets');
define("__MJAX_CORE_ASSET_URL__", MLCApplication::GetAssetUrl('', 'MJax4'));
$_MLC_CLASSES['MJaxFormBase'] = __MJAX_CORE_CTL__ . '/MJaxFormBase.class.php';
$_MLC_CLASSES['MJaxForm'] = __MJAX_CORE_CTL__ . '/MJaxForm.class.php';

$_MLC_CLASSES['MJaxControlBase'] = __MJAX_CORE_CTL__ . '/controls/MJaxControlBase.class.php';
$_MLC_CLASSES['MJaxControl'] = __MJAX_CORE_CTL__ . '/controls/MJaxControl.class.php';
$_MLC_CLASSES['MJaxTextBox'] = __MJAX_CORE_CTL__ . '/controls/MJaxTextBox.class.php';
$_MLC_CLASSES['MJaxLinkButton'] = __MJAX_CORE_CTL__ . '/controls/MJaxLinkButton.class.php';

require_once(__MJAX_CORE_CTL__ . '/_enum.inc.php');

require_once(__MJAX_CORE_MODEL__ . '/_actions.php');
require_once(__MJAX_CORE_MODEL__ . '/_events.php');
require_once(__MJAX_CORE_MODEL__ . '/_exceptions.inc.php');
require_once(__MJAX_CORE_MODEL__ . '/_functions.inc.php');



