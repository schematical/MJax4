<!DOCTYPE html>
<html>
<head>
    <title>How to use RequireJS with jQuery</title>
    <!--<script src='http://local.ffs.com/assets/MJaxTouch2/js/MJax.js' type="text/javascript"></script>
-->
    <link rel="stylesheet"  href="http://local.ffs.com/assets/MJaxTouch2/lib/jquery-mobile/jquery.mobile-1.3.2.css">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,700">
    <!--   <script src="http://local.ffs.com/assets/MJaxTouch2/js/lib/jquery-mobile/jquery.js"></script>
       <script src="http://local.ffs.com/assets/MJaxTouch2/js/lib/jquery-mobile/jquery.mobile-1.3.2.min.js"></script>-->

    <script type="text/javascript">
                //Figure out what page you are on
                var MJax_Data = <?php echo json_encode($this->arrData); ?>;
    </script>
    <script data-main="<?php echo __MJAX_CORE_ASSET_URL__; ?>/js/MJax.js" src='<?php echo __MJAX_CORE_ASSET_URL__; ?>/lib/require.js/require.js' type="text/javascript"></script>

</head>
<body>

</body>
</html>