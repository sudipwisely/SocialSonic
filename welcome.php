<?php /*! Welcome/Landing of this Application */

require_once(dirname(__FILE__) . "/config/config.php");
if ( isset($_SESSION['Cust_ID']) ) {
   	header('location:' . SITE_URL . 'twitter-crm/');
} elseif ( isset($_COOKIE['__ssUn']) && isset($_COOKIE['__ssPs']) ) {
	$username = $_COOKIE['__ssUn'];
	$password = $_COOKIE['__ssPs'];
	$helper->loginWithCookie($username, $password);
}

$page_title = 'Welcome to SocialSonic';
require_once(dirname(__FILE__) . '/templates/header.php'); ?>
	
<div class="sortable-container page-container" data-name="Page Container">
	<div data-name="Menu Bar" class="section menu-bar role-element leadstyle-container" id="menu-bar" data-layout-order="0">
		<div class="overlay" style="opacity:0.3"></div>
		<div class="container">
			<div>
				<div data-name="Image" class="logo-container role-element leadstyle-image-link">
					<a href="<?php echo SITE_URL; ?>"><img src="<?php echo SITE_URL; ?>images/ss-logo.png"></a>
				</div>
				<div data-name="Menu-Container" class="menu-container role-element leadstyle-container"></div>
			</div>
		</div>
	</div>
	
	<div data-name="Header" class="section header role-element leadstyle-container" id="header" data-layout-order="1">
		<div class="overlay" style="opacity:0.25;"></div>
		<div class="container">
			<h1 data-name="Title" class="text-center role-element leadstyle-text">
				<strong class="leadstyle-fontsized">You're Minutes Away From Finding Prospects & Nurturing Relationships With Your Future Clients On Auto-Pilot.</strong>
			</h1>
			<div class="sortable-container text-center">
                <button class="josh-button" type="button" data-toggle="modal" data-target="#loginModal">Sign In</button>
			</div>
		</div>
	</div>
	
	<div data-name="Section 1" class="section section-1 role-element leadstyle-container" id="section-1" data-layout-order="2">
		<div class="container">
			<div class=" text-center">
				<h2 data-name="Text" class="role-element leadstyle-text" data-layout-order="0">
					<span class="leadstyle-fontsized">Small business owners, sales professionals and affiliate marketers are taking advantage of Facebook and Twitter with Social Sonic to grow their pipeline, increase sales conversions &amp; strike JV partnerships.</span>
				</h2>
				<a data-name="Link" class="role-element leadstyle-link">Generate Virtually Unlimited Leads Using Social Sonic</a>
			</div>
		</div>
	</div>
	
	<div data-name="Section 2" class="section section-2 role-element leadstyle-container" id="section-2" data-layout-order="3">
		<div data-name="Background" class="background role-element leadstyle-container"></div>
		<div class="container">
			<div class="sortable-container">
				<div class="role-element leadstyle-container text-center" data-name="Column 1" data-layout-order="1">
					<h2 data-name="Text 2" class="role-element leadstyle-text">
						<span class="left"></span>
						<span class="center">What if Your Pipeline Could Grow Without Any Advertising Cost?</span>
						<span class="right"></span>
					</h2>
					<p data-name="Text" class="role-element leadstyle-text">Social Sonic is the leading social media marketing application which acquires targeted traffic every day.</p>
					<div data-name="Column 1" class="role-element leadstyle-container column-1" data-layout-order="0">
						<span><img src="<?php echo SITE_URL; ?>images/web.png" alt="" /></span>
						<div class="title">2 MILLION+</div>
						<p data-name="Text" class="role-element leadstyle-text">
							<strong>Over 2 million traffic opportunities identified</strong>
						</p>
					</div>
					<div data-name="Column 2" class="role-element leadstyle-container column-1" data-layout-order="1">
						<span><img src="<?php echo SITE_URL; ?>images/people.png" alt="" /></span>
						<div class="title">1,000s</div>
						<p data-name="Text" class="role-element leadstyle-text">
							<strong>Thousands of new relationships nurtured every minute</strong>
						</p>
					</div>
					<div data-name="Column 3" class="role-element leadstyle-container column-1" data-layout-order="2">
						<span><img src="<?php echo SITE_URL; ?>images/clock.png" alt="" /></span>
						<div class="title">100s</div>
						<p data-name="Text" class="role-element leadstyle-text">
							<strong>Hundreds of appointments scheduled every week</strong>
						</p>
					</div>
					<div data-name="Column 4" class="role-element leadstyle-container column-1" data-layout-order="3">
						<span><img src="<?php echo SITE_URL; ?>images/man.png" alt="" /></span>
						<div class="title">10,000s</div>
						<p data-name="Text" class="role-element leadstyle-text">
							<strong>Tens of thousands of prospects generated every 24 hours</strong>
						</p>
					</div>
					<div data-name="Column 5" class="role-element leadstyle-container column-1" data-layout-order="4">
						<span><img src="<?php echo SITE_URL; ?>images/money.png" alt="" /></span>
						<div class="title">$100,000s</div>
						<p data-name="Text" class="role-element leadstyle-text">
							<strong>Hundreds of thousands of dollars in advertising expenses saved every month</strong>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div data-name="Section 3" class="section section-3 role-element leadstyle-container" id="section-3" data-layout-order="4">
		<div data-name="Background" class="background role-element leadstyle-container"></div>
		<div class="container">
			<div class="row flexible sortable-container">
				<div class="col-md-3 role-element leadstyle-container" data-name="Column 1" data-layout-order="0">
					<img src="<?php echo SITE_URL; ?>images/Stuart-Murray.png" data-name="Image" class="role-element leadstyle-image">
				</div>
				<div class="col-md-9 role-element leadstyle-container" data-name="Column 2" data-layout-order="1">
					<p data-name="Text" class="role-element leadstyle-text">"We've nearly doubled our traffic to blogs since we started using Social Sonic. The best part is we don&#8217;t have to spend a penny on ads or even spend a thought on working on SEO"</p>
					<h3 data-name="Text" class="role-element leadstyle-text">Stuart Murray, <span class="designation">Founder, MoshiMobi</span></h3>
				</div>
			</div>
		</div>
	</div>
	
	<div data-name="Section 4" class="section section-4 role-element leadstyle-container" id="section-4" data-layout-order="5">
		<div data-name="Background" class="background role-element leadstyle-container"></div>
		<div class="container">
			<div class="sortable-container text-center">
				<h2 data-name="Text 1" class="role-element leadstyle-text" data-layout-order="0">What If You Could Get An Alert When Someone Was Interested In Your Service on Social Media?</h2>
				<p data-name="Text 2" class="role-element leadstyle-text" data-layout-order="1">Don’t let the opportunity to engage with a hot lead go, especially when they are all ready to opt in or purchase. Know it, as soon as the client indicates interest.</p>
				<p data-name="Text 3" class="role-element leadstyle-text" data-layout-order="2">Social Sonic connects you with hot leads when they may be<br />looking to buy from you or your competitors.</p>
			</div>
		</div>
	</div>
	
	<div data-name="Section 5" class="section section-5 role-element leadstyle-container" id="section-5" data-layout-order="6">
		<div data-name="Background" class="background role-element leadstyle-container"></div>
		<div class="container">
			<h2 data-name="Title" class="text-center role-element leadstyle-text">Are you struggling to establish yourself as an authority among your prospects?</h2>
			<p data-name="Text 1" class="role-element leadstyle-text text-center">Social Sonic helps your authority blogs, articles or website get in front of your target market when they’re talking about your niche.</p>
			<div class="row flexible sortable-container">
				<div class="col-md-4 text-center role-element leadstyle-container" data-name="Column 1" data-layout-order="0">
					<img src="<?php echo SITE_URL; ?>images/network.jpg" data-name="Image" class="role-element leadstyle-image">
				</div>
				<div class="col-md-8 role-element leadstyle-container" data-name="Column 2" data-layout-order="1">
					<p data-name="Text 2" class="role-element leadstyle-text">The last blog you’ve published, is it on #1 page of Google search? Most likely it isn’t. Do you know what percentage of Google users go to the next page to find what they’re looking for? Less than 5%. Writing SEO-optimized articles is a frustrating, painful and time-consuming task. Even if you learn the most ninja tricks, there is no guarantee you can get your articles to rank on the first page of Google. Then how do you come out of obscurity and get attention of your target market and become an authority?</p>
					<p data-name="Text 3" class="role-element leadstyle-text">Social Sonic’s proprietary traffic technology alerts you when people may actually be looking for what you are selling.</p>
				</div>
			</div>
		</div>
	</div>
	
	<div data-name="Section 13" class="section section-13 role-element leadstyle-container" id="section-13" data-layout-order="14">
		<div data-name="Background" class="background role-element leadstyle-container"></div>
		<div class="container">
			<div class="row flexible sortable-container">
				<div class="col-md-6">
					<div class="row">
						<div class="col-md-3 role-element leadstyle-container" data-name="Column 1" data-layout-order="0">
							<img src="<?php echo SITE_URL; ?>images/Roy-Meyers.png" data-name="Image" class="role-element leadstyle-image">
						</div>
						<div class="col-md-9 role-element leadstyle-container" data-name="Column 2" data-layout-order="1">
							<p data-name="Text" class="role-element leadstyle-text">"We found out that the traffic generated via Social Sonic is 3 times as engaged as from any other channel. Thank you Social Sonic"</p>
							<h3 data-name="Text" class="role-element leadstyle-text">Roy Meyers, <span class="designation">Real Estate Consultant</span></h3>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="row">
						<div class="col-md-3 role-element leadstyle-container" data-name="Column 1" data-layout-order="0">
							<img src="<?php echo SITE_URL; ?>images/Elise-C-Quevedo.png" data-name="Image" class="role-element leadstyle-image">
						</div>
						<div class="col-md-9 role-element leadstyle-container" data-name="Column 2" data-layout-order="1">
							<p data-name="Text" class="role-element leadstyle-text">"Social Sonic has saved hundreds of hours of my team’s time which was spent on marketing on Twitter. The “Find Prospect” feature is my team’s favourite."</p>
							<h3 data-name="Text" class="role-element leadstyle-text">Elise C Quevedo, <span class="designation">Celebrity Social Media Strategist</span></h3>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div data-name="Section 14" class="section section-14 role-element leadstyle-container" id="section-14" data-layout-order="15">
		<div data-name="Background" class="background role-element leadstyle-container"></div>
		<div class="container">
			<div class="text-center">
				<a data-name="Link" class="role-element leadstyle-link">Generate Unlimited Leads Using Social Sonic.</a>
			</div>
		</div>
	</div>
	
	<div data-name="Pricing" class="section pricing role-element leadstyle-container" id="pricing" data-layout-order="17">
		<div class="overlay" style="opacity:0.25;"></div>
		<div class="container">
			<h1 data-name="Title" class="text-center role-element leadstyle-text">Get started now!</h1>
            <table class="table role-element leadstyle-container" data-name="Table">
                <tbody>
                    <tr data-name="Row" class="role-element leadstyle-container fpr">
                        <td data-name="Text" class="text-center role-element leadstyle-container">
                            <h2 data-name="Title" class="role-element leadstyle-text">$397/month</h2>
                            <p data-name="Text" class="role-element leadstyle-text"><small>Until you cancel</small><br />Starter</p>
                            <button class="josh-button-bottom" type="button" href="#">COMING SOON</button>
                        </td>

                        <td data-name="Text" class="text-center role-element leadstyle-container">
                            <h2 data-name="Title" class="role-element leadstyle-text">$797/month</h2>
                            <p data-name="Text" class="role-element leadstyle-text"><small>Until you cancel</small><br />Premium</p>
                            <button class="josh-button-bottom" type="button" href="#">COMING SOON</button>
                        </td>
                    
                        <td data-name="Text" class="text-center role-element leadstyle-container">
                            <h2 data-name="Title" class="role-element leadstyle-text">$997/month</h2>
                            <p data-name="Text" class="role-element leadstyle-text"><small>Until you cancel</small><br />Enterprise</p>
                            <button class="josh-button-bottom" type="button" href="#">COMING SOON</button>
                        </td>
                    </tr>
                </tbody>
            </table>
		</div><br /><br /><br /><br /><br />
	</div>
	
	<div data-name="Footer" class="section footer role-element leadstyle-container" id="footer" data-layout-order="19">
		<div data-name="Custom Background" class="custom-background role-element leadstyle-container">
			<img src="<?php echo SITE_URL; ?>images/image-1045102834.jpg" data-name="Image" class="role-element leadstyle-image">
		</div>
		<div data-name="Background" class="background role-element leadstyle-container"></div>
		<div class="overlay"></div>
		<div class="container">
			<div>
				<ul>
					<li><a class="small role-element leadstyle-link" data-name="Link"><b>SOCIAL SONIC</b>&nbsp;&nbsp;Copyright &#169; 2016 - ALL RIGHTS RESERVED</a></li>
				</ul>
			</div>
		</div>
		<div class="container">
			<div>
				<ul>
					<li><a href="http://socialsoniccrm.net/privacy.html" target="_blank">Privacy Policy</a></li>
					<li>||</li>
					<li><a href="http://socialsoniccrm.net/terms.html" target="_blank">Terms of Use</a></li>
					<li>||</li>
					<li><a href="http://socialsoniccrm.net/earnings.html" target="_blank">Earnings Disclaimer</a></li>
					<li>||</li>
					<li><a href="http://socialsoniccrm.net/legal.html" target="_blank">Legal</a></li>
					<li>||</li>
					<li><a href="http://socialsoniccrm.net/testimonial.html" target="_blank">Testimonials & Results</a></li>
					<li>||</li>
					<li><a href="http://socialsoniccrm.net/new-business.html" target="_blank">New Business</a></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<?php require_once(dirname(__FILE__) . '/templates/footer.php'); ?>