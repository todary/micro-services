<?php
$score_identity =  array(
  array('type' => 'adr','title' => 'Address','score' => '0.25'),
  array('type' => 'age','title' => 'Age','score' => '0.25'),
  array('type' => 'cm','title' => 'Company','score' => '0.3'),
  array('type' => 'dob','title' => 'Date of Birth','score' => '0.3'),
  array('type' => 'em','title' => 'Email','score' => '1'),
  array('type' => 'exct','title' => 'Exact City','score' => '0.3'),
  array('type' => 'exct-bg','title' => 'Exact Bg City','score' => '0.3'),
  array('type' => 'exct-sm','title' => 'Exact Sm City','score' => '0.6'),
  array('type' => 'fn','title' => 'First Name','score' => '0.25'),
  array('type' => 'fzn','title' => 'Fuzzy Name','score' => '0.2'),
  array('type' => 'ln','title' => 'Last Name','score' => '0.25'),
  array('type' => 'mn','title' => 'Middle Name','score' => '0.25'),
  array('type' => 'onlyOne','title' => 'OnlyOne profile','score' => '0.1'),
  array('type' => 'pct','title' => 'Partial City','score' => '0.25'),
  array('type' => 'ph','title' => 'Phone','score' => '0.5'),
  array('type' => 'pic','title' => 'Profile Picture','score' => '0.05'),
  array('type' => 'rltvWith','title' => 'Relative Found','score' => '0.5'),
  array('type' => 'sc','title' => 'School','score' => '0.3'),
  array('type' => 'st','title' => 'State','score' => '0.25'),
  array('type' => 'un','title' => 'Username','score' => '0.5')
);

$score_results_count = array(
  array('id' => '1','from_num' => null,'to_num' => '10','score' => '0'),
  array('id' => '2','from_num' => '10','to_num' => '35','score' => '0.25'),
  array('id' => '3','from_num' => '35','to_num' => '150','score' => '1'),
  array('id' => '4','from_num' => '150','to_num' => '250','score' => '0.25'),
  array('id' => '5','from_num' => '200','to_num' => '300','score' => '0')
);

$score_search = array(
  array('id' => '1','key' => 'address','title' => 'Adresses Found','score' => '0.08'),
  array('id' => '2','key' => 'avereage_sources','title' => 'Average Score of Sources','score' => '0.275'),
  array('id' => '3','key' => 'email','title' => 'Email Found','score' => '0.05'),
  array('id' => '4','key' => 'facebook','title' => 'Facebook Found','score' => '0.08'),
  array('id' => '5','key' => 'googleplus','title' => 'Google+','score' => '0.015'),
  array('id' => '6','key' => 'instagram','title' => 'Instagram Found','score' => '0.015'),
  array('id' => '7','key' => 'linkedin','title' => 'LinkedIn Found','score' => '0.08'),
  array('id' => '8','key' => 'relatives','title' => 'Relatives Found','score' => '0.08'),
  array('id' => '9','key' => 'result_count','title' => 'Result Count','score' => '0.15'),
  array('id' => '10','key' => 'twitter','title' => 'Twitter Found','score' => '0.08'),
  array('id' => '11','key' => 'websites','title' => 'Personal Websites Found','score' => '0.08'),
  array('id' => '12','key' => 'youtube','title' => 'Youtube Found','score' => '0.015')
);

$score_single_result = array(
  array('key' => 'identity','title' => 'Identity','score' => '0.4'),
  array('key' => 'result_count','title' => 'Result Count','score' => '0.2'),
  array('key' => 'source','title' => 'Source','score' => '0.2'),
  array('key' => 'source_type','title' => 'Source Type','score' => '0.2')
);

$score_sources = array(
  array('source' => '10digits','score' => '0'),
  array('source' => '411','score' => '0'),
  array('source' => '411locate','score' => '0'),
  array('source' => 'courtcasefinder','score' => '0'),
  array('source' => 'google_not_listed','score' => '0'),
  array('source' => 'intelius','score' => '0'),
  array('source' => 'peekyou','score' => '0'),
  array('source' => 'peoplesmart','score' => '0'),
  array('source' => 'pipl','score' => '0'),
  array('source' => 'radaris','score' => '0'),
  array('source' => 'spokeo','score' => '0'),
  array('source' => 'websites','score' => '0'),
  array('source' => 'myspace','score' => '0.5'),
  array('source' => 'profile_not_listed','score' => '0.5'),
  array('source' => 'vimeo','score' => '0.7'),
  array('source' => 'googleplus','score' => '0.8'),
  array('source' => 'instagram','score' => '0.8'),
  array('source' => 'meetup','score' => '0.8'),
  array('source' => 'pinterest','score' => '0.8'),
  array('source' => 'top_sites','score' => '0.8'),
  array('source' => 'vine','score' => '0.8'),
  array('source' => 'facebook','score' => '1'),
  array('source' => 'flickr','score' => '1'),
  array('source' => 'linkedin','score' => '1'),
  array('source' => 'lookup_list','score' => '1'),
  array('source' => 'photostream','score' => '1'),
  array('source' => 'twitter','score' => '1'),
  array('source' => 'youtube','score' => '1')
);

$score_type = array(
  array('type' => 'comment','title' => 'Comment/ Status','score' => '0.8'),
  array('type' => 'dir_list','title' => 'Directory List','score' => '0'),
  array('type' => 'html','title' => 'Extension HTML','score' => '0'),
  array('type' => 'list','title' => 'List','score' => '0.5'),
  array('type' => 'photo','title' => 'Photo','score' => '0.8'),
  array('type' => 'profile','title' => 'Profile','score' => '1'),
  array('type' => 'relative','title' => 'Relative','score' => '0.7'),
  array('type' => 'video','title' => 'Video','score' => '0.8')
);

$main_source = array(
  array('id' => '0','name' => '-','list_order' => '0'),
  array('id' => '1','name' => '411locate','list_order' => '105'),
  array('id' => '2','name' => 'angel','list_order' => '75'),
  array('id' => '3','name' => 'courtcasefinder','list_order' => '130'),
  array('id' => '4','name' => 'custom','list_order' => '0'),
  array('id' => '5','name' => 'facebook','list_order' => '5'),
  array('id' => '6','name' => 'flickr','list_order' => '75'),
  array('id' => '7','name' => 'foursquare','list_order' => '30'),
  array('id' => '8','name' => 'fullcontact','list_order' => '150'),
  array('id' => '9','name' => 'gcse','list_order' => '0'),
  array('id' => '10','name' => 'getMoreInfo','list_order' => '75'),
  array('id' => '11','name' => 'google','list_order' => '75'),
  array('id' => '12','name' => 'googleimages','list_order' => '0'),
  array('id' => '13','name' => 'instagram','list_order' => '30'),
  array('id' => '14','name' => 'intelius','list_order' => '120'),
  array('id' => '15','name' => 'linkedin','list_order' => '15'),
  array('id' => '16','name' => 'lookup','list_order' => '880'),
  array('id' => '17','name' => 'myspace','list_order' => '40'),
  array('id' => '18','name' => 'pandora','list_order' => '0'),
  array('id' => '19','name' => 'peekyou','list_order' => '180'),
  array('id' => '20','name' => 'pinterest','list_order' => '50'),
  array('id' => '21','name' => 'pipl','list_order' => '100'),
  array('id' => '22','name' => 'reverse','list_order' => '0'),
  array('id' => '23','name' => 'slideshare','list_order' => '72'),
  array('id' => '24','name' => 'soundcloud','list_order' => '40'),
  array('id' => '25','name' => 'spokeo','list_order' => '110'),
  array('id' => '26','name' => 'storeWorkExperience','list_order' => '0'),
  array('id' => '27','name' => 'stumbleupon','list_order' => '0'),
  array('id' => '28','name' => 'tendigits','list_order' => '70'),
  array('id' => '29','name' => 'tloxp','list_order' => '0'),
  array('id' => '30','name' => 'twitter','list_order' => '10'),
  array('id' => '31','name' => 'twitterapi','list_order' => '0'),
  array('id' => '32','name' => 'twitterstatus','list_order' => '10'),
  array('id' => '33','name' => 'twitter_images','list_order' => '200'),
  array('id' => '34','name' => 'username','list_order' => '190'),
  array('id' => '35','name' => 'usernames','list_order' => '190'),
  array('id' => '36','name' => 'websites','list_order' => '999'),
  array('id' => '37','name' => 'websites_work_experience','list_order' => '5'),
  array('id' => '38','name' => 'whitepages','list_order' => '0'),
  array('id' => '39','name' => 'yellowpages','list_order' => '300'),
  array('id' => '40','name' => 'youtube','list_order' => '60'),
  array('id' => '41','name' => 'googleplus','list_order' => '200'),
  array('id' => '42','name' => 'picasaweb','list_order' => '220'),
  array('id' => '43','name' => 'metacafe','list_order' => '220'),
  array('id' => '44','name' => 'reddit','list_order' => '220'),
  array('id' => '45','name' => 'fiverr','list_order' => '220'),
  array('id' => '46','name' => 'imgur','list_order' => '220'),
  array('id' => '47','name' => 'etsy','list_order' => '220'),
  array('id' => '48','name' => 'flipboard','list_order' => '220'),
  array('id' => '49','name' => 'vimeo','list_order' => '220'),
  array('id' => '50','name' => 'f6s','list_order' => '220'),
  array('id' => '51','name' => 'wordpress','list_order' => '220'),
  array('id' => '52','name' => 'medium','list_order' => '220'),
  array('id' => '53','name' => 'dailymotion','list_order' => '220'),
  array('id' => '54','name' => 'meetup','list_order' => '220'),
  array('id' => '55','name' => 'yelp','list_order' => '220'),
  array('id' => '56','name' => 'behance','list_order' => '220'),
  array('id' => '57','name' => 'producthunt','list_order' => '300'),
  array('id' => '58','name' => 'ebay','list_order' => '300'),
  array('id' => '59','name' => 'scribd','list_order' => '300'),
  array('id' => '60','name' => 'drupal','list_order' => '300'),
  array('id' => '61','name' => 'twitpic','list_order' => '300'),
  array('id' => '62','name' => 'hubpages','list_order' => '300'),
  array('id' => '63','name' => 'github','list_order' => '300'),
  array('id' => '64','name' => 'dribbble','list_order' => '300'),
  array('id' => '65','name' => 'deviantart','list_order' => '300'),
  array('id' => '66','name' => 'steamcommunity','list_order' => '300'),
  array('id' => '67','name' => 'plancast','list_order' => '300'),
  array('id' => '68','name' => 'about.me','list_order' => '300'),
  array('id' => '69','name' => 'tripadvisor','list_order' => '300'),
  array('id' => '70','name' => 'quora','list_order' => '300'),
  array('id' => '71','name' => 'lifestream.aol','list_order' => '300'),
  array('id' => '72','name' => 'twitch','list_order' => '300'),
  array('id' => '73','name' => 'vine','list_order' => '300'),
  array('id' => '74','name' => 'photobucket','list_order' => '300'),
  array('id' => '75','name' => 'kik.me','list_order' => '300'),
  array('id' => '76','name' => 'bitly','list_order' => '300'),
  array('id' => '77','name' => 'okcupid','list_order' => '300'),
  array('id' => '78','name' => 'instructables','list_order' => '300'),
  array('id' => '79','name' => 'gravatar','list_order' => '300'),
  array('id' => '80','name' => 'keybase','list_order' => '300'),
  array('id' => '81','name' => 'kongregate','list_order' => '300'),
  array('id' => '82','name' => '8tracks','list_order' => '300'),
  array('id' => '83','name' => 'wired','list_order' => '300'),
  array('id' => '84','name' => 'tunein','list_order' => '300'),
  array('id' => '85','name' => 'picsart','list_order' => '300'),
  array('id' => '86','name' => 'get.google','list_order' => '300'),
  array('id' => '87','name' => 'last.fm','list_order' => '300'),
  array('id' => '88','name' => '500px','list_order' => '300'),
  array('id' => '89','name' => '9gag','list_order' => '300'),
  array('id' => '90','name' => 'ustream.tv','list_order' => '300'),
  array('id' => '116','name' => 'path','list_order' => '300'),
  array('id' => '117','name' => 'disqus','list_order' => '300'),
  array('id' => '118','name' => 'findthecompany','list_order' => '300'),
  array('id' => '119','name' => 'livejournal','list_order' => '300'),
  array('id' => '120','name' => 'tumblr','list_order' => '300'),
  array('id' => '121','name' => 'PPC500px','list_order' => '300'),
  array('id' => '122','name' => 'fastcompany','list_order' => '300'),
  array('id' => '123','name' => 'squarespace','list_order' => '300'),
  array('id' => '124','name' => 'input','list_order' => '130')
);

$source = array(
  array('id' => '1','name' => '-','main_source_id' => '0'),
  array('id' => '2','name' => 'after_facebook','main_source_id' => '5'),
  array('id' => '3','name' => 'after_instagram','main_source_id' => '13'),
  array('id' => '4','name' => 'after_linkedin','main_source_id' => '15'),
  array('id' => '5','name' => 'angel','main_source_id' => '2'),
  array('id' => '6','name' => 'angel_profiles_linked','main_source_id' => '2'),
  array('id' => '8','name' => 'courtcasefinder','main_source_id' => '3'),
  array('id' => '9','name' => 'custom','main_source_id' => '4'),
  array('id' => '10','name' => 'extract_facebook_images','main_source_id' => '5'),
  array('id' => '11','name' => 'extract_facebook_posts','main_source_id' => '5'),
  array('id' => '12','name' => 'extract_instagram_photos','main_source_id' => '13'),
  array('id' => '13','name' => 'extract_linkedin_endorsers','main_source_id' => '15'),
  array('id' => '14','name' => 'extract_twitter_media','main_source_id' => '30'),
  array('id' => '15','name' => 'extract_youtube','main_source_id' => '40'),
  array('id' => '17','name' => 'facebook_by_company','main_source_id' => '5'),
  array('id' => '18','name' => 'facebook_by_relatives','main_source_id' => '5'),
  array('id' => '19','name' => 'facebook_by_school','main_source_id' => '5'),
  array('id' => '20','name' => 'facebook_images','main_source_id' => '5'),
  array('id' => '21','name' => 'facebook_live_in','main_source_id' => '5'),
  array('id' => '22','name' => 'facebook_phone','main_source_id' => '5'),
  array('id' => '23','name' => 'facebook_posts','main_source_id' => '5'),
  array('id' => '24','name' => 'facebook_public_posts','main_source_id' => '5'),
  array('id' => '25','name' => 'facebook_relative','main_source_id' => '5'),
  array('id' => '26','name' => 'facebook_uniquename','main_source_id' => '5'),
  array('id' => '27','name' => 'facebook_url','main_source_id' => '5'),
  array('id' => '28','name' => 'facebook_username','main_source_id' => '5'),
  array('id' => '29','name' => 'flickr','main_source_id' => '6'),
  array('id' => '30','name' => 'flickr_by_username','main_source_id' => '6'),
  array('id' => '31','name' => 'foursquare','main_source_id' => '7'),
  array('id' => '32','name' => 'foursquare_facebook','main_source_id' => '5'),
  array('id' => '33','name' => 'foursquare_front','main_source_id' => '7'),
  array('id' => '34','name' => 'foursquare_profiles_linked','main_source_id' => '7'),
  array('id' => '35','name' => 'fullcontact','main_source_id' => '8'),
  array('id' => '36','name' => 'gcse','main_source_id' => '9'),
  array('id' => '37','name' => 'getmoreinfo','main_source_id' => '10'),
  array('id' => '38','name' => 'google','main_source_id' => '11'),
  array('id' => '39','name' => 'googleimages','main_source_id' => '12'),
  array('id' => '40','name' => 'googleplus','main_source_id' => '11'),
  array('id' => '41','name' => 'googleplus_profiles_linked','main_source_id' => '11'),
  array('id' => '42','name' => 'googleplus_uniquename','main_source_id' => '11'),
  array('id' => '43','name' => 'google_linkedin','main_source_id' => '11'),
  array('id' => '44','name' => 'google_uniquename','main_source_id' => '11'),
  array('id' => '45','name' => 'google_usernames','main_source_id' => '11'),
  array('id' => '46','name' => 'instagram','main_source_id' => '13'),
  array('id' => '47','name' => 'instagram_images','main_source_id' => '13'),
  array('id' => '48','name' => 'instagram_images_custom','main_source_id' => '13'),
  array('id' => '49','name' => 'intelius','main_source_id' => '14'),
  array('id' => '50','name' => 'linkedin','main_source_id' => '15'),
  array('id' => '51','name' => 'linkedin_email','main_source_id' => '15'),
  array('id' => '52','name' => 'linkedin_uniquename','main_source_id' => '15'),
  array('id' => '53','name' => 'locate411','main_source_id' => '1'),
  array('id' => '54','name' => 'lookup','main_source_id' => '16'),
  array('id' => '55','name' => 'myspace','main_source_id' => '17'),
  array('id' => '58','name' => 'peekyou','main_source_id' => '19'),
  array('id' => '59','name' => 'pinterest','main_source_id' => '20'),
  array('id' => '60','name' => 'pinterest_facebook','main_source_id' => '5'),
  array('id' => '61','name' => 'pipl','main_source_id' => '21'),
  array('id' => '62','name' => 'Pipl.api','main_source_id' => '21'),
  array('id' => '63','name' => 'reverse_-_custom','main_source_id' => '22'),
  array('id' => '64','name' => 'reverse_facebook_custom','main_source_id' => '22'),
  array('id' => '65','name' => 'reverse_fullcontact_custom','main_source_id' => '22'),
  array('id' => '66','name' => 'reverse_fullcontact_twitter_custom','main_source_id' => '22'),
  array('id' => '67','name' => 'reverse_google_facebook_custom','main_source_id' => '22'),
  array('id' => '68','name' => 'reverse_google_github_custom','main_source_id' => '22'),
  array('id' => '69','name' => 'reverse_google_googleplus_custom','main_source_id' => '22'),
  array('id' => '70','name' => 'reverse_google_instagram_custom','main_source_id' => '22'),
  array('id' => '71','name' => 'reverse_google_linkedin_custom','main_source_id' => '22'),
  array('id' => '72','name' => 'reverse_google_twitter_custom','main_source_id' => '22'),
  array('id' => '73','name' => 'reverse_instagram_custom','main_source_id' => '22'),
  array('id' => '74','name' => 'reverse_linkedin_custom','main_source_id' => '22'),
  array('id' => '75','name' => 'reverse_myspace_custom','main_source_id' => '22'),
  array('id' => '76','name' => 'reverse_pipl.api_custom','main_source_id' => '22'),
  array('id' => '77','name' => 'reverse_pipl_custom','main_source_id' => '22'),
  array('id' => '78','name' => 'reverse_tloxp_custom','main_source_id' => '22'),
  array('id' => '79','name' => 'reverse_tripadvisor_custom','main_source_id' => '22'),
  array('id' => '80','name' => 'reverse_twitter_custom','main_source_id' => '22'),
  array('id' => '81','name' => 'reverse_whitepages_custom','main_source_id' => '22'),
  array('id' => '82','name' => 'reverse__custom','main_source_id' => '22'),
  array('id' => '83','name' => 'slideshare','main_source_id' => '23'),
  array('id' => '84','name' => 'slideshare_profiles_linked','main_source_id' => '23'),
  array('id' => '85','name' => 'slideshare_uniquename','main_source_id' => '23'),
  array('id' => '86','name' => 'soundcloud','main_source_id' => '24'),
  array('id' => '87','name' => 'spokeo','main_source_id' => '25'),
  array('id' => '88','name' => 'tendigits','main_source_id' => '28'),
  array('id' => '89','name' => 'tloxp','main_source_id' => '29'),
  array('id' => '90','name' => 'twitter','main_source_id' => '30'),
  array('id' => '91','name' => 'twitterapi','main_source_id' => '30'),
  array('id' => '92','name' => 'twitterstatus','main_source_id' => '30'),
  array('id' => '93','name' => 'twitter_images','main_source_id' => '30'),
  array('id' => '94','name' => 'usernames','main_source_id' => '35'),
  array('id' => '95','name' => 'websites','main_source_id' => '36'),
  array('id' => '96','name' => 'websites(facebook)','main_source_id' => '5'),
  array('id' => '97','name' => 'websites_experience','main_source_id' => '36'),
  array('id' => '98','name' => 'websites_work_experience','main_source_id' => '26'),
  array('id' => '99','name' => 'whitepages','main_source_id' => '38'),
  array('id' => '100','name' => 'yellowpages','main_source_id' => '39'),
  array('id' => '101','name' => 'youtube','main_source_id' => '40'),
  array('id' => '102','name' => 'googleplus','main_source_id' => '41'),
  array('id' => '103','name' => 'instagram_username','main_source_id' => '13'),
  array('id' => '104','name' => 'f6s','main_source_id' => '50'),
  array('id' => '105','name' => 'vimeo','main_source_id' => '49'),
  array('id' => '106','name' => 'facebook_init','main_source_id' => '5'),
  array('id' => '107','name' => 'website_owner','main_source_id' => '36'),
  array('id' => '109','name' => 'facebook_friends','main_source_id' => '5'),
  array('id' => '110','name' => 'facebook_by_email','main_source_id' => '5'),
  array('id' => '111','name' => 'facebook_people_search','main_source_id' => '5'),
  array('id' => '112','name' => 'input','main_source_id' => '124'),
  array('id' => '113','name' => 'facebook_in_friends','main_source_id' => '5')
);

$persons = array(
  array('id' => '5','first_name' => 'hector','middle_name' => 'arturo,a','last_name' => 'moreno','date_of_birth' => '02/12/1975','age' => '42','address' => '12111 Louis Ave, Whittier, CA+10430 Nashville Avenue, Whittier, CA+13346 Reis St, Whittier, CA','street' => '12111 Louis Ave+10430 Nashville Avenue+13346 Reis St','city' => 'Whittier, CA','state' => NULL,'country' => '','city_status' => '0','zip' => NULL,'phone' => '(562) 556-8374,(562) 556-8377,(562) 964-8960','company' => NULL,'email' => NULL,'usernames' => '','added_usernames' => '','school' => NULL,'all_count' => '0','current' => '0','completed' => '0','combinations' => '0','current_combination' => '0','started' => '0','case_number' => '0','user_id' => '1','corporate_id' => NULL,'is_paid' => '0','real_start_date' => '0','insert_date' => '1505835913','end_date' => '0','schedule_interval' => '0','has_error' => '0','schedule_now' => '0','google_exc' => '0','search_combinations' => NULL,'func' => '','full_name' => 'hector arturo moreno,hector a moreno,hector moreno','searched_names' => 'hector arturo moreno,hector a moreno,hector moreno','added_emails' => '','invoice_id' => NULL,'service_id' => '2','cost' => '0','paid_amount' => '0','reference' => '00000000','is_api' => '0','api_options' => NULL,'is_deleted' => '0','is_hidden' => '1','search_origin' => 'search','search_type' => 'full','reverse_source' => NULL,'reverse_url' => NULL,'user_ip' => '::1','user_agent' => 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.116 Safari/537.36','version' => 'L','search_dateline' => '2017-09-19 17:36:32','last_combination_run' => NULL,'profiles_in_results' => '1','is_charge' => '0','info_score' => '[]','score' => '0','is_rescan' => '0','rescan_enabled' => '0','rescan_count' => '0','rescan_allowed_count' => '12','rescan_settings' => '','rescan_done' => '0','rescan_type' => '0','rescan_from_id' => NULL,'rescan_expires' => NULL,'number_of_changes' => '0','is_comb_proceeded' => '0','is_premium_search' => '0','department_id' => NULL,'track_number' => '0','upgraded_to_premium' => '0','search_credit_count' => '1','search_analyst_status' => '0','on_complete_start_minute' => '0','on_complete_log_stream' => NULL,'is_public' => '0','sub_used_plan_id' => NULL,'sub_used_price_id' => NULL,'sub_used_addon_id' => NULL,'sub_is_extra_plan' => NULL,'sub_is_extra_addon' => NULL,'sub_is_premium' => NULL,'sub_is_extra_credit' => '0','sub_original_cost' => '0','is_void' => '0','show_void_label' => '0','void_reason' => NULL,'filters' => NULL,'init_data' => '{"person_id":"59948","report_id":"PXND-MV35","link":"tloxp","modified":true,"first_name":"Hector","middle_name":"","last_name":"Moreno","location":"Whittier, CA","street":"11732 Louis Ave Apt 4, Whittier, CA","address":"11732 Louis Ave Apt 4","city":"Whittier","state":"CA","zip":"90605","age":"","other_names":[],"phones":[],"emails":"","relatives":[],"addresses":[],"source":"tloxp","comb_fields":{"FullName":"hector moreno","Address":{"Line1":"13346 Reis St","City":"Whittier","State":"CA"},"Phone":"562 964 8960","UseExactFirstNameMatch":"No"},"searchType":"full","tloxp_recall":true}','view_settings' => NULL),
  array('id' => '12555','first_name' => 'hector','middle_name' => 'arturo,a','last_name' => 'moreno','date_of_birth' => '02/12/1975','age' => '42','address' => '12111 Louis Ave, Whittier, CA+10430 Nashville Avenue, Whittier, CA+13346 Reis St, Whittier, CA','street' => '12111 Louis Ave+10430 Nashville Avenue+13346 Reis St','city' => 'Whittier, CA','state' => NULL,'country' => '','city_status' => '0','zip' => NULL,'phone' => '(562) 556-8374,(562) 556-8377,(562) 964-8960','company' => NULL,'email' => NULL,'usernames' => '','added_usernames' => '','school' => NULL,'all_count' => '0','current' => '0','completed' => '0','combinations' => '0','current_combination' => '0','started' => '0','case_number' => '0','user_id' => '1','corporate_id' => NULL,'is_paid' => '0','real_start_date' => '0','insert_date' => '1505835913','end_date' => '0','schedule_interval' => '0','has_error' => '0','schedule_now' => '0','google_exc' => '0','search_combinations' => NULL,'func' => '','full_name' => 'hector arturo moreno,hector a moreno,hector moreno','searched_names' => 'hector arturo moreno,hector a moreno,hector moreno','added_emails' => '','invoice_id' => NULL,'service_id' => '2','cost' => '0','paid_amount' => '0','reference' => '00000000','is_api' => '0','api_options' => NULL,'is_deleted' => '0','is_hidden' => '1','search_origin' => 'search','search_type' => 'full','reverse_source' => NULL,'reverse_url' => NULL,'user_ip' => '::1','user_agent' => 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.116 Safari/537.36','version' => 'L','search_dateline' => '2017-09-19 17:36:32','last_combination_run' => NULL,'profiles_in_results' => '1','is_charge' => '0','info_score' => '[]','score' => '0','is_rescan' => '0','rescan_enabled' => '0','rescan_count' => '0','rescan_allowed_count' => '12','rescan_settings' => '','rescan_done' => '0','rescan_type' => '0','rescan_from_id' => NULL,'rescan_expires' => NULL,'number_of_changes' => '0','is_comb_proceeded' => '0','is_premium_search' => '0','department_id' => NULL,'track_number' => '0','upgraded_to_premium' => '0','search_credit_count' => '1','search_analyst_status' => '0','on_complete_start_minute' => '0','on_complete_log_stream' => NULL,'is_public' => '0','sub_used_plan_id' => NULL,'sub_used_price_id' => NULL,'sub_used_addon_id' => NULL,'sub_is_extra_plan' => NULL,'sub_is_extra_addon' => NULL,'sub_is_premium' => NULL,'sub_is_extra_credit' => '0','sub_original_cost' => '0','is_void' => '0','show_void_label' => '0','void_reason' => NULL,'filters' => NULL,'init_data' => '{"person_id":"59948","report_id":"PXND-MV35","link":"tloxp","modified":true,"first_name":"Hector","middle_name":"","last_name":"Moreno","location":"Whittier, CA","street":"11732 Louis Ave Apt 4, Whittier, CA","address":"11732 Louis Ave Apt 4","city":"Whittier","state":"CA","zip":"90605","age":"","other_names":[],"phones":[],"emails":"","relatives":[],"addresses":[],"source":"tloxp","comb_fields":{"FullName":"hector moreno","Address":{"Line1":"13346 Reis St","City":"Whittier","State":"CA"},"Phone":"562 964 8960","UseExactFirstNameMatch":"No"},"searchType":"full","tloxp_recall":true}','view_settings' => NULL)
  );

$banned_domains = array(
  array("id"=>1,"domain"=>"pinterest.com/pin/"),
  array("id"=>2,"domain"=>"instagram.com/p/")
);
