<?php
//Testing Term Extraction API using Alchemy API
//http://www.alchemyapi.com/api/

//assign value for title of page
$pageTitle = 'getSEMantic [beta]';
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
<body class="<?php if(!isset($_GET['view'])) { echo 'home'; } else { echo $_GET['view']; } ?>" role="document" vocab="http://schema.org/" typeof="WebPage">
<header role="banner" property="name description">
<h1><?php echo($pageTitle); ?></h1>
<h2><?php echo($subTitle); ?></h2>
</header>
<div class="main">
<main role="main">
<?php
  //turn on full error reports for development - REMOVE when in production
  //error_reporting(E_ALL);

	//set default value for developer key
	$key = isset($_GET['key']) ? htmlentities(strip_tags($_GET['key'])) : 'ADD-YOUR-ALCHEMY-API-KEY-HERE';
  //set base url for API
  $alchemyBase = 'http://access.alchemyapi.com/calls/url/';
  //set default value for type of query
  $type = isset($_GET['type']) ? htmlentities(strip_tags($_GET['type'])) : 'URLGetRankedNamedEntities';
  //set default value for query
  $q = isset($_GET['q']) ? htmlentities(strip_tags($_GET['q'])) : null;
	//set default value for output format
	$format = isset($_GET['format']) ? $_GET['format'] : 'json';
	//set default number of results
	//$limit = isset($_GET['limit']) ? strip_tags((int)$_GET['limit']) : '50';

if (is_null($q)): //if there's no query, show form and allow the user to search
?>

	<form method="get" action="<?php echo htmlentities(strip_tags(basename(__FILE__))); ?>">
	<fieldset>
	<label for="q">Enter URL to get Content Analysis and Terms:</label>
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
    echo '<h2 class="mainHeading">Mmmmm... Semantic Yumminess</h2>'."\n";
    	//if (!is_null($data->entities)) {
    	if (isset($data['entities'])) {
    	echo '<h3>Ranked Entities</h3>'."\n";
			foreach ($data['entities'] as $entity) {
        	echo '<p><strong>'.$entity['text'].'</strong></p>'."\n";
					echo '<ul>'."\n";
					//check for URL at schema.org; print link if URL exists
					$schemaType = 'http://schema.org/'.$entity['type'];
					if (@file_get_contents($schemaType)) {
						echo '<li>type: <a title="look up '.$entity['type'].' type at schema.org" href="http://schema.org/'.$entity['type'].'">'.$entity['type'].'</a></li>'."\n";
					} else {
						echo '<li>type: '.$entity['type'].'</li>'."\n";
					}
        	echo '<li>relevance: '.$entity['relevance'].'</li>'."\n";
        	echo '<li>count: '.$entity['count'].'</li>'."\n";
					if (isset($entity['disambiguated'])) {
        		echo '<li>linked data resources:'."\n";
        		echo '<ul>'."\n";
						echo '<li><a href="'.$entity['disambiguated']['dbpedia'].'">'.$entity['disambiguated']['dbpedia'].'</a></li>'."\n";
						echo '<li><a href="'.$entity['disambiguated']['freebase'].'">'.$entity['disambiguated']['freebase'].'</a></li>'."\n";
        		echo '</ul>'."\n";
        		echo '</li>'."\n";
    			}
					echo '</ul>'."\n";
			}
		}
		echo '<p><a href="http://'.$_SERVER['SERVER_NAME'].htmlentities(strip_tags($_SERVER['REQUEST_URI'])).'">Permalink</a></p>'."\n";
		echo '<p>Source: <a href="'.htmlentities(strip_tags($q)).'">'.htmlentities(strip_tags($q)).'</a></p>'."\n";
		echo '<p class="control"><a href="'.htmlentities(strip_tags(basename(__FILE__))).'" class="refresh">Reset</a></p>'."\n";
	} else {
		echo '<h2 class="mainHeading">Bummer. Empty Belly... <br />No results for <strong>'.$q.'</strong>.</h2>'."\n";
    echo '<p class="control"><a href="'.htmlentities(strip_tags(basename(__FILE__))).'" class="refresh">Reset</a></p>'."\n";
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
