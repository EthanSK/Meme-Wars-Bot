<?php
//this is required is likebot, memewars, videobot
$myPosts = $fb->get('/1370489539690801/posts?fields=created_time,id,message,type', $keyPage['access_token']);
$myPosts = $myPosts->getGraphEdge()->asArray();
$commentsOnLatestPost = $fb->get('/' .$myPosts[0]['id']. '/comments?limit=100000&fields=message,attachment,from,message_tags,like_count,likes', $keyPage['access_token']);
$commentsOnLatestPost = $commentsOnLatestPost->getGraphEdge()->asArray();
$infoAboutMe = $fb->get('/me');
$infoAboutMe = $infoAboutMe->getGraphNode()->asArray();


$numberOfCommentsImade = 0;
$numberOfLikesThisPersonHasMade =0;
  foreach ($commentsOnLatestPost as $keycomnt => $valuecomnt) {
    if ($valuecomnt['from']['id'] == $infoAboutMe['id']){
      $numberOfCommentsImade++;

      $likesOnThisComment = $fb->get('/' . $valuecomnt['id'] . '/likes?limit=1000000', $keyPage['access_token']);
      $likesOnThisComment = $likesOnThisComment->getGraphEdge()->asArray();

      foreach($likesOnThisComment as $keyLikes => $valueLikes)
        {

        if ($valuecomnt['from']['id'] == $valueLikes['id'])
          {
          $numberOfLikesThisPersonHasMade++;


          }
        }
    }
  }
  if (count($commentsOnLatestPost) >0){
    //ugh this keeps getting blocccced
//$makeLike = $fb->post('/' . $commentsOnLatestPost[0]['id'] . '/likes', array() , $page['access_token']);

  }





//if there aren't enough comments then post sme memes ffs
$arrayOfMemesFromPage = array();
$topMemesOnPage = array();
if(count($commentsOnLatestPost) < 15 ){
   echo ("<pre>");
     print_r('started autocommenting memes');
      echo ("</pre>");

  //$numberOfMemesThatNeedCommenting= 10 - count($commentsOnLatestPost);
  $numberOfMemesThatNeedCommenting= 1;

  for ($i=0; $i < $numberOfMemesThatNeedCommenting; $i++) {
    if($numberOfCommentsImade >= 1)continue;
    echo $i;
    $randomPostFromTop = rand(1,count($topMemesOnPage));
    $randomPostPhotoOrVideo = rand(0,1);
    //$randomPostPhotoOrVideo = 1;

    if($stopVideosBeingCommented)$randomPostPhotoOrVideo =0;

    if ($randomPostPhotoOrVideo ==0){
    $getLikeData = $fb->get('/ericscreamymeme/posts?limit=100&fields=full_picture,reactions.limit(0).summary(true),type');


    foreach ($getLikeData->getGraphEdge() as $graphNode) {

                   $getReacts = $graphNode->getField('reactions')->getMetaData();

                     $numberOfLikes = $getReacts['summary']['total_count'];
                     if ($graphNode['type'] == 'photo'){
                        array_push($arrayOfMemesFromPage, array($graphNode['full_picture'] , $numberOfLikes,$graphNode['message'] ,$graphNode['type'] ));
                      }

              }




                   foreach ($arrayOfMemesFromPage as $keysort => $valuesort) {
                     $sortedMemesFromPage[$keysort] = $valuesort[1];

                    }
                    $randomPostFromTop = rand(1,count($topMemesOnPage));

                    array_multisort($sortedMemesFromPage, SORT_DESC, $arrayOfMemesFromPage);
                    $randomPostFromTop = rand(1,count($topMemesOnPage));




                       $topMemesOnPage = array_slice($arrayOfMemesFromPage, 0 , 50, true);


                       $randomPostFromTop = rand(1,count($topMemesOnPage));

                       $randomPostFromTop = $randomPostFromTop -1;

                       $toComment = array(
                         'attachment_url' => $topMemesOnPage[$randomPostFromTop][0],
                         //'message' => "https://www.facebook.com/shitpostcommunity/"
                       );

                     }

                     if ($randomPostPhotoOrVideo ==1){
                    $getLikeData = $fb->get('/videomemesbot/posts?limit=100&fields=full_picture,source,reactions.limit(0).summary(true),type');



                    foreach ($getLikeData->getGraphEdge() as $graphNode) {

                                   $getReacts = $graphNode->getField('reactions')->getMetaData();

                                     $numberOfLikes = $getReacts['summary']['total_count'];
                                     if ($graphNode['type'] == 'video'){
                                        array_push($arrayOfMemesFromPage, array($graphNode['source'] , $numberOfLikes, 0 ,$graphNode['type'] ));
                                      }

                              }




                                   foreach ($arrayOfMemesFromPage as $keysort => $valuesort) {
                                     $sortedMemesFromPage[$keysort] = $valuesort[1];

                                    }
                                    $randomPostFromTop = rand(1,count($topMemesOnPage));

                                    array_multisort($sortedMemesFromPage, SORT_DESC, $arrayOfMemesFromPage);
                                    $randomPostFromTop = rand(1,count($topMemesOnPage));




                                       $topMemesOnPage = array_slice($arrayOfMemesFromPage, 0 , 50, true);


                                       $randomPostFromTop = rand(1,count($topMemesOnPage));

                                       $randomPostFromTop = $randomPostFromTop -1;

                                       $toComment = array(
                                         'attachment_url' => $topMemesOnPage[$randomPostFromTop][0],
                                         //'message' => "https://www.facebook.com/shitpostcommunity/"
                                       );
 echo ("<pre>");
   print_r($toComment);
    echo ("</pre>");

                                     }
     $post = $fb->post('/' .  $myPosts[0]['id'] . '/comments', $toComment);
     $post = $post->getGraphNode()->asArray();

     echo ("<pre>");
     echo 'this is the comment i made';
     print_r($post);
      echo ("</pre>");

  }

}
 ?>
