<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MJax</title>

    <link rel="stylesheet"  href="/assets/MJaxTouch2/lib/jquery-mobile/jquery.mobile-1.3.2.css">
    <!--link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,700"-->

    <script type="text/javascript">
                //Figure out what page you are on
                var MJax_Data = <?php $this->RenderJson(); ?>;
    </script>
    <script data-main="<?php echo __MJAX_CORE_ASSET_URL__; ?>/js/MJax.js" src='<?php echo __MJAX_CORE_ASSET_URL__; ?>/lib/require.js/require.js' type="text/javascript"></script>

</head>
<body>

</body>
</html>