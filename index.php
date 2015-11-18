<?php
//Testing Concept Tagging  using Alchemy API
//http://www.alchemyapi.com/api/

//turn on full error reports for development - REMOVE when in production
//error_reporting(E_ALL);

//assign value for title of page
$pageTitle = 'getSEMantic [beta]';
$subTitle = 'jason a. clark';
//declare filename for additional stylesheet variable - default is "none"
$customCSS = 'master.css';
//create an array with filepaths for multiple page scripts - default is meta/scripts/global.js
$customScript[0] = 'none';
//set default value for developer key
$key = isset($_GET['key']) ? htmlentities(strip_tags($_GET['key'])) : 'ADD-YOUR-ALCHEMY-API-KEY-HERE';
//set base url for API
$alchemyBase = 'http://gateway-a.watsonplatform.net/calls/url/';
//set default value for type of query
$type = isset($_GET['type']) ? htmlentities(strip_tags($_GET['type'])) : 'URLGetRankedConcepts';
//set default value for query
$q = isset($_GET['q']) ? htmlentities(strip_tags($_GET['q'])) : null;
//set default value for output format
$format = isset($_GET['format']) ? $_GET['format'] : 'json';
//set default number of results
//$limit = isset($_GET['limit']) ? strip_tags((int)$_GET['limit']) : '50';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<title><?php echo($pageTitle); ?> - jason a. clark</title>
<meta name="description" content="GetSemantic: Get linked data entities and topics from a URL using the Alchemy Term Extraction API"/>
<meta property="og:title" content="GetSemantic"/>
<meta property="og:description" content="Get linked data entities and topics from a URL using the Alchemy Term Extraction API."/>
<meta property="og:image" content="http://www.lib.montana.edu/~jason/files/getsemantic/meta/img/getsemantic-share-default.png"/>
<meta property="og:url" content="http://www.lib.montana.edu/~jason/files/getsemantic/"/>
<meta property="og:type" content="website"/>
<meta name="twitter:card" content="summary"/>
<meta name="twitter:site" content="http://www.jasonclark.info"/>
<meta name="twitter:creator" content="@jaclark"/>
<link rel="alternate" href="http://www.lib.montana.edu/~jason/files/getsemantic/" hreflang="en-us"/>
<link rel="alternate" type="application/rss+xml" title="diginit - jason clark" href="http://feeds.feedburner.com/diginit"/>
<?php
if (!is_null($q) && $type == 'URLGetRankedConcepts') {
?>
<link rel="alternate" type="application/ld+json" href="./index.json?q=<?php echo htmlentities(strip_tags($q)); ?>" title="Ranked Entities (json-ld)"/>
<?php
}
if (!is_null($q) && $type == 'URLGetRankedKeywords') {
?>
<link rel="alternate" type="application/ld+json" href="./index.json?q=<?php echo htmlentities(strip_tags($q)); ?>&type=URLGetRankedKeywords" title="Ranked Keywords (json-ld)"/>
<?php
}
?>
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
<body class="<?php if(!isset($_GET['view'])) { echo 'home'; } else { echo $_GET['view']; } ?>" role="document" vocab="http://schema.org/" typeof="WebPage">
<header role="banner" property="name description">
<h1><?php echo($pageTitle); ?></h1>
<h2><?php echo($subTitle); ?></h2>
</header>
<div class="main">
<main role="main">
<?php
if (is_null($q)): //if there's no query, show form and allow the user to search
?>

	<form method="get" action="<?php echo htmlentities(strip_tags(basename(__FILE__))); ?>">
	<fieldset>
		<label class="hidden" for="q">Enter URL to get Content Analysis and Terms:</label>
		<input type="text" name="q" id="q" placeholder="Feed me a URL" />
		<button type="submit" class="button">Get Analysis</button>
	</fieldset>
	</form>

<?php
else: //query API and display results

//build request value
$apiURL = $alchemyBase.$type.'?apikey='.$key.'&outputMode='.$format.'&url='.$q.'';

//diagnostic to show actual API request - REMOVE when in production
//echo '<!--'.$apiURL.'-->';

//call API and get data
$request = file_get_contents($apiURL);
//create json object(s) out of API response; set to "true" to turn response into an array
$data = json_decode($request,true);

//diagnostic to show array returned from API request - REMOVE when in production
//echo '<pre>';
//print_r(var_dump($data));
//echo '</pre>';

	//parse elements and display as html
	if ($data['status'] == 'OK') {
?>    
	<p>Mmmmm... Semantic Yumminess</p>
<?php    	
	if (isset($data['concepts'])) {
?>
	<h2 class="mainHeading">Ranked Entities</h2>
		<dl>
<?php
		foreach ($data['concepts'] as $entity) {
?>
			<dt><strong><?php echo $entity['text']; ?></strong></dt>
			<dd>relevance: <?php echo $entity['relevance']; ?></dd>
			<dd>concepts & linked data resources:
				<ul>
<?php
				if (isset($entity['geo'])) {
?>
					<li>coordinates: <?php echo $entity['geo']; ?></li>
<?php
				}
				if (isset($entity['website'])) {
?>
					<li>website: <a href="<?php echo $entity['website']; ?>"><?php echo $entity['website']; ?></a></li>
<?php
}
				if (isset($entity['dbpedia'])) {
?>
					<li><a href="<?php echo $entity['dbpedia']; ?>"><?php echo $entity['dbpedia']; ?></a></li>
<?php
				}
				if (isset($entity['freebase'])) {
?>
					<li><a href="<?php echo $entity['freebase']; ?>"><?php echo $entity['freebase']; ?></a></li>
<?php
				}
				if (isset($entity['geonames'])) {
?>
					<li><a href="<?php echo $entity['geonames']; ?>"><?php echo $entity['geonames']; ?></a></li>
<?php
}
				if (isset($entity['opencyc'])) {
?>
					<li><a href="<?php echo $entity['opencyc']; ?>"><?php echo $entity['opencyc']; ?></a></li>
<?php
				}
				if (isset($entity['yago'])) {
?>
					<li><a href="<?php echo $entity['yago']; ?>"><?php echo $entity['yago']; ?></a></li>
<?php
				}
?>
				</ul>
			</dd>
<?php
		}
?>
		</dl>
<?php
	}
	if (isset($data['keywords'])) {
?>
	<h2 class="mainHeading">Ranked Keywords</h2>
		<dl>
<?php
		foreach ($data['keywords'] as $entity) {
?>
			<dt><strong><?php echo $entity['text']; ?></strong></dt>
			<dd>relevance: <?php echo $entity['relevance']; ?></dd>
<?php
		}
?>
		</dl>
<?php
	}	
	if (isset($data['keywords'])) {
?>
		<p><a class="html" href="<?php echo htmlentities(strip_tags(basename(__FILE__))).'?q='.htmlentities(strip_tags($q)); ?>">Get Ranked Entities</a> | <a class="json" href="./index.json?q=<?php echo htmlentities(strip_tags($q)); ?>">Get Ranked Entities (json-ld)</a></p>
<?php
	} else {
?>
		<p><a href="<?php echo htmlentities(strip_tags($_SERVER['REQUEST_URI'])); ?>&type=URLGetRankedKeywords">Get Ranked Keywords</a> | <a href="./index.json?q=<?php echo htmlentities(strip_tags($q)); ?>&type=URLGetRankedKeywords">Get Ranked Keywords (json-ld)</a></p>
<?php
	}
?>
		<p><a href="http://<?php echo $_SERVER['SERVER_NAME'].htmlentities(strip_tags($_SERVER['REQUEST_URI'])); ?>">Permalink</a></p>
		<p><a href="<?php echo htmlentities(strip_tags($q)); ?>">Source</a></p>
		<p class="control"><a href="<?php echo htmlentities(strip_tags(basename(__FILE__))); ?>" class="refresh">Reset</a></p>
<?php	
	} else {
?>
		<p>Bummer. Empty Belly... <br />No results for <strong><?php echo $q; ?></strong>.</p>
		<p class="control"><a href="<?php echo htmlentities(strip_tags(basename(__FILE__))); ?>" class="refresh">Reset</a></p>
<?php
	}
//end submit isset if statement on line 73
endif;
?>
</main>
</div><!--end .main div-->
<aside role="complementary">
	<h3>Key:</h3>
	<a href="./index.php">Demo App</a>
	<a href="./what.php">What is this?</a>
	<a href="./code.php">View Code</a>
	<a href="http://twitter.com/jaclark" class="twitter">@jaclark</a>
	<a title="Robot Joe by Simon Abrams (CC BY-NC-SA 2.0)" href="http://www.flickr.com/photos/30914459@N00/183272970">Credit</a>
</aside>
</body>
</html>
