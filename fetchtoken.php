<?php

//read more : https://developers.facebook.com/docs/howtos/login/server-side-login/
session_start();
$app_id = "my eyes";
$app_secret = "ONLYYY";
//$my_url = "http://randomshoutouts.com/randomshoutouts/";  // redirect url
$my_url = "https://memewarsbot.000webhostapp.com/";  // redirect url

$code = $_REQUEST["code"];

if(empty($code)) {
    // Redirect to Login Dialog
    $_SESSION['state'] = md5(uniqid(rand(), TRUE)); // CSRF protection
    $dialog_url = "https://www.facebook.com/dialog/oauth?client_id="
       . $app_id . "&redirect_uri=" . urlencode($my_url) . "&state="
       . $_SESSION['state'] . "&scope=manage_pages,publish_pages,publish_actions,read_page_mailboxes,pages_messaging";


    echo("<script> top.location.href='" . $dialog_url . "'</script>");
}
if($_SESSION['state'] && ($_SESSION['state'] === $_REQUEST['state'])) {
    $token_url = "https://graph.facebook.com/oauth/access_token?"
       . "client_id=" . $app_id . "&redirect_uri=" . urlencode($my_url)
       . "&client_secret=" . $app_secret . "&code=" . $code;

     $response = file_get_contents($token_url);
     $params = null;
     parse_str($response, $params);
     $longtoken=$params['access_token'];
     file_put_contents('longlivedaccesstoken.txt', $longtoken. "\n", FILE_APPEND );


//save it to database
}
?>
