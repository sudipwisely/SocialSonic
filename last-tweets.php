<?php /*! Last 10 Tweets of this Application */

require_once(dirname(__FILE__) . "/config/config.php");
if ( !isset($_SESSION['Cust_ID']) ) {
    header('location:' . SITE_URL);
}

$page_title = 'Last 10 Tweets';
require_once(dirname(__FILE__) . "/templates/header.php");

global $mongoDb;

$userid = $_GET['userid'];

$Cust_ID = isset($_SESSION['Cust_ID']) ? $_SESSION['Cust_ID'] : '';
$Customer = $helper->getCustomerById($Cust_ID);
$twitteroauth = new TwitterOAuth($Customer['Cust_API_Key'], $Customer['Cust_API_Secret'], $Customer['Cust_Access_Token'], $Customer['Cust_Token_Secret']);
$tweetData = $twitteroauth->get('statuses/user_timeline', array("user_id" => $userid, "count" => "10")); ?>

<div class="col-xs-8 col-sm-9 col-md-10 col-lg-10 content">
	<div class="group_content_topbar">
        <h2><i class="fa fa-twitter"></i> Last 10 Tweets</h2>
    </div>
    <div class="row">
	   <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

            <?php if ( isset($tweetData->error) && $tweetData->error == 'Not authorized.' ) { ?>
                <div class="protectedProfile">
                    <h2><i class="fa fa-comment-o"></i><br />This Profile is Protected!</h2>
                </div>
            <?php } elseif ( count($tweetData) > 0 ) { ?>
            	<?php if ( !isset($tweetData->errors) ) { ?>
                		
                        <div class="row">
                            <style>.tweets-userbox {background:#0084B4 url(<?php echo !empty($tweetData[0]->user->profile_banner_url) ? $tweetData[0]->user->profile_banner_url : $tweetData[0]->user->profile_background_image_url; ?>) no-repeat 0 0 / cover;}</style>
                            <div class="col-xs-12 col-sm-6 col-md-5 col-lg-5">
                            	<div class="tweets-userboxWrap">
                                    <div class="tweets-userbox"></div>
                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                                        <img width="100%" class="img-thumbnail PicPhoto" src="<?php echo $tweetData[0]->user->profile_image_url; ?>" alt="<?php echo $tweetData[0]->user->screen_name; ?>" />
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-8 col-lg-8">
                                        <h3 style="margin:10px 0;">
                                            <?php echo $tweetData[0]->user->name; ?>
                                        </h3>
                                        <p>
                                            <a target="_blank" href="https://www.twitter.com/<?php echo $tweetData[0]->user->screen_name; ?>">@<?php echo $tweetData[0]->user->screen_name; ?></a>
                                        </p>
                                    </div>
                                    <div class="clearfix"></div><br />
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <p><?php echo $tweetData[0]->user->description; ?></p><br />
                                    </div>
                                    <div class="stats">
                                        <span><small>Following</small><?php echo $tweetData[0]->user->friends_count; ?></span>
                                        <span><small>Followers</small><?php echo $tweetData[0]->user->followers_count; ?></span>
                                        <span><small>Likes</small><?php echo $tweetData[0]->user->favourites_count; ?></span>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        
        					<div class="col-xs-12 col-sm-6 col-md-7 col-lg-7" id="last10Tweets">
                                <div class="row">
                                    <div class="nano">
                                        <div class="nano-content">
        									<?php $i = 0;
                                            foreach ( $tweetData as $tweet ) {
                                                $bgcolor = ($i % 2 == 1 ? '#F5F8FA' : '#fff'); ?>
                                                
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    <div class="tweetbox" style="background-color:<?php echo $bgcolor; ?>">
                                                        <p>
                                                        <?php if(strpos($tweet->text, "http://") !== false || strpos($tweet->text, "https://") !== false){ 
                                    preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $tweet->text, $match); ?>
                                                        	<a target="_blank" style="color:#222" href="<?php echo $match[0][0] ?>">
                                                            <?php echo $tweet->text; ?>
                                                        	</a>
                                                        	<?php } else { 
                                                        		 echo $tweet->text;
                                                        	} ?>

                                                        <!--<a target="_blank" style="color:#222" href="https://www.twitter.com/<?php echo $tweet->user->screen_name; ?>/status/<?php echo $tweet->id_str; ?>">
                                                            <?php echo $tweet->text; ?>
                                                        </a>-->

                                                        </p>
                                                        <p class="tweet-footer">
                                                            <span><i class="fa fa-retweet"></i> <?php echo $tweet->retweet_count; ?></span>&nbsp;&nbsp;&nbsp;
                                                            <span><i class="fa fa-heart"></i> <?php echo $tweet->favorite_count; ?></span>&nbsp;&nbsp;&nbsp;
                                                            <span><i class="fa fa-clock-o"></i>  <?php echo date('D M d H:i:s', strtotime($tweet->created_at)); ?></span>&nbsp;&nbsp;&nbsp;
                                                            <span class="pull-right">
                                                            	<a class="ReplyTweet" data-tweet="<?php echo $tweet->text; ?>" data-tweetid="<?php echo $tweet->id_str; ?>" href="#"><i class="fa fa-reply"></i> REPLY</a>
                                                            </span>
                                                        </p>
                                                    </div>
                                                </div>
                                                
                                                <?php $i++;
                                            } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
    			<?php } ?>
            <?php } else { ?>
                <div class="protectedProfile">
                    <h2><i class="fa fa-comment-o"></i><br />This Profile has no tweets!</h2>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<div class="modal fade in" id="LastReplyTweetsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog text-left">
    	<div class="modal-content">
        	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4>Reply to @<?php echo $tweetData[0]->user->screen_name; ?></h4>
            </div>
            <div class="modal-body">
            	<div class="row" style="margin:0;">
                	<div class="col-lg-2">
                    	<img width="100%" class="img-thumbnail" src="<?php echo $tweetData[0]->user->profile_image_url; ?>" alt="<?php echo $tweetData[0]->user->screen_name; ?>" />
                    </div>
                  	<div class="col-lg-10">
                        <input type="hidden" id="LastUserId" value="<?php echo $tweetData[0]->user->id_str; ?>">
                    	<strong><?php echo $tweetData[0]->user->name; ?></strong>&nbsp;&nbsp;-&nbsp;&nbsp;@<small id="LastScreenName" data-userid="<?php echo $tweetData[0]->user->id_str; ?>" class="text-muted"><?php echo $tweetData[0]->user->screen_name; ?></small><br />
                    	<div id="LastReplyTweet"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            	<form id="LastReplyForm">
                	<div class="form-group">
                    	<input type="hidden" id="LastReplyTweetID" />
                    	<textarea class="form-control" id="TweetMsgBox" rows="1">@<?php echo $tweetData[0]->user->screen_name; ?> </textarea>
                    </div>
                    <div class="col-sm-12">
                        <div class="row">
                    	   <span class="pull-left" id="LastTweetReplyMsg"></span>
                    	   <button type="button" class="btn btn-info tweet-action pull-right" id="LastTweetReplyBtn"><i class="fa fa-reply"></i> Tweet</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once(dirname(__FILE__) . "/templates/footer.php"); ?>