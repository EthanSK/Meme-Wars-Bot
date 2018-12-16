<?php

$access_token = end(file("longlivedaccesstoken.txt", FILE_IGNORE_NEW_LINES));
$fb->setExtendedAccessToken();
$tmp1 = $fb->getAccessToken($access_token);

 echo ("<pre>");
   print_r($access_token);
    echo ("</pre>");

foreach($pages as $keyPage)
	{
	if ($keyPage['name'] == 'Meme Wars in the Comments')
		{

		$enablePosting = 1;
		$forcePost = 0;
		$disableTextMemes = 1;
		$numberOfLikesRequiredToBeEligible = 1;
		$myPosts = $fb->get('/' . $keyPage['id'] . '/posts?fields=created_time,id,message,type', $keyPage['access_token']);
		$myPosts = $myPosts->getGraphEdge()->asArray();
		$datePosted = $myPosts[0]['created_time'];
		$datePosted = strtotime($datePosted->format('Y-m-d H:i:s'));



		echo ("<pre>");
		print_r($datePosted);
		echo ("</pre>");
		if (time() - $datePosted < 3600 * 2 && $forcePost == false)
			{
			$enablePosting = 0;
			echo ("<pre>");
			print_r('its not posting coz the last post was made recently.');
			echo ("</pre>");
			}

		echo ("<pre>");
		echo "last post: ";
		print_r($myPosts[0]);
		echo ("</pre>");
		$commentsOnLatestPost = $fb->get('/' . $myPosts[0]['id'] . '/comments?limit=100000&fields=message,attachment,from,message_tags,like_count,likes,type', $keyPage['access_token']);
		$commentsOnLatestPost = $commentsOnLatestPost->getGraphEdge()->asArray();
		require 'memewarsAutoComment.php';

		$arrayOfPeopleIDsEligible = array();
		$likeCount = array();
		//  echo ("<pre>");
		//  echo 'comments on latest post134';
		//    print_r($commentsOnLatestPost);
		// 	  echo ("</pre>");

		foreach($commentsOnLatestPost as $key => $row)
			{

			if (!array_key_exists("attachment", $row) && $disableTextMemes)
				{
				unset($commentsOnLatestPost[$key]);
				}

			$likeCount[$key] = $row['like_count'];
			}


				function sortById($x, $y) {
    return $y['like_count'] - $x['like_count'];
}

usort($commentsOnLatestPost, 'sortById');


		//array_multisort($likeCount, SORT_DESC, $commentsOnLatestPost);


		$hasAtLeastOneLikeOnACommentBeenMade = false;
		echo ("<pre>");
		echo 'this is how many comments there are: ';
		print_r(count($commentsOnLatestPost));
		echo ("</pre>");
		$iterationOfCommentsScanLoop = 0;

		//this is a big foreach loop
		foreach($commentsOnLatestPost as $key => $value)
			{



				$iterationOfCommentsScanLoop++;


			if ($value['from']['id'] == $keyPage['id']) continue;
			$numberOfLikesThisPersonHasMade = 0;
			$arrayOfTotalCommentsByPerson = array();

			// foreach comment check to see if the person that posted this comment has liked 5 other memes minimum

			foreach($commentsOnLatestPost as $key2 => $value2)
				{
				array_push($arrayOfTotalCommentsByPerson, $value2['from']['id']);
				if ($value2['from']['id'] == $value['from']['id'])
					{
					continue;
					}

				$likesOnThisComment = $fb->get('/' . $value2['id'] . '/likes?limit=1000000', $keyPage['access_token']);
				$likesOnThisComment = $likesOnThisComment->getGraphEdge()->asArray();
				foreach($likesOnThisComment as $keyLikes => $valueLikes)
					{

					$hasAtLeastOneLikeOnACommentBeenMade = true;
					if ($value['from']['id'] == $valueLikes['id'])
						{
						$numberOfLikesThisPersonHasMade++;


						}
					}
				}
				if (!$hasAtLeastOneLikeOnACommentBeenMade) {
	 echo ("<pre>");
	   print_r('there are no likes so $number of likes required is set to 0. Actually no, i will get the bot to like a meme. All around me are familiar faces. fuck this doesnt work as well due to blocc so ill have to just auto selecet a manvan, fredman, or cakis post.');
		  echo ("</pre>");



					$useFakeAccountPostSinceNoCommenterVoted = true;
					//$numberOfLikesRequiredToBeEligible = 0;
					// by the time we've reached the last post, if no likes have been made, like the last post. The last post should be a fake accont post anyway.
					//wait a sec, i realised this is fucking retarded. It wont change if the fake account has liked any other posts, and will disable this shiz. fuck, im gonna have to make the fai kakkount like a post.
					if ($iterationOfCommentsScanLoop == count($commentsOnLatestPost))
					{
						//$makeLike = $fb->post('/' . $commentsOnLatestPost['id'] . '/likes', array() , $page['access_token']);

					}

				}

			echo ("<pre>");
			echo $value['from']['name'];
			echo ' has '. $value['like_count']." likes and is the person that made this comment has liked this many other posts: ". $numberOfLikesThisPersonHasMade;
			echo ("</pre>");
			$arrayOfTotalCommentsByPersonUnique = array_unique($arrayOfTotalCommentsByPerson);



			$repliesOnThisComment = $fb->get('/' . $value['id'] . '/comments', $keyPage['access_token']);
			$repliesOnThisComment = $repliesOnThisComment->getGraphEdge()->asArray();
			//  echo ("<pre>");
			//  echo 'replies on this comment';
			//    print_r($repliesOnThisComment);
			// 	  echo ("</pre>");




			// $arrayOfPeopleIDsEligible will be in order because this loop is in order from most to least liked comments. However, i dont need an array, i can get it to stop as soon as the person has been found.
			// if there are 5 or less comments, well that means people cannot like 5 memes so there is an exception
			// change this 0 back to 5
			//doing this so that if the number of comments is more then the number of likes needed by 1 person, then the concept of this scritp wouldnt crash. It is kinda obsolete now.
			if (count($arrayOfTotalCommentsByPersonUnique) <= $numberOfLikesRequiredToBeEligible)
				{
					echo 'there are fewer comments than likes required. RIP';
					$isZuccWorthy = false;

				if ($numberOfLikesThisPersonHasMade >= count($arrayOfTotalCommentsByPersonUnique) - 1)
					{
						foreach ($repliesOnThisComment as $keyrepliesoncomment => $valuerepliesoncomment) {

							//if changingi the strpos search word from zuccworthy, make sure to change this in meme wars message check too.

							if(strpos($valuerepliesoncomment['message'], "zuccworthy") !== false && $valuerepliesoncomment['from']['id'] == $keyPage['id']){
								echo 'this person is zuccworthy';
								$isZuccWorthy = true;

							}else {echo 'this person is not zuccworthy';}
						}
							if (!$isZuccWorthy){
						array_push($arrayOfPeopleIDsEligible, $value['from']['id'], $value['from']['name'], $value['id']);
						break;
					}

					}
				  else
					{
					if ($enablePosting)
						{
						$repliesOnThisPost = $fb->get('/' . $value['id'] . '/comments', $keyPage['access_token']);
						$repliesOnThisPost = $repliesOnThisPost->getGraphEdge()->asArray();

						$stopReply = false;
						foreach($repliesOnThisPost as $keyrep => $valuerep)
							{
							if ($valuerep['from']['id'] == $keyPage['id'] && strpos($valuerep['message'], 'Apologies') !== false)
								{
								$stopReply = true;
								}
							}

						if (!$stopReply)
							{
							$apologyCozYouDidntVote = $fb->post('/' . $value['id'] . '/comments', array(
								'message' => 'Apologies, ' . explode(' ', trim($value['from']['name'])) [0] . ', but you did not like enough other comments to be eligible. Next time, remember that it doesn\'t matter how many likes your meme gets, you need to participate and vote for at least ' . $numberOfLikesRequiredToBeEligible . ' memes. Also, voting for yourself does not count. Good luck!'
							) , $keyPage['access_token']);
							$apologyCozYouDidntVote = $apologyCozYouDidntVote->getGraphNode()->asArray();
							}
						}
					}

				echo ("<pre>");
				print_r($apologyCozYouDidntVote);
				echo ("</pre>");
				}
			  else
				{
					//echo 'there are more comments than number of likes required per person...yay.';
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
				// change this 0 back to 5
				//this block exists on the outermost foreach loop that loops through every comment once. therefore we can use this info to identify the most liked voter.
				$isZuccWorthy = false;
				if ($numberOfLikesThisPersonHasMade >= $numberOfLikesRequiredToBeEligible)
					{
						echo 'made enough likes';
						foreach ($repliesOnThisComment as $keyrepliesoncomment => $valuerepliesoncomment) {

							//if changingi the strpos search word from zuccworthy, make sure to change this in meme wars message check too.

							if(strpos($valuerepliesoncomment['message'], "zuccworthy") !== false && $valuerepliesoncomment['from']['id'] == $keyPage['id']){
								echo 'this person is zuccworthy';
								$isZuccWorthy = true;

							}else {echo 'this person is not zuccworthy';}
						}
							if (!$isZuccWorthy){
						array_push($arrayOfPeopleIDsEligible, $value['from']['id'], $value['from']['name'], $value['id']);
						break;
					}



					}
				  else
					{
					if ($enablePosting)
						{
						$repliesOnThisPost = $fb->get('/' . $value['id'] . '/comments', $keyPage['access_token']);
						$repliesOnThisPost = $repliesOnThisPost->getGraphEdge()->asArray();

						$stopReply = false;
						foreach($repliesOnThisPost as $keyrep => $valuerep)
							{
							if ($valuerep['from']['id'] == $keyPage['id'] && strpos($valuerep['message'], 'Apologies') !== false)
								{
								$stopReply = true;
								}
							}
							if ($value['from']['id'] == '118862731965062' || $value['from']['id'] == '154227091749617'|| $value['from']['id'] == '140303256483122'){
								$stopReply = true;

							}


						if (!$stopReply )
							{

							$apologyCozYouDidntVote = $fb->post('/' . $value['id'] . '/comments', array(
								'message' => 'Apologies, ' . explode(' ', trim($value['from']['name'])) [0] . ', but you did not like enough other comments to be eligible. Next time, remember that it doesn\'t matter how many likes your meme gets, you need to participate and vote for at least ' . $numberOfLikesRequiredToBeEligible . ' memes. Also, voting for yourself doesn\'t count. Good luck!'
							) , $keyPage['access_token']);
							$apologyCozYouDidntVote = $apologyCozYouDidntVote->getGraphNode()->asArray();

							}
						}
					}
				}
			}
 echo ("<pre>");
 echo 'array of people eligible before checking if empty';
   print_r($arrayOfPeopleIDsEligible);
	  echo ("</pre>");



		if (empty($arrayOfPeopleIDsEligible))
		{
			$randomFakeAccountCommentChoosing = rand(0,2);

			switch ($randomFakeAccountCommentChoosing) {
				case 0:
				//manvan
				$idOfFakeAccount = 118862731965062;
					break;

					case 1:
					//cakis allie
					$idOfFakeAccount = 154227091749617;
						break;
						case 2:
						//sandals succmafeet
						$idOfFakeAccount = 106094306588165;
							break;
			}
			foreach ($commentsOnLatestPost as $keyfaik => $valuefaik) {
				if ($valuefaik['from']['id'] == $idOfFakeAccount ){
				array_push($arrayOfPeopleIDsEligible, $valuefaik['from']['id'], $valuefaik['from']['name'], $valuefaik['id']);

			}
		}

		}
		echo ("<pre>");
		echo 'person who will be posted next:';
		print_r($arrayOfPeopleIDsEligible);
		echo ("</pre>");
		$winnerInfo = $fb->get('/' . $arrayOfPeopleIDsEligible[0] . '?metadata=1', $keyPage['access_token']);
		$winnerInfo = $winnerInfo->getGraphNode()->asArray();
		// echo ("<pre>");
		// echo 'info about winner';
		//   print_r($winnerInfo);
		//   echo ("</pre>");

		echo ("<pre>");
		echo 'type of profile ';
		print_r($winnerInfo['metadata']['type']);
		echo ("</pre>");

		// can be page or user

		$typeOfProfile = $winnerInfo['metadata']['type'];
		$typeOfProfileButThisVarIsOnlyForCommentRepliesStatingTheWin = $typeOfProfile;

		// sadly i just realised you can only tag pages you are admin of...

		$typeOfProfile = 'user';
		$commentInfo = $fb->get('/' . $arrayOfPeopleIDsEligible[2] . '?fields=attachment,from,created_time,id,message', $keyPage['access_token']);
		$commentInfo = $commentInfo->getGraphNode()->asArray();
		echo ("<pre>");
		print_r($commentInfo);
		echo ("</pre>");
		$arrayWithNameAndCommentOfWinner = array();
		$videoSourceOfComment = array();
		if (stripos($commentInfo['attachment']['type'], 'video') !== false)
			{
			$videoSourceOfComment = $fb->get('/' . $commentInfo['attachment']['target']['id'] . '/?fields=source', $keyPage['access_token']);
			$videoSourceOfComment = $videoSourceOfComment->getGraphNode()->asArray();
			}

		if (stripos($commentInfo['attachment']['type'], 'photo') !== false)
			{
			$imageToPost = $commentInfo['attachment']['media']['image']['src'];
			}

		$arrayWithNameAndCommentOfWinner = array(
			'name' => $arrayOfPeopleIDsEligible[1],
			'message' => $commentInfo['message'],
			'imageURL' => $imageToPost,
			'videoURL' => $videoSourceOfComment['source']
		);
		$linkToComment = 'https://www.facebook.com/' . $commentInfo['id'];
		echo ("<pre>");
		echo "person who won and the comment info: ";
		print_r($arrayWithNameAndCommentOfWinner);
		echo ("</pre>");
		$toPost = array();

		// if comment is an image

		if (!empty($arrayWithNameAndCommentOfWinner['imageURL']))
			{
			$toPost = $toPost + array(
				'url' => $arrayWithNameAndCommentOfWinner['imageURL']
			);

			// if there is a message with the image

			if (!empty($arrayWithNameAndCommentOfWinner['message']))
				{
				if ($typeOfProfile == 'page')
					{
					$toPost = $toPost + array(
						'message' => "Congratulations, @[" . $arrayOfPeopleIDsEligible[0] . "], your meme won!\n\n Your meme: " . $arrayWithNameAndCommentOfWinner['message']
					);
					}
				  else
					{
					$toPost = $toPost + array(
						'message' => 'Congratulations, ' . $arrayWithNameAndCommentOfWinner['name'] . ", your meme won!\n\n Your meme: " . $arrayWithNameAndCommentOfWinner['message']
					);
					}



				// if there is no message with the image

				}
			  else
				{
				if ($typeOfProfile == 'page')
					{
					$toPost = $toPost + array(
						'message' => "Congratulations, @[" . $arrayOfPeopleIDsEligible[0] . "], your meme won!\n\n Your meme:"
					);
					}
				  else
					{
					$toPost = $toPost + array(
						'message' => 'Congratulations, ' . $arrayWithNameAndCommentOfWinner['name'] . ", your meme won!\n\n Your meme:"
					);
					}
				}
			}

		// if comment is a video

		if (!empty($arrayWithNameAndCommentOfWinner['videoURL']))
			{
			$toPost = $toPost + array(
				'file_url' => $arrayWithNameAndCommentOfWinner['videoURL']
			);

			// if there is a message with the video

			if (!empty($arrayWithNameAndCommentOfWinner['message']))
				{
				if ($typeOfProfile == 'page')
					{
					$toPost = $toPost + array(
						'description' => "Congratulations, @[" . $arrayOfPeopleIDsEligible[0] . "], your meme won!\n\n Your meme: " . $arrayWithNameAndCommentOfWinner['message']
					);
					}
				  else
					{
					$toPost = $toPost + array(
						'description' => 'Congratulations, ' . $arrayWithNameAndCommentOfWinner['name'] . ", your meme won!\n\n Your meme: " . $arrayWithNameAndCommentOfWinner['message']
					);
					}

				// if there is no message with the image

				}
			  else
				{
				if ($typeOfProfile == 'page')
					{
					$toPost = $toPost + array(
						'description' => "Congratulations, @[" . $arrayOfPeopleIDsEligible[0] . "], your meme won!\n\n Your meme:"
					);
					}
				  else
					{
					$toPost = $toPost + array(
						'description' => 'Congratulations, ' . $arrayWithNameAndCommentOfWinner['name'] . ", your meme won!\n\n Your meme:"
					);
					}
				}
			}

		if ($typeOfProfile == 'page')
			{
			$toPost = $toPost + array(
				'message' => "Congratulations, @[" . $arrayOfPeopleIDsEligible[0] . "], your meme won!\n\n Your meme: " . $arrayWithNameAndCommentOfWinner['message']
			);
			}
		  else
			{
			$toPost = $toPost + array(
				'message' => 'Congratulations, ' . $arrayWithNameAndCommentOfWinner['name'] . ", your meme won!\n\n Your meme: " . $arrayWithNameAndCommentOfWinner['message']
			);
			}

		echo ("<pre>");
		echo "this is toPost";
		print_r($toPost);
		echo ("</pre>");
		$toComment = array(

			// 'message' => "If you want a chance of winning, simply comment your best meme on the latest post, and like at least 5 other comments to vote for them (for now this has been disabled while the page is small). You can comment photos, videos, or just text.\n\nHere is the link to the original comment: ".$linkToComment

			'message' => "If you want a chance of winning, simply comment your best meme on the latest post, and like at least $numberOfLikesRequiredToBeEligible other memes you think are worthy of winning. You can comment photos, videos, or just text.\n\nHere is the link to the original comment: " . $linkToComment
		);



		if (!empty($arrayWithNameAndCommentOfWinner['imageURL'])){
			//type is photo
			echo "type is photo";

		$postToBeValidated =  array( 'url' => $arrayWithNameAndCommentOfWinner['imageURL'], 'message' =>  $arrayWithNameAndCommentOfWinner['message']);
		file_put_contents('memewars.txt', $arrayWithNameAndCommentOfWinner['imageURL']. "|||".$arrayWithNameAndCommentOfWinner['message'] . "\n", FILE_APPEND );
$winnerImageOrVideo = $arrayWithNameAndCommentOfWinner['imageURL'];
	}elseif (!empty($arrayWithNameAndCommentOfWinner['videoURL'])){
		//type is video
		echo "type is video";
		$postToBeValidated =  array( 'url' => $arrayWithNameAndCommentOfWinner['videoURL'], 'message' => $arrayWithNameAndCommentOfWinner['message'] );
		file_put_contents('memewars.txt', $arrayWithNameAndCommentOfWinner['videoURL']. "|||".$arrayWithNameAndCommentOfWinner['message'] . "\n", FILE_APPEND );
$winnerImageOrVideo = $arrayWithNameAndCommentOfWinner['videoURL'];
	}
	$winnerMessage = $arrayWithNameAndCommentOfWinner['message'];
	$winnerName = $arrayWithNameAndCommentOfWinner['name'];


	if ($enablePosting)require "memewarsmessagemecheck.php";

		//
		// $url = "https://http://radiant-plateau-58041.herokuapp.com/";
		// $curlSesh = curl_init();
		// curl_setopt($curlSesh, CURLOPT_URL, $url);
		// curl_setopt($curlSesh, CURLOPT_POST, true);
		// curl_setopt($curlSesh, CURLOPT_POSTFIELDS, $postToBeValidated);
		// curl_setopt($curlSesh, CURLOPT_RETURNTRANSFER, true);
		// $response = curl_exec($curlSesh);
		// curl_close($curlSesh);
		// echo "response: ";
		// echo $response;
		if ($response == "validate post")echo ' post has been validated';






		if ($enablePosting && !empty($arrayWithNameAndCommentOfWinner) && $postFullyApproved)
			{
				echo 'about to start the posting process';
			if (!empty($arrayWithNameAndCommentOfWinner))
				{
				if (!empty($arrayWithNameAndCommentOfWinner['imageURL']))
					{
					$post = $fb->post('/' . $keyPage['id'] . '/photos', $toPost, $keyPage['access_token']);
					$post = $post->getGraphNode()->asArray();
					}
				elseif (!empty($arrayWithNameAndCommentOfWinner['videoURL']))
					{
					$post = $fb->post('/' . $keyPage['id'] . '/videos', $toPost, $keyPage['access_token']);
					$post = $post->getGraphNode()->asArray();
					}
				  else
					{
					$post = $fb->post('/' . $keyPage['id'] . '/feed', $toPost, $keyPage['access_token']);
					$post = $post->getGraphNode()->asArray();
					}

				$comment = $fb->post('/' . $post['id'] . '/comments', $toComment, $keyPage['access_token']);
				$comment = $comment->getGraphNode()->asArray();
				if ($typeOfProfileButThisVarIsOnlyForCommentRepliesStatingTheWin == 'page')
					{
					$fdrPostComment = $fb->post('/' . $arrayOfPeopleIDsEligible[2] . '/comments', array(
						'message' => 'Congratulations, ' . $arrayWithNameAndCommentOfWinner['name'] . ', your meme won! Check the most recent post to see your meme being spread to the world!'
					) , $keyPage['access_token']);
					$fdrPostComment = $fdrPostComment->getGraphNode()->asArray();
					}
				  else
					{
					$fdrPostComment = $fb->post('/' . $arrayOfPeopleIDsEligible[2] . '/comments', array(
						'message' => 'Congratulations, ' . explode(' ', trim($arrayWithNameAndCommentOfWinner['name'])) [0] . ', your meme won! Check the most recent post to see your meme being spread to the world!'
					) , $keyPage['access_token']);
					$fdrPostComment = $fdrPostComment->getGraphNode()->asArray();
					}

				//$makeLike = $fb->post('/' . $arrayOfPeopleIDsEligible[2] . '/likes', array() , $page['access_token']);
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
