<?php /*! FAQ of this Application */

require_once(dirname(__FILE__) . "/config/config.php");
if ( !isset($_SESSION['Cust_ID']) ) {
   	header('location:' . SITE_URL);
}

$page_title = 'FAQ';
require_once(dirname(__FILE__) . "/templates/header.php"); ?>

<div class="col-xs-12 col-sm-9 col-md-10 col-lg-10 content">
    <div class="group_content_topbar">
        <h2><i class="fa fa-info-circle"></i> FAQ</h2>
    </div>
    <div class="row">
        <div class="nano">
            <div class="nano-content">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading1">
                                <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="true" aria-controls="collapse1">
                                        <i class="more-less glyphicon glyphicon-minus"></i>
                                        1. How many leads can I generate per day?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse1" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading1">
                                <div class="panel-body">
                                    <p>The number of leads that you can generate using EngageWise depends on three factors:</p>
                                    <p>A. The number of Twitter users talking about a keyword(s) you have used in your campaign.</p>
                                    <p>B. The number of followers Twitter Influencer you have added to your Category Search campaign.</p>
                                    <p>C. The number of followers you have.</p>
                                    <p>However, in the niches we have selected for you, it is safe to say you can generate 50-100 leads per day.</p>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading2">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse2" aria-expanded="false" aria-controls="collapse2">
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        2. What if Twitter bans the software?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading2">
                                <div class="panel-body">
                                    <p>There are two very strong reasons why a ban is unlikely:</p>
                                    <p>A. We’ve built the software using the API guidelines Twitter has provided to the developer community. So unless the software is being used to spread spam, virus, violence/hatred, graphic images, distasteful/unwanted messages it won’t be banned.</p>
                                    <p>B. Social media marketing applications which you use requires two levels of permissions from a network (Twitter, Facebook etc.)- app level & user level. For most applications of such kind, the app level permission is taken only once. Therefore, if a substantial user-base of the application violates policies consistently, the app may get banned permanently or suspended temporarily.</p>
                                    <p>In SocialSonic, we’re empowering our user base to create their own app. The app can still be used by visitng www.socialsoniccrm.com but every user shall have their own app thereby reducing the scope of getting banned further.</p>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading3">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse3" aria-expanded="false" aria-controls="collapse3">
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        3. Will this work for Real Estate investment?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse3" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading3">
                                <div class="panel-body">
                                    <p>Sure. SocialSonic is primarily a prospecting & relationship marketing application. Real Estate Investors & Private Lenders can identify the right people and opportunities using this app.</p>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading4">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse4" aria-expanded="false" aria-controls="collapse4">
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        4. Is this only for affiliate marketing?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse4" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading4">
                                <div class="panel-body">
                                    <p>No. In fact SocialSonic is even more lethal when used by existing businesses with a dedicated marketing or sales team.</p>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading5">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse5" aria-expanded="false" aria-controls="collapse5">
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        5. How much time do I need to invest?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse5" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading5">
                                <div class="panel-body">
                                    <p>SocialSonic searches leads even when you are not logged on once a campaign is started. However, your campaigns get idle when you do not log in for more than 3 days.</p>
                                    <p>While SocialSonic markets your business even when you’re sleeping, the more you use SocialSonic to develop relationships, the higher the return would be. We recommend you to use SocialSonic 2 hours everyday.</p>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading6">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse6" aria-expanded="false" aria-controls="collapse6">
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        6. Can the software be used for any other Social Media platform?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse6" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading6">
                                <div class="panel-body">
                                    <p>Currently no.</p>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading7">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse7" aria-expanded="false" aria-controls="collapse7">
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        7. Can the software be used for posting to multiple social media sites?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse7" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading7">
                              <div class="panel-body">
                                <p>No.</p>
                              </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading8">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse8" aria-expanded="false" aria-controls="collapse8">
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        8. How can I use the software to promote my Amazon Prime store?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse8" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading8">
                                <div class="panel-body">
                                    <p>Using SocialSonic you can find prospective buyers on Twitter based on what they are talking about and who they are following.</p>
                                    <p>E.g. if you are selling custom coffee mugs you can reach out to people who like to talk about coffee or are following luxury coffee brands on Twitter. You can use SocialSonic to get in touch with them in at least two ways:</p>
                                    <p>By automating relationship nurturing & By scheduling tweets to them.</p>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading9">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse9" aria-expanded="false" aria-controls="collapse9">
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        9. Can Social Sonic be used to schedule post to Twitter?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse9" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading9">
                                <div class="panel-body">
                                    <p>You can’t schedule posts to your Timeline on Twitter, however, you can schedule mentions.</p>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading10">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse10" aria-expanded="false" aria-controls="collapse10">
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        10. Can the software auto-respond to Tweets?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse10" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading10">
                                <div class="panel-body">
                                    <p>Twitter prohibits any application from automatically responding to Tweets. As per Twitter guidelines it is considered as an abuse of their platform.</p>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading11">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse11" aria-expanded="false" aria-controls="collapse11">
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        11. I am in networking marketing, selling a weight loss product, can this work for me?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse11" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading11">
                                <div class="panel-body">
                                    <p>Of course! You can extract the list of Twitter users who are following other weight loss program related Twitter handles or weight loss experts industry. You can also create a list of prospective buyers of your offer by running a keyword campaign.</p>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading12">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse12" aria-expanded="false" aria-controls="collapse12>
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        12. How much time do I need to spend per day to generate leads?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse12" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading12">
                              <div class="panel-body">
                                <p>Once you have set up a campaign, which takes about 20 min - 30 min., you need not spend any time at all to generate leads on a regular basis. You could use the time you save in generating leads, in reaching out to them, either using automation tools or in a more personal level.</p>
                              </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading13">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse13" aria-expanded="false" aria-controls="collapse13">
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        13. I don’t have many followers, will Social Sonic work for me?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse13" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading13">
                                <div class="panel-body">
                                    <p>Definitely. SocialSonic uses two other very powerful ways to generate leads:</p>
                                    <p>A. It mines Twitter to find keywords you have set in the campaign to build a list of people who are using those keywords.</p>
                                    <p>B. It creates a list of Twitter users who are following the key influencers your niche.</p>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading14">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse14" aria-expanded="false" aria-controls="collapse14">
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        14. I am not very tech savvy, is the software hard to use?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse14" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading14">
                                <div class="panel-body">
                                    <p>If you use Facebook, then you can use SocialSonic. If you can use Google search, you can use Social Sonic, It is that simple.</p>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading15">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse15" aria-expanded="false" aria-controls="collapse15">
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        15. Can i use with multiple Twitter accounts?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse15" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading15">
                                <div class="panel-body">
                                    <p>No.</p>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading16">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse16" aria-expanded="false" aria-controls="collapse16">
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        16. Is there plans to integrate with other Social Media sites like Instagram or Snapchat?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse16" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading16">
                                <div class="panel-body">
                                    <p>Currently we do not have such plans.</p>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading17">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse17" aria-expanded="false" aria-controls="collapse17">
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        17. How much will be cost for the second year?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse17" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading17">
                                <div class="panel-body">
                                    <p>Zero. You will have a lifetime access to SocialSonic.</p>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading18">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse18" aria-expanded="false" aria-controls="collapse18">
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        18. Can I use on my Mac?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse18" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading18">
                              <div class="panel-body">
                                <p>Yes, SocialSonic is a web-based application and you can use it through Google, Chrome, Safari, Mozilla Firefox or Internet Explorer.</p>
                              </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading19">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse19" aria-expanded="false" aria-controls="collapse19">
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        19. Are the 123 Virtual Employees trained to use the software?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse19" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading19">
                                <div class="panel-body">
                                    <p>?</p>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading20">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse20" aria-expanded="false" aria-controls="collapse20">
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        20. My target market are buyers of high value items such as fancy colored diamonds and antiques, can your software be used to reach that level of client?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse20" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading20">
                              <div class="panel-body">
                                <p>If your target market is using Twitter, or people who sell to your target market are using twitter, you can use Social Sonic. Think about what they might be tweeting, who they follow, what keywords might be in their Bio. When you have a big list, you don’t always know exactly who is interested in what, you just blast the list, with Social Sonic, you can identify prospects based on the ways explained. Just a different way of looking at how to use a database.</p>
                              </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading21">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse21" aria-expanded="false" aria-controls="collapse21">
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        21. I am a real estate agent, how do I use Social Sonic to find buyers and sellers of home?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse21" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading21">
                                <div class="panel-body">
                                    <p>?</p>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading22">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse22" aria-expanded="false" aria-controls="collapse22">
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        22. If I can’t generate any leads, will you refund me?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse22" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading22">
                                <div class="panel-body">
                                    <p>Yes, we have ___________ refund policy.</p>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading23">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse23" aria-expanded="false" aria-controls="collapse23">
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        23. Can I select my own offer to use with the blogs you create?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse23" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading23">
                                <div class="panel-body">
                                    <p>Your blogs are linked to relevant offers hence you can’t edit the offer.</p>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading24">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse24" aria-expanded="false" aria-controls="collapse24">
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        24. Can I use the software from my iPhone?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse24" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading24">
                                <div class="panel-body">
                                    <p>No SocialSonic can only be used from a desktop/laptop.</p>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading25">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse25" aria-expanded="false" aria-controls="collapse25">
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        25. Can I add my own blogs to the websites you create for me?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse25" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading25">
                                <div class="panel-body">
                                    <p>No, you can’t add your own blogs.</p>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading26">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse26" aria-expanded="false" aria-controls="collapse26">
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        26. Can I use my Virtual Assistant for other tasks?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse26" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading26">
                                <div class="panel-body">
                                    <p>Yes, your VA can work on a variety of tasks such as _______________ Once you invest in the program, my team will contact you and tell you how best to use your Virtual Assistant</p>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading27">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse27" aria-expanded="false" aria-controls="collapse27">
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        27. When is the event?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse27" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading27">
                                <div class="panel-body">
                                    <p>?</p>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading28">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse28" aria-expanded="false" aria-controls="collapse28">
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        28. Im don’t use Twitter, what’s the point of using the software?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse28" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading28">
                                <div class="panel-body">
                                    <p>You don’t have to use Twitter to reap the benefits or SocialSonic. However, you would require a Twitter account. Once you connect your Twitter account with SocialSonic, you shall start generating leads.</p>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading29">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse29" aria-expanded="false" aria-controls="collapse29">
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        29. Is there a risk of getting banned by Twitter?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse29" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading29">
                                <div class="panel-body">
                                    <p>Any automation software runs the risk of getting banned if you are using it to spam other users. With careful usages, you have virtually no risk of getting banned. In case you are banned, you can create another Twitter account and Twitter app and continue using SocialSonic.</p>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading30">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse30" aria-expanded="false" aria-controls="collapse30">
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        30. I live in South Africa, can I use for affiliate marketing?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse30" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading30">
                                <div class="panel-body">
                                    <p>SocialSonic can be used from any part of the world for affiliate marketing.</p>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading31">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse31" aria-expanded="false" aria-controls="collapse31">
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        31. How long does it take to learn?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse31" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading31">
                                <div class="panel-body">
                                    <p>You can learn how to use SocialSonic by watching our training videos. It will take a couple of hours at most.</p>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading32">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse32" aria-expanded="false" aria-controls="collapse32">
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        32. I want to start an online business, but I don’t have a business right now, should I buy this?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse32" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading32">
                                <div class="panel-body">
                                    <p>Here’s my answer: if you’re just starting out, it’s easy to get confused, buying this program and that program, but the one thing you’re going to hear, training after training is the importance of building your list. Well with this program you have a ready made list, and 25 niches to select from. Is this going to make you rich fast? No, but this is going to give you every thing you need to get started, learn the ropes, understand affiliate marketing, access to database of potential prospects.</p>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading33">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse33" aria-expanded="false" aria-controls="collapse33">
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        33. Will you still have this offer when you launch to the public?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse33" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading33">
                                <div class="panel-body">
                                    <p>NOPE, this special is today and today only.</p>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading34">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse34" aria-expanded="false" aria-controls="collapse34">
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        34. Can more than one person use the software at the same time?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse34" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading34">
                                <div class="panel-body">
                                    <p>No, we do not recommend more than one person using the software simultaneously.</p>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading35">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse35" aria-expanded="false" aria-controls="collapse35">
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        35. Can I share the account with my business partner?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse35" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading35">
                                <div class="panel-body">
                                    <p>As long as you are not logged in at the same time, yes you can.</p>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading36">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse36" aria-expanded="false" aria-controls="collapse36">
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        36. What are the different ways to connect with my ideal client?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse36" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading36">
                                <div class="panel-body">
                                    <p>You can send messages to followers, reply to their tweets, or mention them in comments.</p>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading37">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse37" aria-expanded="false" aria-controls="collapse37">
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        37. Can your software be used for promoting events?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse37" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading37">
                                <div class="panel-body">
                                    <p>YES, you can geo target and find people to send a direct message to the ones who follow you, or mention them in a tweet, or reply to a relevant post.</p>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading38">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse38" aria-expanded="false" aria-controls="collapse38">
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        38. I conduct online webinar summits, how can I use the software to promote my event?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse38" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading38">
                                <div class="panel-body">
                                    <p>You can, however, remember, Social Sonic is not ideal for MASS promotion. It’s designed for prospecting, nurturing and engaging. So keep that in mind. If you’re looking for a tool to SPAM people a) we don’t encourage it b) this is not the tool for you.</p>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading39">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse39" aria-expanded="false" aria-controls="collapse39">
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        39. Is this a cloud based software or do I have to install something on my laptop?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse39" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading39">
                                <div class="panel-body">
                                    <p>SocialSonic is a web application hence you need not install anything on your computer.</p>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading40">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse40" aria-expanded="false" aria-controls="collapse40">
                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                        40. Do I need to log into Twitter or can I do everything via Social Sonic?
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse40" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading40">
                                <div class="panel-body">
                                    <p>To set up your account for the first time, you have to login to Twitter. Once your SocialSonic account setup is done, you need not login to Twitter again.</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>                

<?php require_once(dirname(__FILE__) . "/templates/footer.php"); ?>