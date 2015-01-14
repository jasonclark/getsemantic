<?php
//Testing Term Extraction API using Alchemy API
//http://www.alchemyapi.com/api/

//assign value for title of page
$pageTitle = 'Code for getSEMantic';
$subTitle = 'jason a. clark';
//declare filename for additional stylesheet variable - default is "none"
$customCSS = 'master.css';
//create an array with filepaths for multiple page scripts - default is meta/scripts/global.js
$customScript[0] = 'none';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<head>
<title><?php echo($pageTitle); ?> - jason a. clark</title>
<link rel="alternate" type="application/rss+xml" title="diginit - jason clark" href="http://feeds.feedburner.com/diginit" />
<link rel="shortcut icon" href="./favicon.ico" type="image/x-icon">
<link rel="icon" href="./favicon.ico" type="image/x-icon">
<?php if ($customCSS != 'none') {
?>
<link rel="stylesheet" href="<?php echo dirname($_SERVER['PHP_SELF']); ?>/meta/styles/<?php echo $customCSS; ?>">
<?php
}
if ($customScript[0] != 'none') {
  $counted = count($customScript);
  for ($i = 0; $i < $counted; $i++) {
   echo '<script type="text/javascript" src="'.$customScript[$i].'"></script>'."\n";
  }
}
?>
<!--[if lt IE 9]>
<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
</head>
<body class="<?php if(!isset($_GET['view'])) { echo 'code'; } else { echo $_GET['view']; } ?>">
<header>
<h1><?php echo($pageTitle); ?></h1>
<h2><?php echo($subTitle); ?></h2>
</header>
<div class="main">
<main>
<p>Source code here is from the app that shows the top entities and linked data sources extracted from a given URL. Built with PHP, HTML/CSS, and Alchemy Content Analysis API.</p>
<p><a href="code.txt" title="code for search form and php">Search form and PHP</a></p>
<p><a href="https://github.com/jasonclark/getsemantic" title="Full GitHub repo for getSEMantic">Full GitHub repo for getSEMantic</a></p>
</main>
</div><!--end .main div-->
<aside>
	<h3>Key:</h3>
	<a href="./index.php">Demo App</a>
	<a href="./what.php">What is this?</a>
	<a href="./code.php">View Code</a>
	<a href="http://twitter.com/jaclark" class="twitter">@jaclark</a>
	<a title="Robot Joe by Simon Abrams (CC BY-NC-SA 2.0)" href="http://www.flickr.com/photos/30914459@N00/183272970">Credit</a>
</aside>
</body>
</html>
