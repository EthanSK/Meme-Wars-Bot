
<?php

//require "fetchtoken.php";
session_start();
  ini_set('display_errors', 0);
  error_reporting(E_ERROR | E_WARNING | E_PARSE);
  require_once __DIR__ . '/vendor/autoload.php';
  echo "<pre>";
  date_default_timezone_set("GMT");
  echo "The time is " . date("Y-m-d H:i:s").'   '.   time();
  $refreshRate = 300;
  echo "</pre>";
  header( "refresh:$refreshRate;url= index.php" );
  $currentTime = time();
  echo "<pre>";
  echo "script has loaded" . end($version) . "times \n";
  echo "</pre>";
  $fb = new Facebook\Facebook([
    'app_id' => 'no',
    'app_secret' => 'stay aweh',
    'default_graph_version' => 'v2.8',
    ]);

  $helper = $fb->getRedirectLoginHelper();
  $permissions = [ 'manage_pages', 'publish_pages', 'publish_actions', 'read_page_mailboxes', 'pages_messaging'];
  try {
  	if (isset($_SESSION['facebook_access_token'])) {
  		$accessToken = $_SESSION['facebook_access_token'];
  	} else {
    		$accessToken = $helper->getAccessToken();
  	}
  } catch(Facebook\Exceptions\FacebookResponseException $e) {
   	// When Graph returns an error
   	echo 'Graph returned an error: ' . $e->getMessage();

    	exit;
  } catch(Facebook\Exceptions\FacebookSDKException $e) {
   	// When validation fails or other local issues
  	echo 'Facebook SDK returned an error: ' . $e->getMessage();
    	exit;
   }



  if (isset($accessToken)) {
  	if (isset($_SESSION['facebook_access_token'])) {
  		$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
  	} else {
  		// getting short-lived access token
  		$_SESSION['facebook_access_token'] = (string) $accessToken;

  	  	// OAuth 2.0 client handler
  		$oAuth2Client = $fb->getOAuth2Client();

  		// Exchanges a short-lived access token for a long-lived one
  		$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);

  		$_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;

  		// setting default access token to be used in script
  		$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
  	}

  	// redirect the user back to the same page if it has "code" GET variable
  	if (isset($_GET['code'])) {
  		//header('Location: ./');
  	}

  	// getting basic info about user
  	try {
  		//$profile_request = $fb->get('/me?fields=name,first_name,last_name,email');
  		//$profile = $profile_request->getGraphNode()->asArray();
  	} catch(Facebook\Exceptions\FacebookResponseException $e) {
  		// When Graph returns an error
  		echo 'Graph returned an error: ' . $e->getMessage();
  		//session_destroy();
  		// redirecting user back to app login page
  		//header("Location: ./");
  		exit;
  	} catch(Facebook\Exceptions\FacebookSDKException $e) {
  		// When validation fails or other local issues
  		echo 'Facebook SDK returned an error: ' . $e->getMessage();
  		exit;
  	}
//•••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••

  try {
    echo 'this is me: ';
        $infoAboutMe = $fb->get('/me');
        $infoAboutMe = $infoAboutMe->getGraphNode()->asArray();

        echo ("<pre>");
          print_r($infoAboutMe);
           echo ("</pre>");

           $pages = $pages = $fb->get('/me/accounts');
           $pages = $pages->getGraphEdge()->asArray();

    //require "randomshoutoutsscript.php";
    require "memewars.php";


	} catch(Facebook\Exceptions\FacebookResponseException $e) {
		// When Graph returns an error
		echo 'Graph returned an error: ' . $e->getMessage();
		exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		// When validation fails or other local issues
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
	}

  	// Now you can redirect to another page and use the access token from $_SESSION['facebook_access_token']
}



else {
	// replace your website URL same as added in the developers.facebook.com/apps e.g. if you used http instead of https and you used non-www version or www version of your website then you must add the same here
	$loginUrl = $helper->getLoginUrl('http://randomshoutouts.com/randomshoutouts/', $permissions);
	echo '<a id = "login" href="   ' . $loginUrl . '   ">Log in with Facebook!</a>';
}
echo "loaded  the random shoutouts bot ";

?>


<head>
  <meta http-equiv="refresh" content="1000;url=http://randomshoutouts.com/randomshoutouts/">

<script>


      var elm=document.getElementById('login');
      document.location.href = elm.href;

</script>
