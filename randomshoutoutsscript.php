<?php
$enablePosting = 1;

foreach($pages as $keyPage)
	{
	if ($keyPage['name'] == 'The Random Shoutout Bot')
		{

      $myPosts = $fb->get('/' .$keyPage['id']. '/posts?fields=created_time,id,message,type', $keyPage['access_token']);
      $myPosts = $myPosts->getGraphEdge()->asArray();

      $datePosted = $myPosts[0]['created_time'];
      $datePosted = strtotime($datePosted->format('Y-m-d H:i:s'));
 echo ("<pre>");
   print_r($datePosted);
    echo ("</pre>");


       echo ("<pre>");
       echo "last post: ";
         print_r($myPosts[0]);
          echo ("</pre>");


      $commentsOnLatestPost = $fb->get('/' .$myPosts[0]['id']. '/comments?limit=100000&fields=message,attachment,from,message_tags,likes', $keyPage['access_token']);
      $commentsOnLatestPost = $commentsOnLatestPost->getGraphEdge()->asArray();

			$arrayOfCommentsILiked = array();
 echo ("<pre>");
   print_r($commentsOnLatestPost);
    echo ("</pre>");

      $reactsOnLatestPost = $fb->get('/' .$myPosts[0]['id'].'/reactions?limit=1000000', $keyPage['access_token']);
      $reactsOnLatestPost = $reactsOnLatestPost->getGraphEdge()->asArray();

      $arrayOfPeopleWhoCommented = array();
      $arrayOfPeopleWhoReacted = array();

      foreach ($commentsOnLatestPost as $key => $value) {
        $totalLengthOfTags = 0;
        foreach ($value['message_tags'] as $keytags => $valuetags) {
          $totalLengthOfTags = $totalLengthOfTags + strlen($valuetags['name']) +1;

        }
 echo ("<pre>");
 echo 'tag length: ';
   print_r($totalLengthOfTags  );
    echo ("</pre>");

 echo ("<pre>");
 echo 'message length ';
   print_r(strlen($value['message']));
    echo ("</pre>");

    if (strlen($value['message']) > $totalLengthOfTags ){
        array_push($arrayOfPeopleWhoCommented, $value['from']['name']);
      }
			foreach ($value['likes'] as $keyLikes => $valueLikes) {
				if($valueLikes['id'] ==  $keyPage['id']){
					array_push($arrayOfCommentsILiked,$value['from']['name']);
				}
			}




      }

      foreach ($reactsOnLatestPost as $key => $value) {
        array_push($arrayOfPeopleWhoReacted, $value['name']);
      }

    $arrayOfPeopleWhoReactedAndCommented =  array_intersect($arrayOfPeopleWhoCommented,$arrayOfPeopleWhoReacted);
    $arrayOfPeopleWhoReactedAndCommented = array_unique($arrayOfPeopleWhoReactedAndCommented);

 echo ("<pre>");
 echo "people who commented ";
   print_r($arrayOfPeopleWhoCommented);
    echo ("</pre>");


      echo ("<pre>");
      echo "people who reacted ";
        print_r($arrayOfPeopleWhoReacted);
         echo ("</pre>");

          echo ("<pre>");
          echo "people who commented and reacted ";
            print_r($arrayOfPeopleWhoReactedAndCommented);
             echo ("</pre>");

						 if(!empty($arrayOfCommentsILiked)){
							 $arrayOfPeopleWhoReactedAndCommented = $arrayOfCommentsILiked;
						 }

            $randomArrayKey = array_rand($arrayOfPeopleWhoReactedAndCommented);

             echo ("<pre>");
             echo "this person is the lucky winner: ";
               print_r($arrayOfPeopleWhoReactedAndCommented[$randomArrayKey]);
                echo ("</pre>");



                foreach ($commentsOnLatestPost as $key => $value) {


                  if($value['from']['name'] == $arrayOfPeopleWhoReactedAndCommented[$randomArrayKey]){


                    $arrayWithNameAndCommentOfWinner = array();
                    $videoSourceOfComment = array();

                    if(stripos($value['attachment']['type'], 'video') !== false){
                      $videoSourceOfComment = $fb->get('/' .$value['attachment']['target']['id'].'/?fields=source', $keyPage['access_token']);
                      $videoSourceOfComment = $videoSourceOfComment->getGraphNode()->asArray();
                    }


                    if (stripos($value['attachment']['type'], 'share') !== false || stripos($value['attachment']['type'], 'video') !== false){
                      unset($imageToPost);
                    }else {
                      $imageToPost = $value['attachment']['media']['image']['src'];
                    }

                     echo ("<pre>");
                     echo "value";
                       print_r($value);
                        echo ("</pre>");
                        $valueIDtoReplyTo = $value['id'];

                      $arrayWithNameAndCommentOfWinner = array('name' => $arrayOfPeopleWhoReactedAndCommented[$randomArrayKey], 'message' => $value['message'], 'imageURL' => $imageToPost, 'videoURL' => $videoSourceOfComment['source']);

                      $linkToComment = 'https://www.facebook.com/'.$value['id'];

                  }

                }

                 echo ("<pre>");
                 echo "person who won and the comment info: ";
                   print_r($arrayWithNameAndCommentOfWinner);
                    echo ("</pre>");
                    $toPost = array();


                    //if comment is an image
                    if(!empty($arrayWithNameAndCommentOfWinner['imageURL'])){
                      $toPost = $toPost + array('url' => $arrayWithNameAndCommentOfWinner['imageURL']) ;
                      //if there is a message with the image

                      if(!empty($arrayWithNameAndCommentOfWinner['message'])){
                        $toPost = $toPost + array('message' => 'Congratulations, '.$arrayWithNameAndCommentOfWinner['name'].", you won the s͏h͏o͏u͏t͏o͏u͏t!\n\n Your message: ". $arrayWithNameAndCommentOfWinner['message']);
                        //if there is no message with the image
                      }else{
                        $toPost = $toPost + array('message' => 'Congratulations, '.$arrayWithNameAndCommentOfWinner['name'].", you won the s͏h͏o͏u͏t͏o͏u͏t!\n\n Your photo:");

                      }
                    }

                    //if comment is a video
                    if(!empty($arrayWithNameAndCommentOfWinner['videoURL'])){
                      $toPost = $toPost + array('file_url' => $arrayWithNameAndCommentOfWinner['videoURL']) ;
                      //if there is a message with the video

                      if(!empty($arrayWithNameAndCommentOfWinner['message'])){
                        $toPost = $toPost + array('description' => 'Congratulations, '.$arrayWithNameAndCommentOfWinner['name'].", you won the s͏h͏o͏u͏t͏o͏u͏t!\n\n Your message: ". $arrayWithNameAndCommentOfWinner['message']);
                        //if there is no message with the image
                      }else{
                        $toPost = $toPost + array('description' => 'Congratulations, '.$arrayWithNameAndCommentOfWinner['name'].", you won the s͏h͏o͏u͏t͏o͏u͏t!\n\n Your video:");

                      }
                    }

                    $toPost = $toPost + array('message' => 'Congratulations, '.$arrayWithNameAndCommentOfWinner['name'].", you won the s͏h͏o͏u͏t͏o͏u͏t!\n\n Your message: ". $arrayWithNameAndCommentOfWinner['message']);


 echo ("<pre>");
 echo "this is toPost";
   print_r($toPost);
    echo ("</pre>");

                    $toComment = array(
                      'message' => "Like and comment on this post if you want a chance to win the next shoutout! Remember that multiple comments will not increase your chance of winning. You can comment photos and videos.\n\nHere is the link to the original comment: ".$linkToComment
                    );
                    if (time()-$datePosted < 3600)break;


                    if($enablePosting){
											if(!empty($arrayWithNameAndCommentOfWinner)){
                      if(!empty($arrayWithNameAndCommentOfWinner['imageURL'])){
                    $post = $fb->post('/' .  $keyPage['id'] . '/photos', $toPost, $keyPage['access_token']);
                    $post = $post->getGraphNode()->asArray();
                  } elseif(!empty($arrayWithNameAndCommentOfWinner['videoURL'])){
                    $post = $fb->post('/' .  $keyPage['id'] . '/videos', $toPost, $keyPage['access_token']);
                    $post = $post->getGraphNode()->asArray();
                  }else{
                    $post = $fb->post('/' .  $keyPage['id'] . '/feed', $toPost, $keyPage['access_token']);
                    $post = $post->getGraphNode()->asArray();
                  }

                    $comment = $fb->post('/' .  $post['id'] . '/comments', $toComment, $keyPage['access_token']);
                    $comment = $comment->getGraphNode()->asArray();

                    $fdrPostComment = $fb->post('/' . $valueIDtoReplyTo . '/comments', array(
                      'message' => 'Congratulations, '.explode(' ', trim($arrayWithNameAndCommentOfWinner['name'])) [0].', you won the shoutout! Check the most recent post to see your message being spread to the world!'

                    ) , $keyPage['access_token']);
                    $fdrPostComment = $fdrPostComment->getGraphNode()->asArray();
										}
                  }

                     echo ("<pre>");
                       print_r($post);
                        echo ("</pre>");

                         echo ("<pre>");
                           print_r($fdrPostComment);
                            echo ("</pre>");


  }
}
 ?>
