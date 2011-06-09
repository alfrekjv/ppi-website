<?php
if(isset($isAjax) && $isAjax == false):
	include_once($viewDir . $actionFile);
else:
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <title>PPI Framework</title>
    <meta name="description" content="PPI, MVC, PHP, Framework, PHP Framework, PPI Documentation, PPI Sessions, PPI Cache, PPI Input, PPI Controllers, PPI Views, PPI Models, Models, Views, Input, Cache, Controllers, Sessions, AJAX">
    <meta name="keywords" content="PPI, MVC, PHP, Framework, PHP Framework, PPI Documentation, PPI Sessions, PPI Cache, PPI Input, PPI Controllers, PPI Views, PPI Models, Models, Views, Input, Cache, Controllers, Sessions, AJAX">
    <meta name="author" content="Paul Dragoonis">
    <meta name="google-site-verification" content="a1rxyoMv_U3udQdSavKyu88mnxocX0gIwJE9DBylyqo" />

    <!--  Mobile viewport optimized: j.mp/bplateviewport -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Place favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <!-- CSS : implied media="all" -->
    <link rel="stylesheet" href="css/base.css">

    <!-- All JavaScript at the bottom, except for Modernizr which enables HTML5 elements & feature detects -->
    <script src="scripts/modernizr-1.6.min.js"></script>

</head>

<body>



<div id="header">
    <div class="header-container">
        <div class="fl header-left"></div>
        <div class="fl rel header-middle">
            <a href="http://www.ppiframework.com" title="PPI Framework"><img class="abs" src="images/icons-logo.png" alt="PPI Logo"></a>
        </div>
        <div class="fl header-right"></div>
    </div>
</div>
<div class="clearfix"></div>
<div class="header-separator"></div>
<div id="wrapper">
    <section class="main-content-container">
        <div class="main-content-container-inner">

            <div class="header-separator">
                <div class="fl green-2"></div>
                <div class="fl green-3"></div>
                <div class="fl green-4"></div>
            </div>

            <div class="fl main-content">
<?php include_once($viewDir . $actionFile); ?>
            </div> <!-- .main-content -->
        </div> <!-- .main-content-contrainer-inner -->


    </section>

</div> <!-- #wrapper -->


<script src="//ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.js"></script>
<script>!window.jQuery && document.write(unescape('%3Cscript src="scripts/jquery-1.4.2.js"%3E%3C/script%3E'))</script>
<script type="text/javascript" src="scripts/jquery.scrollto.min.js"></script>
<script type="text/javascript" src="scripts/common.js"></script>
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-20964741-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
  <!--[if lt IE 7 ]>
    <script src="scripts/libs/dd_belatedpng.js"></script>
    <script> DD_belatedPNG.fix('img, .png_bg'); //fix any <img> or .png_bg backgrounds </script>
  <![endif]-->

</body>
</html>
<?php
endif;
?>