<?php

	// application
	define('_MI_BITLY_NAME','Bit.ly Bomb');
	define('_MI_BITLY_DESCRIPTION','Bit.ly Bomb is a URL Shortening tool for XOOPS');
	define('_MI_BITLY_DIRNAME','bitly');
	
	// menus
	define('_MI_BITLY_MENU_SHORTEN_URL','Shorten URL');
	define('_MI_BITLY_MENU_POPULAR_RSS','Popular URL\'s (Feed)');
	define('_MI_BITLY_MENU_AUTHORISE_ROOT_USER','Authorise Root User');
	define('_MI_BITLY_MENU_AUTHORISE_MY_BITLY','Authorise My Account');
	
	//Preferences
	define('_MI_BITLY_SANITIZER','Enable Sanitization of URLs with Bit.ly');
	define('_MI_BITLY_SANITIZER_DESC','You will have had to had patched the /class folder with the files provided with the package for this to work.');
	define('_MI_BITLY_CLIENT_ID','Bit.ly Client ID - <a href="https://bitly.com/a/create_oauth_app">Get Client ID</a>');
	define('_MI_BITLY_CLIENT_ID_DESC','This is the main application key for OAuth');
	define('_MI_BITLY_CLIENT_SECRET','Bit.ly Client Secret - <a href="https://bitly.com/a/create_oauth_app">Get Client Secret</a>');
	define('_MI_BITLY_CLIENT_SECRET_DESC','This is the main application secret for OAuth');
	define('_MI_BITLY_DOMAIN','Domain to Shorten with?');
	define('_MI_BITLY_DOMAIN_DESC','This is the domain to Shorten with; if you have a bit.ly pro domain then you will have to list it in the box below and select other.');
	define('_MI_BITLY_OTHER_DOMAIN','Pro bit.ly Domain to Shorten with?');
	define('_MI_BITLY_OTHER_DOMAIN_DESC','This is the domain to Shorten with; if you have a bit.ly pro domain then you will have to list it here and select other in the field above.');
	define('_MI_BITLY_CALLBACK_URL','Your site callback URL');
	define('_MI_BITLY_CALLBACK_URL_DESC','Change this if your callback url is SEO or somewhere different than default otherwise don\'t change if your unsure.');
	define('_MI_BITLY_CRAWLNEXT','Crawl next');
	define('_MI_BITLY_CRAWLNEXT_DESC','Number of seconds to wait before an item is crawled again!');
	define('_MI_BITLY_REALTIME_LINKS_KEPT','Realtime Links Records Kept for?');
	define('_MI_BITLY_REALTIME_LINKS_KEPT_DESC','This is how long a realtime link popularity list is kept for!');
	define('_MI_BITLY_LIMIT_ON_CRAWL','Limit on number of person to crawl');
	define('_MI_BITLY_LIMIT_ON_CRAWL_DESC','0 = Unlimited. Maximum number of people to crawl per session!');
	define('_MI_BITLY_CRAWL_SORT','Crawl Sort');
	define('_MI_BITLY_CRAWL_SORT_DESC','This is the sort method for the crawl');
	define('_MI_BITLY_CRAWL_ORDER','Crawl Sort Order');
	define('_MI_BITLY_CRAWL_ORDER_DESC','This is the order of the crawling sort order');
	define('_MI_BITLY_USER_AGENT','Application Useragent');
	define('_MI_BITLY_USER_AGENT_DESC','This is the application Useragent with CURL');
	define('_MI_BITLY_CURL_CONNECT_TIMEOUT','CURL Connection Timeout');
	define('_MI_BITLY_CURL_CONNECT_TIMEOUT_DESC','This is how many second CURL waits to connect via DNS');
	define('_MI_BITLY_CURL_TIMEOUT','CURL Response Timeout');
	define('_MI_BITLY_CURL_TIMEOUT_DESC','This is how many seconds CURL waits for a response.');
	define('_MI_BITLY_CRONTYPE','Cron Execution Type');
	define('_MI_BITLY_CRONTYPE_DESC','This is how the cron is executed!');
	define('_MI_BITLY_CRONTYPE_PRELOADER','Executed via Preloader');
	define('_MI_BITLY_CRONTYPE_CRONTAB','Executed via UNIX Cron Job');
	define('_MI_BITLY_CRONTYPE_SCHEDULER','Executed via Windows Scheduler');
	define('_MI_BITLY_INTERVAL_OF_CRON','Interval of cron');
	define('_MI_BITLY_INTERVAL_OF_CRON_DESC','This is the number seconds a cron will be executed on, generally the interval of the cron is set by this.');
	define('_MI_BITLY_LENGTH_OF_CRON','Length of cron');
	define('_MI_BITLY_LENGTH_OF_CRON_DESC','This is the number seconds a cron will be executed for, interval of the cron is a basis of this variable which should be less.');
	define('_MI_BITLY_CRON_CRAWL','Support Shorten Link Statistical Crawling Cron');
	define('_MI_BITLY_CRON_CRAWL_DESC','When turned on the crawling cron will run');
	define('_MI_BITLY_CRON_POLL','Support Realtime Link Polling Cron');
	define('_MI_BITLY_CRON_POLL_DESC','When turned on the polling cron will run');
	define('_MI_BITLY_SALT','Blowfish Encrytion Salt');
	define('_MI_BITLY_SALT_DESC','Do not change on production machine, this is the salt for encryption and ciphers');
	define('_MI_BITLY_ODDS_LOWER','When odds are lower than this number then it is tails of the odd');
	define('_MI_BITLY_ODDS_LOWER_DESC','When a random is choosen when the number is lower than this it is a tails odd');
	define('_MI_BITLY_ODDS_HIGHER','When odds are higher than this number then it is heads of the odd');
	define('_MI_BITLY_ODDS_HIGHER_DESC','When a random is choosen when the number is higher than this it is a heads odd');
	define('_MI_BITLY_ODDS_MINIMUM','The minimum number odds are pulled from');
	define('_MI_BITLY_ODDS_MINIMUM_DESC','When alot a random this is the minimum number used');
	define('_MI_BITLY_ODDS_MAXIMUM','The maximum number odds are pulled from');
	define('_MI_BITLY_ODDS_MAXIMUM_DESC','When alot a random this is the maximum number used');
	define('_MI_BITLY_HTACCESS','Enabled .htaccess SEO');
	define('_MI_BITLY_HTACCESS_DESC','Turn on when you have placed the redirection text in .htaccess in '.XOOPS_ROOT_PATH);
	define('_MI_BITLY_BASEURL','Base of URL');
	define('_MI_BITLY_BASEURL_DESC','This is the base of the URL for SEO');
	define('_MI_BITLY_ENDOFURL','End of URL for HTML');
	define('_MI_BITLY_ENDOFURL_DESC','This is what the URL ends with for HTML Pages');
	define('_MI_BITLY_ENDOFURLRSS','End of URL for RSS Feeds');
	define('_MI_BITLY_ENDOFURLRSS_DESC','This is what the URL ends with for RSS XML Feeds');
	define('_MI_BITLY_TIME_30MINUTES','30 minutes');
	define('_MI_BITLY_TIME_1HOURS','1 hour');
	define('_MI_BITLY_TIME_2HOURS','2 hours');
	define('_MI_BITLY_TIME_3HOURS','3 hours');
	define('_MI_BITLY_TIME_6HOURS','6 hours');
	define('_MI_BITLY_TIME_12HOURS','12 hours');
	define('_MI_BITLY_TIME_24HOURS','24 hours');
	define('_MI_BITLY_TIME_1WEEK','7 days');
	define('_MI_BITLY_TIME_FORTNIGHT','2 weeks');
	define('_MI_BITLY_TIME_1MONTH','4 weeks');
	define('_MI_BITLY_TIME_2MONTHS','2 months');
	define('_MI_BITLY_TIME_3MONTHS','3 months');
	define('_MI_BITLY_TIME_4MONTHS','4 months');
	define('_MI_BITLY_TIME_5MONTHS','5 months');
	define('_MI_BITLY_TIME_6MONTHS','6 months');
	define('_MI_BITLY_TIME_12MONTHS','12 months');
	define('_MI_BITLY_TIME_24MONTHS','24 months');
	define('_MI_BITLY_CRAWL_SORT_RANDOM','Random Sort');
	define('_MI_BITLY_CRAWL_SORT_CREATED','Creation date/time');
	define('_MI_BITLY_CRAWL_SORT_UPDATED','Updated date/time');
	define('_MI_BITLY_CRAWL_SORT_EXPIRES','Expires date/time');
	define('_MI_BITLY_CRAWL_SORT_CRAWLED','Crawled date/time');
	define('_MI_BITLY_CRAWL_ORDER_ASC','Ascending');
	
	// Global Messages
	define('_MI_BITLY_NO_EMAIL_ADDRESS_WITH_USER','You have no email account associated with your username and password, you will need to specify one now!');
	
	// Admin Menu
	define('_MI_BITLY_TITLE_ADMENU0','Dashboard');
	define('_MI_BITLY_ICON_ADMENU0','../../Frameworks/moduleclasses/icons/32/home.png');
	define('_MI_BITLY_LINK_ADMENU0','admin/dashboard.php');
	define('_MI_BITLY_TITLE_ADMENU1','Shorten URL');
	define('_MI_BITLY_ICON_ADMENU1','images/icons/bitly.shorten.png');
	define('_MI_BITLY_LINK_ADMENU1','admin/shorten.php');
	define('_MI_BITLY_TITLE_ADMENU2','Shortened URLs');
	define('_MI_BITLY_ICON_ADMENU2','images/icons/bitly.urls.png');
	define('_MI_BITLY_LINK_ADMENU2','admin/urls.php');
	define('_MI_BITLY_TITLE_ADMENU3','Popular URLs');
	define('_MI_BITLY_ICON_ADMENU3','images/icons/bitly.popular.png');
	define('_MI_BITLY_LINK_ADMENU3','admin/popular.php');
	define('_MI_BITLY_TITLE_ADMENU4','About');
	define('_MI_BITLY_ICON_ADMENU4','../../Frameworks/moduleclasses/icons/32/about.png');
	define('_MI_BITLY_LINK_ADMENU4','admin/about.php');
	
?>
	