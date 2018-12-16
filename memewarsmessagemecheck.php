<?php

$winnerPost = end(file('memewars.txt', FILE_IGNORE_NEW_LINES));
//$winnerImageOrVideo = explode('|||', $winnerPost)[0];
//$winnerMessage = explode('|||', $winnerPost)[1];

//$winnerURLWithoutFirstLetter = substr($winnerImageOrVideo,1);

$messageToWarnMeText = array(
  'message' => "The mesage of the winner's meme: $winnerMessage\n\nThe name of the winner: $winnerName\n\nThe url of the winner meme: "
);

$messageToWarnMeURL = array(

  'message'=> "$winnerImageOrVideo"
);




$messages = $fb->get('/' . $keyPage['id'] . '/conversations?limit=10000000&fields=snippet,unread_count,senders,can_reply', $keyPage['access_token']);
$messages = $messages->getGraphEdge()->asArray();

 echo ("<pre>");
   print_r($messages);
    echo ("</pre>");


  foreach($messages as $keymes => $valuemes)
    {



//if it's my convo
    if ($valuemes['senders'][0]['name'] == 'Ethan Sarif-Kattan' && $valuemes['can_reply'] == 1)
      {
        echo 'gonna reply';


        $individualMessage = $fb->get('/' . $valuemes['id'] . '/messages?limit=10&fields=from,message', $keyPage['access_token']);
        $individualMessage = $individualMessage->getGraphEdge()->asArray();

foreach ($individualMessage as $keyindi => $valueindi) {
  //get the most recent url message
  if (stripos($valueindi['message'],'https://') != false){
    $mostRecentURLmessage = $valueindi['message'];
    break;
  }
}
 //
 // echo ("<pre>");
 // echo 'individual message';
 //   print_r($individualMessage);
 //    echo ("</pre>");
 //
 //
// it looks 3 messages back for the url because that is where the url is in located in the string of text sent by the accumulated messages from all bots.
//the reason it keeps sending me messages anyway if i say no is because my fake accounts keep getting randomly selected for the winner meme if no one else is availabe.
        if ($individualMessage[0]['message'] == $winnerImageOrVideo || $individualMessage[2]['message'] == $winnerImageOrVideo) {
if (!$wasMessageMeCalledBecauseTooMuchTimePassed){
          echo ' message duplicate being prevent rn ';
          break;
        }
}

if (in_array($individualMessage[0]['message'], array('yes', 'Yes', 'Yeah', 'yep', 'ok', 'sure', 'Sure','OK', 'Yep', 'Ok', 'alright'))){
$ISaidYesToProceeding = true;
}else if (in_array($individualMessage[0]['message'], array('no', 'No', 'NO', 'nope', 'nah', 'nein'))){

$ISaidNoToProceeding = true;


$CommentOfIneligibility = $fb->post('/' . $arrayOfPeopleIDsEligible[2] . '/comments', array(
  'message' =>  explode(' ', trim($arrayWithNameAndCommentOfWinner['name'])) [0] . ', your meme was deemed inappropriate or zuccworthy. In order to protect this page, we cannot accept these types of memes. If you continue to post such memes, you will be banned from the page.'
) , $keyPage['access_token']);
$CommentOfIneligibility = $CommentOfIneligibility->getGraphNode()->asArray();

file_put_contents('zuccworthyposters.txt',$arrayOfPeopleIDsEligible[1]. "\n", FILE_APPEND);

}
//i need to find most recent url sent
if ($ISaidYesToProceeding && ($mostRecentURLmessage == $arrayWithNameAndCommentOfWinner['videoURL'] || $mostRecentURLmessage == $arrayWithNameAndCommentOfWinner['imageURL'])){
$postFullyApproved = true;
}
if ($ISaidNoToProceeding && ($mostRecentURLmessage == $arrayWithNameAndCommentOfWinner['videoURL'] || $mostRecentURLmessage == $arrayWithNameAndCommentOfWinner['imageURL'])){
$postDisapproved = true;
}
 echo ("<pre>");
   print_r($CommentOfIneligibility);
    echo ("</pre>");
//the comment gets posted too slowly so noppp
// $repliesOnThisComment = $fb->get('/' . $arrayOfPeopleIDsEligible[2]['id'] . '/comments', $keyPage['access_token']);
// $repliesOnThisComment = $repliesOnThisComment->getGraphEdge()->asArray();
//
// $zuccworthyMessageSent = false;
//
// foreach ($repliesOnThisComment as $keyrepliesoncomment => $valuerepliesoncomment) {
//   if(strpos($valuerepliesoncomment['message'], "zuccworthy") !== false && $valuerepliesoncomment['from']['id'] == $keyPage['id']){
//     echo 'this person is zuccworthy message sent';
//     $zuccworthyMessageSent = true;
//
//   }else {echo 'zuccworthy message not sent.';}
// }
//make it just detect if the message its about to send is the same as the one it just sent
if (!$ISaidNoToProceeding && !$ISaidYesToProceeding){
        echo 'should send me message now ';
      $replymessage = $fb->post('/' . $valuemes['id'] . '/messages', $messageToWarnMeText, $keyPage['access_token']);
      $replymessage = $replymessage->getGraphNode()->asArray();
      $replymessage = $fb->post('/' . $valuemes['id'] . '/messages', $messageToWarnMeURL, $keyPage['access_token']);
      $replymessage = $replymessage->getGraphNode()->asArray();
      echo ("<pre>");
      print_r("replied to:" . $individualMessage[0]['from']['name']);
      echo ("</pre>");

    }else if ($ISaidNoToProceeding){
      $replymessage = $fb->post('/' . $valuemes['id'] . '/messages', array('message' => 'Ok, this meme will not be posted'), $keyPage['access_token']);
    $replymessage = $replymessage->getGraphNode()->asArray();
  } else if ($ISaidYesToProceeding){
    $replymessage = $fb->post('/' . $valuemes['id'] . '/messages', array('message' => 'Sure, this meme will be posted'), $keyPage['access_token']);
  $replymessage = $replymessage->getGraphNode()->asArray();

  }


    break;
      }


    }
 ?>
