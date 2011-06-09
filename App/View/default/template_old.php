<?php if(!isset($isAjax) || $isAjax == false) { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
	<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl; ?>css/framework.css"/>
	<link rel="stylesheet" href="<?php echo $baseUrl; ?>style.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl; ?>css/formbuilder.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl; ?>css/style.css"/>
	<script type="text/javascript" language="javascript" src="<?php echo $baseUrl; ?>scripts/jquery1.4.2.min.js"></script>
	<script type="text/javascript">var baseUrl = "<?php echo $baseUrl; ?>";</script>
	<?php include_once($viewDir . 'framework/javascript.php'); ?>
	<?php include_once($viewDir . 'framework/stylesheet.php'); ?>
	<title>PPI framework | Open Source PHP Framework</title>

</head>

<body>
		<header>
			<h3>Header content here</h3>
		</header>

		<div id="wrapper" style="padding: 25px; 1px solid grey;">
		<?php include $viewDir . "framework/flashmessage.php" ?>
		<?php include_once($viewDir . $actionFile); ?>
		</div>

		<footer>
			<h3>Footer content here</h3>
		</footer>

	</body>
</html>
<?php } else { ?>
			<?php include_once($viewDir . $actionFile); ?>
<?php } ?>