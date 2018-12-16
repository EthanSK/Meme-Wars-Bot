<?php

//if (isset ($_POST["url"], $_POST["message"])){
file_put_contents('log.txt',"1 scrip run. \n", FILE_APPEND );

$enableMessaging = 1;
$memeWarsPotentialURLsOG = file('memewars.txt');
$memeWarsPotentialURLs = end($memeWarsPotentialURLsOG);
$memeWarsPotentialURLs = explode("|||", $memeWarsPotentialURLs);
$memeWarsPosted = file('memewarsalreadyposted.txt');
$token = "EAADoWvCdqRQBALT1KhxHZBKnZCSEVZACOIen3dnRxZCkmUsuUPVZBQbZBbpQAnpZBFdQXveOuNPrLtu9acTH2Yf5ZCxs63JkjGpvCRw9H02OKqPa3It5VOIhkYkyycYgol335KO4UZCrytj8IvIr7DZBSqw08c015b2fjpYRfgmf1HdQZDZD";
$key = 0;

/* validate verify token needed for setting up web hook */
if (isset($_GET['hub_verify_token'])) {
    if ($_GET['hub_verify_token'] === '1234') {
        echo $_GET['hub_challenge'];
        //echo 'true';
        return;
    } else {
        echo 'Invalid Verify Token';
        return;
        //echo 'false';
    }
}

file_put_contents("fb.txt", file_get_contents('php://input'));

$txtContents = file_get_contents("fb.txt");
$txtArray = json_decode($txtContents);
   echo ("<pre>");
     print_r($txtArray);
      echo ("</pre>");

$input = json_decode(file_get_contents('php://input'), true);
file_get_contents("https://graph.facebook.com/v2.8/me/messages?access_token=$token", false);
if (isset($input['entry'][0]['messaging'][0]['sender']['id'])) {

foreach ($memeWarsPotentialURLs as $key => $value) {

      echo "validate post";
                file_put_contents('log.txt',"sendMessageAsking: $sendMessageAsking \n", FILE_APPEND );

      //file_put_contents('memewars.txt', $_POST["url"]. "|||". $_POST["message"] . "\n", FILE_APPEND );
    $sendMessageAsking = true;
          //file_put_contents('log.txt',"sendMessageAsking: $sendMessageAsking \n", FILE_APPEND );


    $sender = $input['entry'][0]['messaging'][0]['sender']['id']; //sender facebook id
    $message = $input['entry'][0]['messaging'][0]['message']['text']; //text that user sent

    $url = "https://graph.facebook.com/v2.6/me/messages?access_token=$token";

    $ch = curl_init($url);
      if ($key == 0){
   $dataArray = array(
        "recipient" => array(
            "id" => $sender
            ),
        "message" => array(
            "text" => "This is the message of the next potential post: ". $memeWarsPotentialURLs[1],
            )
       );
    }
    if ($key == 1){

   $dataArray = array(
        "recipient" => array(
            "id" => $sender
            ),
        "message" => array(
            //"text" => "This is the message of the next potential post: ". $memeWarsPotentialURLs[1],
            "attachment" => array(
                "type" => "image",
                "payload" => array(
                    "url" => $memeWarsPotentialURLs[0],
                    "is_reusable" => false
                    )
                )
            ),
       );
     }

$jsonData = json_encode($dataArray);


  $enableMessaging = true;
    if ($enableMessaging){
    /* curl setting to send a json post data */
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
   // curl_setopt($ch, CURL_RETURNTRANSFER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    if (!empty($message)) {
        $result = curl_exec($ch); // user will get the message
                               file_put_contents('log.txt',"sendFunc was called. \n", FILE_APPEND );

    }

    curl_close($ch);
}
    file_put_contents('memewarsalreadyposted.txt',$memeWarsPotentialURLsOG );

//}
$sendMessageAsking = false;
//require "memewarscheck.php";
}
}
?>
