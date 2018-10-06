<?php

use Skopenow\UrlInfo\{ProfileImage, CURL};

class ProfileImageTest extends TestCase
{
    /** @test */
    public function should_return_twitter_profile_image()
    {
        $content = ['body' => '{"page":"<a class="ProfileAvatar-container u-block js-tooltip profile-picture "
        href="https://pbs.twimg.com/profile_images/878491891733549056/3urQeq4j_400x400.jpg"
        title="Mohammed Attya"
        data-resolved-url-large="https://pbs.twimg.com/profile_images/878491891733549056/3urQeq4j_400x400.jpg"
        data-url="https://pbs.twimg.com/profile_images/878491891733549056/3urQeq4j_400x400.jpg"
        target="_blank"
        rel="noopener">
      <img class="ProfileAvatar-image" src="https://pbs.twimg.com/profile_images/878491891733549056/3urQeq4j_400x400.jpg" alt="Mohammed Attya">
    </a>
</div>

        <div class="ProfileAvatarEditing is-withAvatar">
  <div class="ProfileAvatarEditing-placeholder u-bgUserColor"></div>
  <div class="ProfileAvatarEditing-container">"}'];

        $moch = $this->getMockBuilder(CURL::class)
                     ->getMock();
        $moch->method('curl_content')
             ->willReturn($content);
        $url = "https://twitter.com/blabla";
        $profile = new ProfileImage($url, $moch);
        $actual = $profile->getProfileImage();
        $expected = "https://pbs.twimg.com/profile_images/878491891733549056/3urQeq4j_400x400.jpg";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_empty_image_from_non_twitter_profile()
    {
        $content = ['body' => '{"page":"<a ""}'];

        $moch = $this->getMockBuilder(CURL::class)
                     ->getMock();
        $moch->method('curl_content')
             ->willReturn($content);
        $url = "https://twitter.com/blabla";
        $profile = new ProfileImage($url, $moch);
        $actual = $profile->getProfileImage();
        $expected = "";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_empty_image_from_non_twitter_profile_error()
    {
        $content = ['body' => '{"page":"<a ""}', 'error_no' => 101];

        $moch = $this->getMockBuilder(CURL::class)
                     ->getMock();
        $moch->method('curl_content')
             ->willReturn($content);
        $url = "https://twitter.com/blabla";
        $profile = new ProfileImage($url, $moch);
        $actual = $profile->getProfileImage();
        $expected = "";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_empty_image_from_non_twitter_profile_empty_body()
    {
        $content = [];

        $moch = $this->getMockBuilder(CURL::class)
                     ->getMock();
        $moch->method('curl_content')
             ->willReturn($content);
        $url = "https://twitter.com/blabla";
        $profile = new ProfileImage($url, $moch);
        $actual = $profile->getProfileImage();
        $expected = "";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_my_twitter_profile_image()
    {
        $body = '{"page":"
<!DOCTYPE html>
<html lang="en" data-scribe-reduced-action-queue="true">
  <head>







    <meta charset="utf-8">

    <noscript><meta http-equiv="refresh" content="0; URL=https://mobile.twitter.com/i/nojs_router?path=%2Fmohammed_attya"></noscript>
      <script  nonce="NRtPTJsIBHvTBXdB7Np/ag==">
        !function(){window.initErrorstack||(window.initErrorstack=[]),window.onerror=function(r,i,n,o,t){r.indexOf("Script error.")>-1||window.initErrorstack.push({errorMsg:r,url:i,lineNumber:n,column:o,errorObj:t})}}();
      </script>



  <script id="bouncer_terminate_iframe" nonce="NRtPTJsIBHvTBXdB7Np/ag==">
    if (window.top != window) {
  window.top.postMessage({"bouncer": true, "event": "complete"}, "*");
}
  </script>
  <script id="ttft_boot_data" nonce="NRtPTJsIBHvTBXdB7Np/ag==">
    window.ttftData={"transaction_id":"00720cbf004489c8.8dae10d97e86b880\u003c:00913fa800406305","server_request_start_time":1503998153239,"user_id":250377148,"is_ssl":true,"rendered_on_server":true,"is_tfe":true,"client":"macaw-swift","tfe_version":"tsa_f\/1.0.1\/20170726.1729.d3477ad","ttft_browser":"chrome"};!function(){function t(t,n){window.ttftData&&!window.ttftData[t]&&(window.ttftData[t]=n)}function n(){return o?Math.round(w.now()+w.timing.navigationStart):(new Date).getTime()}var w=window.performance,o=w&&w.now;window.ttft||(window.ttft={}),window.ttft.recordMilestone||(window.ttft.recordMilestone=t),window.ttft.now||(window.ttft.now=n)}();
  </script>
  <script id="swift_action_queue" nonce="NRtPTJsIBHvTBXdB7Np/ag==">
    !function(){function e(e){if(e||(e=window.event),!e)return!1;if(e.timestamp=(new Date).getTime(),!e.target&&e.srcElement&&(e.target=e.srcElement),document.documentElement.getAttribute("data-scribe-reduced-action-queue"))for(var t=e.target;t&&t!=document.body;){if("A"==t.tagName)return;t=t.parentNode}return i("all",o(e)),a(e)?(document.addEventListener||(e=o(e)),e.preventDefault=e.stopPropagation=e.stopImmediatePropagation=function(){},y?(v.push(e),i("captured",e)):i("ignored",e),!1):(i("direct",e),!0)}function t(e){n();for(var t,r=0;t=v[r];r++){var a=e(t.target),i=a.closest("a")[0];if("click"==t.type&&i){var o=e.data(i,"events"),u=o&&o.click,c=!i.hostname.match(g)||!i.href.match(/#$/);if(!u&&c){window.location=i.href;continue}}a.trigger(e.event.fix(t))}window.swiftActionQueue.wasFlushed=!0}function r(){for(var e in b)if("all"!=e)for(var t=b[e],r=0;r<t.length;r++)console.log("actionQueue",c(t[r]))}function n(){clearTimeout(w);for(var e,t=0;e=h[t];t++)document["on"+e]=null}function a(e){if(!e.target)return!1;var t=e.target,r=(t.tagName||"").toLowerCase();if(e.metaKey)return!1;if(e.shiftKey&&"a"==r)return!1;if(t.hostname&&!t.hostname.match(g))return!1;if(e.type.match(p)&&s(t))return!1;if("label"==r){var n=t.getAttribute("for");if(n){var a=document.getElementById(n);if(a&&f(a))return!1}else for(var i,o=0;i=t.childNodes[o];o++)if(f(i))return!1}return!0}function i(e,t){t.bucket=e,b[e].push(t)}function o(e){var t={};for(var r in e)t[r]=e[r];return t}function u(e){for(;e&&e!=document.body;){if("A"==e.tagName)return e;e=e.parentNode}}function c(e){var t=[];e.bucket&&t.push("["+e.bucket+"]"),t.push(e.type);var r,n,a=e.target,i=u(a),o="",c=e.timestamp&&e.timestamp-d;return"click"===e.type&&i?(r=i.className.trim().replace(/\s+/g,"."),n=i.id.trim(),o=/[^#]$/.test(i.href)?" ("+i.href+")":"",a="""+i.innerText.replace(/\n+/g," ").trim()+"""):(r=a.className.trim().replace(/\s+/g,"."),n=a.id.trim(),a=a.tagName.toLowerCase(),e.keyCode&&(a=String.fromCharCode(e.keyCode)+" : "+a)),t.push(a+o+(n&&"#"+n)+(!n&&r?"."+r:"")),c&&t.push(c),t.join(" ")}function f(e){var t=(e.tagName||"").toLowerCase();return"input"==t&&"checkbox"==e.getAttribute("type")}function s(e){var t=(e.tagName||"").toLowerCase();return"textarea"==t||"input"==t&&"text"==e.getAttribute("type")||"true"==e.getAttribute("contenteditable")}for(var m,d=(new Date).getTime(),l=1e4,g=/^([^\.]+\.)*twitter\.com$/,p=/^key/,h=["click","keydown","keypress","keyup"],v=[],w=null,y=!0,b={captured:[],ignored:[],direct:[],all:[]},k=0;m=h[k];k++)document["on"+m]=e;w=setTimeout(function(){y=!1},l),window.swiftActionQueue={buckets:b,flush:t,logActions:r,wasFlushed:!1}}();
  </script>
  <script id="composition_state" nonce="NRtPTJsIBHvTBXdB7Np/ag==">
    !function(){function t(t){t.target.setAttribute("data-in-composition","true")}function n(t){t.target.removeAttribute("data-in-composition")}document.addEventListener&&(document.addEventListener("compositionstart",t,!1),document.addEventListener("compositionend",n,!1))}();
  </script>

    <link rel="stylesheet" href="https://abs.twimg.com/a/1503707773/css/t1/twitter_core.bundle.css" class="coreCSSBundles">
  <link rel="stylesheet" class="moreCSSBundles" href="https://abs.twimg.com/a/1503707773/css/t1/twitter_more_1.bundle.css">
  <link rel="stylesheet" class="moreCSSBundles" href="https://abs.twimg.com/a/1503707773/css/t1/twitter_more_2.bundle.css">

    <link rel="dns-prefetch" href="https://pbs.twimg.com">
    <link rel="dns-prefetch" href="https://t.co">
      <link rel="preload" href="https://abs.twimg.com/k/en/init.en.00bc5bac2f4866212098.js" as="script">
      <link rel="preload" href="https://abs.twimg.com/k/en/0.commons.en.745e6f82ede0898cee10.js" as="script">
      <link rel="preload" href="https://abs.twimg.com/k/en/3.pages_profile.en.d8389dd447e0ba16386b.js" as="script">

      <title>Mohammed Attya (@mohammed_attya) | Twitter</title>



<meta name="msapplication-TileImage" content="//abs.twimg.com/favicons/win8-tile-144.png"/>
<meta name="msapplication-TileColor" content="#00aced"/>



<link rel="mask-icon" sizes="any" href="https://abs.twimg.com/a/1503707773/icons/favicon.svg" color="#1da1f2">

<link rel="shortcut icon" href="//abs.twimg.com/favicons/favicon.ico" type="image/x-icon">
<link rel="apple-touch-icon" href="https://abs.twimg.com/icons/apple-touch-icon-192x192.png" sizes="192x192">

<link rel="manifest" href="/manifest.json">


  <meta name="swift-page-name" id="swift-page-name" content="me">
  <meta name="swift-page-section" id="swift-section-name" content="profile">

    <link rel="canonical" href="https://twitter.com/mohammed_attya">



    <link rel="alternate" type="application/json+oembed" href="https://publish.twitter.com/oembed?url=https://twitter.com/mohammed_attya" title="Mohammed Attya (@mohammed_attya) | Twitter">





<link rel="search" type="application/opensearchdescription+xml" href="/opensearch.xml" title="Twitter">

    <link id="async-css-placeholder">

<style id="user-style-mohammed_attya">






  a,
  a:hover,
  a:focus,
  a:active {
    color: #1B95E0;
  }

  .u-textUserColor,
  .u-textUserColorHover:hover,
  .u-textUserColorHover:hover .ProfileTweet-actionCount,
  .u-textUserColorHover:focus {
    color: #1B95E0 !important;
  }

  .u-borderUserColor,
  .u-borderUserColorHover:hover,
  .u-borderUserColorHover:focus {
    border-color: #1B95E0 !important;
  }

  .u-bgUserColor,
  .u-bgUserColorHover:hover,
  .u-bgUserColorHover:focus {
    background-color: #1B95E0 !important;
  }

  .u-dropdownUserColor > li:hover,
  .u-dropdownUserColor > li:focus,
  .u-dropdownUserColor > li > button:hover,
  .u-dropdownUserColor > li > button:focus,
  .u-dropdownUserColor > li > a:focus,
  .u-dropdownUserColor > li > a:hover {
    color: #fff !important;
    background-color: #1B95E0 !important;
  }

  .u-boxShadowInsetUserColorHover:hover,
  .u-boxShadowInsetUserColorHover:focus {
    box-shadow: inset 0 0 0 5px #1B95E0 !important;
  }

  .u-dropdownOpenUserColor.dropdown.open .dropdown-toggle {
    color: #1B95E0;
  }


  .u-textUserColorLight {
    color: #A3D4F2 !important;
  }

  .u-borderUserColorLight,
  .u-borderUserColorLightFocus:focus,
  .u-borderUserColorLightHover:hover,
  .u-borderUserColorLightHover:focus {
    border-color: #A3D4F2 !important;
  }

  .u-bgUserColorLight {
    background-color: #A3D4F2 !important;
  }


  .u-boxShadowUserColorLighterFocus:focus {
    box-shadow: 0 0 8px rgba(0, 0, 0, 0.05), inset 0 1px 2px rgba(27,149,224,0.25) !important;
  }


  .u-textUserColorLightest {
    color: #E8F4FB !important;
  }

  .u-borderUserColorLightest {
    border-color: #E8F4FB !important;
  }

  .u-bgUserColorLightest {
    background-color: #E8F4FB !important;
  }


  .u-textUserColorLighter {
    color: #C6E4F7 !important;
  }

  .u-borderUserColorLighter {
    border-color: #C6E4F7 !important;
  }

  .u-bgUserColorLighter {
    background-color: #C6E4F7 !important;
  }


  .u-bgUserColorDarkHover:hover {
    background-color: #1B7FBE !important;
  }

  .u-borderUserColorDark {
    border-color: #1B7FBE !important;
  }


  .u-bgUserColorDarkerActive:active {
    background-color: #1B699C !important;
  }













a,
.btn-link,
.btn-link:focus,
.icon-btn,



.pretty-link b,
.pretty-link:hover s,
.pretty-link:hover b,
.pretty-link:focus s,
.pretty-link:focus b,

.metadata a:hover,
.metadata a:focus,

a.account-group:hover .fullname,
a.account-group:focus .fullname,
.account-summary:focus .fullname,

.message .message-text a,
.message .message-text button,
.stats a strong,
.plain-btn:hover,
.plain-btn:focus,
.dropdown.open .user-dropdown.plain-btn,
.open > .plain-btn,
#global-actions .new:before,
.module .list-link:hover,
.module .list-link:focus,

.stats a:hover,
.stats a:hover strong,
.stats a:focus,
.stats a:focus strong,

.find-friends-sources li:hover .source,





.stream-item a:hover .fullname,
.stream-item a:focus .fullname,

.stream-item .view-all-supplements:hover,
.stream-item .view-all-supplements:focus,

.tweet .time a:hover,
.tweet .time a:focus,
.tweet .details.with-icn b,
.tweet .details.with-icn .Icon,

.stream-item:hover .original-tweet .details b,
.stream-item .original-tweet.focus .details b,
.stream-item.open .original-tweet .details b,

.client-and-actions a:hover,
.client-and-actions a:focus,

.dismiss-btn:hover b,

.tweet .context .pretty-link:hover s,
.tweet .context .pretty-link:hover b,
.tweet .context .pretty-link:focus s,
.tweet .context .pretty-link:focus b,

.list .username a:hover,
.list .username a:focus,
.list-membership-container .create-a-list,
.list-membership-container .create-a-list:hover,
.new-tweets-bar,



.card .list-details a:hover,
.card .list-details a:focus,
.card .card-body:hover .attribution,
.card .card-body .attribution:focus {
  color: #1B95E0;
}



    #global-actions > li > a {
      border-bottom-color: #1B95E0;
    }

    #global-actions > li:hover > a,
    #global-actions > li > a:focus,
    #global-actions > li.active .text,
    .nav.right-actions > li > a:hover,
    .nav.right-actions > li > a:focus {
      color: #1B95E0;
    }


      #global-actions .people.new:before {
        content: none;
      }


  .FoundMediaSearch--keyboard .FoundMediaSearch-focusable.is-focused {
    border-color: #1B95E0;
  }


  .photo-selector:hover .btn,
  .icon-btn:hover,
  .icon-btn:active,
  .icon-btn.active,
  .icon-btn.enabled {
    border-color: #1B95E0;
    border-color: rgba(27,149,224,0.4);
    color: #1B95E0;
  }


  .photo-selector:hover .btn,
  .icon-btn:hover {
    background-image: linear-gradient(rgba(255,255,255,0), rgba(27,149,224,0.1));
  }

  .icon-btn.disabled,
  .icon-btn.disabled:hover,
  .icon-btn[disabled],
  .icon-btn[aria-disabled=true] {
    color: #1B95E0;
  }




  .EdgeButton--primary,
  .EdgeButton--primary:focus {
    background-color: #48AAE6;
    border-color: transparent;
  }

  .EdgeButton--primary:hover,
  .EdgeButton--primary:active {
    background-color: #1B95E0;
    border-color: #1B95E0;
  }

  .EdgeButton--primary:focus {
    box-shadow:
      0 0 0 2px #FFFFFF,
      0 0 0 4px #A3D4F2;
  }

  .EdgeButton--primary:active {
    box-shadow:
      0 0 0 2px #FFFFFF,
      0 0 0 4px #48AAE6;
  }




  .EdgeButton--secondary,
  .EdgeButton--secondary:hover,
  .EdgeButton--secondary:focus,
  .EdgeButton--secondary:active {
    border-color: #1B95E0;
    color: #1B95E0;
  }

  .EdgeButton--secondary:hover,
  .EdgeButton--secondary:active {
    background-color: #E8F4FB;
  }

  .EdgeButton--secondary:focus {
    box-shadow:
      0 0 0 2px #FFFFFF,
      0 0 0 4px rgba(27,149,224,0.4);
  }

  .EdgeButton--secondary:active {
    box-shadow:
      0 0 0 2px #FFFFFF,
      0 0 0 4px #1B95E0;
  }




  .EdgeButton--invertedPrimary {
    color: #1B95E0 !important;
  }

  .EdgeButton--invertedPrimary:focus {
    box-shadow:
      0 0 0 2px #1B95E0,
      0 0 0 4px #A3D4F2;
  }

  .EdgeButton--invertedPrimary:active {
    box-shadow:
      0 0 0 2px #1B95E0,
      0 0 0 4px #FFFFFF;
  }




  .EdgeButton--invertedSecondary {
    background-color: #1B95E0;
  }

  .EdgeButton--invertedSecondary:hover {
    background-color: #48AAE6;
  }

  .EdgeButton--invertedSecondary:focus {
    box-shadow:
      0 0 0 2px #1B95E0,
      0 0 0 4px #A3D4F2;
  }

  .EdgeButton--invertedSecondary:active {
    box-shadow:
      0 0 0 2px #1B95E0,
      0 0 0 4px #FFFFFF;
  }



  .btn:focus,
  .btn.focus,
  .Button:focus,
  .EmojiPicker-item.is-focused,
  .EmojiPicker .EmojiCategoryIcon:focus,
  .EmojiPicker-skinTone:focus + .EmojiPicker-skinToneSwatch,
  a:focus > img:first-child:last-child,
  button:focus {
    box-shadow:
      0 0 0 2px #FFFFFF,
      0 0 2px 4px rgba(27,149,224,0.4);
  }

  .selected-stream-item:focus {
    box-shadow: 0 0 0 3px rgba(27,149,224,0.4);
  }


  .js-navigable-stream.stream-table-view .selected-stream-item[tabindex="-1"]:focus {
    outline: 3px solid rgba(27,149,224,0.4) !important;
  }


  .js-navigable-stream.stream-table-view .selected-stream-item:focus {
    box-shadow: none;
  }



  .global-dm-nav.new.with-count .dm-new .count-inner {
    background: #1B95E0;
  }

  .global-nav .people .count .count-inner {
    background: #1B95E0;
  }

  .dropdown-menu li > a:hover,
  .dropdown-menu li > a:focus,
  .dropdown-menu .dropdown-link:hover,
  .dropdown-menu .dropdown-link:focus,
  .dropdown-menu .dropdown-link.is-focused,
  .dropdown-menu li:hover .dropdown-link,
  .dropdown-menu li:focus .dropdown-link,
  .dropdown-menu .selected a,
  .dropdown-menu .dropdown-link.selected {
    background-color: #1B95E0 !important;
  }

  /* for items in typeahead dropdown menu on logged in pages */
  .dropdown-menu .typeahead-items li > a:focus,
  .dropdown-menu .typeahead-items li > a:hover,
  .dropdown-menu .typeahead-items .selected,
  .dropdown-menu .typeahead-items .selected a {
    background-color: #E8F4FB !important;
    color: #1B95E0 !important;
  }

  .typeahead a:hover,
  .typeahead a:hover strong,
  .typeahead a:hover .fullname,
  .typeahead .selected a,
  .typeahead .selected strong,
  .typeahead .selected .fullname,
  .typeahead .selected .Icon--close {
    color: #1B95E0 !important;
  }


.home-tweet-box,
.LiveVideo-tweetBox,
.RetweetDialog-commentBox {
  background-color: #E8F4FB;
}

.top-timeline-tweetbox .timeline-tweet-box .tweet-form.condensed .tweet-box {
  color: #1B95E0;
}

.RichEditor,
.TweetBoxAttachments {
  border-color: #C6E4F7;
}

input:focus,
textarea:focus,
div[contenteditable="true"]:focus,
div[contenteditable="true"].fake-focus,
div[contenteditable="plaintext-only"]:focus,
div[contenteditable="plaintext-only"].fake-focus {
  border-color: #A3D4F2;
  box-shadow: inset 0 0 0 1px rgba(27,149,224,0.7);
}

.tweet-box textarea:focus,
.tweet-box input[type=text],
.currently-dragging .tweet-form.is-droppable .tweet-drag-help,
.tweet-box[contenteditable="true"]:focus,
.RichEditor.is-fakeFocus,
.RichEditor.is-fakeFocus ~ .TweetBoxAttachments {
  border-color: #A3D4F2;
  box-shadow: 0 0 0 1px #A3D4F2;
}

.MomentCapsuleItem.selected-stream-item:focus {
  box-shadow: 0 0 0 3px rgba(27,149,224,0.4);
}




s,
.pretty-link:hover s,
.pretty-link:focus s,
.stream-item-activity-notification .latest-tweet .tweet-row a:hover s,
.stream-item-activity-notification .latest-tweet .tweet-row a:focus s {
    color: #1B95E0;
}



.vellip,
.vellip:before,
.vellip:after,
.conversation-module > li:after,
.conversation-module > li:before,
.ThreadedConversation-tweet:not(.is-hiddenAncestor) ~ .ThreadedConversation-tweet:before,
.ThreadedConversation-tweet:after,
.ThreadedConversation-moreReplies:before,
.ThreadedConversation-viewOther:before,
.ThreadedConversation-unavailableTweet:before,
.ThreadedConversation-unavailableTweet:after,
.ThreadedConversation--permalinkTweetWithAncestors:before {
    border-color: #A3D4F2;
}




.tweet .sm-reply,
.tweet .sm-rt,
.tweet .sm-fav,
.tweet .sm-image,
.tweet .sm-video,
.tweet .sm-audio,
.tweet .sm-geo,
.tweet .sm-in,
.tweet .sm-trash,
.tweet .sm-more,
.tweet .sm-page,
.tweet .sm-embed,
.tweet .sm-summary,
.tweet .sm-chat,

.timelines-navigation .active .profile-nav-icon,
.timelines-navigation .profile-nav-icon:hover,
.timelines-navigation .profile-nav-link:focus .profile-nav-icon,

.sm-top-tweet {
    background-color: #1B95E0;
}

.enhanced-mini-profile .mini-profile .profile-summary {
  background-image: url(https://pbs.twimg.com/profile_banners/250377148/1495478263/mobile);
}

  #global-tweet-dialog .modal-header,
  #Tweetstorm-dialog .modal-header {
    border-bottom: solid 1px rgba(27,149,224,0.25);
  }

  #global-tweet-dialog .modal-tweet-form-container,
  #Tweetstorm-dialog .modal-body {
    background-color: #1B95E0;
    background: rgba(27,149,224,0.1);
  }

  .TweetstormDialog-tweet-box .tweet-box-avatar:after,
  .TweetstormDialog-tweet-box .tweet-box-avatar:before {
    border-color: #A3D4F2;
  }

  .global-nav .search-input:focus,
  .global-nav .search-input.focus {
    border: 2px solid #1B95E0;
  }
}

  .inline-reply-tweetbox {
    background-color: #E8F4FB;
  }

</style>


<style id="user-style-mohammed_attya-header-img" class="js-user-style-header-img">

    body.user-style-mohammed_attya .enhanced-mini-profile .mini-profile .profile-summary {
      background-image: url(https://pbs.twimg.com/profile_banners/250377148/1495478263/web);
    }

    .DashboardProfileCard-bg {
      background-image: url(https://pbs.twimg.com/profile_banners/250377148/1495478263/600x200);
    }

</style>



        <meta  property="al:ios:url" content="twitter://user?screen_name=mohammed_attya">
    <meta  property="al:ios:app_store_id" content="333903271">
    <meta  property="al:ios:app_name" content="Twitter">
    <meta  property="al:android:url" content="twitter://user?screen_name=mohammed_attya">
    <meta  property="al:android:package" content="com.twitter.android">
    <meta  property="al:android:app_name" content="Twitter">

  </head>
  <body class="three-col logged-in user-style-mohammed_attya enhanced-mini-profile ProfilePage ProfilePage--withWarning"
data-fouc-class-names="swift-loading no-nav-banners"
 dir="ltr">
      <script id="swift_loading_indicator" nonce="NRtPTJsIBHvTBXdB7Np/ag==">
        document.body.className=document.body.className+" "+document.body.getAttribute("data-fouc-class-names");
      </script>

    <a href="#timeline" class="u-hiddenVisually focusable">Skip to content</a>










    <div id="doc" data-at-shortcutkeys="{&quot;n&quot;:&quot;New Tweet&quot;,&quot;l&quot;:&quot;Like&quot;,&quot;r&quot;:&quot;Reply&quot;,&quot;t&quot;:&quot;Retweet&quot;,&quot;m&quot;:&quot;Direct message&quot;,&quot;u&quot;:&quot;Mute User&quot;,&quot;b&quot;:&quot;Block User&quot;,&quot;Enter&quot;:&quot;Open Tweet details&quot;,&quot;o&quot;:&quot;Expand photo&quot;,&quot;/&quot;:&quot;Search&quot;,&quot;CtrlEnter&quot;:&quot;Send Tweet&quot;,&quot;?&quot;:&quot;This menu&quot;,&quot;j&quot;:&quot;Next Tweet&quot;,&quot;k&quot;:&quot;Previous Tweet&quot;,&quot;Space&quot;:&quot;Page down&quot;,&quot;.&quot;:&quot;Load new Tweets&quot;,&quot;gh&quot;:&quot;Home&quot;,&quot;go&quot;:&quot;Moments&quot;,&quot;gn&quot;:&quot;Notifications&quot;,&quot;gr&quot;:&quot;Mentions&quot;,&quot;gp&quot;:&quot;Profile&quot;,&quot;gl&quot;:&quot;Likes&quot;,&quot;gi&quot;:&quot;Lists&quot;,&quot;gm&quot;:&quot;Messages&quot;,&quot;gs&quot;:&quot;Settings&quot;,&quot;gu&quot;:&quot;Go to user\u2026&quot;}" class="route-profile">
        <div class="topbar js-topbar">



    <div class="ProfilePage-editingOverlay"></div>


  <div class="global-nav" data-section-term="top_nav">
    <div class="global-nav-inner">
      <div class="container">

<h1 class="Icon Icon--bird bird-topbar-etched" style="display: inline-block; width: 24px; height: 21px;">
  <span class="visuallyhidden">Twitter</span>
</h1>


  <div role="navigation" style="display: inline-block;"><ul class="nav js-global-actions" id="global-actions"><li id="global-nav-home" class="home" data-global-action="home">
        <a class="js-nav js-tooltip js-dynamic-tooltip" data-placement="bottom" href="/" data-component-context="home_nav" data-nav="home">
          <span class="Icon Icon--home Icon--large"></span>
          <span class="Icon Icon--homeFilled Icon--large u-textUserColor"></span>
          <span class="text" aria-hidden="true">Home</span>
          <span class="u-hiddenVisually a11y-inactive-page-text">Home</span>
          <span class="u-hiddenVisually a11y-active-page-text">Home, current page.</span>
          <span class="u-hiddenVisually hidden-new-items-text">New Tweets available.</span>
        </a>
      </li><li class="moments js-moments-tab " data-global-action="moments">
        <a role="button" href="/i/moments" class="js-nav js-tooltip js-dynamic-tooltip" data-component-context="moments_nav" data-nav="moments" data-placement="bottom">
          <span class="Icon Icon--lightning Icon--large"></span>
          <span class="Icon Icon--lightningFilled Icon--large u-textUserColor"></span>
          <span class="text" aria-hidden="true">Moments</span>
          <span class="u-hiddenVisually a11y-inactive-page-text">Moments</span>
          <span class="u-hiddenVisually a11y-active-page-text">Moments, current page.</span>
        </a>
      </li><li class="people notifications" data-global-action="connect">
        <a class="js-nav js-tooltip js-dynamic-tooltip" data-placement="bottom" href="/i/notifications" data-component-context="connect_nav" data-nav="connect">
          <span class="Icon Icon--notifications Icon--large"></span>
          <span class="Icon Icon--notificationsFilled Icon--large u-textUserColor"></span>
          <span class="text" aria-hidden="true">Notifications</span>
          <span class="u-hiddenVisually a11y-inactive-page-text">Notifications</span>
          <span class="u-hiddenVisually a11y-active-page-text">Notifications, current page.</span>
            <span class="count">
              <span class="count-inner">0</span>
            </span>
        </a>
      </li><li class="dm-nav">
        <a role="button" href="#" class="js-tooltip js-dynamic-tooltip global-dm-nav" data-placement="bottom">
          <span class="Icon Icon--dm Icon--large"></span>
          <span class="text">Messages</span>
          <span class="dm-new"><span class="count-inner"></span></span>
        </a>
      </li></ul>
  </div>

  <div class="pull-right nav-extras">
    <div role="search">
  <form class="t1-form form-search js-search-form" action="/search" id="global-nav-search">
    <label class="visuallyhidden" for="search-query">Search query</label>
    <input class="search-input" type="text" id="search-query" placeholder="Search Twitter" name="q" autocomplete="off" spellcheck="false">
    <span class="search-icon js-search-action">
      <button type="submit" class="Icon Icon--medium Icon--search nav-search">
        <span class="visuallyhidden">Search Twitter</span>
      </button>
    </span>
      <div role="listbox" class="dropdown-menu typeahead">
  <div aria-hidden="true" class="dropdown-caret">
    <div class="caret-outer"></div>
    <div class="caret-inner"></div>
  </div>
  <div role="presentation" class="dropdown-inner js-typeahead-results">
      <div role="presentation" class="typeahead-recent-searches">
  <h3 id="recent-searches-heading" class="typeahead-category-title recent-searches-title">Recent searches</h3><button type="button" tabindex="-1" class="btn-link clear-recent-searches">Clear All</button>
  <ul role="presentation" class="typeahead-items recent-searches-list">

    <li role="presentation" class="typeahead-item typeahead-recent-search-item">
      <span class="Icon Icon--close" aria-hidden="true"><span class="visuallyhidden">Remove</span></span>
      <a role="option" aria-describedby="recent-searches-heading" class="js-nav" href="" data-search-query="" data-query-source="" data-ds="recent_search" tabindex="-1"></a>
    </li>
  </ul>
</div>

    <div role="presentation" class="typeahead-saved-searches">
  <h3 id="saved-searches-heading" class="typeahead-category-title saved-searches-title">Saved searches</h3>
  <ul role="presentation" class="typeahead-items saved-searches-list">

    <li role="presentation" class="typeahead-item typeahead-saved-search-item">
      <span class="Icon Icon--close" aria-hidden="true"><span class="visuallyhidden">Remove</span></span>
      <a role="option" aria-describedby="saved-searches-heading" class="js-nav" href="" data-search-query="" data-query-source="" data-ds="saved_search" tabindex="-1"></a>
    </li>
  </ul>
</div>

    <ul role="presentation" class="typeahead-items typeahead-topics">

  <li role="presentation" class="typeahead-item typeahead-topic-item">
    <a role="option" class="js-nav" href="" data-search-query="" data-query-source="typeahead_click" data-ds="topics" tabindex="-1"></a>
  </li>
</ul>
    <ul role="presentation" class="typeahead-items typeahead-accounts social-context js-typeahead-accounts">

  <li role="presentation" data-user-id="" data-user-screenname="" data-remote="true" data-score="" class="typeahead-item typeahead-account-item js-selectable">

    <a role="option" class="js-nav" data-query-source="typeahead_click" data-search-query="" data-ds="account">
      <div class="js-selectable typeahead-in-conversation hidden">
        <span class="Icon Icon--follower Icon--small"></span>
        <span class="typeahead-in-conversation-text">In this conversation</span>
      </div>
      <img class="avatar size32" alt="">
      <span class="typeahead-user-item-info account-group">
        <span class="fullname"></span><span class="UserBadges"><span class="Icon Icon--verified js-verified hidden"><span class="u-hiddenVisually">Verified account</span></span><span class="Icon Icon--protected js-protected hidden"><span class="u-hiddenVisually">Protected Tweets</span></span></span><span class="UserNameBreak">&nbsp;</span><span class="username u-dir" dir="ltr">@<b></b></span>
      </span>
      <span class="typeahead-social-context"></span>
    </a>
  </li>
  <li role="presentation" class="js-selectable typeahead-accounts-shortcut js-shortcut"><a role="option" class="js-nav" href="" data-search-query="" data-query-source="typeahead_click" data-shortcut="true" data-ds="account_search"></a></li>
</ul>

    <ul role="presentation" class="typeahead-items typeahead-trend-locations-list">

  <li role="presentation" class="typeahead-item typeahead-trend-locations-item"><a role="option" class="js-nav" href="" data-ds="trend_location" data-search-query="" tabindex="-1"></a></li>
</ul>
  </div>
</div>

  </form>
</div>

    <ul class="nav right-actions">
      <li class="me dropdown session js-session" data-global-action="t1me" id="user-dropdown">
        <a href="/settings/account" class="btn js-tooltip settings dropdown-toggle js-dropdown-toggle" id="user-dropdown-toggle" title="Profile and settings" data-placement="bottom" rel="noopener">
          <img class="Avatar Avatar--size32" src="https://pbs.twimg.com/profile_images/878491891733549056/3urQeq4j_normal.jpg" alt="Profile and settings" data-user-id="250377148">
        </a>
          <div class="DashUserDropdown dropdown-menu dropdown-menu--rightAlign is-forceRight is-autoCentered">
    <div class="dropdown-caret">
      <span class="caret-outer"></span>
      <span class="caret-inner"></span>
    </div>
    <ul>


      <li class="DashUserDropdown-userInfo" data-name="user-info">
        <a href="/mohammed_attya" class="DashUserDropdown-userInfoLink js-nav" data-nav="view_profile">
          <b class="fullname">Mohammed Attya</b><span class="UserBadges"></span>
          <p class="name"><span class="username u-dir" dir="ltr" >@<b>mohammed_attya</b></span></p>
        </a>
      </li>

      <li class="dropdown-divider"></li>

      <li class="current-user" data-name="profile">
        <a href="/mohammed_attya" class="js-nav" data-nav="view_profile">
          <span class="DashUserDropdown-linkIcon Icon Icon--medium Icon--me"></span>Profile
        </a>
      </li>

      <li data-name="lists">
        <a class="js-nav" href="/mohammed_attya/lists" data-nav="all_lists">
          <span class="DashUserDropdown-linkIcon Icon Icon--medium Icon--list"></span>Lists
        </a>
      </li>


      <li data-name="moments">
        <a class="js-nav" href="/mohammed_attya/moments" data-nav="all_moments">
          <span class="DashUserDropdown-linkIcon Icon Icon--medium Icon--lightning"></span>Moments
        </a>
      </li>

      <li class="dropdown-divider"></li>


        <li>
          <a href="https://ads.twitter.com/start?ref=gl-tw-tw-twitter-ads" target="_blank" data-nav="ads" rel="noopener">
            <span class="DashUserDropdown-linkIcon Icon Icon--medium Icon--promotedStroked"></span>Twitter Ads
          </a>
        </li>


        <li>
          <a class="user-dropdown-analytics" href="https://analytics.twitter.com/" target="_blank" data-nav="user_dropdown_analytics" rel="noopener">
            <span class="DashUserDropdown-linkIcon Icon Icon--medium Icon--analytics"></span>Analytics
          </a>
        </li>









      <li class="dropdown-divider"></li>


      <li><a href="/settings/account" data-nav="settings" class="js-nav" rel="noopener">Settings and privacy</a></li>


      <li><a href="//support.twitter.com" data-nav="help_center" rel="noopener">Help Center</a></li>


        <li class="js-keyboard-shortcut-trigger" data-nav="shortcuts">
          <button type="button" class="dropdown-link">Keyboard shortcuts</button>
        </li>


      <li class="js-signout-button" id="signout-button" data-nav="logout">
        <button type="button" class="dropdown-link">Log out</button>
        <form class="t1-form dropdown-link-form signout-form" id="signout-form" action="/logout" method="POST">
          <input type="hidden" value="a587a1e6111389fec0f83e815383d80da89d6907" name="authenticity_token" class="authenticity_token">
          <input type="hidden" name="reliability_event" class="js-reliability-event">
          <input type="hidden" name="scribe_log">
        </form>
      </li>



    </ul>
  </div>

      </li>
      <li role="complementary" aria-labelledby="global-new-tweet-button" class="topbar-tweet-btn">
        <button id="global-new-tweet-button" class="js-global-new-tweet js-tooltip EdgeButton EdgeButton--primary js-dynamic-tooltip" data-placement="bottom" data-component-context="new_tweet_button">
          <span class="text">Tweet</span>
        </button>
      </li>
    </ul>
  </div>


      </div>
    </div>
  </div>
</div>



        <div id="page-outer">
          <div id="page-container" class="AppContent">






      <div class="ProfilePage-editingOverlay"></div>


      <div class="ProfilePage-testForEditingCss"></div>
    <div class="ProfileCanopy ProfileCanopy--withNav ProfileCanopy--large js-variableHeightTopBar">
  <div class="ProfileCanopy-inner">

    <div class="ProfileCanopy-header u-bgUserColor">
  <div class="ProfileCanopy-headerBg">
    <img alt=""
      src="https://pbs.twimg.com/profile_banners/250377148/1495478263/1500x500"

    >
  </div>
    <div class="ProfileHeaderEditing is-withHeader">
  <div class="ProfileHeaderEditing-overlay"></div>
  <div class="ProfileHeaderEditing-iframeSaving">
    <div class="ProfileHeaderEditing-iframeSavingHelp">
      <span class="Icon Icon--clock"></span>
      <p>Saving</p>
    </div>
  </div>
  <button class="ProfileHeaderEditing-button u-boxShadowInsetUserColorHover" type="button" tabindex="2">
    <div class="ProfileHeaderEditing-addHeaderHelp">
      <span class="Icon Icon--cameraPlus"></span>
      <p>Add a header photo</p>
    </div>
    <div class="ProfileHeaderEditing-changeHeaderHelp">
      <span class="Icon Icon--camera"></span>
      <p>Change your header photo</p>
    </div>
    <div class="ProfileHeaderEditing-dropHeaderHelp">
      <span class="Icon Icon--cameraPlus"></span>
      <p>Drop header photo here</p>
    </div>
  </button>
    <div id="choose-header-container">
      <div id="choose-header" class="controls header-settings inline-upload-header dropdown center">
        <div class="uploader-image uploader-header clearfix">
          <div class="dropdown-menu">
  <div class="dropdown-caret">
    <span class="caret-outer"></span>
    <span class="caret-inner"></span>
  </div>
  <ul>
    <li id="header-choose-existing" class="header-choose-existing upload-photo">
      <button type="button" class="dropdown-link">Upload photo</button>
      <div class="photo-selector">
  <button class="btn" type="button" disabled>
      Change header
    </button>
  <span class="photo-file-name">No file selected</span>
  <div class="image-selector">
    <input type="hidden" name="media_file_name" class="file-name">
    <input type="hidden" name="media_data_empty" class="file-data">
    <label class="t1-label">
      <span class="u-hiddenVisually">Add Photo</span>
      <input type="file" name="media_empty" class="file-input js-tooltip" tabindex="-1" title="Add Photo" accept="image/gif,image/jpeg,image/jpg,image/png">
    </label>
  </div>
</div>

    </li>
    <li id="header-delete-image" class="">
      <button type="button" class="dropdown-link">Remove</button>
    </li>
      <li class="dropdown-divider" role="presentation"></li>
      <li class="cancel-options">
        <button type="button" class="dropdown-link">Cancel</button>
      </li>
  </ul>
</div>

        </div>
      </div>
    </div>
</div>

      <div id="header_image_upload_dialog" class="ProfileHeaderUploadDialog modal-container">
  <div class="modal profile-header-modal">
    <div class="modal-content">
      <div class="modal-body">
        <div class="ProfileHeaderUploadDialog-cropZone u-bgUserColor">
          <div class="ProfileHeaderUploadDialog-cropMask">
            <div class="ProfileHeaderUploadDialog-cropOverlay"></div>
            <img class="ProfileHeaderUploadDialog-cropImage" alt="Mohammed Attya">
          </div>
        </div>
        <div class="u-hidden">
          <canvas class="drawsurface"></canvas>
        </div>
      </div>
      <div class="ProfileHeaderUploadDialog-footer modal-footer">
        <div class="ProfileHeaderUploadDialog-footerInner Grid">
          <div class="Grid-cell u-before1of4 u-size3of4">
            <div class="ProfileHeaderUploadDialog-footerContent Arrange">
              <div class="ProfileHeaderUploadDialog-cropperHelp Arrange-sizeFit">
                <div class="ProfileHeaderUploadDialog-cropperHelpTitle">
                  Reposition &amp; scale header
                </div>
                <div class="ProfileHeaderUploadDialog-cropperHelpSubtitle">
                  Some areas may be cropped on larger screens
                </div>
              </div>
              <div class="ProfileHeaderUploadDialog-cropperSlider Arrange-sizeFill">
                <span class="Icon Icon--imageCrop Icon--small u-alignBottom u-colorDeepGray"></span><div class="cropper-slider"></div><span class="Icon Icon--imageCrop Icon--large u-alignBottom u-colorDeepGray"></span>
              </div>
              <div class="ProfileHeaderUploadDialog-buttons Arrange-sizeFit">
                <span class="ProfileHeaderUploadDialog-savingIndicator spinner-small spinner-dark-bg"></span>
                <button type="button" class="EdgeButton EdgeButton--tertiary profile-image-cancel js-close">Cancel</button>
                <button type="button" class="EdgeButton EdgeButton--primary profile-image-save">Apply</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


  <div class="AppContainer">

    <div class="ProfileCanopy-avatar">
      <div class="ProfileAvatar">
    <a class="ProfileAvatar-placeholder u-block js-nav js-tooltip profile-picture u-hidden"
        href="/mohammed_attya?edit=true"
        title="Add a profile photo"
        data-placement="right"
        data-scribe-element="profile_avatar">
      <img class="ProfileAvatar-placeholderImage u-bgUserColor" data-avatar-placeholder="true" src="https://abs.twimg.com/a/1503707773/img/t1/highline/empty_state/owner_empty_avatar.png" alt="Mohammed Attya">
    </a>
    <a class="ProfileAvatar-container u-block js-tooltip profile-picture "
        href="https://pbs.twimg.com/profile_images/878491891733549056/3urQeq4j_400x400.jpg"
        title="Mohammed Attya"
        data-resolved-url-large="https://pbs.twimg.com/profile_images/878491891733549056/3urQeq4j_400x400.jpg"
        data-url="https://pbs.twimg.com/profile_images/878491891733549056/3urQeq4j_400x400.jpg"
        target="_blank"
        rel="noopener">
      <img class="ProfileAvatar-image" src="https://pbs.twimg.com/profile_images/878491891733549056/3urQeq4j_400x400.jpg" alt="Mohammed Attya">
    </a>
</div>

        <div class="ProfileAvatarEditing is-withAvatar">
  <div class="ProfileAvatarEditing-placeholder u-bgUserColor"></div>
  <div class="ProfileAvatarEditing-container">
    <img class="ProfileAvatarEditing-image avatar" src="https://pbs.twimg.com/profile_images/878491891733549056/3urQeq4j_400x400.jpg" alt="Mohammed Attya">
  </div>
  <div class="ProfileAvatarEditing-overlay"></div>
  <div class="ProfileAvatarEditing-iframeSaving">
    <div class="ProfileAvatarEditing-iframeSavingHelp">
      <span class="Icon Icon--clock"></span>
      <p>Saving</p>
    </div>
  </div>
  <div class="ProfileAvatarEditing-buttonContainer">
    <button class="ProfileAvatarEditing-button u-boxShadowInsetUserColorHover" type="button" tabindex="2">
      <div class="ProfileAvatarEditing-addAvatarHelp">
        <span class="Icon Icon--cameraPlus"></span>
        <p>Add a profile photo</p>
      </div>
      <div class="ProfileAvatarEditing-changeAvatarHelp">
        <span class="Icon Icon--camera"></span>
        <p>Change your profile photo</p>
      </div>
      <div class="ProfileAvatarEditing-dropAvatarHelp">
        <span class="Icon Icon--cameraPlus"></span>
        <p>Drop profile photo here</p>
      </div>
    </button>
  </div>
    <div id="choose-photo" class="controls avatar-settings inline-upload-avatar dropdown center">
      <div class="uploader-image uploader-avatar clearfix">
        <div class="dropdown-menu">
  <div class="dropdown-caret">
    <span class="caret-outer"></span>
    <span class="caret-inner"></span>
  </div>
  <ul>
    <li id="photo-choose-existing" class="photo-choose-existing upload-photo">
      <button type="button" class="dropdown-link">Upload photo</button>
      <div class="photo-selector">
  <button class="btn" type="button" disabled>
      Change photo
    </button>
  <span class="photo-file-name">No file selected</span>
  <div class="image-selector">
    <input type="hidden" name="media_file_name" class="file-name">
    <input type="hidden" name="media_data_empty" class="file-data">
    <label class="t1-label">
      <span class="u-hiddenVisually">Add Photo</span>
      <input type="file" name="media_empty" class="file-input js-tooltip" tabindex="-1" title="Add Photo" accept="image/gif,image/jpeg,image/jpg,image/png">
    </label>
  </div>
</div>

    </li>
      <li id="photo-choose-webcam" class="u-hidden">
        <button type="button" class="dropdown-link">Take photo</button>
      </li>
    <li id="photo-delete-image" class="">
      <button type="button" class="dropdown-link">Remove</button>
    </li>
      <li class="dropdown-divider" role="presentation"></li>
      <li class="cancel-options">
        <button type="button" class="dropdown-link">Cancel</button>
      </li>
  </ul>
</div>

      </div>
    </div>
</div>

    </div>


    <div class="ProfileCanopy-headerPromptAnchor"></div>
  </div>

</div>


    <div class="ProfileCanopy-navBar u-boxShadow">
      <div class="ProfilePage-editingOverlay"></div>

      <div class="AppContainer">
        <div class="Grid Grid--withGutter">
          <div class="Grid-cell u-size1of3 u-lg-size1of4">
            <div class="ProfileCanopy-card" role="presentation">
              <div class="ProfileCardMini">
  <a class="ProfileCardMini-avatar profile-picture js-tooltip"
     href="https://pbs.twimg.com/profile_images/878491891733549056/3urQeq4j.jpg"
     title="Mohammed Attya"
     data-resolved-url-large="https://pbs.twimg.com/profile_images/878491891733549056/3urQeq4j.jpg"
     data-url="https://pbs.twimg.com/profile_images/878491891733549056/3urQeq4j.jpg"
     target="_blank"
     rel="noopener">
    <img class="ProfileCardMini-avatarImage" alt="Mohammed Attya" src="https://pbs.twimg.com/profile_images/878491891733549056/3urQeq4j_normal.jpg" >
  </a>
  <div class="ProfileCardMini-details">
    <div class="ProfileNameTruncated account-group">
  <div class="u-textTruncate u-inlineBlock">
    <a class="fullname ProfileNameTruncated-link u-textInheritColor js-nav" href="/mohammed_attya"  data-aria-label-part>
      Mohammed Attya</a></div><span class="UserBadges"></span>
</div>
    <div class="ProfileCardMini-screenname">
      <a href="/mohammed_attya" class="ProfileCardMini-screennameLink u-linkComplex js-nav u-dir" dir="ltr">
        <span class="username u-dir" dir="ltr">@<b class="u-linkComplex-target">mohammed_attya</b></span>
      </a>
    </div>
  </div>
</div>

            </div>
          </div>

          <div class="Grid-cell u-size2of3 u-lg-size3of4">
            <div class="ProfileCanopy-nav">

  <div class="ProfileNav" role="navigation" data-user-id="250377148">
    <ul class="ProfileNav-list">
<li class="ProfileNav-item ProfileNav-item--tweets is-active">
          <a class="ProfileNav-stat ProfileNav-stat--link u-borderUserColor u-textCenter js-tooltip js-nav" title="25,605 Tweets" data-nav="tweets"
              tabindex=0
>
            <span class="ProfileNav-label" aria-hidden="true">Tweets</span>
              <span class="u-hiddenVisually">Tweets, current page.</span>
            <span class="ProfileNav-value"  data-count=25605 data-is-compact="true">25.6K
            </span>
          </a>
        </li><li class="ProfileNav-item ProfileNav-item--following">
        <a class="ProfileNav-stat ProfileNav-stat--link u-borderUserColor u-textCenter js-tooltip js-nav u-textUserColor" title="310 Following" data-nav="following"
            href="/following"
>
          <span class="ProfileNav-label" aria-hidden="true">Following</span>
            <span class="u-hiddenVisually">Following</span>
          <span class="ProfileNav-value" data-count=310 data-is-compact="false">310</span>
        </a>
      </li><li class="ProfileNav-item ProfileNav-item--followers">
        <a class="ProfileNav-stat ProfileNav-stat--link u-borderUserColor u-textCenter js-tooltip js-nav u-textUserColor" title="324 Followers" data-nav="followers"
            href="/followers"
>
          <span class="ProfileNav-label" aria-hidden="true">Followers</span>
            <span class="u-hiddenVisually">Followers</span>
          <span class="ProfileNav-value" data-count=324 data-is-compact="false">324</span>
        </a>
      </li><li class="ProfileNav-item ProfileNav-item--favorites" data-more-item=".ProfileNav-dropdownItem--favorites">
        <a class="ProfileNav-stat ProfileNav-stat--link u-borderUserColor u-textCenter js-tooltip js-nav u-textUserColor" title="446 Likes" data-nav="favorites"
            href="/i/likes"
>
          <span class="ProfileNav-label" aria-hidden="true">Likes</span>
            <span class="u-hiddenVisually">Likes</span>
          <span class="ProfileNav-value" data-count=446 data-is-compact="false">446</span>
        </a>
      </li><li class="ProfileNav-item ProfileNav-item--lists" data-more-item=".ProfileNav-dropdownItem--lists">
        <a class="ProfileNav-stat ProfileNav-stat--link u-borderUserColor u-textCenter js-tooltip  js-nav u-textUserColor" title="2 Lists" data-nav="all_lists"
            href="/mohammed_attya/lists"
>
          <span class="ProfileNav-label" aria-hidden="true">Lists</span>
            <span class="u-hiddenVisually">Lists</span>
          <span class="ProfileNav-value" data-is-compact="false">2</span>
        </a></li><li class="ProfileNav-item ProfileNav-item--moments" data-more-item=".ProfileNav-dropdownItem--userMoments">
        <a class="ProfileNav-stat ProfileNav-stat--link u-borderUserColor u-textCenter js-tooltip  js-nav u-textUserColor is-CurrentUser"
          data-nav="user_moments"
            href="/mohammed_attya/moments"
            data-placement="bottom"
              title="Moments you create can be found here!"
>
          <span class="ProfileNav-label" aria-hidden="true">Moments</span>
            <span class="u-hiddenVisually">Moments</span>
          <span class="ProfileNav-value" data-is-compact="false">0</span>
        </a></li><li class="ProfileNav-item ProfileNav-item--more dropdown is-hidden">        <a class="ProfileNav-stat ProfileNav-stat--link ProfileNav-stat--moreLink js-dropdown-toggle" role="button" href="#more">
          <span class="ProfileNav-label">&nbsp;</span>
          <span class="ProfileNav-value">More <span class="ProfileNav-dropdownCaret Icon Icon--medium Icon--caretDown"></span></span>
        </a>
        <div class="dropdown-menu">
          <div class="dropdown-caret">
            <span class="caret-outer"></span>
            <span class="caret-inner"></span>
          </div>
          <ul><li>
              <a href="/i/likes" class="ProfileNav-dropdownItem ProfileNav-dropdownItem--favorites is-hidden u-bgUserColorHover u-bgUserColorFocus u-linkClean js-nav">Likes</a></li><li>
              <a href="/mohammed_attya/lists" class="ProfileNav-dropdownItem ProfileNav-dropdownItem--lists is-hidden u-bgUserColorHover u-bgUserColorFocus u-linkClean js-nav">Lists</a></li><li>
              <a href="/mohammed_attya/moments" class="ProfileNav-dropdownItem ProfileNav-dropdownItem--userMoments is-hidden u-bgUserColorHover u-bgUserColorFocus u-linkClean js-nav">Moments</a></li></ul>
        </div>
      </li><li class="ProfileNav-item ProfileNav-item--userActions u-floatRight u-textRight with-rightCaret ">
        <div class="UserActions   u-textLeft" >
      <button type="button" class="UserActions-editButton edit-button EdgeButton EdgeButton--tertiary" data-scribe-element="profile_edit_button">
        <span class="button-text">Edit profile</span>
      </button>
      <div class="ProfilePage-editingButtons">
  <button class="ProfilePage-cancelButton EdgeButton EdgeButton--tertiary" data-scribe-element="cancel_button" tabindex="4">Cancel</button>
  <span class="ProfilePage-savingIndicator"><span class="spinner-small"></span>Saving...</span>
  <button class="ProfilePage-saveButton EdgeButton EdgeButton--secondary" tabindex="3">Save changes</button>
</div>

</div>

      </li>
    </ul>
  </div>

            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>



    <div class="AppContainer">
      <div class="AppContent-main content-main u-cf" role="main" aria-labelledby="content-main-heading">
        <div class="Grid Grid--withGutter">
          <div class="Grid-cell u-size1of3 u-lg-size1of4">
            <div class="Grid Grid--withGutter">
              <div class="Grid-cell">
                <div class="ProfileSidebar ProfileSidebar--withLeftAlignment">
  <div class="ProfileHeaderCard">
  <h1 class="ProfileHeaderCard-name">
    <a href="/mohammed_attya"
       class="ProfileHeaderCard-nameLink u-textInheritColor js-nav">Mohammed Attya</a>
  </h1>

  <h2 class="ProfileHeaderCard-screenname u-inlineBlock u-dir" dir="ltr">
    <a class="ProfileHeaderCard-screennameLink u-linkComplex js-nav" href="/mohammed_attya">
      <span class="username u-dir" dir="ltr">@<b class="u-linkComplex-target">mohammed_attya</b></span>
    </a>
  </h2>

    <p class="ProfileHeaderCard-bio u-dir" dir="ltr">Web Developer, PHP, Laravel, Geek, Programming, Biking, Walking, Coffee, Reading, <img class="Emoji Emoji--forText" src="https://abs.twimg.com/emoji/v2/72x72/1f6b2.png" draggable="false" alt="🚲" title="Bicycle" aria-label="Emoji: Bicycle"><img class="Emoji Emoji--forText" src="https://abs.twimg.com/emoji/v2/72x72/1f93e.png" draggable="false" alt="🤾" title="Handball" aria-label="Emoji: Handball"><img class="Emoji Emoji--forText" src="https://abs.twimg.com/emoji/v2/72x72/1f54b.png" draggable="false" alt="🕋" title="Kaaba" aria-label="Emoji: Kaaba"><img class="Emoji Emoji--forText" src="https://abs.twimg.com/emoji/v2/72x72/1f4be.png" draggable="false" alt="💾" title="Floppy disk" aria-label="Emoji: Floppy disk"><img class="Emoji Emoji--forText" src="https://abs.twimg.com/emoji/v2/72x72/1f4bb.png" draggable="false" alt="💻" title="Personal computer" aria-label="Emoji: Personal computer"></p>

    <div class="ProfileHeaderCard-location ">
      <span class="Icon Icon--geo Icon--medium"></span>
      <span class="ProfileHeaderCard-locationText u-dir" dir="ltr">
            <a href="/search?q=place%3Ac659cb4912229666" data-place-id="c659cb4912229666">Egypt</a>

      </span>
    </div>

    <div class="ProfileHeaderCard-url  u-hidden">
      <span class="Icon Icon--url Icon--medium"></span>
      <span class="ProfileHeaderCard-urlText u-dir">
</span>
    </div>



    <div class="ProfileHeaderCard-joinDate">
      <span class="Icon Icon--calendar Icon--medium"></span>
      <span class="ProfileHeaderCard-joinDateText js-tooltip u-dir" dir="ltr" title="1:57 AM - 11 Feb 2011">Joined February 2011</span>
    </div>

    <div class="ProfileHeaderCard-birthdate ">
      <span class="Icon Icon--balloon Icon--medium"></span>
      <span class="ProfileHeaderCard-birthdateText u-dir" dir="ltr"><span class="js-tooltip" title="Only Me">    Born on October 25, 1991
</span>
</span>
    </div>


</div>


    <div class="ProfileHeaderCardEditing u-bgUserColorLightest ProfileHeaderCardEditing--withEmoji ProfileHeaderCardEditing--withExtraFields">
  <div class="ProfileHeaderCardEditing-name ProfileHeaderCardEditing-item">
    <input type="text" id="user_name" name="user[name]"
           class="ProfileHeaderCardEditing-editableField u-borderUserColorLight"
           value="Mohammed Attya" placeholder="Name"
           maxlength="20" autocomplete="off" tabindex="2">
  </div>
  <div class="ProfileHeaderCardEditing-screenname u-textUserColor"><span class="username u-dir" dir="ltr" >@<b>mohammed_attya</b></span></div>
  <div class="ProfileHeaderCardEditing-bioRich ProfileHeaderCardEditing-item">
<div class="RichEditor RichEditor--emojiPicker u-borderUserColorLight">

  <div class="RichEditor-mozillaCursorWorkaround">&nbsp;</div>
  <div class="RichEditor-container u-borderRadiusInherit">

    <div class="RichEditor-scrollContainer u-borderRadiusInherit">
            <div name="user[description]" id="user_description"
        class="ProfileHeaderCardEditing-editableField rich-editor u-borderUserColorLight" contenteditable="true" spellcheck="true"
        aria-multiline="true" data-placeholder="Bio" role="textbox" tabindex="2">Web Developer, PHP, Laravel, Geek, Programming, Biking, Walking, Coffee, Reading, 🚲🤾🕋💾💻</div>

      <div class="RichEditor-pictographs" aria-hidden="true"></div>
    </div>

          <div class="RichEditor-rightItems RichEditor-bottomItems">
        <div class="EmojiPicker dropdown is-loading">
  <button type="button" class="EmojiPicker-trigger js-dropdown-toggle js-tooltip u-textUserColorHover"
      title="Add emoji" data-delay="150">
    <span class="Icon Icon--smiley"></span>
    <span class="text u-hiddenVisually">
      Add emoji
    </span>
  </button>
  <div class="EmojiPicker-dropdownMenu dropdown-menu" tabindex="-1">
    <div class="EmojiPicker-content Caret Caret--stroked"></div>
  </div>
</div>

      </div>

  </div>
  <div class="RichEditor-mozillaCursorWorkaround">&nbsp;</div>
</div>
</div>
  <div class="ProfileHeaderCardEditing-location ProfileHeaderCardEditing-bioRich ProfileHeaderCardEditing-item dropdown">
  <span class="visuallyhidden" id="user_location_label">Location</span>
<div class="RichEditor RichEditor--emojiPicker u-borderUserColorLight ProfileHeaderCardEditing-GeoLocationFieldInput">

  <div class="RichEditor-mozillaCursorWorkaround">&nbsp;</div>
  <div class="RichEditor-container u-borderRadiusInherit">

    <div class="RichEditor-scrollContainer u-borderRadiusInherit">
            <div id="user_location" name="user[location]" aria-multiline="true"
          aria-labelledby="user_location_label"
          class="ProfileHeaderCardEditing-editableField rich-editor u-borderUserColorLight GeoSearch-queryInput js-geo-search-trigger js-dropdown-prevent-close" data-placeholder="Location"
          maxlength="30" autocomplete="off" role="textbox" tabindex="2" data-initial-location="Egypt" contenteditable="true">Egypt</div>

      <div class="RichEditor-pictographs" aria-hidden="true"></div>
    </div>

          <div id="profile-geo-dropdown" class="GeoSearch-dropdownMenu dropdown-menu">
        <div class="dropdown-caret" aria-hidden="true">
          <span class="caret-outer"></span>
          <span class="caret-inner"></span>
        </div>
        <ul role="presentation">
          <li class="GeoSearch-searchStatus" role="presentation"></li>
        </ul>
        <ul class="GeoSearch-resultsContainer" role="presentation">
        </ul>
      </div>
      <input class="ProfileHeaderCardEditing-locationPlaceId GeoSearch-placeId" type="hidden" name="user[location_place_id]" value="c659cb4912229666">

  </div>
  <div class="RichEditor-mozillaCursorWorkaround">&nbsp;</div>
</div>
</div>

  <div class="ProfileHeaderCardEditing-url ProfileHeaderCardEditing-item">
    <input type="text" id="user_url" name="user[url]"
           class="ProfileHeaderCardEditing-editableField u-borderUserColorLight"
           value="" placeholder="Website"
           maxlength="100" autocomplete="off" tabindex="2">
  </div>
  <div class="ProfileHeaderCardEditing-userColor ProfileHeaderCardEditing-item dropdown">
    <button type="button" id="js-userColorButton" name="user[profile_link_color]"
            class="ProfileHeaderCardEditing-userColorButton u-bgUserColor u-bgUserColorDarkHover u-bgUserColorDarkerActive js-current-color js-dropdown-toggle"
            data-color="#1B95E0" tabindex="2">
      Theme color
    </button>
  </div>
</div>


  <div class="ProfileHeaderCardEditing ProfileHeaderCardEditing--extraFields u-bgUserColorLighter">
      <div class="ProfileHeaderCardEditing-item">
        <div class="BirthdateSelect " aria-labelledby="BirthdateSelect-label">
    <button type="button" class="BirthdateSelect-button u-borderUserColorLight " tabindex="2">
        October 25, 1991
    </button>

  <fieldset class="BirthdateSelect-fields " tabindex="2">

      <legend class="BirthdateSelect-label" id="BirthdateSelect-label">
        Birthday
      </legend>
    <div class="BirthdateSelect-birthday u-inlineBlock">
        <select
  class="BirthdateSelect-selectField BirthdateSelect-month js-dateSelector js-dateSelectMonth u-borderUserColorLight t1-select"
  name="user[birthdate][month]"
  tabindex="2"
  aria-label="Month">
  <option aria-label="Remove" value>Month</option>
    <option value="1" >January</option>
    <option value="2" >February</option>
    <option value="3" >March</option>
    <option value="4" >April</option>
    <option value="5" >May</option>
    <option value="6" >June</option>
    <option value="7" >July</option>
    <option value="8" >August</option>
    <option value="9" >September</option>
    <option value="10" selected>October</option>
    <option value="11" >November</option>
    <option value="12" >December</option>
</select>

      <select
  class="BirthdateSelect-selectField BirthdateSelect-day js-dateSelector js-dateSelectDay u-borderUserColorLight t1-select"
  name="user[birthdate][day]"
  tabindex="2"
  aria-label="Day">
  <option aria-label="Remove" value>Day</option>
    <option value="1" >1</option>
    <option value="2" >2</option>
    <option value="3" >3</option>
    <option value="4" >4</option>
    <option value="5" >5</option>
    <option value="6" >6</option>
    <option value="7" >7</option>
    <option value="8" >8</option>
    <option value="9" >9</option>
    <option value="10" >10</option>
    <option value="11" >11</option>
    <option value="12" >12</option>
    <option value="13" >13</option>
    <option value="14" >14</option>
    <option value="15" >15</option>
    <option value="16" >16</option>
    <option value="17" >17</option>
    <option value="18" >18</option>
    <option value="19" >19</option>
    <option value="20" >20</option>
    <option value="21" >21</option>
    <option value="22" >22</option>
    <option value="23" >23</option>
    <option value="24" >24</option>
    <option value="25" selected>25</option>
    <option value="26" >26</option>
    <option value="27" >27</option>
    <option value="28" >28</option>
    <option value="29" >29</option>
    <option value="30" >30</option>
    <option value="31" >31</option>
</select>


        <div class="VisibilitySettings u-inlineBlock u-textRight">
  <div class="dropdown">
    <button class="VisibilitySettings-button dropdown-toggle js-tooltip js-dropdown-toggle js-keepTabIndex"
      type="button"
      title="Only me"
      tabindex="2"
      aria-label="Birthday visibility, Only me">
          <span class="VisibilitySettings-dropdownIcon Icon Icon--visibilityPublic visibilityPublic-4 Icon--medium u-hidden u-alignMiddle"></span>
          <span class="VisibilitySettings-dropdownIcon Icon Icon--visibilityFollowers visibilityFollowers-3 Icon--medium u-hidden u-alignMiddle"></span>
          <span class="VisibilitySettings-dropdownIcon Icon Icon--visibilityFollowing visibilityFollowing-2 Icon--medium u-hidden u-alignMiddle"></span>
          <span class="VisibilitySettings-dropdownIcon Icon Icon--visibilityMutual visibilityMutual-1 Icon--medium u-hidden u-alignMiddle"></span>
          <span class="VisibilitySettings-dropdownIcon Icon Icon--visibilityOnlyMe visibilityOnlyMe-0 Icon--medium  u-alignMiddle"></span>
        <span class="VisibilitySettings-dropdownCaret Icon Icon--chevronDown Icon--smallest"></span>
    </button>
    <div class="VisibilitySettings-menu dropdown-menu is-autoCentered u-textLeft">
      <div class="dropdown-caret">
        <div class="caret-outer"></div>
        <div class="caret-inner"></div>
      </div>
      <ul>
          <li>
            <button class="VisibilitySettings-option dropdown-link" data-option-value="4" tabindex="2">
              <span class="VisibilitySettings-optionIcon Icon Icon--visibilityPublic Icon--medium" role="presentation" aria-hidden="true"></span>
              <span class="VisibilitySettings-optionDisplay">Public</span>
              <input class="js-visibilitySettingsRadio u-hiddenVisually"
                     type="radio"
                     name="user[birthdate][birthday_visibility]"
                     value="4"

                     data-dropdown-icon-selector=".visibilityPublic-4"
                     data-setting-name="Public">
            </button>
          </li>
          <li>
            <button class="VisibilitySettings-option dropdown-link" data-option-value="3" tabindex="2">
              <span class="VisibilitySettings-optionIcon Icon Icon--visibilityFollowers Icon--medium" role="presentation" aria-hidden="true"></span>
              <span class="VisibilitySettings-optionDisplay">My followers</span>
              <input class="js-visibilitySettingsRadio u-hiddenVisually"
                     type="radio"
                     name="user[birthdate][birthday_visibility]"
                     value="3"

                     data-dropdown-icon-selector=".visibilityFollowers-3"
                     data-setting-name="My followers">
            </button>
          </li>
          <li>
            <button class="VisibilitySettings-option dropdown-link" data-option-value="2" tabindex="2">
              <span class="VisibilitySettings-optionIcon Icon Icon--visibilityFollowing Icon--medium" role="presentation" aria-hidden="true"></span>
              <span class="VisibilitySettings-optionDisplay">People I follow</span>
              <input class="js-visibilitySettingsRadio u-hiddenVisually"
                     type="radio"
                     name="user[birthdate][birthday_visibility]"
                     value="2"

                     data-dropdown-icon-selector=".visibilityFollowing-2"
                     data-setting-name="People I follow">
            </button>
          </li>
          <li>
            <button class="VisibilitySettings-option dropdown-link" data-option-value="1" tabindex="2">
              <span class="VisibilitySettings-optionIcon Icon Icon--visibilityMutual Icon--medium" role="presentation" aria-hidden="true"></span>
              <span class="VisibilitySettings-optionDisplay">We follow each other</span>
              <input class="js-visibilitySettingsRadio u-hiddenVisually"
                     type="radio"
                     name="user[birthdate][birthday_visibility]"
                     value="1"

                     data-dropdown-icon-selector=".visibilityMutual-1"
                     data-setting-name="We follow each other">
            </button>
          </li>
          <li>
            <button class="VisibilitySettings-option dropdown-link" data-option-value="0" tabindex="2">
              <span class="VisibilitySettings-optionIcon Icon Icon--visibilityOnlyMe Icon--medium" role="presentation" aria-hidden="true"></span>
              <span class="VisibilitySettings-optionDisplay">Only me</span>
              <input class="js-visibilitySettingsRadio u-hiddenVisually"
                     type="radio"
                     name="user[birthdate][birthday_visibility]"
                     value="0"
                     checked="checked"
                     data-dropdown-icon-selector=".visibilityOnlyMe-0"
                     data-setting-name="Only me">
            </button>
          </li>
          <li class="VisibilitySettings-learnMore">
            <a class="VisibilitySettings-learnMoreLink u-textUserColor" href="//support.twitter.com/articles/20172733" target="_blank" tabindex="2" rel="noopener">
              Learn more about these settings
            </a>
          </li>
      </ul>
    </div>
  </div>
</div>
    </div>
    <div class="BirthdateSelect-birthyear u-inlineBlock">
      <select
  class="BirthdateSelect-selectField BirthdateSelect-year js-dateSelector js-dateSelectYear u-borderUserColorLight t1-select"
  name="user[birthdate][year]"
  tabindex="2"
  aria-label="Year">
  <option aria-label="Remove" value>Year</option>
    <option value="1998" >1998</option>
    <option value="1997" >1997</option>
    <option value="1996" >1996</option>
    <option value="1995" >1995</option>
    <option value="1994" >1994</option>
    <option value="1993" >1993</option>
    <option value="1992" >1992</option>
    <option value="1991" selected>1991</option>
    <option value="1990" >1990</option>
    <option value="1989" >1989</option>
    <option value="1988" >1988</option>
    <option value="1987" >1987</option>
    <option value="1986" >1986</option>
    <option value="1985" >1985</option>
    <option value="1984" >1984</option>
    <option value="1983" >1983</option>
    <option value="1982" >1982</option>
    <option value="1981" >1981</option>
    <option value="1980" >1980</option>
    <option value="1979" >1979</option>
    <option value="1978" >1978</option>
    <option value="1977" >1977</option>
    <option value="1976" >1976</option>
    <option value="1975" >1975</option>
    <option value="1974" >1974</option>
    <option value="1973" >1973</option>
    <option value="1972" >1972</option>
    <option value="1971" >1971</option>
    <option value="1970" >1970</option>
    <option value="1969" >1969</option>
    <option value="1968" >1968</option>
    <option value="1967" >1967</option>
    <option value="1966" >1966</option>
    <option value="1965" >1965</option>
    <option value="1964" >1964</option>
    <option value="1963" >1963</option>
    <option value="1962" >1962</option>
    <option value="1961" >1961</option>
    <option value="1960" >1960</option>
    <option value="1959" >1959</option>
    <option value="1958" >1958</option>
    <option value="1957" >1957</option>
    <option value="1956" >1956</option>
    <option value="1955" >1955</option>
    <option value="1954" >1954</option>
    <option value="1953" >1953</option>
    <option value="1952" >1952</option>
    <option value="1951" >1951</option>
    <option value="1950" >1950</option>
    <option value="1949" >1949</option>
    <option value="1948" >1948</option>
    <option value="1947" >1947</option>
    <option value="1946" >1946</option>
    <option value="1945" >1945</option>
    <option value="1944" >1944</option>
    <option value="1943" >1943</option>
    <option value="1942" >1942</option>
    <option value="1941" >1941</option>
    <option value="1940" >1940</option>
    <option value="1939" >1939</option>
    <option value="1938" >1938</option>
    <option value="1937" >1937</option>
    <option value="1936" >1936</option>
    <option value="1935" >1935</option>
    <option value="1934" >1934</option>
    <option value="1933" >1933</option>
    <option value="1932" >1932</option>
    <option value="1931" >1931</option>
    <option value="1930" >1930</option>
    <option value="1929" >1929</option>
    <option value="1928" >1928</option>
    <option value="1927" >1927</option>
    <option value="1926" >1926</option>
    <option value="1925" >1925</option>
    <option value="1924" >1924</option>
    <option value="1923" >1923</option>
    <option value="1922" >1922</option>
    <option value="1921" >1921</option>
    <option value="1920" >1920</option>
    <option value="1919" >1919</option>
    <option value="1918" >1918</option>
    <option value="1917" >1917</option>
    <option value="1916" >1916</option>
    <option value="1915" >1915</option>
    <option value="1914" >1914</option>
    <option value="1913" >1913</option>
    <option value="1912" >1912</option>
    <option value="1911" >1911</option>
    <option value="1910" >1910</option>
    <option value="1909" >1909</option>
    <option value="1908" >1908</option>
    <option value="1907" >1907</option>
    <option value="1906" >1906</option>
    <option value="1905" >1905</option>
    <option value="1904" >1904</option>
    <option value="1903" >1903</option>
    <option value="1902" >1902</option>
    <option value="1901" >1901</option>
    <option value="1900" >1900</option>
    <option value="1899" >1899</option>
    <option value="1898" >1898</option>
    <option value="1897" >1897</option>
    <option value="1896" >1896</option>
    <option value="1895" >1895</option>
    <option value="1894" >1894</option>
    <option value="1893" >1893</option>
    <option value="1892" >1892</option>
    <option value="1891" >1891</option>
</select>

        <div class="VisibilitySettings u-inlineBlock u-textRight">
  <div class="dropdown">
    <button class="VisibilitySettings-button dropdown-toggle js-tooltip js-dropdown-toggle js-keepTabIndex"
      type="button"
      title="Only me"
      tabindex="2"
      aria-label="Birth year visibility, Only me">
          <span class="VisibilitySettings-dropdownIcon Icon Icon--visibilityPublic visibilityPublic-4 Icon--medium u-hidden u-alignMiddle"></span>
          <span class="VisibilitySettings-dropdownIcon Icon Icon--visibilityFollowers visibilityFollowers-3 Icon--medium u-hidden u-alignMiddle"></span>
          <span class="VisibilitySettings-dropdownIcon Icon Icon--visibilityFollowing visibilityFollowing-2 Icon--medium u-hidden u-alignMiddle"></span>
          <span class="VisibilitySettings-dropdownIcon Icon Icon--visibilityMutual visibilityMutual-1 Icon--medium u-hidden u-alignMiddle"></span>
          <span class="VisibilitySettings-dropdownIcon Icon Icon--visibilityOnlyMe visibilityOnlyMe-0 Icon--medium  u-alignMiddle"></span>
        <span class="VisibilitySettings-dropdownCaret Icon Icon--chevronDown Icon--smallest"></span>
    </button>
    <div class="VisibilitySettings-menu dropdown-menu is-autoCentered u-textLeft">
      <div class="dropdown-caret">
        <div class="caret-outer"></div>
        <div class="caret-inner"></div>
      </div>
      <ul>
          <li>
            <button class="VisibilitySettings-option dropdown-link" data-option-value="4" tabindex="2">
              <span class="VisibilitySettings-optionIcon Icon Icon--visibilityPublic Icon--medium" role="presentation" aria-hidden="true"></span>
              <span class="VisibilitySettings-optionDisplay">Public</span>
              <input class="js-visibilitySettingsRadio u-hiddenVisually"
                     type="radio"
                     name="user[birthdate][birthyear_visibility]"
                     value="4"

                     data-dropdown-icon-selector=".visibilityPublic-4"
                     data-setting-name="Public">
            </button>
          </li>
          <li>
            <button class="VisibilitySettings-option dropdown-link" data-option-value="3" tabindex="2">
              <span class="VisibilitySettings-optionIcon Icon Icon--visibilityFollowers Icon--medium" role="presentation" aria-hidden="true"></span>
              <span class="VisibilitySettings-optionDisplay">My followers</span>
              <input class="js-visibilitySettingsRadio u-hiddenVisually"
                     type="radio"
                     name="user[birthdate][birthyear_visibility]"
                     value="3"

                     data-dropdown-icon-selector=".visibilityFollowers-3"
                     data-setting-name="My followers">
            </button>
          </li>
          <li>
            <button class="VisibilitySettings-option dropdown-link" data-option-value="2" tabindex="2">
              <span class="VisibilitySettings-optionIcon Icon Icon--visibilityFollowing Icon--medium" role="presentation" aria-hidden="true"></span>
              <span class="VisibilitySettings-optionDisplay">People I follow</span>
              <input class="js-visibilitySettingsRadio u-hiddenVisually"
                     type="radio"
                     name="user[birthdate][birthyear_visibility]"
                     value="2"

                     data-dropdown-icon-selector=".visibilityFollowing-2"
                     data-setting-name="People I follow">
            </button>
          </li>
          <li>
            <button class="VisibilitySettings-option dropdown-link" data-option-value="1" tabindex="2">
              <span class="VisibilitySettings-optionIcon Icon Icon--visibilityMutual Icon--medium" role="presentation" aria-hidden="true"></span>
              <span class="VisibilitySettings-optionDisplay">We follow each other</span>
              <input class="js-visibilitySettingsRadio u-hiddenVisually"
                     type="radio"
                     name="user[birthdate][birthyear_visibility]"
                     value="1"

                     data-dropdown-icon-selector=".visibilityMutual-1"
                     data-setting-name="We follow each other">
            </button>
          </li>
          <li>
            <button class="VisibilitySettings-option dropdown-link" data-option-value="0" tabindex="2">
              <span class="VisibilitySettings-optionIcon Icon Icon--visibilityOnlyMe Icon--medium" role="presentation" aria-hidden="true"></span>
              <span class="VisibilitySettings-optionDisplay">Only me</span>
              <input class="js-visibilitySettingsRadio u-hiddenVisually"
                     type="radio"
                     name="user[birthdate][birthyear_visibility]"
                     value="0"
                     checked="checked"
                     data-dropdown-icon-selector=".visibilityOnlyMe-0"
                     data-setting-name="Only me">
            </button>
          </li>
          <li class="VisibilitySettings-learnMore">
            <a class="VisibilitySettings-learnMoreLink u-textUserColor" href="//support.twitter.com/articles/20172733" target="_blank" tabindex="2" rel="noopener">
              Learn more about these settings
            </a>
          </li>
      </ul>
    </div>
  </div>
</div>
    </div>
  </fieldset>
</div>
      </div>
  </div>




      <div class="PhotoRail">
  <div class="PhotoRail-heading">
    <span class="Icon Icon--camera Icon--medium"></span>
    <span class="PhotoRail-headingText">
            <a href="/mohammed_attya/media" class="PhotoRail-headingWithCount js-nav">

                749 Photos and videos
            </a>
          <a href="/mohammed_attya/media" class="PhotoRail-headingWithoutCount js-nav">
            Photos and videos
          </a>
    </span>
  </div>
  <div class="PhotoRail-mediaBox">
    <span class="js-photoRailInsertPoint"></span>
  </div>
</div>



</div>

              </div>
            </div>
          </div>

          <div class="Grid-cell u-size2of3 u-lg-size3of4">
            <div class="Grid Grid--withGutter">
                <div class="Grid-cell">
                  <div class="js-profileClusterFollow"></div>
                </div>

              <div class="Grid-cell
                    u-lg-size2of3
              " data-test-selector="ProfileTimeline">

                    <div class="ProfileHeading">
  <div class="ProfileHeading-spacer"></div>
    <div class="ProfileHeading-content">
      <h2 id="content-main-heading" class="ProfileHeading-title u-hiddenVisually ">Tweets</h2>
        <ul class="ProfileHeading-toggle">
            <li class="ProfileHeading-toggleItem  is-active"
              data-element-term="tweets_toggle">
                <span aria-hidden="true">Tweets</span>
                <span class="u-hiddenVisually">Tweets, current page.</span>
            </li>
            <li class="ProfileHeading-toggleItem  u-textUserColor"
              data-element-term="tweets_with_replies_toggle">
                <a class="ProfileHeading-toggleLink js-nav"
                href="/mohammed_attya/with_replies"
                data-nav="tweets_with_replies_toggle">
                  Tweets &amp; replies
                </a>
            </li>
            <li class="ProfileHeading-toggleItem  u-textUserColor"
              data-element-term="photos_and_videos_toggle">
                <a class="ProfileHeading-toggleLink js-nav"
                href="/mohammed_attya/media"
                data-nav="photos_and_videos_toggle">
                  Media
                </a>
            </li>
        </ul>
    </div>
</div>

                  <div class="ProfileWarningTimeline" data-element-context="blocked_profile">
  <h2 class="ProfileWarningTimeline-heading" id="content-main-heading">You blocked <span class="username u-dir" dir="ltr" >@<b>mohammed_attya</b></span></h2>
  <p class="ProfileWarningTimeline-explanation">Are you sure you want to view these Tweets? Viewing Tweets won"t unblock <span class="username u-dir" dir="ltr" >@<b>mohammed_attya</b></span></p>
  <button class="EdgeButton EdgeButton--tertiary ProfileWarningTimeline-button">Yes, view profile</button>
</div>





  <div id="scroll-bump-dialog" class="ScrollBumpDialog modal-container">
  <div class="modal draggable">
    <div class="modal-content clearfix">

      <button type="button" class="modal-btn modal-close js-close">
  <span class="Icon Icon--close Icon--medium">
    <span class="visuallyhidden">Close</span>
  </span>
</button>


      <div class="modal-header">
        <h3 class="modal-title">
            You followed

        </h3>
      </div>

      <div class="modal-body">
        <div class="loading">
          <span class="spinner-bigger"></span>
        </div>
        <ol class="ScrollBumpDialog-usersList clearfix js-users-list"></ol>
      </div>
    </div>
  </div>
</div>





    <div id="timeline" class="ProfileTimeline ">
        <div class="stream-container  "
    data-max-position="901914127739834371" data-min-position="899733729945546752"
    >
      <div class="stream-item js-new-items-bar-container">
</div>

    <div class="stream">
        <ol class="stream-items js-navigable-stream" id="stream-items-id">

      <li class="js-stream-item stream-item stream-item
" data-item-id="901914056491229184"
id="stream-item-tweet-901914056491229184"
data-item-type="tweet"
 data-suggestion-json="{&quot;suggestion_details&quot;:{},&quot;tweet_ids&quot;:&quot;901914056491229184&quot;,&quot;scribe_component&quot;:&quot;tweet&quot;}">




  <div class="tweet js-stream-tweet js-actionable-tweet js-profile-popup-actionable dismissible-content
       original-tweet js-original-tweet

       retweeted has-cards  has-content
"

data-tweet-id="901914056491229184"
data-item-id="901914056491229184"
data-permalink-path="/aezzarab25/status/901914056491229184"
data-conversation-id="901914056491229184"



data-tweet-nonce="901914056491229184-04a3cf7f-af32-4d93-8ee2-d8e2e0902751"
data-tweet-stat-initialized="true"



 data-my-retweet-id="901914127739834371"
    data-retweet-id="901914127739834371"
     data-retweeter="mohammed_attya"


  data-screen-name="aezzarab25" data-name="ahmed ezzarab" data-user-id="1303786886"
  data-you-follow="true"
  data-follows-you="false"
  data-you-block="false"


data-reply-to-users-json="[{&quot;id_str&quot;:&quot;1303786886&quot;,&quot;screen_name&quot;:&quot;aezzarab25&quot;,&quot;name&quot;:&quot;ahmed ezzarab&quot;,&quot;emojified_name&quot;:{&quot;text&quot;:&quot;ahmed ezzarab&quot;,&quot;emojified_text_as_html&quot;:&quot;ahmed ezzarab&quot;}}]"







data-disclosure-type=""




 data-has-cards="true"








    >

    <div class="context">

          <div class="tweet-context with-icn

    ">

      <span class="Icon Icon--small Icon--retweeted"></span>



            <span class="js-retweet-text">
                <a class="pretty-link js-user-profile-link" href="/mohammed_attya" data-user-id="250377148" rel="noopener"><b>You</b></a> Retweeted
            </span>





    </div>

    </div>

    <div class="content">





      <div class="stream-item-header">
          <a  class="account-group js-account-group js-action-profile js-user-profile-link js-nav" href="/aezzarab25" data-user-id="1303786886">
    <img class="avatar js-action-profile-avatar" src="https://pbs.twimg.com/profile_images/669197285746700290/5_kFLJRX_bigger.jpg" alt="">
    <span class="FullNameGroup">
      <strong class="fullname show-popup-with-id " data-aria-label-part>ahmed ezzarab</strong><span>&rlm;</span><span class="UserBadges"></span><span class="UserNameBreak">&nbsp;</span></span><span class="username u-dir" dir="ltr" data-aria-label-part>@<b>aezzarab25</b></span></a>


        <small class="time">
  <a href="/aezzarab25/status/901914056491229184" class="tweet-timestamp js-permalink js-nav js-tooltip" title="11:07 PM - 27 Aug 2017"  data-conversation-id="901914056491229184"><span class="_timestamp js-short-timestamp " data-aria-label-part="last" data-time="1503868042" data-time-ms="1503868042000" data-long-form="true">Aug 27</span></a>
</small>

          <div class="ProfileTweet-action ProfileTweet-action--more js-more-ProfileTweet-actions">
    <div class="dropdown">
  <button class="ProfileTweet-actionButton u-textUserColorHover dropdown-toggle js-dropdown-toggle" type="button">
      <div class="IconContainer js-tooltip" title="More">
        <span class="Icon Icon--caretDownLight Icon--small"></span>
        <span class="u-hiddenVisually">More</span>
      </div>
  </button>
  <div class="dropdown-menu is-autoCentered">
  <div class="dropdown-caret">
    <div class="caret-outer"></div>
    <div class="caret-inner"></div>
  </div>
  <ul>

      <li class="copy-link-to-tweet js-actionCopyLinkToTweet">
        <button type="button" class="dropdown-link">Copy link to Tweet</button>
      </li>
      <li class="embed-link js-actionEmbedTweet" data-nav="embed_tweet">
        <button type="button" class="dropdown-link">Embed Tweet</button>
      </li>
          <li class="mute-user-item"><button type="button" class="dropdown-link">Mute <span class="username u-dir" dir="ltr" >@<b>aezzarab25</b></span></button></li>
    <li class="unmute-user-item"><button type="button" class="dropdown-link">Unmute <span class="username u-dir" dir="ltr" >@<b>aezzarab25</b></span></button></li>

        <li class="block-link js-actionBlock" data-nav="block">
          <button type="button" class="dropdown-link">Block <span class="username u-dir" dir="ltr" >@<b>aezzarab25</b></span></button>
        </li>
        <li class="unblock-link js-actionUnblock" data-nav="unblock">
          <button type="button" class="dropdown-link">Unblock <span class="username u-dir" dir="ltr" >@<b>aezzarab25</b></span></button>
        </li>
      <li class="report-link js-actionReport" data-nav="report">
        <button type="button" class="dropdown-link">


            Report Tweet
        </button>
      </li>
      <li class="dropdown-divider"></li>
      <li class="js-actionMomentMakerAddTweetToOtherMoment MomentMakerAddTweetToOtherMoment">
        <button type="button" class="dropdown-link">Add to other Moment</button>
      </li>
      <li class="js-actionMomentMakerCreateMoment">
        <button type="button" class="dropdown-link">Add to new Moment</button>
      </li>
  </ul>
</div>
</div>

  </div>

      </div>





        <div class="js-tweet-text-container">
  <p class="TweetTextSize TweetTextSize--normal js-tweet-text tweet-text tweet-text-rtl" lang="ar" data-aria-label-part="0">كانت رسوم الحج تقليد شعبى شائع له مبدعوه من الفنانين التلقائيين يمارسونه كعمل موسمى مؤقت فى اغلب المحافظات قبل ان يتراجع الى  بعض من الصعيد<a href="https://t.co/qGwdsWX0Wc" class="twitter-timeline-link u-hidden" data-pre-embedded="true" dir="ltr" >pic.twitter.com/qGwdsWX0Wc</a></p>
</div>


        <button type="button"
      class="btn-link js-translate-tweet translate-button u-textUserColorHover"
      data-nav="translate_tweet"
    >
    <span class="Icon Icon--translator"></span>Translate from Arabic
  </button>
  <div class="tweet-translation-container">
  <div class="spinner tweet-translation-loading" title="Loading..."></div>
  <div class="tweet-translation needs-translation" data-dest-lang="en">
    <div class="translation-attribution">
      <span>Translated from <span class="tweet-language">Arabic</span> by <a class="attribution-logo" href="http://aka.ms/Twitter2BingTranslator"><span class="invisible">Bing</span></a></span>
        <button type="button" class="btn-link js-translation-feedback-button" data-nav="">Wrong translation?</button>
    </div>
    <p class="tweet-translation-text"></p>
  </div>
</div>



            <div class="AdaptiveMediaOuterContainer">
    <div class="AdaptiveMedia


        is-square



        "
          style="max-width:400px;"
      >
      <div class="AdaptiveMedia-container">
          <div class="AdaptiveMedia-singlePhoto"
    style="padding-top: calc(0.75 * 100% - 0.5px);"
>
    <div class="AdaptiveMedia-photoContainer js-adaptive-photo "
  data-image-url="https://pbs.twimg.com/media/DIQ8tQrXkAEcMK3.jpg"


  data-element-context="platform_photo_card"
    style="background-color:rgba(64,54,39,1.0);"
    data-dominant-color="[64,54,39]"
>
  <img data-aria-label-part src="https://pbs.twimg.com/media/DIQ8tQrXkAEcMK3.jpg" alt=""
>
</div>


</div>
      </div>
    </div>
  </div>








      <div class="stream-item-footer">

      <div class="ProfileTweet-actionCountList u-hiddenVisually">


    <span class="ProfileTweet-action--reply u-hiddenVisually">
      <span class="ProfileTweet-actionCount" aria-hidden="true" data-tweet-stat-count="0">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-reply-count-aria-901914056491229184" >0 replies</span>
      </span>
    </span>
    <span class="ProfileTweet-action--retweet u-hiddenVisually">
      <span class="ProfileTweet-actionCount"  data-tweet-stat-count="5">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-retweet-count-aria-901914056491229184" data-aria-label-part>5 retweets</span>
      </span>
    </span>
    <span class="ProfileTweet-action--favorite u-hiddenVisually">
      <span class="ProfileTweet-actionCount"  data-tweet-stat-count="15">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-favorite-count-aria-901914056491229184" data-aria-label-part>15 likes</span>
      </span>
    </span>
  </div>

  <div class="ProfileTweet-actionList js-actions" role="group" aria-label="Tweet actions">
    <div class="ProfileTweet-action ProfileTweet-action--reply">
  <button class="ProfileTweet-actionButton js-actionButton js-actionReply"
    data-modal="ProfileTweet-reply" type="button"
    aria-describedby="profile-tweet-action-reply-count-aria-901914056491229184">
    <div class="IconContainer js-tooltip" title="Reply">
      <span class="Icon Icon--medium Icon--reply"></span>
      <span class="u-hiddenVisually">Reply</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero ">
        <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
      </span>
  </button>
</div>

    <div class="ProfileTweet-action ProfileTweet-action--retweet js-toggleState js-toggleRt">
  <button class="ProfileTweet-actionButton  js-actionButton js-actionRetweet"

    data-modal="ProfileTweet-retweet"
    type="button"
    aria-describedby="profile-tweet-action-retweet-count-aria-901914056491229184">
    <div class="IconContainer js-tooltip" title="Retweet">
      <span class="Icon Icon--medium Icon--retweet"></span>
      <span class="u-hiddenVisually">Retweet</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">5</span>
  </span>

  </button><button class="ProfileTweet-actionButtonUndo js-actionButton js-actionRetweet" data-modal="ProfileTweet-retweet" type="button">
    <div class="IconContainer js-tooltip" title="Undo retweet">
      <span class="Icon Icon--medium Icon--retweet"></span>
      <span class="u-hiddenVisually">Retweeted</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">5</span>
  </span>

  </button>
</div>


    <div class="ProfileTweet-action ProfileTweet-action--favorite js-toggleState">
  <button class="ProfileTweet-actionButton js-actionButton js-actionFavorite" type="button"
    aria-describedby="profile-tweet-action-favorite-count-aria-901914056491229184">
    <div class="IconContainer js-tooltip" title="Like">
      <span role="presentation" class="Icon Icon--heart Icon--medium"></span>
      <div class="HeartAnimation"></div>
      <span class="u-hiddenVisually">Like</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">15</span>
  </span>

  </button><button class="ProfileTweet-actionButtonUndo ProfileTweet-action--unfavorite u-linkClean js-actionButton js-actionFavorite" type="button">
    <div class="IconContainer js-tooltip" title="Undo like">
      <span role="presentation" class="Icon Icon--heart Icon--medium"></span>
      <div class="HeartAnimation"></div>
      <span class="u-hiddenVisually">Liked</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">15</span>
  </span>

  </button>
</div>


      <div class="ProfileTweet-action ProfileTweet-action--dm">
    <button class="ProfileTweet-actionButton u-textUserColorHover js-actionButton js-actionShareViaDM"
      type="button"
      data-nav="share_tweet_dm"
    >
      <div class="IconContainer js-tooltip" title="Direct message">
        <span class="Icon Icon--medium Icon--dm"></span>
        <span class="u-hiddenVisually">Direct message</span>
      </div>
    </button>
  </div>




  </div>

</div>







    </div>
  </div>




<div class="dismiss-module">
  <div class="dismissed-module">
      <div class="feedback-action" data-feedback-type="DontLike">
        <div class="action-confirmation">Thanks. Twitter will use this to make your timeline better. <span class="undo-action u-textUserColor">Undo</span></div>
      </div>
  </div>
</div>

</li>

      <li class="js-stream-item stream-item stream-item
" data-item-id="901555821775114240"
id="stream-item-tweet-901555821775114240"
data-item-type="tweet"
 data-suggestion-json="{&quot;suggestion_details&quot;:{},&quot;tweet_ids&quot;:&quot;901555821775114240&quot;,&quot;scribe_component&quot;:&quot;tweet&quot;}">




  <div class="tweet js-stream-tweet js-actionable-tweet js-profile-popup-actionable dismissible-content
       original-tweet js-original-tweet

       retweeted
"

data-tweet-id="901555821775114240"
data-item-id="901555821775114240"
data-permalink-path="/WesalHammam/status/901555821775114240"
data-conversation-id="901555821775114240"



data-tweet-nonce="901555821775114240-36843be4-61be-4feb-b261-0500ce8bd48a"
data-tweet-stat-initialized="true"



 data-my-retweet-id="901558793301090305"
    data-retweet-id="901558793301090305"
     data-retweeter="mohammed_attya"


  data-screen-name="WesalHammam" data-name="ويزو" data-user-id="857335607135285248"
  data-you-follow="false"
  data-follows-you="false"
  data-you-block="false"


data-reply-to-users-json="[{&quot;id_str&quot;:&quot;857335607135285248&quot;,&quot;screen_name&quot;:&quot;WesalHammam&quot;,&quot;name&quot;:&quot;\u0648\u064a\u0632\u0648&quot;,&quot;emojified_name&quot;:{&quot;text&quot;:&quot;\u0648\u064a\u0632\u0648&quot;,&quot;emojified_text_as_html&quot;:&quot;\u0648\u064a\u0632\u0648&quot;}}]"







data-disclosure-type=""













    >

    <div class="context">

          <div class="tweet-context with-icn

    ">

      <span class="Icon Icon--small Icon--retweeted"></span>



            <span class="js-retweet-text">
                <a class="pretty-link js-user-profile-link" href="/mohammed_attya" data-user-id="250377148" rel="noopener"><b>You</b></a> Retweeted
            </span>





    </div>

    </div>

    <div class="content">





      <div class="stream-item-header">
          <a  class="account-group js-account-group js-action-profile js-user-profile-link js-nav" href="/WesalHammam" data-user-id="857335607135285248">
    <img class="avatar js-action-profile-avatar" src="https://pbs.twimg.com/profile_images/902306438630055936/RnhHU9m0_bigger.jpg" alt="">
    <span class="FullNameGroup">
      <strong class="fullname show-popup-with-id fullname-rtl" data-aria-label-part>ويزو</strong><span>&rlm;</span><span class="UserBadges"></span><span class="UserNameBreak">&nbsp;</span></span><span class="username u-dir" dir="ltr" data-aria-label-part>@<b>WesalHammam</b></span></a>


        <small class="time">
  <a href="/WesalHammam/status/901555821775114240" class="tweet-timestamp js-permalink js-nav js-tooltip" title="11:23 PM - 26 Aug 2017"  data-conversation-id="901555821775114240"><span class="_timestamp js-short-timestamp " data-aria-label-part="last" data-time="1503782632" data-time-ms="1503782632000" data-long-form="true">Aug 26</span></a>
</small>

          <div class="ProfileTweet-action ProfileTweet-action--more js-more-ProfileTweet-actions">
    <div class="dropdown">
  <button class="ProfileTweet-actionButton u-textUserColorHover dropdown-toggle js-dropdown-toggle" type="button">
      <div class="IconContainer js-tooltip" title="More">
        <span class="Icon Icon--caretDownLight Icon--small"></span>
        <span class="u-hiddenVisually">More</span>
      </div>
  </button>
  <div class="dropdown-menu is-autoCentered">
  <div class="dropdown-caret">
    <div class="caret-outer"></div>
    <div class="caret-inner"></div>
  </div>
  <ul>

      <li class="copy-link-to-tweet js-actionCopyLinkToTweet">
        <button type="button" class="dropdown-link">Copy link to Tweet</button>
      </li>
      <li class="embed-link js-actionEmbedTweet" data-nav="embed_tweet">
        <button type="button" class="dropdown-link">Embed Tweet</button>
      </li>
          <li class="mute-user-item"><button type="button" class="dropdown-link">Mute <span class="username u-dir" dir="ltr" >@<b>WesalHammam</b></span></button></li>
    <li class="unmute-user-item"><button type="button" class="dropdown-link">Unmute <span class="username u-dir" dir="ltr" >@<b>WesalHammam</b></span></button></li>

        <li class="block-link js-actionBlock" data-nav="block">
          <button type="button" class="dropdown-link">Block <span class="username u-dir" dir="ltr" >@<b>WesalHammam</b></span></button>
        </li>
        <li class="unblock-link js-actionUnblock" data-nav="unblock">
          <button type="button" class="dropdown-link">Unblock <span class="username u-dir" dir="ltr" >@<b>WesalHammam</b></span></button>
        </li>
      <li class="report-link js-actionReport" data-nav="report">
        <button type="button" class="dropdown-link">


            Report Tweet
        </button>
      </li>
      <li class="dropdown-divider"></li>
      <li class="js-actionMomentMakerAddTweetToOtherMoment MomentMakerAddTweetToOtherMoment">
        <button type="button" class="dropdown-link">Add to other Moment</button>
      </li>
      <li class="js-actionMomentMakerCreateMoment">
        <button type="button" class="dropdown-link">Add to new Moment</button>
      </li>
  </ul>
</div>
</div>

  </div>

      </div>





        <div class="js-tweet-text-container">
  <p class="TweetTextSize TweetTextSize--normal js-tweet-text tweet-text tweet-text-rtl" lang="ar" data-aria-label-part="0">محتاجة كتب الأسباني بتاعت اولى و تانية و تالتة ثانوي، ممكن ألاقيها عند حد ؟ <a href="/hashtag/%D8%B1%D9%8A%D8%AA%D9%88%D9%8A%D8%AA?src=hash" data-query-source="hashtag_click" class="twitter-hashtag pretty-link js-nav" dir="rtl" ><s>#</s><b>ريتويت</b></a></p>
</div>


        <button type="button"
      class="btn-link js-translate-tweet translate-button u-textUserColorHover"
      data-nav="translate_tweet"
    >
    <span class="Icon Icon--translator"></span>Translate from Arabic
  </button>
  <div class="tweet-translation-container">
  <div class="spinner tweet-translation-loading" title="Loading..."></div>
  <div class="tweet-translation needs-translation" data-dest-lang="en">
    <div class="translation-attribution">
      <span>Translated from <span class="tweet-language">Arabic</span> by <a class="attribution-logo" href="http://aka.ms/Twitter2BingTranslator"><span class="invisible">Bing</span></a></span>
        <button type="button" class="btn-link js-translation-feedback-button" data-nav="">Wrong translation?</button>
    </div>
    <p class="tweet-translation-text"></p>
  </div>
</div>










      <div class="stream-item-footer">

      <div class="ProfileTweet-actionCountList u-hiddenVisually">


    <span class="ProfileTweet-action--reply u-hiddenVisually">
      <span class="ProfileTweet-actionCount"  data-tweet-stat-count="1">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-reply-count-aria-901555821775114240" data-aria-label-part>1 reply</span>
      </span>
    </span>
    <span class="ProfileTweet-action--retweet u-hiddenVisually">
      <span class="ProfileTweet-actionCount"  data-tweet-stat-count="10">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-retweet-count-aria-901555821775114240" data-aria-label-part>10 retweets</span>
      </span>
    </span>
    <span class="ProfileTweet-action--favorite u-hiddenVisually">
      <span class="ProfileTweet-actionCount"  data-tweet-stat-count="2">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-favorite-count-aria-901555821775114240" data-aria-label-part>2 likes</span>
      </span>
    </span>
  </div>

  <div class="ProfileTweet-actionList js-actions" role="group" aria-label="Tweet actions">
    <div class="ProfileTweet-action ProfileTweet-action--reply">
  <button class="ProfileTweet-actionButton js-actionButton js-actionReply"
    data-modal="ProfileTweet-reply" type="button"
    aria-describedby="profile-tweet-action-reply-count-aria-901555821775114240">
    <div class="IconContainer js-tooltip" title="Reply">
      <span class="Icon Icon--medium Icon--reply"></span>
      <span class="u-hiddenVisually">Reply</span>
    </div>
      <span class="ProfileTweet-actionCount ">
        <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">1</span>
      </span>
  </button>
</div>

    <div class="ProfileTweet-action ProfileTweet-action--retweet js-toggleState js-toggleRt">
  <button class="ProfileTweet-actionButton  js-actionButton js-actionRetweet"

    data-modal="ProfileTweet-retweet"
    type="button"
    aria-describedby="profile-tweet-action-retweet-count-aria-901555821775114240">
    <div class="IconContainer js-tooltip" title="Retweet">
      <span class="Icon Icon--medium Icon--retweet"></span>
      <span class="u-hiddenVisually">Retweet</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">10</span>
  </span>

  </button><button class="ProfileTweet-actionButtonUndo js-actionButton js-actionRetweet" data-modal="ProfileTweet-retweet" type="button">
    <div class="IconContainer js-tooltip" title="Undo retweet">
      <span class="Icon Icon--medium Icon--retweet"></span>
      <span class="u-hiddenVisually">Retweeted</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">10</span>
  </span>

  </button>
</div>


    <div class="ProfileTweet-action ProfileTweet-action--favorite js-toggleState">
  <button class="ProfileTweet-actionButton js-actionButton js-actionFavorite" type="button"
    aria-describedby="profile-tweet-action-favorite-count-aria-901555821775114240">
    <div class="IconContainer js-tooltip" title="Like">
      <span role="presentation" class="Icon Icon--heart Icon--medium"></span>
      <div class="HeartAnimation"></div>
      <span class="u-hiddenVisually">Like</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">2</span>
  </span>

  </button><button class="ProfileTweet-actionButtonUndo ProfileTweet-action--unfavorite u-linkClean js-actionButton js-actionFavorite" type="button">
    <div class="IconContainer js-tooltip" title="Undo like">
      <span role="presentation" class="Icon Icon--heart Icon--medium"></span>
      <div class="HeartAnimation"></div>
      <span class="u-hiddenVisually">Liked</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">2</span>
  </span>

  </button>
</div>


      <div class="ProfileTweet-action ProfileTweet-action--dm">
    <button class="ProfileTweet-actionButton u-textUserColorHover js-actionButton js-actionShareViaDM"
      type="button"
      data-nav="share_tweet_dm"
    >
      <div class="IconContainer js-tooltip" title="Direct message">
        <span class="Icon Icon--medium Icon--dm"></span>
        <span class="u-hiddenVisually">Direct message</span>
      </div>
    </button>
  </div>




  </div>

</div>







    </div>
  </div>




<div class="dismiss-module">
  <div class="dismissed-module">
      <div class="feedback-action" data-feedback-type="DontLike">
        <div class="action-confirmation">Thanks. Twitter will use this to make your timeline better. <span class="undo-action u-textUserColor">Undo</span></div>
      </div>
  </div>
</div>

</li>

      <li class="js-stream-item stream-item stream-item
" data-item-id="901544227250868226"
id="stream-item-tweet-901544227250868226"
data-item-type="tweet"
 data-suggestion-json="{&quot;suggestion_details&quot;:{},&quot;tweet_ids&quot;:&quot;901544227250868226&quot;,&quot;scribe_component&quot;:&quot;tweet&quot;}">




  <div class="tweet js-stream-tweet js-actionable-tweet js-profile-popup-actionable dismissible-content
       original-tweet js-original-tweet

       retweeted has-cards cards-forward
"

data-tweet-id="901544227250868226"
data-item-id="901544227250868226"
data-permalink-path="/7okaha/status/901544227250868226"
data-conversation-id="901544227250868226"



data-tweet-nonce="901544227250868226-f007db93-4437-4220-932c-9f264f1a82ae"
data-tweet-stat-initialized="true"



 data-my-retweet-id="901558174859198464"
    data-retweet-id="901558174859198464"
     data-retweeter="mohammed_attya"


  data-screen-name="7okaha" data-name="ana BABA yala" data-user-id="241700937"
  data-you-follow="true"
  data-follows-you="false"
  data-you-block="false"


data-reply-to-users-json="[{&quot;id_str&quot;:&quot;241700937&quot;,&quot;screen_name&quot;:&quot;7okaha&quot;,&quot;name&quot;:&quot;ana BABA yala&quot;,&quot;emojified_name&quot;:{&quot;text&quot;:&quot;ana BABA yala&quot;,&quot;emojified_text_as_html&quot;:&quot;ana BABA yala&quot;}}]"







data-disclosure-type=""



 data-card2-type="poll4choice_text_only"
 data-has-cards="true"








    >

    <div class="context">

          <div class="tweet-context with-icn

    ">

      <span class="Icon Icon--small Icon--retweeted"></span>



            <span class="js-retweet-text">
                <a class="pretty-link js-user-profile-link" href="/mohammed_attya" data-user-id="250377148" rel="noopener"><b>You</b></a> Retweeted
            </span>





    </div>

    </div>

    <div class="content">





      <div class="stream-item-header">
          <a  class="account-group js-account-group js-action-profile js-user-profile-link js-nav" href="/7okaha" data-user-id="241700937">
    <img class="avatar js-action-profile-avatar" src="https://pbs.twimg.com/profile_images/1579165154/Malcolm_X_bigger.jpg" alt="">
    <span class="FullNameGroup">
      <strong class="fullname show-popup-with-id " data-aria-label-part>ana BABA yala</strong><span>&rlm;</span><span class="UserBadges"></span><span class="UserNameBreak">&nbsp;</span></span><span class="username u-dir" dir="ltr" data-aria-label-part>@<b>7okaha</b></span></a>


        <small class="time">
  <a href="/7okaha/status/901544227250868226" class="tweet-timestamp js-permalink js-nav js-tooltip" title="10:37 PM - 26 Aug 2017"  data-conversation-id="901544227250868226"><span class="_timestamp js-short-timestamp " data-aria-label-part="last" data-time="1503779868" data-time-ms="1503779868000" data-long-form="true">Aug 26</span></a>
</small>

          <div class="ProfileTweet-action ProfileTweet-action--more js-more-ProfileTweet-actions">
    <div class="dropdown">
  <button class="ProfileTweet-actionButton u-textUserColorHover dropdown-toggle js-dropdown-toggle" type="button">
      <div class="IconContainer js-tooltip" title="More">
        <span class="Icon Icon--caretDownLight Icon--small"></span>
        <span class="u-hiddenVisually">More</span>
      </div>
  </button>
  <div class="dropdown-menu is-autoCentered">
  <div class="dropdown-caret">
    <div class="caret-outer"></div>
    <div class="caret-inner"></div>
  </div>
  <ul>

      <li class="copy-link-to-tweet js-actionCopyLinkToTweet">
        <button type="button" class="dropdown-link">Copy link to Tweet</button>
      </li>
      <li class="embed-link js-actionEmbedTweet" data-nav="embed_tweet">
        <button type="button" class="dropdown-link">Embed Tweet</button>
      </li>
          <li class="mute-user-item"><button type="button" class="dropdown-link">Mute <span class="username u-dir" dir="ltr" >@<b>7okaha</b></span></button></li>
    <li class="unmute-user-item"><button type="button" class="dropdown-link">Unmute <span class="username u-dir" dir="ltr" >@<b>7okaha</b></span></button></li>

        <li class="block-link js-actionBlock" data-nav="block">
          <button type="button" class="dropdown-link">Block <span class="username u-dir" dir="ltr" >@<b>7okaha</b></span></button>
        </li>
        <li class="unblock-link js-actionUnblock" data-nav="unblock">
          <button type="button" class="dropdown-link">Unblock <span class="username u-dir" dir="ltr" >@<b>7okaha</b></span></button>
        </li>
      <li class="report-link js-actionReport" data-nav="report">
        <button type="button" class="dropdown-link">


            Report Tweet
        </button>
      </li>
      <li class="dropdown-divider"></li>
      <li class="js-actionMomentMakerAddTweetToOtherMoment MomentMakerAddTweetToOtherMoment">
        <button type="button" class="dropdown-link">Add to other Moment</button>
      </li>
      <li class="js-actionMomentMakerCreateMoment">
        <button type="button" class="dropdown-link">Add to new Moment</button>
      </li>
  </ul>
</div>
</div>

  </div>

      </div>





        <div class="js-tweet-text-container">
  <p class="TweetTextSize TweetTextSize--normal js-tweet-text tweet-text tweet-text-rtl" lang="ar" data-aria-label-part="0">ايه اللغة اللي شايف انك حابب تتعلمها غير الإنجليزية
لو أخري ياريت كوت ريتويت وتقول اللغة
ياريت تشاركوا
رأيكم يهمني</p>
</div>


        <button type="button"
      class="btn-link js-translate-tweet translate-button u-textUserColorHover"
      data-nav="translate_tweet"
    >
    <span class="Icon Icon--translator"></span>Translate from Arabic
  </button>
  <div class="tweet-translation-container">
  <div class="spinner tweet-translation-loading" title="Loading..."></div>
  <div class="tweet-translation needs-translation" data-dest-lang="en">
    <div class="translation-attribution">
      <span>Translated from <span class="tweet-language">Arabic</span> by <a class="attribution-logo" href="http://aka.ms/Twitter2BingTranslator"><span class="invisible">Bing</span></a></span>
        <button type="button" class="btn-link js-translation-feedback-button" data-nav="">Wrong translation?</button>
    </div>
    <p class="tweet-translation-text"></p>
  </div>
</div>







          <div class="card2 js-media-container
    "
    data-card2-name="poll4choice_text_only"
  >
    <div class="js-macaw-cards-iframe-container initial-card-height card-type-poll4choice_text_only"
  data-src="/i/cards/tfw/v1/901544227250868226?cardname=poll4choice_text_only&amp;autoplay_disabled=true&amp;forward=true&amp;earned=true&amp;edge=true&amp;lang=en"
  data-card-name="poll4choice_text_only"
  data-card-url="card://901544225149526016"
  data-publisher-id=""
  data-creator-id=""
  data-amplify-content-id=""
  data-amplify-playlist-url=""
  data-full-card-iframe-url="/i/cards/tfw/v1/901544227250868226?cardname=poll4choice_text_only&amp;autoplay_disabled=true&amp;earned=true&amp;edge=true&amp;lang=en"
  data-has-autoplayable-media="false">
</div>

</div>




      <div class="stream-item-footer">

      <div class="ProfileTweet-actionCountList u-hiddenVisually">


    <span class="ProfileTweet-action--reply u-hiddenVisually">
      <span class="ProfileTweet-actionCount"  data-tweet-stat-count="19">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-reply-count-aria-901544227250868226" data-aria-label-part>19 replies</span>
      </span>
    </span>
    <span class="ProfileTweet-action--retweet u-hiddenVisually">
      <span class="ProfileTweet-actionCount"  data-tweet-stat-count="6">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-retweet-count-aria-901544227250868226" data-aria-label-part>6 retweets</span>
      </span>
    </span>
    <span class="ProfileTweet-action--favorite u-hiddenVisually">
      <span class="ProfileTweet-actionCount"  data-tweet-stat-count="2">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-favorite-count-aria-901544227250868226" data-aria-label-part>2 likes</span>
      </span>
    </span>
  </div>

  <div class="ProfileTweet-actionList js-actions" role="group" aria-label="Tweet actions">
    <div class="ProfileTweet-action ProfileTweet-action--reply">
  <button class="ProfileTweet-actionButton js-actionButton js-actionReply"
    data-modal="ProfileTweet-reply" type="button"
    aria-describedby="profile-tweet-action-reply-count-aria-901544227250868226">
    <div class="IconContainer js-tooltip" title="Reply">
      <span class="Icon Icon--medium Icon--reply"></span>
      <span class="u-hiddenVisually">Reply</span>
    </div>
      <span class="ProfileTweet-actionCount ">
        <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">19</span>
      </span>
  </button>
</div>

    <div class="ProfileTweet-action ProfileTweet-action--retweet js-toggleState js-toggleRt">
  <button class="ProfileTweet-actionButton  js-actionButton js-actionRetweet"

    data-modal="ProfileTweet-retweet"
    type="button"
    aria-describedby="profile-tweet-action-retweet-count-aria-901544227250868226">
    <div class="IconContainer js-tooltip" title="Retweet">
      <span class="Icon Icon--medium Icon--retweet"></span>
      <span class="u-hiddenVisually">Retweet</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">6</span>
  </span>

  </button><button class="ProfileTweet-actionButtonUndo js-actionButton js-actionRetweet" data-modal="ProfileTweet-retweet" type="button">
    <div class="IconContainer js-tooltip" title="Undo retweet">
      <span class="Icon Icon--medium Icon--retweet"></span>
      <span class="u-hiddenVisually">Retweeted</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">6</span>
  </span>

  </button>
</div>


    <div class="ProfileTweet-action ProfileTweet-action--favorite js-toggleState">
  <button class="ProfileTweet-actionButton js-actionButton js-actionFavorite" type="button"
    aria-describedby="profile-tweet-action-favorite-count-aria-901544227250868226">
    <div class="IconContainer js-tooltip" title="Like">
      <span role="presentation" class="Icon Icon--heart Icon--medium"></span>
      <div class="HeartAnimation"></div>
      <span class="u-hiddenVisually">Like</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">2</span>
  </span>

  </button><button class="ProfileTweet-actionButtonUndo ProfileTweet-action--unfavorite u-linkClean js-actionButton js-actionFavorite" type="button">
    <div class="IconContainer js-tooltip" title="Undo like">
      <span role="presentation" class="Icon Icon--heart Icon--medium"></span>
      <div class="HeartAnimation"></div>
      <span class="u-hiddenVisually">Liked</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">2</span>
  </span>

  </button>
</div>


      <div class="ProfileTweet-action ProfileTweet-action--dm">
    <button class="ProfileTweet-actionButton u-textUserColorHover js-actionButton js-actionShareViaDM"
      type="button"
      data-nav="share_tweet_dm"
    >
      <div class="IconContainer js-tooltip" title="Direct message">
        <span class="Icon Icon--medium Icon--dm"></span>
        <span class="u-hiddenVisually">Direct message</span>
      </div>
    </button>
  </div>




  </div>

</div>







    </div>
  </div>




<div class="dismiss-module">
  <div class="dismissed-module">
      <div class="feedback-action" data-feedback-type="DontLike">
        <div class="action-confirmation">Thanks. Twitter will use this to make your timeline better. <span class="undo-action u-textUserColor">Undo</span></div>
      </div>
  </div>
</div>

</li>

      <li class="js-stream-item stream-item stream-item
" data-item-id="901534656595542020"
id="stream-item-tweet-901534656595542020"
data-item-type="tweet"
 data-suggestion-json="{&quot;suggestion_details&quot;:{},&quot;tweet_ids&quot;:&quot;901534656595542020&quot;,&quot;scribe_component&quot;:&quot;tweet&quot;}">




  <div class="tweet js-stream-tweet js-actionable-tweet js-profile-popup-actionable dismissible-content
       original-tweet js-original-tweet

       retweeted has-cards  has-content
"

data-tweet-id="901534656595542020"
data-item-id="901534656595542020"
data-permalink-path="/Hassaneini/status/901534656595542020"
data-conversation-id="901520402676981762"
 data-is-reply-to="true"
 data-has-parent-tweet="true"

data-tweet-nonce="901534656595542020-e63679f9-b48e-4552-a24d-6bdb3278ce23"
data-tweet-stat-initialized="true"



 data-my-retweet-id="901534771188305921"
    data-retweet-id="901534771188305921"
     data-retweeter="mohammed_attya"


  data-screen-name="Hassaneini" data-name="حسنين" data-user-id="813373674"
  data-you-follow="true"
  data-follows-you="false"
  data-you-block="false"


data-reply-to-users-json="[{&quot;id_str&quot;:&quot;813373674&quot;,&quot;screen_name&quot;:&quot;Hassaneini&quot;,&quot;name&quot;:&quot;\u062d\u0633\u0646\u064a\u0646&quot;,&quot;emojified_name&quot;:{&quot;text&quot;:&quot;\u062d\u0633\u0646\u064a\u0646&quot;,&quot;emojified_text_as_html&quot;:&quot;\u062d\u0633\u0646\u064a\u0646&quot;}}]"







data-disclosure-type=""




 data-has-cards="true"








    >

    <div class="context">

          <div class="tweet-context with-icn

    ">

      <span class="Icon Icon--small Icon--retweeted"></span>



            <span class="js-retweet-text">
                <a class="pretty-link js-user-profile-link" href="/mohammed_attya" data-user-id="250377148" rel="noopener"><b>You</b></a> Retweeted
            </span>





    </div>

    </div>

    <div class="content">





      <div class="stream-item-header">
          <a  class="account-group js-account-group js-action-profile js-user-profile-link js-nav" href="/Hassaneini" data-user-id="813373674">
    <img class="avatar js-action-profile-avatar" src="https://pbs.twimg.com/profile_images/900445759870337024/MDRYbrL5_bigger.jpg" alt="">
    <span class="FullNameGroup">
      <strong class="fullname show-popup-with-id fullname-rtl" data-aria-label-part>حسنين</strong><span>&rlm;</span><span class="UserBadges"></span><span class="UserNameBreak">&nbsp;</span></span><span class="username u-dir" dir="ltr" data-aria-label-part>@<b>Hassaneini</b></span></a>


        <small class="time">
  <a href="/Hassaneini/status/901534656595542020" class="tweet-timestamp js-permalink js-nav js-tooltip" title="9:59 PM - 26 Aug 2017"  data-conversation-id="901520402676981762"><span class="_timestamp js-short-timestamp " data-aria-label-part="last" data-time="1503777586" data-time-ms="1503777586000" data-long-form="true">Aug 26</span></a>
</small>

          <div class="ProfileTweet-action ProfileTweet-action--more js-more-ProfileTweet-actions">
    <div class="dropdown">
  <button class="ProfileTweet-actionButton u-textUserColorHover dropdown-toggle js-dropdown-toggle" type="button">
      <div class="IconContainer js-tooltip" title="More">
        <span class="Icon Icon--caretDownLight Icon--small"></span>
        <span class="u-hiddenVisually">More</span>
      </div>
  </button>
  <div class="dropdown-menu is-autoCentered">
  <div class="dropdown-caret">
    <div class="caret-outer"></div>
    <div class="caret-inner"></div>
  </div>
  <ul>

      <li class="copy-link-to-tweet js-actionCopyLinkToTweet">
        <button type="button" class="dropdown-link">Copy link to Tweet</button>
      </li>
      <li class="embed-link js-actionEmbedTweet" data-nav="embed_tweet">
        <button type="button" class="dropdown-link">Embed Tweet</button>
      </li>
          <li class="mute-user-item"><button type="button" class="dropdown-link">Mute <span class="username u-dir" dir="ltr" >@<b>Hassaneini</b></span></button></li>
    <li class="unmute-user-item"><button type="button" class="dropdown-link">Unmute <span class="username u-dir" dir="ltr" >@<b>Hassaneini</b></span></button></li>

        <li class="block-link js-actionBlock" data-nav="block">
          <button type="button" class="dropdown-link">Block <span class="username u-dir" dir="ltr" >@<b>Hassaneini</b></span></button>
        </li>
        <li class="unblock-link js-actionUnblock" data-nav="unblock">
          <button type="button" class="dropdown-link">Unblock <span class="username u-dir" dir="ltr" >@<b>Hassaneini</b></span></button>
        </li>
      <li class="report-link js-actionReport" data-nav="report">
        <button type="button" class="dropdown-link">


            Report Tweet
        </button>
      </li>
      <li class="dropdown-divider"></li>
      <li class="js-actionMomentMakerAddTweetToOtherMoment MomentMakerAddTweetToOtherMoment">
        <button type="button" class="dropdown-link">Add to other Moment</button>
      </li>
      <li class="js-actionMomentMakerCreateMoment">
        <button type="button" class="dropdown-link">Add to new Moment</button>
      </li>
  </ul>
</div>
</div>

  </div>

      </div>



        <div class="ReplyingToContextBelowAuthor" data-aria-label-part>
    Replying to <a class="pretty-link js-user-profile-link" href="/Hassaneini" data-user-id="813373674" rel="noopener" dir="ltr"><span class="username u-dir" dir="ltr" >@<b>Hassaneini</b></span></a>



</div>



        <div class="js-tweet-text-container">
  <p class="TweetTextSize TweetTextSize--normal js-tweet-text tweet-text" lang="de" data-aria-label-part="0">Dionys moser / Western Desert <a href="/hashtag/Egypt?src=hash" data-query-source="hashtag_click" class="twitter-hashtag pretty-link js-nav" dir="ltr" ><s>#</s><b>Egypt</b></a><a href="https://t.co/AtyA0vDnjp" class="twitter-timeline-link u-hidden" data-pre-embedded="true" dir="ltr" >pic.twitter.com/AtyA0vDnjp</a></p>
</div>





            <div class="AdaptiveMediaOuterContainer">
    <div class="AdaptiveMedia






        "
      >
      <div class="AdaptiveMedia-container">
          <div class="AdaptiveMedia-quadPhoto">
    <div class="AdaptiveMedia-threeQuartersWidthPhoto">
      <div class="AdaptiveMedia-photoContainer js-adaptive-photo "
  data-image-url="https://pbs.twimg.com/media/DILk9PxVoAANBB1.jpg"


  data-element-context="platform_photo_card"
    style="background-color:rgba(41,34,18,1.0);"
    data-dominant-color="[41,34,18]"
>
  <img data-aria-label-part src="https://pbs.twimg.com/media/DILk9PxVoAANBB1.jpg" alt=""
      style="width: 100%; top: -93px;"
>
</div>


    </div>
  <div class="AdaptiveMedia-thirdHeightPhotoContainer">
      <div class="AdaptiveMedia-thirdHeightPhoto">
        <div class="AdaptiveMedia-photoContainer js-adaptive-photo "
  data-image-url="https://pbs.twimg.com/media/DILk-qpUwAAdu53.jpg"


  data-element-context="platform_photo_card"
    style="background-color:rgba(64,28,10,1.0);"
    data-dominant-color="[64,28,10]"
>
  <img data-aria-label-part src="https://pbs.twimg.com/media/DILk-qpUwAAdu53.jpg" alt=""
      style="width: 100%; top: -31px;"
>
</div>


      </div>
      <div class="AdaptiveMedia-thirdHeightPhoto">
        <div class="AdaptiveMedia-photoContainer js-adaptive-photo "
  data-image-url="https://pbs.twimg.com/media/DILk_DYVYAAVR-l.jpg"


  data-element-context="platform_photo_card"
    style="background-color:rgba(40,31,22,1.0);"
    data-dominant-color="[40,31,22]"
>
  <img data-aria-label-part src="https://pbs.twimg.com/media/DILk_DYVYAAVR-l.jpg" alt=""
      style="height: 100%; left: -31px;"
>
</div>


      </div>
      <div class="AdaptiveMedia-thirdHeightPhoto">
        <div class="AdaptiveMedia-photoContainer js-adaptive-photo "
  data-image-url="https://pbs.twimg.com/media/DILk_WLVoAAaMsM.jpg"


  data-element-context="platform_photo_card"
    style="background-color:rgba(16,29,64,1.0);"
    data-dominant-color="[16,29,64]"
>
  <img data-aria-label-part src="https://pbs.twimg.com/media/DILk_WLVoAAaMsM.jpg" alt=""
      style="height: 100%; left: -31px;"
>
</div>


      </div>
  </div>
</div>

      </div>
    </div>
  </div>








      <div class="stream-item-footer">

      <div class="ProfileTweet-actionCountList u-hiddenVisually">


    <span class="ProfileTweet-action--reply u-hiddenVisually">
      <span class="ProfileTweet-actionCount"  data-tweet-stat-count="1">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-reply-count-aria-901534656595542020" data-aria-label-part>1 reply</span>
      </span>
    </span>
    <span class="ProfileTweet-action--retweet u-hiddenVisually">
      <span class="ProfileTweet-actionCount"  data-tweet-stat-count="32">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-retweet-count-aria-901534656595542020" data-aria-label-part>32 retweets</span>
      </span>
    </span>
    <span class="ProfileTweet-action--favorite u-hiddenVisually">
      <span class="ProfileTweet-actionCount"  data-tweet-stat-count="21">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-favorite-count-aria-901534656595542020" data-aria-label-part>21 likes</span>
      </span>
    </span>
  </div>

  <div class="ProfileTweet-actionList js-actions" role="group" aria-label="Tweet actions">
    <div class="ProfileTweet-action ProfileTweet-action--reply">
  <button class="ProfileTweet-actionButton js-actionButton js-actionReply"
    data-modal="ProfileTweet-reply" type="button"
    aria-describedby="profile-tweet-action-reply-count-aria-901534656595542020">
    <div class="IconContainer js-tooltip" title="Reply">
      <span class="Icon Icon--medium Icon--reply"></span>
      <span class="u-hiddenVisually">Reply</span>
    </div>
      <span class="ProfileTweet-actionCount ">
        <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">1</span>
      </span>
  </button>
</div>

    <div class="ProfileTweet-action ProfileTweet-action--retweet js-toggleState js-toggleRt">
  <button class="ProfileTweet-actionButton  js-actionButton js-actionRetweet"

    data-modal="ProfileTweet-retweet"
    type="button"
    aria-describedby="profile-tweet-action-retweet-count-aria-901534656595542020">
    <div class="IconContainer js-tooltip" title="Retweet">
      <span class="Icon Icon--medium Icon--retweet"></span>
      <span class="u-hiddenVisually">Retweet</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">32</span>
  </span>

  </button><button class="ProfileTweet-actionButtonUndo js-actionButton js-actionRetweet" data-modal="ProfileTweet-retweet" type="button">
    <div class="IconContainer js-tooltip" title="Undo retweet">
      <span class="Icon Icon--medium Icon--retweet"></span>
      <span class="u-hiddenVisually">Retweeted</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">32</span>
  </span>

  </button>
</div>


    <div class="ProfileTweet-action ProfileTweet-action--favorite js-toggleState">
  <button class="ProfileTweet-actionButton js-actionButton js-actionFavorite" type="button"
    aria-describedby="profile-tweet-action-favorite-count-aria-901534656595542020">
    <div class="IconContainer js-tooltip" title="Like">
      <span role="presentation" class="Icon Icon--heart Icon--medium"></span>
      <div class="HeartAnimation"></div>
      <span class="u-hiddenVisually">Like</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">21</span>
  </span>

  </button><button class="ProfileTweet-actionButtonUndo ProfileTweet-action--unfavorite u-linkClean js-actionButton js-actionFavorite" type="button">
    <div class="IconContainer js-tooltip" title="Undo like">
      <span role="presentation" class="Icon Icon--heart Icon--medium"></span>
      <div class="HeartAnimation"></div>
      <span class="u-hiddenVisually">Liked</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">21</span>
  </span>

  </button>
</div>


      <div class="ProfileTweet-action ProfileTweet-action--dm">
    <button class="ProfileTweet-actionButton u-textUserColorHover js-actionButton js-actionShareViaDM"
      type="button"
      data-nav="share_tweet_dm"
    >
      <div class="IconContainer js-tooltip" title="Direct message">
        <span class="Icon Icon--medium Icon--dm"></span>
        <span class="u-hiddenVisually">Direct message</span>
      </div>
    </button>
  </div>




  </div>

</div>







    </div>
  </div>




<div class="dismiss-module">
  <div class="dismissed-module">
      <div class="feedback-action" data-feedback-type="DontLike">
        <div class="action-confirmation">Thanks. Twitter will use this to make your timeline better. <span class="undo-action u-textUserColor">Undo</span></div>
      </div>
  </div>
</div>

</li>

      <li class="js-stream-item stream-item stream-item
" data-item-id="901534466715156480"
id="stream-item-tweet-901534466715156480"
data-item-type="tweet"
 data-suggestion-json="{&quot;suggestion_details&quot;:{},&quot;tweet_ids&quot;:&quot;901534466715156480&quot;,&quot;scribe_component&quot;:&quot;tweet&quot;}">




  <div class="tweet js-stream-tweet js-actionable-tweet js-profile-popup-actionable dismissible-content
       original-tweet js-original-tweet

       retweeted has-cards  has-content
"

data-tweet-id="901534466715156480"
data-item-id="901534466715156480"
data-permalink-path="/Hassaneini/status/901534466715156480"
data-conversation-id="901520402676981762"
 data-is-reply-to="true"
 data-has-parent-tweet="true"

data-tweet-nonce="901534466715156480-63fa748b-1a9b-4aa8-a90c-f8dbf8682fa6"
data-tweet-stat-initialized="true"



 data-my-retweet-id="901534747700199426"
    data-retweet-id="901534747700199426"
     data-retweeter="mohammed_attya"


  data-screen-name="Hassaneini" data-name="حسنين" data-user-id="813373674"
  data-you-follow="true"
  data-follows-you="false"
  data-you-block="false"


data-reply-to-users-json="[{&quot;id_str&quot;:&quot;813373674&quot;,&quot;screen_name&quot;:&quot;Hassaneini&quot;,&quot;name&quot;:&quot;\u062d\u0633\u0646\u064a\u0646&quot;,&quot;emojified_name&quot;:{&quot;text&quot;:&quot;\u062d\u0633\u0646\u064a\u0646&quot;,&quot;emojified_text_as_html&quot;:&quot;\u062d\u0633\u0646\u064a\u0646&quot;}}]"







data-disclosure-type=""




 data-has-cards="true"








    >

    <div class="context">

          <div class="tweet-context with-icn

    ">

      <span class="Icon Icon--small Icon--retweeted"></span>



            <span class="js-retweet-text">
                <a class="pretty-link js-user-profile-link" href="/mohammed_attya" data-user-id="250377148" rel="noopener"><b>You</b></a> Retweeted
            </span>





    </div>

    </div>

    <div class="content">





      <div class="stream-item-header">
          <a  class="account-group js-account-group js-action-profile js-user-profile-link js-nav" href="/Hassaneini" data-user-id="813373674">
    <img class="avatar js-action-profile-avatar" src="https://pbs.twimg.com/profile_images/900445759870337024/MDRYbrL5_bigger.jpg" alt="">
    <span class="FullNameGroup">
      <strong class="fullname show-popup-with-id fullname-rtl" data-aria-label-part>حسنين</strong><span>&rlm;</span><span class="UserBadges"></span><span class="UserNameBreak">&nbsp;</span></span><span class="username u-dir" dir="ltr" data-aria-label-part>@<b>Hassaneini</b></span></a>


        <small class="time">
  <a href="/Hassaneini/status/901534466715156480" class="tweet-timestamp js-permalink js-nav js-tooltip" title="9:59 PM - 26 Aug 2017"  data-conversation-id="901520402676981762"><span class="_timestamp js-short-timestamp " data-aria-label-part="last" data-time="1503777541" data-time-ms="1503777541000" data-long-form="true">Aug 26</span></a>
</small>

          <div class="ProfileTweet-action ProfileTweet-action--more js-more-ProfileTweet-actions">
    <div class="dropdown">
  <button class="ProfileTweet-actionButton u-textUserColorHover dropdown-toggle js-dropdown-toggle" type="button">
      <div class="IconContainer js-tooltip" title="More">
        <span class="Icon Icon--caretDownLight Icon--small"></span>
        <span class="u-hiddenVisually">More</span>
      </div>
  </button>
  <div class="dropdown-menu is-autoCentered">
  <div class="dropdown-caret">
    <div class="caret-outer"></div>
    <div class="caret-inner"></div>
  </div>
  <ul>

      <li class="copy-link-to-tweet js-actionCopyLinkToTweet">
        <button type="button" class="dropdown-link">Copy link to Tweet</button>
      </li>
      <li class="embed-link js-actionEmbedTweet" data-nav="embed_tweet">
        <button type="button" class="dropdown-link">Embed Tweet</button>
      </li>
          <li class="mute-user-item"><button type="button" class="dropdown-link">Mute <span class="username u-dir" dir="ltr" >@<b>Hassaneini</b></span></button></li>
    <li class="unmute-user-item"><button type="button" class="dropdown-link">Unmute <span class="username u-dir" dir="ltr" >@<b>Hassaneini</b></span></button></li>

        <li class="block-link js-actionBlock" data-nav="block">
          <button type="button" class="dropdown-link">Block <span class="username u-dir" dir="ltr" >@<b>Hassaneini</b></span></button>
        </li>
        <li class="unblock-link js-actionUnblock" data-nav="unblock">
          <button type="button" class="dropdown-link">Unblock <span class="username u-dir" dir="ltr" >@<b>Hassaneini</b></span></button>
        </li>
      <li class="report-link js-actionReport" data-nav="report">
        <button type="button" class="dropdown-link">


            Report Tweet
        </button>
      </li>
      <li class="dropdown-divider"></li>
      <li class="js-actionMomentMakerAddTweetToOtherMoment MomentMakerAddTweetToOtherMoment">
        <button type="button" class="dropdown-link">Add to other Moment</button>
      </li>
      <li class="js-actionMomentMakerCreateMoment">
        <button type="button" class="dropdown-link">Add to new Moment</button>
      </li>
  </ul>
</div>
</div>

  </div>

      </div>



        <div class="ReplyingToContextBelowAuthor" data-aria-label-part>
    Replying to <a class="pretty-link js-user-profile-link" href="/Hassaneini" data-user-id="813373674" rel="noopener" dir="ltr"><span class="username u-dir" dir="ltr" >@<b>Hassaneini</b></span></a>



</div>



        <div class="js-tweet-text-container">
  <p class="TweetTextSize TweetTextSize--normal js-tweet-text tweet-text" lang="de" data-aria-label-part="0">Dionys moser / Western Desert <a href="/hashtag/Egypt?src=hash" data-query-source="hashtag_click" class="twitter-hashtag pretty-link js-nav" dir="ltr" ><s>#</s><b>Egypt</b></a><a href="https://t.co/3H42P7IEgd" class="twitter-timeline-link u-hidden" data-pre-embedded="true" dir="ltr" >pic.twitter.com/3H42P7IEgd</a></p>
</div>


        <button type="button"
      class="btn-link js-translate-tweet translate-button u-textUserColorHover"
      data-nav="translate_tweet"
    >
    <span class="Icon Icon--translator"></span>Translate from German
  </button>
  <div class="tweet-translation-container">
  <div class="spinner tweet-translation-loading" title="Loading..."></div>
  <div class="tweet-translation needs-translation" data-dest-lang="en">
    <div class="translation-attribution">
      <span>Translated from <span class="tweet-language">German</span> by <a class="attribution-logo" href="http://aka.ms/Twitter2BingTranslator"><span class="invisible">Bing</span></a></span>
        <button type="button" class="btn-link js-translation-feedback-button" data-nav="">Wrong translation?</button>
    </div>
    <p class="tweet-translation-text"></p>
  </div>
</div>



            <div class="AdaptiveMediaOuterContainer">
    <div class="AdaptiveMedia


        is-square



        "
      >
      <div class="AdaptiveMedia-container">
          <div class="AdaptiveMedia-singlePhoto"
    style="padding-top: calc(0.6533333333333333 * 100% - 0.5px);"
>
    <div class="AdaptiveMedia-photoContainer js-adaptive-photo "
  data-image-url="https://pbs.twimg.com/media/DILkx_zUQAQGzMd.jpg"


  data-element-context="platform_photo_card"
    style="background-color:rgba(64,38,17,1.0);"
    data-dominant-color="[64,38,17]"
>
  <img data-aria-label-part src="https://pbs.twimg.com/media/DILkx_zUQAQGzMd.jpg" alt=""
      style="width: 100%; top: -0px;"
>
</div>


</div>
      </div>
    </div>
  </div>








      <div class="stream-item-footer">

      <div class="ProfileTweet-actionCountList u-hiddenVisually">


    <span class="ProfileTweet-action--reply u-hiddenVisually">
      <span class="ProfileTweet-actionCount"  data-tweet-stat-count="3">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-reply-count-aria-901534466715156480" data-aria-label-part>3 replies</span>
      </span>
    </span>
    <span class="ProfileTweet-action--retweet u-hiddenVisually">
      <span class="ProfileTweet-actionCount"  data-tweet-stat-count="41">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-retweet-count-aria-901534466715156480" data-aria-label-part>41 retweets</span>
      </span>
    </span>
    <span class="ProfileTweet-action--favorite u-hiddenVisually">
      <span class="ProfileTweet-actionCount"  data-tweet-stat-count="41">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-favorite-count-aria-901534466715156480" data-aria-label-part>41 likes</span>
      </span>
    </span>
  </div>

  <div class="ProfileTweet-actionList js-actions" role="group" aria-label="Tweet actions">
    <div class="ProfileTweet-action ProfileTweet-action--reply">
  <button class="ProfileTweet-actionButton js-actionButton js-actionReply"
    data-modal="ProfileTweet-reply" type="button"
    aria-describedby="profile-tweet-action-reply-count-aria-901534466715156480">
    <div class="IconContainer js-tooltip" title="Reply">
      <span class="Icon Icon--medium Icon--reply"></span>
      <span class="u-hiddenVisually">Reply</span>
    </div>
      <span class="ProfileTweet-actionCount ">
        <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">3</span>
      </span>
  </button>
</div>

    <div class="ProfileTweet-action ProfileTweet-action--retweet js-toggleState js-toggleRt">
  <button class="ProfileTweet-actionButton  js-actionButton js-actionRetweet"

    data-modal="ProfileTweet-retweet"
    type="button"
    aria-describedby="profile-tweet-action-retweet-count-aria-901534466715156480">
    <div class="IconContainer js-tooltip" title="Retweet">
      <span class="Icon Icon--medium Icon--retweet"></span>
      <span class="u-hiddenVisually">Retweet</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">41</span>
  </span>

  </button><button class="ProfileTweet-actionButtonUndo js-actionButton js-actionRetweet" data-modal="ProfileTweet-retweet" type="button">
    <div class="IconContainer js-tooltip" title="Undo retweet">
      <span class="Icon Icon--medium Icon--retweet"></span>
      <span class="u-hiddenVisually">Retweeted</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">41</span>
  </span>

  </button>
</div>


    <div class="ProfileTweet-action ProfileTweet-action--favorite js-toggleState">
  <button class="ProfileTweet-actionButton js-actionButton js-actionFavorite" type="button"
    aria-describedby="profile-tweet-action-favorite-count-aria-901534466715156480">
    <div class="IconContainer js-tooltip" title="Like">
      <span role="presentation" class="Icon Icon--heart Icon--medium"></span>
      <div class="HeartAnimation"></div>
      <span class="u-hiddenVisually">Like</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">41</span>
  </span>

  </button><button class="ProfileTweet-actionButtonUndo ProfileTweet-action--unfavorite u-linkClean js-actionButton js-actionFavorite" type="button">
    <div class="IconContainer js-tooltip" title="Undo like">
      <span role="presentation" class="Icon Icon--heart Icon--medium"></span>
      <div class="HeartAnimation"></div>
      <span class="u-hiddenVisually">Liked</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">41</span>
  </span>

  </button>
</div>


      <div class="ProfileTweet-action ProfileTweet-action--dm">
    <button class="ProfileTweet-actionButton u-textUserColorHover js-actionButton js-actionShareViaDM"
      type="button"
      data-nav="share_tweet_dm"
    >
      <div class="IconContainer js-tooltip" title="Direct message">
        <span class="Icon Icon--medium Icon--dm"></span>
        <span class="u-hiddenVisually">Direct message</span>
      </div>
    </button>
  </div>




  </div>

</div>







    </div>
  </div>




<div class="dismiss-module">
  <div class="dismissed-module">
      <div class="feedback-action" data-feedback-type="DontLike">
        <div class="action-confirmation">Thanks. Twitter will use this to make your timeline better. <span class="undo-action u-textUserColor">Undo</span></div>
      </div>
  </div>
</div>

</li>

      <li class="js-stream-item stream-item stream-item
" data-item-id="901520402676981762"
id="stream-item-tweet-901520402676981762"
data-item-type="tweet"
 data-suggestion-json="{&quot;suggestion_details&quot;:{},&quot;tweet_ids&quot;:&quot;901520402676981762&quot;,&quot;scribe_component&quot;:&quot;tweet&quot;}">




  <div class="tweet js-stream-tweet js-actionable-tweet js-profile-popup-actionable dismissible-content
       original-tweet js-original-tweet

       retweeted has-cards  has-content
"

data-tweet-id="901520402676981762"
data-item-id="901520402676981762"
data-permalink-path="/Hassaneini/status/901520402676981762"
data-conversation-id="901520402676981762"



data-tweet-nonce="901520402676981762-445fbc0d-8322-4b35-ab55-7cfe91044e8a"
data-tweet-stat-initialized="true"



 data-my-retweet-id="901534729178152962"
    data-retweet-id="901534729178152962"
     data-retweeter="mohammed_attya"


  data-screen-name="Hassaneini" data-name="حسنين" data-user-id="813373674"
  data-you-follow="true"
  data-follows-you="false"
  data-you-block="false"


data-reply-to-users-json="[{&quot;id_str&quot;:&quot;813373674&quot;,&quot;screen_name&quot;:&quot;Hassaneini&quot;,&quot;name&quot;:&quot;\u062d\u0633\u0646\u064a\u0646&quot;,&quot;emojified_name&quot;:{&quot;text&quot;:&quot;\u062d\u0633\u0646\u064a\u0646&quot;,&quot;emojified_text_as_html&quot;:&quot;\u062d\u0633\u0646\u064a\u0646&quot;}}]"







data-disclosure-type=""




 data-has-cards="true"








    >

    <div class="context">

          <div class="tweet-context with-icn

    ">

      <span class="Icon Icon--small Icon--retweeted"></span>



            <span class="js-retweet-text">
                <a class="pretty-link js-user-profile-link" href="/mohammed_attya" data-user-id="250377148" rel="noopener"><b>You</b></a> Retweeted
            </span>





    </div>

    </div>

    <div class="content">





      <div class="stream-item-header">
          <a  class="account-group js-account-group js-action-profile js-user-profile-link js-nav" href="/Hassaneini" data-user-id="813373674">
    <img class="avatar js-action-profile-avatar" src="https://pbs.twimg.com/profile_images/900445759870337024/MDRYbrL5_bigger.jpg" alt="">
    <span class="FullNameGroup">
      <strong class="fullname show-popup-with-id fullname-rtl" data-aria-label-part>حسنين</strong><span>&rlm;</span><span class="UserBadges"></span><span class="UserNameBreak">&nbsp;</span></span><span class="username u-dir" dir="ltr" data-aria-label-part>@<b>Hassaneini</b></span></a>


        <small class="time">
  <a href="/Hassaneini/status/901520402676981762" class="tweet-timestamp js-permalink js-nav js-tooltip" title="9:03 PM - 26 Aug 2017"  data-conversation-id="901520402676981762"><span class="_timestamp js-short-timestamp " data-aria-label-part="last" data-time="1503774188" data-time-ms="1503774188000" data-long-form="true">Aug 26</span></a>
</small>

          <div class="ProfileTweet-action ProfileTweet-action--more js-more-ProfileTweet-actions">
    <div class="dropdown">
  <button class="ProfileTweet-actionButton u-textUserColorHover dropdown-toggle js-dropdown-toggle" type="button">
      <div class="IconContainer js-tooltip" title="More">
        <span class="Icon Icon--caretDownLight Icon--small"></span>
        <span class="u-hiddenVisually">More</span>
      </div>
  </button>
  <div class="dropdown-menu is-autoCentered">
  <div class="dropdown-caret">
    <div class="caret-outer"></div>
    <div class="caret-inner"></div>
  </div>
  <ul>

      <li class="copy-link-to-tweet js-actionCopyLinkToTweet">
        <button type="button" class="dropdown-link">Copy link to Tweet</button>
      </li>
      <li class="embed-link js-actionEmbedTweet" data-nav="embed_tweet">
        <button type="button" class="dropdown-link">Embed Tweet</button>
      </li>
          <li class="mute-user-item"><button type="button" class="dropdown-link">Mute <span class="username u-dir" dir="ltr" >@<b>Hassaneini</b></span></button></li>
    <li class="unmute-user-item"><button type="button" class="dropdown-link">Unmute <span class="username u-dir" dir="ltr" >@<b>Hassaneini</b></span></button></li>

        <li class="block-link js-actionBlock" data-nav="block">
          <button type="button" class="dropdown-link">Block <span class="username u-dir" dir="ltr" >@<b>Hassaneini</b></span></button>
        </li>
        <li class="unblock-link js-actionUnblock" data-nav="unblock">
          <button type="button" class="dropdown-link">Unblock <span class="username u-dir" dir="ltr" >@<b>Hassaneini</b></span></button>
        </li>
      <li class="report-link js-actionReport" data-nav="report">
        <button type="button" class="dropdown-link">


            Report Tweet
        </button>
      </li>
      <li class="dropdown-divider"></li>
      <li class="js-actionMomentMakerAddTweetToOtherMoment MomentMakerAddTweetToOtherMoment">
        <button type="button" class="dropdown-link">Add to other Moment</button>
      </li>
      <li class="js-actionMomentMakerCreateMoment">
        <button type="button" class="dropdown-link">Add to new Moment</button>
      </li>
  </ul>
</div>
</div>

  </div>

      </div>





        <div class="js-tweet-text-container">
  <p class="TweetTextSize TweetTextSize--normal js-tweet-text tweet-text tweet-text-rtl" lang="ar" data-aria-label-part="0">الصحراء الغربية في مصر بعدسة  Dionys moser
انا منبهر بصراحة ..عمري مارحتها ماتوقعتش انها كده !<a href="https://t.co/tsKy7y8WOZ" class="twitter-timeline-link u-hidden" data-pre-embedded="true" dir="ltr" >pic.twitter.com/tsKy7y8WOZ</a></p>
</div>


        <button type="button"
      class="btn-link js-translate-tweet translate-button u-textUserColorHover"
      data-nav="translate_tweet"
    >
    <span class="Icon Icon--translator"></span>Translate from Arabic
  </button>
  <div class="tweet-translation-container">
  <div class="spinner tweet-translation-loading" title="Loading..."></div>
  <div class="tweet-translation needs-translation" data-dest-lang="en">
    <div class="translation-attribution">
      <span>Translated from <span class="tweet-language">Arabic</span> by <a class="attribution-logo" href="http://aka.ms/Twitter2BingTranslator"><span class="invisible">Bing</span></a></span>
        <button type="button" class="btn-link js-translation-feedback-button" data-nav="">Wrong translation?</button>
    </div>
    <p class="tweet-translation-text"></p>
  </div>
</div>



            <div class="AdaptiveMediaOuterContainer">
    <div class="AdaptiveMedia






        "
      >
      <div class="AdaptiveMedia-container">
          <div class="AdaptiveMedia-quadPhoto">
    <div class="AdaptiveMedia-threeQuartersWidthPhoto">
      <div class="AdaptiveMedia-photoContainer js-adaptive-photo "
  data-image-url="https://pbs.twimg.com/media/DILX5gTUIAA9mM5.jpg"


  data-element-context="platform_photo_card"
    style="background-color:rgba(58,21,9,1.0);"
    data-dominant-color="[58,21,9]"
>
  <img data-aria-label-part src="https://pbs.twimg.com/media/DILX5gTUIAA9mM5.jpg" alt=""
      style="height: 100%; left: -3px;"
>
</div>


    </div>
  <div class="AdaptiveMedia-thirdHeightPhotoContainer">
      <div class="AdaptiveMedia-thirdHeightPhoto">
        <div class="AdaptiveMedia-photoContainer js-adaptive-photo "
  data-image-url="https://pbs.twimg.com/media/DILX7cyVYAAZymO.jpg"


  data-element-context="platform_photo_card"
    style="background-color:rgba(49,53,64,1.0);"
    data-dominant-color="[49,53,64]"
>
  <img data-aria-label-part src="https://pbs.twimg.com/media/DILX7cyVYAAZymO.jpg" alt=""
      style="height: 100%; left: -0px;"
>
</div>


      </div>
      <div class="AdaptiveMedia-thirdHeightPhoto">
        <div class="AdaptiveMedia-photoContainer js-adaptive-photo "
  data-image-url="https://pbs.twimg.com/media/DILX8zJUMAAS0j5.jpg"


  data-element-context="platform_photo_card"
    style="background-color:rgba(64,43,25,1.0);"
    data-dominant-color="[64,43,25]"
>
  <img data-aria-label-part src="https://pbs.twimg.com/media/DILX8zJUMAAS0j5.jpg" alt=""
      style="height: 100%; left: -0px;"
>
</div>


      </div>
      <div class="AdaptiveMedia-thirdHeightPhoto">
        <div class="AdaptiveMedia-photoContainer js-adaptive-photo "
  data-image-url="https://pbs.twimg.com/media/DILX-y6V0AEVEV7.jpg"


  data-element-context="platform_photo_card"
    style="background-color:rgba(64,52,50,1.0);"
    data-dominant-color="[64,52,50]"
>
  <img data-aria-label-part src="https://pbs.twimg.com/media/DILX-y6V0AEVEV7.jpg" alt=""
      style="height: 100%; left: -0px;"
>
</div>


      </div>
  </div>
</div>

      </div>
    </div>
  </div>








      <div class="stream-item-footer">

      <div class="ProfileTweet-actionCountList u-hiddenVisually">


    <span class="ProfileTweet-action--reply u-hiddenVisually">
      <span class="ProfileTweet-actionCount"  data-tweet-stat-count="28">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-reply-count-aria-901520402676981762" data-aria-label-part>28 replies</span>
      </span>
    </span>
    <span class="ProfileTweet-action--retweet u-hiddenVisually">
      <span class="ProfileTweet-actionCount"  data-tweet-stat-count="383">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-retweet-count-aria-901520402676981762" data-aria-label-part>383 retweets</span>
      </span>
    </span>
    <span class="ProfileTweet-action--favorite u-hiddenVisually">
      <span class="ProfileTweet-actionCount"  data-tweet-stat-count="415">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-favorite-count-aria-901520402676981762" data-aria-label-part>415 likes</span>
      </span>
    </span>
  </div>

  <div class="ProfileTweet-actionList js-actions" role="group" aria-label="Tweet actions">
    <div class="ProfileTweet-action ProfileTweet-action--reply">
  <button class="ProfileTweet-actionButton js-actionButton js-actionReply"
    data-modal="ProfileTweet-reply" type="button"
    aria-describedby="profile-tweet-action-reply-count-aria-901520402676981762">
    <div class="IconContainer js-tooltip" title="Reply">
      <span class="Icon Icon--medium Icon--reply"></span>
      <span class="u-hiddenVisually">Reply</span>
    </div>
      <span class="ProfileTweet-actionCount ">
        <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">28</span>
      </span>
  </button>
</div>

    <div class="ProfileTweet-action ProfileTweet-action--retweet js-toggleState js-toggleRt">
  <button class="ProfileTweet-actionButton  js-actionButton js-actionRetweet"

    data-modal="ProfileTweet-retweet"
    type="button"
    aria-describedby="profile-tweet-action-retweet-count-aria-901520402676981762">
    <div class="IconContainer js-tooltip" title="Retweet">
      <span class="Icon Icon--medium Icon--retweet"></span>
      <span class="u-hiddenVisually">Retweet</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">383</span>
  </span>

  </button><button class="ProfileTweet-actionButtonUndo js-actionButton js-actionRetweet" data-modal="ProfileTweet-retweet" type="button">
    <div class="IconContainer js-tooltip" title="Undo retweet">
      <span class="Icon Icon--medium Icon--retweet"></span>
      <span class="u-hiddenVisually">Retweeted</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">383</span>
  </span>

  </button>
</div>


    <div class="ProfileTweet-action ProfileTweet-action--favorite js-toggleState">
  <button class="ProfileTweet-actionButton js-actionButton js-actionFavorite" type="button"
    aria-describedby="profile-tweet-action-favorite-count-aria-901520402676981762">
    <div class="IconContainer js-tooltip" title="Like">
      <span role="presentation" class="Icon Icon--heart Icon--medium"></span>
      <div class="HeartAnimation"></div>
      <span class="u-hiddenVisually">Like</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">415</span>
  </span>

  </button><button class="ProfileTweet-actionButtonUndo ProfileTweet-action--unfavorite u-linkClean js-actionButton js-actionFavorite" type="button">
    <div class="IconContainer js-tooltip" title="Undo like">
      <span role="presentation" class="Icon Icon--heart Icon--medium"></span>
      <div class="HeartAnimation"></div>
      <span class="u-hiddenVisually">Liked</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">415</span>
  </span>

  </button>
</div>


      <div class="ProfileTweet-action ProfileTweet-action--dm">
    <button class="ProfileTweet-actionButton u-textUserColorHover js-actionButton js-actionShareViaDM"
      type="button"
      data-nav="share_tweet_dm"
    >
      <div class="IconContainer js-tooltip" title="Direct message">
        <span class="Icon Icon--medium Icon--dm"></span>
        <span class="u-hiddenVisually">Direct message</span>
      </div>
    </button>
  </div>




  </div>

</div>







    </div>
  </div>




<div class="dismiss-module">
  <div class="dismissed-module">
      <div class="feedback-action" data-feedback-type="DontLike">
        <div class="action-confirmation">Thanks. Twitter will use this to make your timeline better. <span class="undo-action u-textUserColor">Undo</span></div>
      </div>
  </div>
</div>

</li>

      <li class="js-stream-item stream-item stream-item
" data-item-id="901505882516848640"
id="stream-item-tweet-901505882516848640"
data-item-type="tweet"
 data-suggestion-json="{&quot;suggestion_details&quot;:{},&quot;tweet_ids&quot;:&quot;901505882516848640&quot;,&quot;scribe_component&quot;:&quot;tweet&quot;}">




  <div class="tweet js-stream-tweet js-actionable-tweet js-profile-popup-actionable dismissible-content
       original-tweet js-original-tweet

       my-tweet has-cards  has-content
"

data-tweet-id="901505882516848640"
data-item-id="901505882516848640"
data-permalink-path="/mohammed_attya/status/901505882516848640"
data-conversation-id="901505882516848640"



data-tweet-nonce="901505882516848640-0600b7fd-800d-4c14-a21d-540e100b0a26"
data-tweet-stat-initialized="true"






  data-screen-name="mohammed_attya" data-name="Mohammed Attya" data-user-id="250377148"
  data-you-follow="false"
  data-follows-you="false"
  data-you-block="false"


data-reply-to-users-json="[{&quot;id_str&quot;:&quot;250377148&quot;,&quot;screen_name&quot;:&quot;mohammed_attya&quot;,&quot;name&quot;:&quot;Mohammed Attya&quot;,&quot;emojified_name&quot;:{&quot;text&quot;:&quot;Mohammed Attya&quot;,&quot;emojified_text_as_html&quot;:&quot;Mohammed Attya&quot;}}]"







data-disclosure-type=""




 data-has-cards="true"







 data-tfb-view="/i/tfb/v1/quick_promote/901505882516848640"
    >

    <div class="context">


    </div>

    <div class="content">





      <div class="stream-item-header">
          <a  class="account-group js-account-group js-action-profile js-user-profile-link js-nav" href="/mohammed_attya" data-user-id="250377148">
    <img class="avatar js-action-profile-avatar" src="https://pbs.twimg.com/profile_images/878491891733549056/3urQeq4j_bigger.jpg" alt="">
    <span class="FullNameGroup">
      <strong class="fullname show-popup-with-id " data-aria-label-part>Mohammed Attya</strong><span>&rlm;</span><span class="UserBadges"></span><span class="UserNameBreak">&nbsp;</span></span><span class="username u-dir" dir="ltr" data-aria-label-part>@<b>mohammed_attya</b></span></a>


        <small class="time">
  <a href="/mohammed_attya/status/901505882516848640" class="tweet-timestamp js-permalink js-nav js-tooltip" title="8:05 PM - 26 Aug 2017"  data-conversation-id="901505882516848640"><span class="_timestamp js-short-timestamp " data-aria-label-part="last" data-time="1503770726" data-time-ms="1503770726000" data-long-form="true">Aug 26</span></a>
</small>

          <div class="ProfileTweet-action ProfileTweet-action--more js-more-ProfileTweet-actions">
    <div class="dropdown">
  <button class="ProfileTweet-actionButton u-textUserColorHover dropdown-toggle js-dropdown-toggle" type="button">
      <div class="IconContainer js-tooltip" title="More">
        <span class="Icon Icon--caretDownLight Icon--small"></span>
        <span class="u-hiddenVisually">More</span>
      </div>
  </button>
  <div class="dropdown-menu is-autoCentered">
  <div class="dropdown-caret">
    <div class="caret-outer"></div>
    <div class="caret-inner"></div>
  </div>
  <ul>
      <li class="share-via-dm js-actionShareViaDM" data-nav="share_tweet_dm">
        <button type="button" class="dropdown-link">Share via Direct Message</button>
      </li>

      <li class="copy-link-to-tweet js-actionCopyLinkToTweet">
        <button type="button" class="dropdown-link">Copy link to Tweet</button>
      </li>
      <li class="embed-link js-actionEmbedTweet" data-nav="embed_tweet">
        <button type="button" class="dropdown-link">Embed Tweet</button>
      </li>
      <li class="user-pin-tweet js-actionPinTweet" data-nav="user_pin_tweet">
        <button type="button" class="dropdown-link">Pin to your profile page</button>
      </li>
      <li class="user-unpin-tweet js-actionPinTweet" data-nav="user_unpin_tweet">
        <button type="button" class="dropdown-link">Unpin from profile page</button>
      </li>
      <li class="js-actionDelete">
        <button type="button" class="dropdown-link">Delete Tweet</button>
      </li>
      <li class="dropdown-divider"></li>
      <li class="js-actionMomentMakerAddTweetToOtherMoment MomentMakerAddTweetToOtherMoment">
        <button type="button" class="dropdown-link">Add to other Moment</button>
      </li>
      <li class="js-actionMomentMakerCreateMoment">
        <button type="button" class="dropdown-link">Add to new Moment</button>
      </li>
  </ul>
</div>
</div>

  </div>

      </div>





        <div class="js-tweet-text-container">
  <p class="TweetTextSize TweetTextSize--normal js-tweet-text tweet-text" lang="und" data-aria-label-part="0"><a href="https://t.co/tCsvLCoF1S" class="twitter-timeline-link u-hidden" data-pre-embedded="true" dir="ltr" >pic.twitter.com/tCsvLCoF1S</a></p>
</div>





            <div class="AdaptiveMediaOuterContainer">
    <div class="AdaptiveMedia


        is-square



        "
      >
      <div class="AdaptiveMedia-container">
          <div class="AdaptiveMedia-singlePhoto"
    style="padding-top: calc(1.7777777777777777 * 100% - 0.5px);"
>
    <div class="AdaptiveMedia-photoContainer js-adaptive-photo "
  data-image-url="https://pbs.twimg.com/media/DILKy-7WAAI8Lpd.jpg"


  data-element-context="platform_photo_card"
    style="background-color:rgba(23,37,64,1.0);"
    data-dominant-color="[23,37,64]"
>
  <img data-aria-label-part src="https://pbs.twimg.com/media/DILKy-7WAAI8Lpd.jpg" alt=""
      style="width: 100%; top: -53px;"
>
</div>


</div>
      </div>
    </div>
  </div>








      <div class="stream-item-footer">

      <div class="ProfileTweet-actionCountList u-hiddenVisually">


    <span class="ProfileTweet-action--reply u-hiddenVisually">
      <span class="ProfileTweet-actionCount" aria-hidden="true" data-tweet-stat-count="0">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-reply-count-aria-901505882516848640" >0 replies</span>
      </span>
    </span>
    <span class="ProfileTweet-action--retweet u-hiddenVisually">
      <span class="ProfileTweet-actionCount" aria-hidden="true" data-tweet-stat-count="0">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-retweet-count-aria-901505882516848640" >0 retweets</span>
      </span>
    </span>
    <span class="ProfileTweet-action--favorite u-hiddenVisually">
      <span class="ProfileTweet-actionCount" aria-hidden="true" data-tweet-stat-count="0">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-favorite-count-aria-901505882516848640" >0 likes</span>
      </span>
    </span>
  </div>

  <div class="ProfileTweet-actionList js-actions" role="group" aria-label="Tweet actions">
    <div class="ProfileTweet-action ProfileTweet-action--reply">
  <button class="ProfileTweet-actionButton js-actionButton js-actionReply"
    data-modal="ProfileTweet-reply" type="button"
    aria-describedby="profile-tweet-action-reply-count-aria-901505882516848640">
    <div class="IconContainer js-tooltip" title="Reply">
      <span class="Icon Icon--medium Icon--reply"></span>
      <span class="u-hiddenVisually">Reply</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero ">
        <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
      </span>
  </button>
</div>

    <div class="ProfileTweet-action ProfileTweet-action--retweet js-toggleState js-toggleRt">
  <button class="ProfileTweet-actionButton  js-actionButton js-actionRetweet"

    data-modal="ProfileTweet-retweet"
    type="button"
    aria-describedby="profile-tweet-action-retweet-count-aria-901505882516848640">
    <div class="IconContainer js-tooltip" title="Retweet">
      <span class="Icon Icon--medium Icon--retweet"></span>
      <span class="u-hiddenVisually">Retweet</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
  </span>

  </button><button class="ProfileTweet-actionButtonUndo js-actionButton js-actionRetweet" data-modal="ProfileTweet-retweet" type="button">
    <div class="IconContainer js-tooltip" title="Undo retweet">
      <span class="Icon Icon--medium Icon--retweet"></span>
      <span class="u-hiddenVisually">Retweeted</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
  </span>

  </button>
</div>


    <div class="ProfileTweet-action ProfileTweet-action--favorite js-toggleState">
  <button class="ProfileTweet-actionButton js-actionButton js-actionFavorite" type="button"
    aria-describedby="profile-tweet-action-favorite-count-aria-901505882516848640">
    <div class="IconContainer js-tooltip" title="Like">
      <span role="presentation" class="Icon Icon--heart Icon--medium"></span>
      <div class="HeartAnimation"></div>
      <span class="u-hiddenVisually">Like</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
  </span>

  </button><button class="ProfileTweet-actionButtonUndo ProfileTweet-action--unfavorite u-linkClean js-actionButton js-actionFavorite" type="button">
    <div class="IconContainer js-tooltip" title="Undo like">
      <span role="presentation" class="Icon Icon--heart Icon--medium"></span>
      <div class="HeartAnimation"></div>
      <span class="u-hiddenVisually">Liked</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
  </span>

  </button>
</div>





  <div class="ProfileTweet-action ProfileTweet-action--analytics">
    <button class="ProfileTweet-actionButton u-textUserColorHover js-actionButton js-actionQuickPromote" type="button">
      <div class="IconContainer js-tooltip" title="View Tweet activity">
        <span class="Icon Icon--medium Icon--analytics"></span>
        <span class="u-hiddenVisually">View Tweet activity</span>
      </div>
    </button>
  </div>

  </div>

</div>







    </div>
  </div>




<div class="dismiss-module">
  <div class="dismissed-module">
      <div class="feedback-action" data-feedback-type="DontLike">
        <div class="action-confirmation">Thanks. Twitter will use this to make your timeline better. <span class="undo-action u-textUserColor">Undo</span></div>
      </div>
  </div>
</div>

</li>

      <li class="js-stream-item stream-item stream-item
" data-item-id="901504263611965441"
id="stream-item-tweet-901504263611965441"
data-item-type="tweet"
 data-suggestion-json="{&quot;suggestion_details&quot;:{},&quot;tweet_ids&quot;:&quot;901504263611965441&quot;,&quot;scribe_component&quot;:&quot;tweet&quot;}">




  <div class="tweet js-stream-tweet js-actionable-tweet js-profile-popup-actionable dismissible-content
       original-tweet js-original-tweet

       my-tweet has-cards  has-content
"

data-tweet-id="901504263611965441"
data-item-id="901504263611965441"
data-permalink-path="/mohammed_attya/status/901504263611965441"
data-conversation-id="901504263611965441"



data-tweet-nonce="901504263611965441-e80f69d1-6d03-43cf-ba84-c97f29d24e5a"
data-tweet-stat-initialized="true"






  data-screen-name="mohammed_attya" data-name="Mohammed Attya" data-user-id="250377148"
  data-you-follow="false"
  data-follows-you="false"
  data-you-block="false"


data-reply-to-users-json="[{&quot;id_str&quot;:&quot;250377148&quot;,&quot;screen_name&quot;:&quot;mohammed_attya&quot;,&quot;name&quot;:&quot;Mohammed Attya&quot;,&quot;emojified_name&quot;:{&quot;text&quot;:&quot;Mohammed Attya&quot;,&quot;emojified_text_as_html&quot;:&quot;Mohammed Attya&quot;}}]"







data-disclosure-type=""




 data-has-cards="true"







 data-tfb-view="/i/tfb/v1/quick_promote/901504263611965441"
    >

    <div class="context">


    </div>

    <div class="content">





      <div class="stream-item-header">
          <a  class="account-group js-account-group js-action-profile js-user-profile-link js-nav" href="/mohammed_attya" data-user-id="250377148">
    <img class="avatar js-action-profile-avatar" src="https://pbs.twimg.com/profile_images/878491891733549056/3urQeq4j_bigger.jpg" alt="">
    <span class="FullNameGroup">
      <strong class="fullname show-popup-with-id " data-aria-label-part>Mohammed Attya</strong><span>&rlm;</span><span class="UserBadges"></span><span class="UserNameBreak">&nbsp;</span></span><span class="username u-dir" dir="ltr" data-aria-label-part>@<b>mohammed_attya</b></span></a>


        <small class="time">
  <a href="/mohammed_attya/status/901504263611965441" class="tweet-timestamp js-permalink js-nav js-tooltip" title="7:59 PM - 26 Aug 2017"  data-conversation-id="901504263611965441"><span class="_timestamp js-short-timestamp " data-aria-label-part="last" data-time="1503770340" data-time-ms="1503770340000" data-long-form="true">Aug 26</span></a>
</small>

          <div class="ProfileTweet-action ProfileTweet-action--more js-more-ProfileTweet-actions">
    <div class="dropdown">
  <button class="ProfileTweet-actionButton u-textUserColorHover dropdown-toggle js-dropdown-toggle" type="button">
      <div class="IconContainer js-tooltip" title="More">
        <span class="Icon Icon--caretDownLight Icon--small"></span>
        <span class="u-hiddenVisually">More</span>
      </div>
  </button>
  <div class="dropdown-menu is-autoCentered">
  <div class="dropdown-caret">
    <div class="caret-outer"></div>
    <div class="caret-inner"></div>
  </div>
  <ul>
      <li class="share-via-dm js-actionShareViaDM" data-nav="share_tweet_dm">
        <button type="button" class="dropdown-link">Share via Direct Message</button>
      </li>

      <li class="copy-link-to-tweet js-actionCopyLinkToTweet">
        <button type="button" class="dropdown-link">Copy link to Tweet</button>
      </li>
      <li class="embed-link js-actionEmbedTweet" data-nav="embed_tweet">
        <button type="button" class="dropdown-link">Embed Tweet</button>
      </li>
      <li class="user-pin-tweet js-actionPinTweet" data-nav="user_pin_tweet">
        <button type="button" class="dropdown-link">Pin to your profile page</button>
      </li>
      <li class="user-unpin-tweet js-actionPinTweet" data-nav="user_unpin_tweet">
        <button type="button" class="dropdown-link">Unpin from profile page</button>
      </li>
      <li class="js-actionDelete">
        <button type="button" class="dropdown-link">Delete Tweet</button>
      </li>
      <li class="dropdown-divider"></li>
      <li class="js-actionMomentMakerAddTweetToOtherMoment MomentMakerAddTweetToOtherMoment">
        <button type="button" class="dropdown-link">Add to other Moment</button>
      </li>
      <li class="js-actionMomentMakerCreateMoment">
        <button type="button" class="dropdown-link">Add to new Moment</button>
      </li>
  </ul>
</div>
</div>

  </div>

      </div>





        <div class="js-tweet-text-container">
  <p class="TweetTextSize TweetTextSize--normal js-tweet-text tweet-text tweet-text-rtl" lang="ar" data-aria-label-part="0">بالمناسبة حسن مصطفى هو رئيس الاتحاد الدولي لكرة اليد<a href="https://t.co/dbrTB7MR9x" class="twitter-timeline-link u-hidden" data-pre-embedded="true" dir="ltr" >pic.twitter.com/dbrTB7MR9x</a></p>
</div>


        <button type="button"
      class="btn-link js-translate-tweet translate-button u-textUserColorHover"
      data-nav="translate_tweet"
    >
    <span class="Icon Icon--translator"></span>Translate from Arabic
  </button>
  <div class="tweet-translation-container">
  <div class="spinner tweet-translation-loading" title="Loading..."></div>
  <div class="tweet-translation needs-translation" data-dest-lang="en">
    <div class="translation-attribution">
      <span>Translated from <span class="tweet-language">Arabic</span> by <a class="attribution-logo" href="http://aka.ms/Twitter2BingTranslator"><span class="invisible">Bing</span></a></span>
        <button type="button" class="btn-link js-translation-feedback-button" data-nav="">Wrong translation?</button>
    </div>
    <p class="tweet-translation-text"></p>
  </div>
</div>



            <div class="AdaptiveMediaOuterContainer">
    <div class="AdaptiveMedia


        is-square



        "
      >
      <div class="AdaptiveMedia-container">
          <div class="AdaptiveMedia-singlePhoto"
    style="padding-top: calc(1.7777777777777777 * 100% - 0.5px);"
>
    <div class="AdaptiveMedia-photoContainer js-adaptive-photo "
  data-image-url="https://pbs.twimg.com/media/DILJTc8WAAEYCvN.jpg"


  data-element-context="platform_photo_card"
    style="background-color:rgba(64,64,64,1.0);"
    data-dominant-color="[64,64,64]"
>
  <img data-aria-label-part src="https://pbs.twimg.com/media/DILJTc8WAAEYCvN.jpg" alt=""
      style="width: 100%; top: -196px;"
>
</div>


</div>
      </div>
    </div>
  </div>








      <div class="stream-item-footer">

      <div class="ProfileTweet-actionCountList u-hiddenVisually">


    <span class="ProfileTweet-action--reply u-hiddenVisually">
      <span class="ProfileTweet-actionCount" aria-hidden="true" data-tweet-stat-count="0">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-reply-count-aria-901504263611965441" >0 replies</span>
      </span>
    </span>
    <span class="ProfileTweet-action--retweet u-hiddenVisually">
      <span class="ProfileTweet-actionCount" aria-hidden="true" data-tweet-stat-count="0">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-retweet-count-aria-901504263611965441" >0 retweets</span>
      </span>
    </span>
    <span class="ProfileTweet-action--favorite u-hiddenVisually">
      <span class="ProfileTweet-actionCount" aria-hidden="true" data-tweet-stat-count="0">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-favorite-count-aria-901504263611965441" >0 likes</span>
      </span>
    </span>
  </div>

  <div class="ProfileTweet-actionList js-actions" role="group" aria-label="Tweet actions">
    <div class="ProfileTweet-action ProfileTweet-action--reply">
  <button class="ProfileTweet-actionButton js-actionButton js-actionReply"
    data-modal="ProfileTweet-reply" type="button"
    aria-describedby="profile-tweet-action-reply-count-aria-901504263611965441">
    <div class="IconContainer js-tooltip" title="Reply">
      <span class="Icon Icon--medium Icon--reply"></span>
      <span class="u-hiddenVisually">Reply</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero ">
        <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
      </span>
  </button>
</div>

    <div class="ProfileTweet-action ProfileTweet-action--retweet js-toggleState js-toggleRt">
  <button class="ProfileTweet-actionButton  js-actionButton js-actionRetweet"

    data-modal="ProfileTweet-retweet"
    type="button"
    aria-describedby="profile-tweet-action-retweet-count-aria-901504263611965441">
    <div class="IconContainer js-tooltip" title="Retweet">
      <span class="Icon Icon--medium Icon--retweet"></span>
      <span class="u-hiddenVisually">Retweet</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
  </span>

  </button><button class="ProfileTweet-actionButtonUndo js-actionButton js-actionRetweet" data-modal="ProfileTweet-retweet" type="button">
    <div class="IconContainer js-tooltip" title="Undo retweet">
      <span class="Icon Icon--medium Icon--retweet"></span>
      <span class="u-hiddenVisually">Retweeted</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
  </span>

  </button>
</div>


    <div class="ProfileTweet-action ProfileTweet-action--favorite js-toggleState">
  <button class="ProfileTweet-actionButton js-actionButton js-actionFavorite" type="button"
    aria-describedby="profile-tweet-action-favorite-count-aria-901504263611965441">
    <div class="IconContainer js-tooltip" title="Like">
      <span role="presentation" class="Icon Icon--heart Icon--medium"></span>
      <div class="HeartAnimation"></div>
      <span class="u-hiddenVisually">Like</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
  </span>

  </button><button class="ProfileTweet-actionButtonUndo ProfileTweet-action--unfavorite u-linkClean js-actionButton js-actionFavorite" type="button">
    <div class="IconContainer js-tooltip" title="Undo like">
      <span role="presentation" class="Icon Icon--heart Icon--medium"></span>
      <div class="HeartAnimation"></div>
      <span class="u-hiddenVisually">Liked</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
  </span>

  </button>
</div>





  <div class="ProfileTweet-action ProfileTweet-action--analytics">
    <button class="ProfileTweet-actionButton u-textUserColorHover js-actionButton js-actionQuickPromote" type="button">
      <div class="IconContainer js-tooltip" title="View Tweet activity">
        <span class="Icon Icon--medium Icon--analytics"></span>
        <span class="u-hiddenVisually">View Tweet activity</span>
      </div>
    </button>
  </div>

  </div>

</div>







    </div>
  </div>




<div class="dismiss-module">
  <div class="dismissed-module">
      <div class="feedback-action" data-feedback-type="DontLike">
        <div class="action-confirmation">Thanks. Twitter will use this to make your timeline better. <span class="undo-action u-textUserColor">Undo</span></div>
      </div>
  </div>
</div>

</li>

      <li class="js-stream-item stream-item stream-item
" data-item-id="901202767070597126"
id="stream-item-tweet-901202767070597126"
data-item-type="tweet"
 data-suggestion-json="{&quot;suggestion_details&quot;:{},&quot;tweet_ids&quot;:&quot;901202767070597126&quot;,&quot;scribe_component&quot;:&quot;tweet&quot;}">




  <div class="tweet js-stream-tweet js-actionable-tweet js-profile-popup-actionable dismissible-content
       original-tweet js-original-tweet

       retweeted has-cards  has-content
"

data-tweet-id="901202767070597126"
data-item-id="901202767070597126"
data-permalink-path="/SalmaSameh19/status/901202767070597126"
data-conversation-id="901202767070597126"



data-tweet-nonce="901202767070597126-7b1edd81-5889-499e-a043-858fad44de8f"




 data-my-retweet-id="901488376196550658"
    data-retweet-id="901488376196550658"
     data-retweeter="mohammed_attya"


  data-screen-name="SalmaSameh19" data-name="SALMA" data-user-id="594769304"
  data-you-follow="false"
  data-follows-you="false"
  data-you-block="false"


data-reply-to-users-json="[{&quot;id_str&quot;:&quot;594769304&quot;,&quot;screen_name&quot;:&quot;SalmaSameh19&quot;,&quot;name&quot;:&quot;SALMA&quot;,&quot;emojified_name&quot;:{&quot;text&quot;:&quot;SALMA&quot;,&quot;emojified_text_as_html&quot;:&quot;SALMA&quot;}}]"







data-disclosure-type=""




 data-has-cards="true"








    >

    <div class="context">

          <div class="tweet-context with-icn

    ">

      <span class="Icon Icon--small Icon--retweeted"></span>



            <span class="js-retweet-text">
                <a class="pretty-link js-user-profile-link" href="/mohammed_attya" data-user-id="250377148" rel="noopener"><b>You</b></a> Retweeted
            </span>





    </div>

    </div>

    <div class="content">





      <div class="stream-item-header">
          <a  class="account-group js-account-group js-action-profile js-user-profile-link js-nav" href="/SalmaSameh19" data-user-id="594769304">
    <img class="avatar js-action-profile-avatar" src="https://pbs.twimg.com/profile_images/902368065572282370/nnDsT-zH_bigger.jpg" alt="">
    <span class="FullNameGroup">
      <strong class="fullname show-popup-with-id " data-aria-label-part>SALMA</strong><span>&rlm;</span><span class="UserBadges"></span><span class="UserNameBreak">&nbsp;</span></span><span class="username u-dir" dir="ltr" data-aria-label-part>@<b>SalmaSameh19</b></span></a>


        <small class="time">
  <a href="/SalmaSameh19/status/901202767070597126" class="tweet-timestamp js-permalink js-nav js-tooltip" title="12:00 AM - 26 Aug 2017"  data-conversation-id="901202767070597126"><span class="_timestamp js-short-timestamp " data-aria-label-part="last" data-time="1503698457" data-time-ms="1503698457000" data-long-form="true">Aug 26</span></a>
</small>

          <div class="ProfileTweet-action ProfileTweet-action--more js-more-ProfileTweet-actions">
    <div class="dropdown">
  <button class="ProfileTweet-actionButton u-textUserColorHover dropdown-toggle js-dropdown-toggle" type="button">
      <div class="IconContainer js-tooltip" title="More">
        <span class="Icon Icon--caretDownLight Icon--small"></span>
        <span class="u-hiddenVisually">More</span>
      </div>
  </button>
  <div class="dropdown-menu is-autoCentered">
  <div class="dropdown-caret">
    <div class="caret-outer"></div>
    <div class="caret-inner"></div>
  </div>
  <ul>

      <li class="copy-link-to-tweet js-actionCopyLinkToTweet">
        <button type="button" class="dropdown-link">Copy link to Tweet</button>
      </li>
      <li class="embed-link js-actionEmbedTweet" data-nav="embed_tweet">
        <button type="button" class="dropdown-link">Embed Tweet</button>
      </li>
          <li class="mute-user-item"><button type="button" class="dropdown-link">Mute <span class="username u-dir" dir="ltr" >@<b>SalmaSameh19</b></span></button></li>
    <li class="unmute-user-item"><button type="button" class="dropdown-link">Unmute <span class="username u-dir" dir="ltr" >@<b>SalmaSameh19</b></span></button></li>

        <li class="block-link js-actionBlock" data-nav="block">
          <button type="button" class="dropdown-link">Block <span class="username u-dir" dir="ltr" >@<b>SalmaSameh19</b></span></button>
        </li>
        <li class="unblock-link js-actionUnblock" data-nav="unblock">
          <button type="button" class="dropdown-link">Unblock <span class="username u-dir" dir="ltr" >@<b>SalmaSameh19</b></span></button>
        </li>
      <li class="report-link js-actionReport" data-nav="report">
        <button type="button" class="dropdown-link">


            Report Tweet
        </button>
      </li>
      <li class="dropdown-divider"></li>
      <li class="js-actionMomentMakerAddTweetToOtherMoment MomentMakerAddTweetToOtherMoment">
        <button type="button" class="dropdown-link">Add to other Moment</button>
      </li>
      <li class="js-actionMomentMakerCreateMoment">
        <button type="button" class="dropdown-link">Add to new Moment</button>
      </li>
  </ul>
</div>
</div>

  </div>

      </div>





        <div class="js-tweet-text-container">
  <p class="TweetTextSize TweetTextSize--normal js-tweet-text tweet-text tweet-text-rtl" lang="ar" data-aria-label-part="0">بعد 18 ساعة شغل بالوان الزيت ، ايه رأيكوا <img class="Emoji Emoji--forText" src="https://abs.twimg.com/emoji/v2/72x72/1f425.png" draggable="false" alt="🐥" title="Front-facing baby chick" aria-label="Emoji: Front-facing baby chick"><a href="https://t.co/EuY0fEakY9" class="twitter-timeline-link u-hidden" data-pre-embedded="true" dir="ltr" >pic.twitter.com/EuY0fEakY9</a></p>
</div>


        <button type="button"
      class="btn-link js-translate-tweet translate-button u-textUserColorHover"
      data-nav="translate_tweet"
    >
    <span class="Icon Icon--translator"></span>Translate from Arabic
  </button>
  <div class="tweet-translation-container">
  <div class="spinner tweet-translation-loading" title="Loading..."></div>
  <div class="tweet-translation needs-translation" data-dest-lang="en">
    <div class="translation-attribution">
      <span>Translated from <span class="tweet-language">Arabic</span> by <a class="attribution-logo" href="http://aka.ms/Twitter2BingTranslator"><span class="invisible">Bing</span></a></span>
        <button type="button" class="btn-link js-translation-feedback-button" data-nav="">Wrong translation?</button>
    </div>
    <p class="tweet-translation-text"></p>
  </div>
</div>



            <div class="AdaptiveMediaOuterContainer">
    <div class="AdaptiveMedia






        "
      >
      <div class="AdaptiveMedia-container">
          <div class="AdaptiveMedia-doublePhoto">
    <div class="AdaptiveMedia-halfWidthPhoto">
      <div class="AdaptiveMedia-photoContainer js-adaptive-photo "
  data-image-url="https://pbs.twimg.com/media/DIGzURZXYAA0Lpn.jpg"


  data-element-context="platform_photo_card"
    style="background-color:rgba(38,29,32,1.0);"
    data-dominant-color="[38,29,32]"
>
  <img data-aria-label-part src="https://pbs.twimg.com/media/DIGzURZXYAA0Lpn.jpg" alt=""
      style="width: 100%; top: -35px;"
>
</div>


    </div>
    <div class="AdaptiveMedia-halfWidthPhoto">
      <div class="AdaptiveMedia-photoContainer js-adaptive-photo "
  data-image-url="https://pbs.twimg.com/media/DIG2czWXsAACyAH.jpg"


  data-element-context="platform_photo_card"
    style="background-color:rgba(38,32,27,1.0);"
    data-dominant-color="[38,32,27]"
>
  <img data-aria-label-part src="https://pbs.twimg.com/media/DIG2czWXsAACyAH.jpg" alt=""
      style="height: 100%; left: -26px;"
>
</div>


    </div>
</div>

      </div>
    </div>
  </div>








      <div class="stream-item-footer">

      <div class="ProfileTweet-actionCountList u-hiddenVisually">


    <span class="ProfileTweet-action--reply u-hiddenVisually">
      <span class="ProfileTweet-actionCount"  data-tweet-stat-count="620">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-reply-count-aria-901202767070597126" data-aria-label-part>620 replies</span>
      </span>
    </span>
    <span class="ProfileTweet-action--retweet u-hiddenVisually">
      <span class="ProfileTweet-actionCount"  data-tweet-stat-count="5258">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-retweet-count-aria-901202767070597126" data-aria-label-part>5,258 retweets</span>
      </span>
    </span>
    <span class="ProfileTweet-action--favorite u-hiddenVisually">
      <span class="ProfileTweet-actionCount"  data-tweet-stat-count="15547">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-favorite-count-aria-901202767070597126" data-aria-label-part>15,547 likes</span>
      </span>
    </span>
  </div>

  <div class="ProfileTweet-actionList js-actions" role="group" aria-label="Tweet actions">
    <div class="ProfileTweet-action ProfileTweet-action--reply">
  <button class="ProfileTweet-actionButton js-actionButton js-actionReply"
    data-modal="ProfileTweet-reply" type="button"
    aria-describedby="profile-tweet-action-reply-count-aria-901202767070597126">
    <div class="IconContainer js-tooltip" title="Reply">
      <span class="Icon Icon--medium Icon--reply"></span>
      <span class="u-hiddenVisually">Reply</span>
    </div>
      <span class="ProfileTweet-actionCount ">
        <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
      </span>
  </button>
</div>

    <div class="ProfileTweet-action ProfileTweet-action--retweet js-toggleState js-toggleRt">
  <button class="ProfileTweet-actionButton  js-actionButton js-actionRetweet"

    data-modal="ProfileTweet-retweet"
    type="button"
    aria-describedby="profile-tweet-action-retweet-count-aria-901202767070597126">
    <div class="IconContainer js-tooltip" title="Retweet">
      <span class="Icon Icon--medium Icon--retweet"></span>
      <span class="u-hiddenVisually">Retweet</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
  </span>

  </button><button class="ProfileTweet-actionButtonUndo js-actionButton js-actionRetweet" data-modal="ProfileTweet-retweet" type="button">
    <div class="IconContainer js-tooltip" title="Undo retweet">
      <span class="Icon Icon--medium Icon--retweet"></span>
      <span class="u-hiddenVisually">Retweeted</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
  </span>

  </button>
</div>


    <div class="ProfileTweet-action ProfileTweet-action--favorite js-toggleState">
  <button class="ProfileTweet-actionButton js-actionButton js-actionFavorite" type="button"
    aria-describedby="profile-tweet-action-favorite-count-aria-901202767070597126">
    <div class="IconContainer js-tooltip" title="Like">
      <span role="presentation" class="Icon Icon--heart Icon--medium"></span>
      <div class="HeartAnimation"></div>
      <span class="u-hiddenVisually">Like</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
  </span>

  </button><button class="ProfileTweet-actionButtonUndo ProfileTweet-action--unfavorite u-linkClean js-actionButton js-actionFavorite" type="button">
    <div class="IconContainer js-tooltip" title="Undo like">
      <span role="presentation" class="Icon Icon--heart Icon--medium"></span>
      <div class="HeartAnimation"></div>
      <span class="u-hiddenVisually">Liked</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
  </span>

  </button>
</div>


      <div class="ProfileTweet-action ProfileTweet-action--dm">
    <button class="ProfileTweet-actionButton u-textUserColorHover js-actionButton js-actionShareViaDM"
      type="button"
      data-nav="share_tweet_dm"
    >
      <div class="IconContainer js-tooltip" title="Direct message">
        <span class="Icon Icon--medium Icon--dm"></span>
        <span class="u-hiddenVisually">Direct message</span>
      </div>
    </button>
  </div>




  </div>

</div>







    </div>
  </div>




<div class="dismiss-module">
  <div class="dismissed-module">
      <div class="feedback-action" data-feedback-type="DontLike">
        <div class="action-confirmation">Thanks. Twitter will use this to make your timeline better. <span class="undo-action u-textUserColor">Undo</span></div>
      </div>
  </div>
</div>

</li>

      <li class="js-stream-item stream-item stream-item
" data-item-id="901218354681319425"
id="stream-item-tweet-901218354681319425"
data-item-type="tweet"
 data-suggestion-json="{&quot;suggestion_details&quot;:{},&quot;tweet_ids&quot;:&quot;901218354681319425&quot;,&quot;scribe_component&quot;:&quot;tweet&quot;}">




  <div class="tweet js-stream-tweet js-actionable-tweet js-profile-popup-actionable dismissible-content
       original-tweet js-original-tweet

       retweeted
"

data-tweet-id="901218354681319425"
data-item-id="901218354681319425"
data-permalink-path="/MadamLaam/status/901218354681319425"
data-conversation-id="901218354681319425"



data-tweet-nonce="901218354681319425-ff2ba0fc-f229-4500-a314-16b85960285e"
data-tweet-stat-initialized="true"



 data-my-retweet-id="901350584376328193"
    data-retweet-id="901350584376328193"
     data-retweeter="mohammed_attya"


  data-screen-name="MadamLaam" data-name="لام" data-user-id="879700334"
  data-you-follow="true"
  data-follows-you="true"
  data-you-block="false"


data-reply-to-users-json="[{&quot;id_str&quot;:&quot;879700334&quot;,&quot;screen_name&quot;:&quot;MadamLaam&quot;,&quot;name&quot;:&quot;\u0644\u0627\u0645&quot;,&quot;emojified_name&quot;:{&quot;text&quot;:&quot;\u0644\u0627\u0645&quot;,&quot;emojified_text_as_html&quot;:&quot;\u0644\u0627\u0645&quot;}}]"







data-disclosure-type=""













    >

    <div class="context">

          <div class="tweet-context with-icn

    ">

      <span class="Icon Icon--small Icon--retweeted"></span>



            <span class="js-retweet-text">
                <a class="pretty-link js-user-profile-link" href="/mohammed_attya" data-user-id="250377148" rel="noopener"><b>You</b></a> Retweeted
            </span>





    </div>

    </div>

    <div class="content">





      <div class="stream-item-header">
          <a  class="account-group js-account-group js-action-profile js-user-profile-link js-nav" href="/MadamLaam" data-user-id="879700334">
    <img class="avatar js-action-profile-avatar" src="https://pbs.twimg.com/profile_images/882556191561326592/1G7EDHwL_bigger.jpg" alt="">
    <span class="FullNameGroup">
      <strong class="fullname show-popup-with-id fullname-rtl" >لام</strong><span>&rlm;</span><span class="UserBadges"></span><span class="UserNameBreak">&nbsp;</span></span><span class="username u-dir" dir="ltr" >@<b>MadamLaam</b></span></a>


        <small class="time">
  <a href="/MadamLaam/status/901218354681319425" class="tweet-timestamp js-permalink js-nav js-tooltip" title="1:02 AM - 26 Aug 2017"  data-conversation-id="901218354681319425"><span class="_timestamp js-short-timestamp " data-aria-label-part="last" data-time="1503702174" data-time-ms="1503702174000" data-long-form="true">Aug 26</span></a>
</small>

          <div class="ProfileTweet-action ProfileTweet-action--more js-more-ProfileTweet-actions">
    <div class="dropdown">
  <button class="ProfileTweet-actionButton u-textUserColorHover dropdown-toggle js-dropdown-toggle" type="button">
      <div class="IconContainer js-tooltip" title="More">
        <span class="Icon Icon--caretDownLight Icon--small"></span>
        <span class="u-hiddenVisually">More</span>
      </div>
  </button>
  <div class="dropdown-menu is-autoCentered">
  <div class="dropdown-caret">
    <div class="caret-outer"></div>
    <div class="caret-inner"></div>
  </div>
  <ul>

      <li class="copy-link-to-tweet js-actionCopyLinkToTweet">
        <button type="button" class="dropdown-link">Copy link to Tweet</button>
      </li>
      <li class="embed-link js-actionEmbedTweet" data-nav="embed_tweet">
        <button type="button" class="dropdown-link">Embed Tweet</button>
      </li>
          <li class="mute-user-item"><button type="button" class="dropdown-link">Mute <span class="username u-dir" dir="ltr" >@<b>MadamLaam</b></span></button></li>
    <li class="unmute-user-item"><button type="button" class="dropdown-link">Unmute <span class="username u-dir" dir="ltr" >@<b>MadamLaam</b></span></button></li>

        <li class="block-link js-actionBlock" data-nav="block">
          <button type="button" class="dropdown-link">Block <span class="username u-dir" dir="ltr" >@<b>MadamLaam</b></span></button>
        </li>
        <li class="unblock-link js-actionUnblock" data-nav="unblock">
          <button type="button" class="dropdown-link">Unblock <span class="username u-dir" dir="ltr" >@<b>MadamLaam</b></span></button>
        </li>
      <li class="report-link js-actionReport" data-nav="report">
        <button type="button" class="dropdown-link">


            Report Tweet
        </button>
      </li>
      <li class="dropdown-divider"></li>
      <li class="js-actionMomentMakerAddTweetToOtherMoment MomentMakerAddTweetToOtherMoment">
        <button type="button" class="dropdown-link">Add to other Moment</button>
      </li>
      <li class="js-actionMomentMakerCreateMoment">
        <button type="button" class="dropdown-link">Add to new Moment</button>
      </li>
  </ul>
</div>
</div>

  </div>

      </div>





          <p class="u-hiddenVisually" aria-hidden="true" data-aria-label-part="1">لام Retweeted Alaa Nabil</p>


<div class="js-tweet-text-container">
  <p class="TweetTextSize TweetTextSize--normal js-tweet-text tweet-text tweet-text-rtl" lang="ar" data-aria-label-part="4">الكتاب عن أشرف مروان زوج بنت عبد الناصر وأعظم جاسوس في تاريخ إسرائيل كما يراه الكثير من الأسرائيليين ..الثريد به عرض للكتاب<a href="https://t.co/7x5jer6Wtz" rel="nofollow noopener" dir="ltr" data-expanded-url="https://twitter.com/3ala2_nabil/status/901207968649293825" class="twitter-timeline-link u-hidden" target="_blank" title="https://twitter.com/3ala2_nabil/status/901207968649293825" ><span class="tco-ellipsis"></span><span class="invisible">https://</span><span class="js-display-url">twitter.com/3ala2_nabil/st</span><span class="invisible">atus/901207968649293825</span><span class="tco-ellipsis"><span class="invisible">&nbsp;</span>…</span></a></p>
</div>


<p class="u-hiddenVisually" aria-hidden="true" data-aria-label-part="3">لام added,</p>

      <div class="QuoteTweet


    u-block js-tweet-details-fixer">
  <div class="QuoteTweet-container">
    <a class="QuoteTweet-link js-nav" href="/3ala2_nabil/status/901207968649293825" data-conversation-id="901207968649293825" aria-hidden="true"
       >
    </a>
    <div class="QuoteTweet-innerContainer u-cf js-permalink js-media-container"
      data-item-id="901207968649293825"
      data-item-type="tweet"
      data-screen-name="3ala2_nabil"
      data-user-id="1950331711"
      href="/3ala2_nabil/status/901207968649293825"
      data-conversation-id="901207968649293825"
      tabindex="0">
      <div class="tweet-content">
        <div class="QuoteTweet-authorAndText u-alignTop">

  <div class="QuoteTweet-originalAuthor u-cf u-textTruncate stream-item-header account-group js-user-profile-link">
    <b class="QuoteTweet-fullname u-linkComplex-target">Alaa Nabil</b><span class="UserBadges"></span><span class="UserNameBreak">&nbsp;</span><span class="username u-dir" dir="ltr" >@<b>3ala2_nabil</b></span>
  </div>


          <div class="QuoteTweet-text tweet-text u-dir js-ellipsis"
            lang="ar"
            data-aria-label-part="2"
            dir="rtl"
            >أنهيت لتوي كتاب مذهل في تفاصيله و معلوماته، و حاسس إني لازم أتكلم عنه قبل ما أنام، الكتاب هو &quot; الملاك&quot; للكاتب الاسرائيلي يوري بار-جوزيف</div>
        </div>
      </div>
    </div>
  </div>
</div>



        <button type="button"
      class="btn-link js-translate-tweet translate-button u-textUserColorHover"
      data-nav="translate_tweet"
    >
    <span class="Icon Icon--translator"></span>Translate from Arabic
  </button>
  <div class="tweet-translation-container">
  <div class="spinner tweet-translation-loading" title="Loading..."></div>
  <div class="tweet-translation needs-translation" data-dest-lang="en">
    <div class="translation-attribution">
      <span>Translated from <span class="tweet-language">Arabic</span> by <a class="attribution-logo" href="http://aka.ms/Twitter2BingTranslator"><span class="invisible">Bing</span></a></span>
        <button type="button" class="btn-link js-translation-feedback-button" data-nav="">Wrong translation?</button>
    </div>
    <p class="tweet-translation-text"></p>
  </div>
</div>










      <div class="stream-item-footer">

      <div class="ProfileTweet-actionCountList u-hiddenVisually">


    <span class="ProfileTweet-action--reply u-hiddenVisually">
      <span class="ProfileTweet-actionCount"  data-tweet-stat-count="2">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-reply-count-aria-901218354681319425" data-aria-label-part>2 replies</span>
      </span>
    </span>
    <span class="ProfileTweet-action--retweet u-hiddenVisually">
      <span class="ProfileTweet-actionCount"  data-tweet-stat-count="11">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-retweet-count-aria-901218354681319425" data-aria-label-part>11 retweets</span>
      </span>
    </span>
    <span class="ProfileTweet-action--favorite u-hiddenVisually">
      <span class="ProfileTweet-actionCount"  data-tweet-stat-count="7">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-favorite-count-aria-901218354681319425" data-aria-label-part>7 likes</span>
      </span>
    </span>
  </div>

  <div class="ProfileTweet-actionList js-actions" role="group" aria-label="Tweet actions">
    <div class="ProfileTweet-action ProfileTweet-action--reply">
  <button class="ProfileTweet-actionButton js-actionButton js-actionReply"
    data-modal="ProfileTweet-reply" type="button"
    aria-describedby="profile-tweet-action-reply-count-aria-901218354681319425">
    <div class="IconContainer js-tooltip" title="Reply">
      <span class="Icon Icon--medium Icon--reply"></span>
      <span class="u-hiddenVisually">Reply</span>
    </div>
      <span class="ProfileTweet-actionCount ">
        <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">2</span>
      </span>
  </button>
</div>

    <div class="ProfileTweet-action ProfileTweet-action--retweet js-toggleState js-toggleRt">
  <button class="ProfileTweet-actionButton  js-actionButton js-actionRetweet"

    data-modal="ProfileTweet-retweet"
    type="button"
    aria-describedby="profile-tweet-action-retweet-count-aria-901218354681319425">
    <div class="IconContainer js-tooltip" title="Retweet">
      <span class="Icon Icon--medium Icon--retweet"></span>
      <span class="u-hiddenVisually">Retweet</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">11</span>
  </span>

  </button><button class="ProfileTweet-actionButtonUndo js-actionButton js-actionRetweet" data-modal="ProfileTweet-retweet" type="button">
    <div class="IconContainer js-tooltip" title="Undo retweet">
      <span class="Icon Icon--medium Icon--retweet"></span>
      <span class="u-hiddenVisually">Retweeted</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">11</span>
  </span>

  </button>
</div>


    <div class="ProfileTweet-action ProfileTweet-action--favorite js-toggleState">
  <button class="ProfileTweet-actionButton js-actionButton js-actionFavorite" type="button"
    aria-describedby="profile-tweet-action-favorite-count-aria-901218354681319425">
    <div class="IconContainer js-tooltip" title="Like">
      <span role="presentation" class="Icon Icon--heart Icon--medium"></span>
      <div class="HeartAnimation"></div>
      <span class="u-hiddenVisually">Like</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">7</span>
  </span>

  </button><button class="ProfileTweet-actionButtonUndo ProfileTweet-action--unfavorite u-linkClean js-actionButton js-actionFavorite" type="button">
    <div class="IconContainer js-tooltip" title="Undo like">
      <span role="presentation" class="Icon Icon--heart Icon--medium"></span>
      <div class="HeartAnimation"></div>
      <span class="u-hiddenVisually">Liked</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">7</span>
  </span>

  </button>
</div>


      <div class="ProfileTweet-action ProfileTweet-action--dm">
    <button class="ProfileTweet-actionButton u-textUserColorHover js-actionButton js-actionShareViaDM"
      type="button"
      data-nav="share_tweet_dm"
    >
      <div class="IconContainer js-tooltip" title="Direct message">
        <span class="Icon Icon--medium Icon--dm"></span>
        <span class="u-hiddenVisually">Direct message</span>
      </div>
    </button>
  </div>




  </div>

</div>







    </div>
  </div>




<div class="dismiss-module">
  <div class="dismissed-module">
      <div class="feedback-action" data-feedback-type="DontLike">
        <div class="action-confirmation">Thanks. Twitter will use this to make your timeline better. <span class="undo-action u-textUserColor">Undo</span></div>
      </div>
  </div>
</div>

</li>

      <li class="js-stream-item stream-item stream-item
" data-item-id="901219821131628544"
id="stream-item-tweet-901219821131628544"
data-item-type="tweet"
 data-suggestion-json="{&quot;suggestion_details&quot;:{},&quot;tweet_ids&quot;:&quot;901219821131628544&quot;,&quot;scribe_component&quot;:&quot;tweet&quot;}">




  <div class="tweet js-stream-tweet js-actionable-tweet js-profile-popup-actionable dismissible-content
       original-tweet js-original-tweet

       retweeted
"

data-tweet-id="901219821131628544"
data-item-id="901219821131628544"
data-permalink-path="/mohamedmsayed/status/901219821131628544"
data-conversation-id="901207968649293825"
 data-is-reply-to="true"
 data-has-parent-tweet="true"

data-tweet-nonce="901219821131628544-8c35b0c4-ac44-4582-a884-0b807c79d074"
data-tweet-stat-initialized="true"



 data-my-retweet-id="901341446183284737"
    data-retweet-id="901341446183284737"
     data-retweeter="mohammed_attya"


  data-screen-name="mohamedmsayed" data-name="محمد سيد 📱💻 📝" data-user-id="96543231"
  data-you-follow="false"
  data-follows-you="false"
  data-you-block="false"
 data-mentions="3ala2_nabil"

data-reply-to-users-json="[{&quot;id_str&quot;:&quot;96543231&quot;,&quot;screen_name&quot;:&quot;mohamedmsayed&quot;,&quot;name&quot;:&quot;\u0645\u062d\u0645\u062f \u0633\u064a\u062f \ud83d\udcf1\ud83d\udcbb \ud83d\udcdd&quot;,&quot;emojified_name&quot;:{&quot;text&quot;:&quot;\u0645\u062d\u0645\u062f \u0633\u064a\u062f \ud83d\udcf1\ud83d\udcbb \ud83d\udcdd&quot;,&quot;emojified_text_as_html&quot;:&quot;\u0645\u062d\u0645\u062f \u0633\u064a\u062f \u003cspan class=\&quot;Emoji Emoji--forLinks\&quot; style=\&quot;background-image:url(&#39;https:\/\/abs.twimg.com\/emoji\/v2\/72x72\/1f4f1.png&#39;)\&quot; title=\&quot;Mobile phone\&quot; aria-label=\&quot;Emoji: Mobile phone\&quot;\u003e&amp;nbsp;\u003c\/span\u003e\u003cspan class=\&quot;visuallyhidden\&quot; aria-hidden=\&quot;true\&quot;\u003e\ud83d\udcf1\u003c\/span\u003e\u003cspan class=\&quot;Emoji Emoji--forLinks\&quot; style=\&quot;background-image:url(&#39;https:\/\/abs.twimg.com\/emoji\/v2\/72x72\/1f4bb.png&#39;)\&quot; title=\&quot;Personal computer\&quot; aria-label=\&quot;Emoji: Personal computer\&quot;\u003e&amp;nbsp;\u003c\/span\u003e\u003cspan class=\&quot;visuallyhidden\&quot; aria-hidden=\&quot;true\&quot;\u003e\ud83d\udcbb\u003c\/span\u003e \u003cspan class=\&quot;Emoji Emoji--forLinks\&quot; style=\&quot;background-image:url(&#39;https:\/\/abs.twimg.com\/emoji\/v2\/72x72\/1f4dd.png&#39;)\&quot; title=\&quot;Memo\&quot; aria-label=\&quot;Emoji: Memo\&quot;\u003e&amp;nbsp;\u003c\/span\u003e\u003cspan class=\&quot;visuallyhidden\&quot; aria-hidden=\&quot;true\&quot;\u003e\ud83d\udcdd\u003c\/span\u003e&quot;}},{&quot;id_str&quot;:&quot;1950331711&quot;,&quot;screen_name&quot;:&quot;3ala2_nabil&quot;,&quot;name&quot;:&quot;Alaa Nabil&quot;,&quot;emojified_name&quot;:{&quot;text&quot;:&quot;Alaa Nabil&quot;,&quot;emojified_text_as_html&quot;:&quot;Alaa Nabil&quot;}}]"







data-disclosure-type=""













    >

    <div class="context">

          <div class="tweet-context with-icn

    ">

      <span class="Icon Icon--small Icon--retweeted"></span>



            <span class="js-retweet-text">
                <a class="pretty-link js-user-profile-link" href="/mohammed_attya" data-user-id="250377148" rel="noopener"><b>You</b></a> Retweeted
            </span>





    </div>

    </div>

    <div class="content">





      <div class="stream-item-header">
          <a  class="account-group js-account-group js-action-profile js-user-profile-link js-nav" href="/mohamedmsayed" data-user-id="96543231">
    <img class="avatar js-action-profile-avatar" src="https://pbs.twimg.com/profile_images/902196404180963328/5EPatzU__bigger.jpg" alt="">
    <span class="FullNameGroup">
      <strong class="fullname show-popup-with-id fullname-rtl" data-aria-label-part>محمد سيد <span class="Emoji Emoji--forLinks" style="background-image:url("https://abs.twimg.com/emoji/v2/72x72/1f4f1.png")" title="Mobile phone" aria-label="Emoji: Mobile phone">&nbsp;</span><span class="visuallyhidden" aria-hidden="true">📱</span><span class="Emoji Emoji--forLinks" style="background-image:url("https://abs.twimg.com/emoji/v2/72x72/1f4bb.png")" title="Personal computer" aria-label="Emoji: Personal computer">&nbsp;</span><span class="visuallyhidden" aria-hidden="true">💻</span> <span class="Emoji Emoji--forLinks" style="background-image:url("https://abs.twimg.com/emoji/v2/72x72/1f4dd.png")" title="Memo" aria-label="Emoji: Memo">&nbsp;</span><span class="visuallyhidden" aria-hidden="true">📝</span></strong><span>&rlm;</span><span class="UserBadges"></span><span class="UserNameBreak">&nbsp;</span></span><span class="username u-dir" dir="ltr" data-aria-label-part>@<b>mohamedmsayed</b></span></a>


        <small class="time">
  <a href="/mohamedmsayed/status/901219821131628544" class="tweet-timestamp js-permalink js-nav js-tooltip" title="1:08 AM - 26 Aug 2017"  data-conversation-id="901207968649293825"><span class="_timestamp js-short-timestamp " data-aria-label-part="last" data-time="1503702523" data-time-ms="1503702523000" data-long-form="true">Aug 26</span></a>
</small>

          <div class="ProfileTweet-action ProfileTweet-action--more js-more-ProfileTweet-actions">
    <div class="dropdown">
  <button class="ProfileTweet-actionButton u-textUserColorHover dropdown-toggle js-dropdown-toggle" type="button">
      <div class="IconContainer js-tooltip" title="More">
        <span class="Icon Icon--caretDownLight Icon--small"></span>
        <span class="u-hiddenVisually">More</span>
      </div>
  </button>
  <div class="dropdown-menu is-autoCentered">
  <div class="dropdown-caret">
    <div class="caret-outer"></div>
    <div class="caret-inner"></div>
  </div>
  <ul>

      <li class="copy-link-to-tweet js-actionCopyLinkToTweet">
        <button type="button" class="dropdown-link">Copy link to Tweet</button>
      </li>
      <li class="embed-link js-actionEmbedTweet" data-nav="embed_tweet">
        <button type="button" class="dropdown-link">Embed Tweet</button>
      </li>
          <li class="mute-user-item"><button type="button" class="dropdown-link">Mute <span class="username u-dir" dir="ltr" >@<b>mohamedmsayed</b></span></button></li>
    <li class="unmute-user-item"><button type="button" class="dropdown-link">Unmute <span class="username u-dir" dir="ltr" >@<b>mohamedmsayed</b></span></button></li>

        <li class="block-link js-actionBlock" data-nav="block">
          <button type="button" class="dropdown-link">Block <span class="username u-dir" dir="ltr" >@<b>mohamedmsayed</b></span></button>
        </li>
        <li class="unblock-link js-actionUnblock" data-nav="unblock">
          <button type="button" class="dropdown-link">Unblock <span class="username u-dir" dir="ltr" >@<b>mohamedmsayed</b></span></button>
        </li>
      <li class="report-link js-actionReport" data-nav="report">
        <button type="button" class="dropdown-link">


            Report Tweet
        </button>
      </li>
      <li class="dropdown-divider"></li>
      <li class="js-actionMomentMakerAddTweetToOtherMoment MomentMakerAddTweetToOtherMoment">
        <button type="button" class="dropdown-link">Add to other Moment</button>
      </li>
      <li class="js-actionMomentMakerCreateMoment">
        <button type="button" class="dropdown-link">Add to new Moment</button>
      </li>
  </ul>
</div>
</div>

  </div>

      </div>



        <div class="ReplyingToContextBelowAuthor" data-aria-label-part>
    Replying to <a class="pretty-link js-user-profile-link" href="/3ala2_nabil" data-user-id="1950331711" rel="noopener" dir="ltr"><span class="username u-dir" dir="ltr" >@<b>3ala2_nabil</b></span></a>



</div>



        <div class="js-tweet-text-container">
  <p class="TweetTextSize TweetTextSize--normal js-tweet-text tweet-text tweet-text-rtl" lang="ar" data-aria-label-part="0">الكتاب هيتعمل مسلسل ونتفلكس اشترت حقوق بثه.</p>
</div>


        <button type="button"
      class="btn-link js-translate-tweet translate-button u-textUserColorHover"
      data-nav="translate_tweet"
    >
    <span class="Icon Icon--translator"></span>Translate from Arabic
  </button>
  <div class="tweet-translation-container">
  <div class="spinner tweet-translation-loading" title="Loading..."></div>
  <div class="tweet-translation needs-translation" data-dest-lang="en">
    <div class="translation-attribution">
      <span>Translated from <span class="tweet-language">Arabic</span> by <a class="attribution-logo" href="http://aka.ms/Twitter2BingTranslator"><span class="invisible">Bing</span></a></span>
        <button type="button" class="btn-link js-translation-feedback-button" data-nav="">Wrong translation?</button>
    </div>
    <p class="tweet-translation-text"></p>
  </div>
</div>










      <div class="stream-item-footer">

      <div class="ProfileTweet-actionCountList u-hiddenVisually">


    <span class="ProfileTweet-action--reply u-hiddenVisually">
      <span class="ProfileTweet-actionCount"  data-tweet-stat-count="1">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-reply-count-aria-901219821131628544" data-aria-label-part>1 reply</span>
      </span>
    </span>
    <span class="ProfileTweet-action--retweet u-hiddenVisually">
      <span class="ProfileTweet-actionCount"  data-tweet-stat-count="3">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-retweet-count-aria-901219821131628544" data-aria-label-part>3 retweets</span>
      </span>
    </span>
    <span class="ProfileTweet-action--favorite u-hiddenVisually">
      <span class="ProfileTweet-actionCount"  data-tweet-stat-count="1">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-favorite-count-aria-901219821131628544" data-aria-label-part>1 like</span>
      </span>
    </span>
  </div>

  <div class="ProfileTweet-actionList js-actions" role="group" aria-label="Tweet actions">
    <div class="ProfileTweet-action ProfileTweet-action--reply">
  <button class="ProfileTweet-actionButton js-actionButton js-actionReply"
    data-modal="ProfileTweet-reply" type="button"
    aria-describedby="profile-tweet-action-reply-count-aria-901219821131628544">
    <div class="IconContainer js-tooltip" title="Reply">
      <span class="Icon Icon--medium Icon--reply"></span>
      <span class="u-hiddenVisually">Reply</span>
    </div>
      <span class="ProfileTweet-actionCount ">
        <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">1</span>
      </span>
  </button>
</div>

    <div class="ProfileTweet-action ProfileTweet-action--retweet js-toggleState js-toggleRt">
  <button class="ProfileTweet-actionButton  js-actionButton js-actionRetweet"

    data-modal="ProfileTweet-retweet"
    type="button"
    aria-describedby="profile-tweet-action-retweet-count-aria-901219821131628544">
    <div class="IconContainer js-tooltip" title="Retweet">
      <span class="Icon Icon--medium Icon--retweet"></span>
      <span class="u-hiddenVisually">Retweet</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">3</span>
  </span>

  </button><button class="ProfileTweet-actionButtonUndo js-actionButton js-actionRetweet" data-modal="ProfileTweet-retweet" type="button">
    <div class="IconContainer js-tooltip" title="Undo retweet">
      <span class="Icon Icon--medium Icon--retweet"></span>
      <span class="u-hiddenVisually">Retweeted</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">3</span>
  </span>

  </button>
</div>


    <div class="ProfileTweet-action ProfileTweet-action--favorite js-toggleState">
  <button class="ProfileTweet-actionButton js-actionButton js-actionFavorite" type="button"
    aria-describedby="profile-tweet-action-favorite-count-aria-901219821131628544">
    <div class="IconContainer js-tooltip" title="Like">
      <span role="presentation" class="Icon Icon--heart Icon--medium"></span>
      <div class="HeartAnimation"></div>
      <span class="u-hiddenVisually">Like</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">1</span>
  </span>

  </button><button class="ProfileTweet-actionButtonUndo ProfileTweet-action--unfavorite u-linkClean js-actionButton js-actionFavorite" type="button">
    <div class="IconContainer js-tooltip" title="Undo like">
      <span role="presentation" class="Icon Icon--heart Icon--medium"></span>
      <div class="HeartAnimation"></div>
      <span class="u-hiddenVisually">Liked</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">1</span>
  </span>

  </button>
</div>


      <div class="ProfileTweet-action ProfileTweet-action--dm">
    <button class="ProfileTweet-actionButton u-textUserColorHover js-actionButton js-actionShareViaDM"
      type="button"
      data-nav="share_tweet_dm"
    >
      <div class="IconContainer js-tooltip" title="Direct message">
        <span class="Icon Icon--medium Icon--dm"></span>
        <span class="u-hiddenVisually">Direct message</span>
      </div>
    </button>
  </div>




  </div>

</div>







    </div>
  </div>




<div class="dismiss-module">
  <div class="dismissed-module">
      <div class="feedback-action" data-feedback-type="DontLike">
        <div class="action-confirmation">Thanks. Twitter will use this to make your timeline better. <span class="undo-action u-textUserColor">Undo</span></div>
      </div>
  </div>
</div>

</li>

      <li class="js-stream-item stream-item stream-item
" data-item-id="899370913514364929"
id="stream-item-tweet-899370913514364929"
data-item-type="tweet"
 data-suggestion-json="{&quot;suggestion_details&quot;:{},&quot;tweet_ids&quot;:&quot;899370913514364929&quot;,&quot;scribe_component&quot;:&quot;tweet&quot;}">




  <div class="tweet js-stream-tweet js-actionable-tweet js-profile-popup-actionable dismissible-content
       original-tweet js-original-tweet

       retweeted
"

data-tweet-id="899370913514364929"
data-item-id="899370913514364929"
data-permalink-path="/FidoHieth/status/899370913514364929"
data-conversation-id="899370913514364929"



data-tweet-nonce="899370913514364929-9a3cfa92-21fc-4eb2-b823-15a93712a654"
data-tweet-stat-initialized="true"



 data-my-retweet-id="901207238068645889"
    data-retweet-id="901207238068645889"
     data-retweeter="mohammed_attya"


  data-screen-name="FidoHieth" data-name="Karim Ariqat" data-user-id="162470314"
  data-you-follow="false"
  data-follows-you="false"
  data-you-block="false"


data-reply-to-users-json="[{&quot;id_str&quot;:&quot;162470314&quot;,&quot;screen_name&quot;:&quot;FidoHieth&quot;,&quot;name&quot;:&quot;Karim Ariqat&quot;,&quot;emojified_name&quot;:{&quot;text&quot;:&quot;Karim Ariqat&quot;,&quot;emojified_text_as_html&quot;:&quot;Karim Ariqat&quot;}}]"







data-disclosure-type=""













    >

    <div class="context">

          <div class="tweet-context with-icn

    ">

      <span class="Icon Icon--small Icon--retweeted"></span>



            <span class="js-retweet-text">
                <a class="pretty-link js-user-profile-link" href="/mohammed_attya" data-user-id="250377148" rel="noopener"><b>You</b></a> Retweeted
            </span>





    </div>

    </div>

    <div class="content">





      <div class="stream-item-header">
          <a  class="account-group js-account-group js-action-profile js-user-profile-link js-nav" href="/FidoHieth" data-user-id="162470314">
    <img class="avatar js-action-profile-avatar" src="https://pbs.twimg.com/profile_images/856092401177440256/4Lp6Kg2Y_bigger.jpg" alt="">
    <span class="FullNameGroup">
      <strong class="fullname show-popup-with-id " >Karim Ariqat</strong><span>&rlm;</span><span class="UserBadges"></span><span class="UserNameBreak">&nbsp;</span></span><span class="username u-dir" dir="ltr" >@<b>FidoHieth</b></span></a>


        <small class="time">
  <a href="/FidoHieth/status/899370913514364929" class="tweet-timestamp js-permalink js-nav js-tooltip" title="10:41 PM - 20 Aug 2017"  data-conversation-id="899370913514364929"><span class="_timestamp js-short-timestamp " data-aria-label-part="last" data-time="1503261709" data-time-ms="1503261709000" data-long-form="true">Aug 20</span></a>
</small>

          <div class="ProfileTweet-action ProfileTweet-action--more js-more-ProfileTweet-actions">
    <div class="dropdown">
  <button class="ProfileTweet-actionButton u-textUserColorHover dropdown-toggle js-dropdown-toggle" type="button">
      <div class="IconContainer js-tooltip" title="More">
        <span class="Icon Icon--caretDownLight Icon--small"></span>
        <span class="u-hiddenVisually">More</span>
      </div>
  </button>
  <div class="dropdown-menu is-autoCentered">
  <div class="dropdown-caret">
    <div class="caret-outer"></div>
    <div class="caret-inner"></div>
  </div>
  <ul>

      <li class="copy-link-to-tweet js-actionCopyLinkToTweet">
        <button type="button" class="dropdown-link">Copy link to Tweet</button>
      </li>
      <li class="embed-link js-actionEmbedTweet" data-nav="embed_tweet">
        <button type="button" class="dropdown-link">Embed Tweet</button>
      </li>
          <li class="mute-user-item"><button type="button" class="dropdown-link">Mute <span class="username u-dir" dir="ltr" >@<b>FidoHieth</b></span></button></li>
    <li class="unmute-user-item"><button type="button" class="dropdown-link">Unmute <span class="username u-dir" dir="ltr" >@<b>FidoHieth</b></span></button></li>

        <li class="block-link js-actionBlock" data-nav="block">
          <button type="button" class="dropdown-link">Block <span class="username u-dir" dir="ltr" >@<b>FidoHieth</b></span></button>
        </li>
        <li class="unblock-link js-actionUnblock" data-nav="unblock">
          <button type="button" class="dropdown-link">Unblock <span class="username u-dir" dir="ltr" >@<b>FidoHieth</b></span></button>
        </li>
      <li class="report-link js-actionReport" data-nav="report">
        <button type="button" class="dropdown-link">


            Report Tweet
        </button>
      </li>
      <li class="dropdown-divider"></li>
      <li class="js-actionMomentMakerAddTweetToOtherMoment MomentMakerAddTweetToOtherMoment">
        <button type="button" class="dropdown-link">Add to other Moment</button>
      </li>
      <li class="js-actionMomentMakerCreateMoment">
        <button type="button" class="dropdown-link">Add to new Moment</button>
      </li>
  </ul>
</div>
</div>

  </div>

      </div>





          <p class="u-hiddenVisually" aria-hidden="true" data-aria-label-part="1">Karim Ariqat Retweeted غريبة</p>


<div class="js-tweet-text-container">
  <p class="TweetTextSize TweetTextSize--normal js-tweet-text tweet-text" lang="en" data-aria-label-part="4">Personal space is important ppl.<a href="https://t.co/HrIaIhxO4l" rel="nofollow noopener" dir="ltr" data-expanded-url="https://twitter.com/Ghrebaa/status/899342781189173249" class="twitter-timeline-link u-hidden" target="_blank" title="https://twitter.com/Ghrebaa/status/899342781189173249" ><span class="tco-ellipsis"></span><span class="invisible">https://</span><span class="js-display-url">twitter.com/Ghrebaa/status</span><span class="invisible">/899342781189173249</span><span class="tco-ellipsis"><span class="invisible">&nbsp;</span>…</span></a></p>
</div>


<p class="u-hiddenVisually" aria-hidden="true" data-aria-label-part="3">Karim Ariqat added,</p>

      <div class="QuoteTweet


    u-block js-tweet-details-fixer">
  <div class="QuoteTweet-container">
    <a class="QuoteTweet-link js-nav" href="/Ghrebaa/status/899342781189173249" data-conversation-id="899342781189173249" aria-hidden="true"
       >
    </a>
    <div class="QuoteTweet-innerContainer u-cf js-permalink js-media-container"
      data-item-id="899342781189173249"
      data-item-type="tweet"
      data-screen-name="Ghrebaa"
      data-user-id="897643243"
      href="/Ghrebaa/status/899342781189173249"
      data-conversation-id="899342781189173249"
      tabindex="0">
      <div class="tweet-content">
            <div class="QuoteMedia">
      <div class="QuoteMedia-container js-quote-media-container">
          <div class="QuoteMedia-quadPhoto">
    <div class="QuoteMedia-quarterPhoto">
      <div class="QuoteMedia-photoContainer js-quote-photo"
  data-image-url="https://pbs.twimg.com/media/DHsbfVAXoAAxE_6.jpg"

  data-element-context="platform_photo_card"
    style="background-color:rgba(62,64,50,1.0);"
    data-dominant-color="[62,64,50]"
>
  <img data-aria-label-part src="https://pbs.twimg.com/media/DHsbfVAXoAAxE_6.jpg" alt=""
      style="height: 100%; left: -12px;"
>
</div>

    </div>
    <div class="QuoteMedia-quarterPhoto">
      <div class="QuoteMedia-photoContainer js-quote-photo"
  data-image-url="https://pbs.twimg.com/media/DHsbfU6XUAENB7D.jpg"

  data-element-context="platform_photo_card"
    style="background-color:rgba(64,50,35,1.0);"
    data-dominant-color="[64,50,35]"
>
  <img data-aria-label-part src="https://pbs.twimg.com/media/DHsbfU6XUAENB7D.jpg" alt=""
      style="height: 100%; left: -12px;"
>
</div>

    </div>
    <div class="QuoteMedia-quarterPhoto">
      <div class="QuoteMedia-photoContainer js-quote-photo"
  data-image-url="https://pbs.twimg.com/media/DHsbfVZXoAIHXTY.jpg"

  data-element-context="platform_photo_card"
    style="background-color:rgba(20,41,16,1.0);"
    data-dominant-color="[20,41,16]"
>
  <img data-aria-label-part src="https://pbs.twimg.com/media/DHsbfVZXoAIHXTY.jpg" alt=""
      style="height: 100%; left: -12px;"
>
</div>

    </div>
    <div class="QuoteMedia-quarterPhoto">
      <div class="QuoteMedia-photoContainer js-quote-photo"
  data-image-url="https://pbs.twimg.com/media/DHsbfYmXkAUTb2J.jpg"

  data-element-context="platform_photo_card"
    style="background-color:rgba(43,47,34,1.0);"
    data-dominant-color="[43,47,34]"
>
  <img data-aria-label-part src="https://pbs.twimg.com/media/DHsbfYmXkAUTb2J.jpg" alt=""
      style="height: 100%; left: -8px;"
>
</div>

    </div>
</div>
      </div>
  </div>

        <div class="QuoteTweet-authorAndText u-alignTop">

  <div class="QuoteTweet-originalAuthor u-cf u-textTruncate stream-item-header account-group js-user-profile-link">
    <b class="QuoteTweet-fullname u-linkComplex-target">غريبة</b><span class="UserBadges"></span><span class="UserNameBreak">&nbsp;</span><span class="username u-dir" dir="ltr" >@<b>Ghrebaa</b></span>
  </div>


          <div class="QuoteTweet-text tweet-text u-dir js-ellipsis"
            lang="en"
            data-aria-label-part="2"
            dir="rtl"
            >هذه الصور لا تصدق وهى مثال على ظاهرة &quot;خجل التاج&quot; حيث تحافظ تيجان الأشجار على مسافة بينية بين بعضها البعض <span class="twitter-timeline-link u-hidden" data-pre-embedded="true" dir="ltr" >pic.twitter.com/GXKwxuqKaP</span></div>
        </div>
      </div>
    </div>
  </div>
</div>













      <div class="stream-item-footer">

      <div class="ProfileTweet-actionCountList u-hiddenVisually">


    <span class="ProfileTweet-action--reply u-hiddenVisually">
      <span class="ProfileTweet-actionCount" aria-hidden="true" data-tweet-stat-count="0">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-reply-count-aria-899370913514364929" >0 replies</span>
      </span>
    </span>
    <span class="ProfileTweet-action--retweet u-hiddenVisually">
      <span class="ProfileTweet-actionCount"  data-tweet-stat-count="14">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-retweet-count-aria-899370913514364929" data-aria-label-part>14 retweets</span>
      </span>
    </span>
    <span class="ProfileTweet-action--favorite u-hiddenVisually">
      <span class="ProfileTweet-actionCount"  data-tweet-stat-count="12">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-favorite-count-aria-899370913514364929" data-aria-label-part>12 likes</span>
      </span>
    </span>
  </div>

  <div class="ProfileTweet-actionList js-actions" role="group" aria-label="Tweet actions">
    <div class="ProfileTweet-action ProfileTweet-action--reply">
  <button class="ProfileTweet-actionButton js-actionButton js-actionReply"
    data-modal="ProfileTweet-reply" type="button"
    aria-describedby="profile-tweet-action-reply-count-aria-899370913514364929">
    <div class="IconContainer js-tooltip" title="Reply">
      <span class="Icon Icon--medium Icon--reply"></span>
      <span class="u-hiddenVisually">Reply</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero ">
        <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
      </span>
  </button>
</div>

    <div class="ProfileTweet-action ProfileTweet-action--retweet js-toggleState js-toggleRt">
  <button class="ProfileTweet-actionButton  js-actionButton js-actionRetweet"

    data-modal="ProfileTweet-retweet"
    type="button"
    aria-describedby="profile-tweet-action-retweet-count-aria-899370913514364929">
    <div class="IconContainer js-tooltip" title="Retweet">
      <span class="Icon Icon--medium Icon--retweet"></span>
      <span class="u-hiddenVisually">Retweet</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">14</span>
  </span>

  </button><button class="ProfileTweet-actionButtonUndo js-actionButton js-actionRetweet" data-modal="ProfileTweet-retweet" type="button">
    <div class="IconContainer js-tooltip" title="Undo retweet">
      <span class="Icon Icon--medium Icon--retweet"></span>
      <span class="u-hiddenVisually">Retweeted</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">14</span>
  </span>

  </button>
</div>


    <div class="ProfileTweet-action ProfileTweet-action--favorite js-toggleState">
  <button class="ProfileTweet-actionButton js-actionButton js-actionFavorite" type="button"
    aria-describedby="profile-tweet-action-favorite-count-aria-899370913514364929">
    <div class="IconContainer js-tooltip" title="Like">
      <span role="presentation" class="Icon Icon--heart Icon--medium"></span>
      <div class="HeartAnimation"></div>
      <span class="u-hiddenVisually">Like</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">12</span>
  </span>

  </button><button class="ProfileTweet-actionButtonUndo ProfileTweet-action--unfavorite u-linkClean js-actionButton js-actionFavorite" type="button">
    <div class="IconContainer js-tooltip" title="Undo like">
      <span role="presentation" class="Icon Icon--heart Icon--medium"></span>
      <div class="HeartAnimation"></div>
      <span class="u-hiddenVisually">Liked</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">12</span>
  </span>

  </button>
</div>


      <div class="ProfileTweet-action ProfileTweet-action--dm">
    <button class="ProfileTweet-actionButton u-textUserColorHover js-actionButton js-actionShareViaDM"
      type="button"
      data-nav="share_tweet_dm"
    >
      <div class="IconContainer js-tooltip" title="Direct message">
        <span class="Icon Icon--medium Icon--dm"></span>
        <span class="u-hiddenVisually">Direct message</span>
      </div>
    </button>
  </div>




  </div>

</div>







    </div>
  </div>




<div class="dismiss-module">
  <div class="dismissed-module">
      <div class="feedback-action" data-feedback-type="DontLike">
        <div class="action-confirmation">Thanks. Twitter will use this to make your timeline better. <span class="undo-action u-textUserColor">Undo</span></div>
      </div>
  </div>
</div>

</li>

      <li class="js-stream-item stream-item stream-item
" data-item-id="901037758344572929"
id="stream-item-tweet-901037758344572929"
data-item-type="tweet"
 data-suggestion-json="{&quot;suggestion_details&quot;:{},&quot;tweet_ids&quot;:&quot;901037758344572929&quot;,&quot;scribe_component&quot;:&quot;tweet&quot;}">




  <div class="tweet js-stream-tweet js-actionable-tweet js-profile-popup-actionable dismissible-content
       original-tweet js-original-tweet

       retweeted has-cards  has-content
"

data-tweet-id="901037758344572929"
data-item-id="901037758344572929"
data-permalink-path="/Bassem_Mourid/status/901037758344572929"
data-conversation-id="901037758344572929"



data-tweet-nonce="901037758344572929-1918b1a6-6476-482d-9ee2-af2603f59527"
data-tweet-stat-initialized="true"



 data-my-retweet-id="901101428336447490"
    data-retweet-id="901101428336447490"
     data-retweeter="mohammed_attya"


  data-screen-name="Bassem_Mourid" data-name="BassemMourid" data-user-id="342532604"
  data-you-follow="false"
  data-follows-you="false"
  data-you-block="false"


data-reply-to-users-json="[{&quot;id_str&quot;:&quot;342532604&quot;,&quot;screen_name&quot;:&quot;Bassem_Mourid&quot;,&quot;name&quot;:&quot;BassemMourid&quot;,&quot;emojified_name&quot;:{&quot;text&quot;:&quot;BassemMourid&quot;,&quot;emojified_text_as_html&quot;:&quot;BassemMourid&quot;}}]"







data-disclosure-type=""




 data-has-cards="true"








    >

    <div class="context">

          <div class="tweet-context with-icn

    ">

      <span class="Icon Icon--small Icon--retweeted"></span>



            <span class="js-retweet-text">
                <a class="pretty-link js-user-profile-link" href="/mohammed_attya" data-user-id="250377148" rel="noopener"><b>You</b></a> Retweeted
            </span>





    </div>

    </div>

    <div class="content">





      <div class="stream-item-header">
          <a  class="account-group js-account-group js-action-profile js-user-profile-link js-nav" href="/Bassem_Mourid" data-user-id="342532604">
    <img class="avatar js-action-profile-avatar" src="https://pbs.twimg.com/profile_images/861587238303526912/lvcd34Xs_bigger.jpg" alt="">
    <span class="FullNameGroup">
      <strong class="fullname show-popup-with-id " data-aria-label-part>BassemMourid</strong><span>&rlm;</span><span class="UserBadges"></span><span class="UserNameBreak">&nbsp;</span></span><span class="username u-dir" dir="ltr" data-aria-label-part>@<b>Bassem_Mourid</b></span></a>


        <small class="time">
  <a href="/Bassem_Mourid/status/901037758344572929" class="tweet-timestamp js-permalink js-nav js-tooltip" title="1:05 PM - 25 Aug 2017"  data-conversation-id="901037758344572929"><span class="_timestamp js-short-timestamp " data-aria-label-part="last" data-time="1503659116" data-time-ms="1503659116000" data-long-form="true">Aug 25</span></a>
</small>

          <div class="ProfileTweet-action ProfileTweet-action--more js-more-ProfileTweet-actions">
    <div class="dropdown">
  <button class="ProfileTweet-actionButton u-textUserColorHover dropdown-toggle js-dropdown-toggle" type="button">
      <div class="IconContainer js-tooltip" title="More">
        <span class="Icon Icon--caretDownLight Icon--small"></span>
        <span class="u-hiddenVisually">More</span>
      </div>
  </button>
  <div class="dropdown-menu is-autoCentered">
  <div class="dropdown-caret">
    <div class="caret-outer"></div>
    <div class="caret-inner"></div>
  </div>
  <ul>

      <li class="copy-link-to-tweet js-actionCopyLinkToTweet">
        <button type="button" class="dropdown-link">Copy link to Tweet</button>
      </li>
      <li class="embed-link js-actionEmbedTweet" data-nav="embed_tweet">
        <button type="button" class="dropdown-link">Embed Tweet</button>
      </li>
          <li class="mute-user-item"><button type="button" class="dropdown-link">Mute <span class="username u-dir" dir="ltr" >@<b>Bassem_Mourid</b></span></button></li>
    <li class="unmute-user-item"><button type="button" class="dropdown-link">Unmute <span class="username u-dir" dir="ltr" >@<b>Bassem_Mourid</b></span></button></li>

        <li class="block-link js-actionBlock" data-nav="block">
          <button type="button" class="dropdown-link">Block <span class="username u-dir" dir="ltr" >@<b>Bassem_Mourid</b></span></button>
        </li>
        <li class="unblock-link js-actionUnblock" data-nav="unblock">
          <button type="button" class="dropdown-link">Unblock <span class="username u-dir" dir="ltr" >@<b>Bassem_Mourid</b></span></button>
        </li>
      <li class="report-link js-actionReport" data-nav="report">
        <button type="button" class="dropdown-link">


            Report Tweet
        </button>
      </li>
      <li class="dropdown-divider"></li>
      <li class="js-actionMomentMakerAddTweetToOtherMoment MomentMakerAddTweetToOtherMoment">
        <button type="button" class="dropdown-link">Add to other Moment</button>
      </li>
      <li class="js-actionMomentMakerCreateMoment">
        <button type="button" class="dropdown-link">Add to new Moment</button>
      </li>
  </ul>
</div>
</div>

  </div>

      </div>





        <div class="js-tweet-text-container">
  <p class="TweetTextSize TweetTextSize--normal js-tweet-text tweet-text tweet-text-rtl" lang="ar" data-aria-label-part="0"><a href="https://t.co/E4EbC3Vt9c" rel="nofollow noopener" dir="ltr" data-expanded-url="https://twitter.com/nnnaaaa500" class="twitter-timeline-link" target="_blank" title="https://twitter.com/nnnaaaa500" ><span class="tco-ellipsis"></span><span class="invisible">https://</span><span class="js-display-url">twitter.com/nnnaaaa500</span><span class="invisible"></span><span class="tco-ellipsis"><span class="invisible">&nbsp;</span></span></a>
<a href="https://t.co/QfBgGaQFY8" rel="nofollow noopener" dir="ltr" data-expanded-url="https://twitter.com/gggoooo369" class="twitter-timeline-link" target="_blank" title="https://twitter.com/gggoooo369" ><span class="tco-ellipsis"></span><span class="invisible">https://</span><span class="js-display-url">twitter.com/gggoooo369</span><span class="invisible"></span><span class="tco-ellipsis"><span class="invisible">&nbsp;</span></span></a>
ريبورت و ريتويت لو سمحتوا و نقفل الاتنيين دول كمان. الطريقة فى الصور<a href="https://t.co/ZEEdKyyChC" class="twitter-timeline-link u-hidden" data-pre-embedded="true" dir="ltr" >pic.twitter.com/ZEEdKyyChC</a></p>
</div>


        <button type="button"
      class="btn-link js-translate-tweet translate-button u-textUserColorHover"
      data-nav="translate_tweet"
    >
    <span class="Icon Icon--translator"></span>Translate from Arabic
  </button>
  <div class="tweet-translation-container">
  <div class="spinner tweet-translation-loading" title="Loading..."></div>
  <div class="tweet-translation needs-translation" data-dest-lang="en">
    <div class="translation-attribution">
      <span>Translated from <span class="tweet-language">Arabic</span> by <a class="attribution-logo" href="http://aka.ms/Twitter2BingTranslator"><span class="invisible">Bing</span></a></span>
        <button type="button" class="btn-link js-translation-feedback-button" data-nav="">Wrong translation?</button>
    </div>
    <p class="tweet-translation-text"></p>
  </div>
</div>



            <div class="AdaptiveMediaOuterContainer">
    <div class="AdaptiveMedia






        "
      >
      <div class="AdaptiveMedia-container">
          <div class="AdaptiveMedia-triplePhoto">
    <div class="AdaptiveMedia-twoThirdsWidthPhoto">
      <div class="AdaptiveMedia-photoContainer js-adaptive-photo "
  data-image-url="https://pbs.twimg.com/media/DIEg-1tXYAA-RV2.jpg"


  data-element-context="platform_photo_card"
    style="background-color:rgba(20,47,64,1.0);"
    data-dominant-color="[20,47,64]"
>
  <img data-aria-label-part src="https://pbs.twimg.com/media/DIEg-1tXYAA-RV2.jpg" alt=""
      style="height: 100%; left: -21px;"
>
</div>


    </div>
  <div class="AdaptiveMedia-halfHeightPhotoContainer">
      <div class="AdaptiveMedia-halfHeightPhoto">
        <div class="AdaptiveMedia-photoContainer js-adaptive-photo "
  data-image-url="https://pbs.twimg.com/media/DIEg-2aXkAEmkxx.jpg"


  data-element-context="platform_photo_card"
    style="background-color:rgba(20,47,64,1.0);"
    data-dominant-color="[20,47,64]"
>
  <img data-aria-label-part src="https://pbs.twimg.com/media/DIEg-2aXkAEmkxx.jpg" alt=""
      style="height: 100%; left: -11px;"
>
</div>


      </div>
      <div class="AdaptiveMedia-halfHeightPhoto">
        <div class="AdaptiveMedia-photoContainer js-adaptive-photo "
  data-image-url="https://pbs.twimg.com/media/DIEg-41XsAA2aM-.jpg"


  data-element-context="platform_photo_card"
    style="background-color:rgba(34,37,46,1.0);"
    data-dominant-color="[34,37,46]"
>
  <img data-aria-label-part src="https://pbs.twimg.com/media/DIEg-41XsAA2aM-.jpg" alt=""
      style="height: 100%; left: -10px;"
>
</div>


      </div>
  </div>
</div>

      </div>
    </div>
  </div>








      <div class="stream-item-footer">

      <div class="ProfileTweet-actionCountList u-hiddenVisually">


    <span class="ProfileTweet-action--reply u-hiddenVisually">
      <span class="ProfileTweet-actionCount"  data-tweet-stat-count="15">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-reply-count-aria-901037758344572929" data-aria-label-part>15 replies</span>
      </span>
    </span>
    <span class="ProfileTweet-action--retweet u-hiddenVisually">
      <span class="ProfileTweet-actionCount"  data-tweet-stat-count="33">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-retweet-count-aria-901037758344572929" data-aria-label-part>33 retweets</span>
      </span>
    </span>
    <span class="ProfileTweet-action--favorite u-hiddenVisually">
      <span class="ProfileTweet-actionCount"  data-tweet-stat-count="4">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-favorite-count-aria-901037758344572929" data-aria-label-part>4 likes</span>
      </span>
    </span>
  </div>

  <div class="ProfileTweet-actionList js-actions" role="group" aria-label="Tweet actions">
    <div class="ProfileTweet-action ProfileTweet-action--reply">
  <button class="ProfileTweet-actionButton js-actionButton js-actionReply"
    data-modal="ProfileTweet-reply" type="button"
    aria-describedby="profile-tweet-action-reply-count-aria-901037758344572929">
    <div class="IconContainer js-tooltip" title="Reply">
      <span class="Icon Icon--medium Icon--reply"></span>
      <span class="u-hiddenVisually">Reply</span>
    </div>
      <span class="ProfileTweet-actionCount ">
        <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">15</span>
      </span>
  </button>
</div>

    <div class="ProfileTweet-action ProfileTweet-action--retweet js-toggleState js-toggleRt">
  <button class="ProfileTweet-actionButton  js-actionButton js-actionRetweet"

    data-modal="ProfileTweet-retweet"
    type="button"
    aria-describedby="profile-tweet-action-retweet-count-aria-901037758344572929">
    <div class="IconContainer js-tooltip" title="Retweet">
      <span class="Icon Icon--medium Icon--retweet"></span>
      <span class="u-hiddenVisually">Retweet</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">33</span>
  </span>

  </button><button class="ProfileTweet-actionButtonUndo js-actionButton js-actionRetweet" data-modal="ProfileTweet-retweet" type="button">
    <div class="IconContainer js-tooltip" title="Undo retweet">
      <span class="Icon Icon--medium Icon--retweet"></span>
      <span class="u-hiddenVisually">Retweeted</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">33</span>
  </span>

  </button>
</div>


    <div class="ProfileTweet-action ProfileTweet-action--favorite js-toggleState">
  <button class="ProfileTweet-actionButton js-actionButton js-actionFavorite" type="button"
    aria-describedby="profile-tweet-action-favorite-count-aria-901037758344572929">
    <div class="IconContainer js-tooltip" title="Like">
      <span role="presentation" class="Icon Icon--heart Icon--medium"></span>
      <div class="HeartAnimation"></div>
      <span class="u-hiddenVisually">Like</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">4</span>
  </span>

  </button><button class="ProfileTweet-actionButtonUndo ProfileTweet-action--unfavorite u-linkClean js-actionButton js-actionFavorite" type="button">
    <div class="IconContainer js-tooltip" title="Undo like">
      <span role="presentation" class="Icon Icon--heart Icon--medium"></span>
      <div class="HeartAnimation"></div>
      <span class="u-hiddenVisually">Liked</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">4</span>
  </span>

  </button>
</div>


      <div class="ProfileTweet-action ProfileTweet-action--dm">
    <button class="ProfileTweet-actionButton u-textUserColorHover js-actionButton js-actionShareViaDM"
      type="button"
      data-nav="share_tweet_dm"
    >
      <div class="IconContainer js-tooltip" title="Direct message">
        <span class="Icon Icon--medium Icon--dm"></span>
        <span class="u-hiddenVisually">Direct message</span>
      </div>
    </button>
  </div>




  </div>

</div>







    </div>
  </div>




<div class="dismiss-module">
  <div class="dismissed-module">
      <div class="feedback-action" data-feedback-type="DontLike">
        <div class="action-confirmation">Thanks. Twitter will use this to make your timeline better. <span class="undo-action u-textUserColor">Undo</span></div>
      </div>
  </div>
</div>

</li>

      <li class="js-stream-item stream-item stream-item
" data-item-id="901090554267787264"
id="stream-item-tweet-901090554267787264"
data-item-type="tweet"
 data-suggestion-json="{&quot;suggestion_details&quot;:{},&quot;tweet_ids&quot;:&quot;901090554267787264&quot;,&quot;scribe_component&quot;:&quot;tweet&quot;}">




  <div class="tweet js-stream-tweet js-actionable-tweet js-profile-popup-actionable dismissible-content
       original-tweet js-original-tweet

       my-tweet
"

data-tweet-id="901090554267787264"
data-item-id="901090554267787264"
data-permalink-path="/mohammed_attya/status/901090554267787264"
data-conversation-id="901090554267787264"



data-tweet-nonce="901090554267787264-e2cf744b-e12c-416e-aae2-c103e5798bc3"
data-tweet-stat-initialized="true"






  data-screen-name="mohammed_attya" data-name="Mohammed Attya" data-user-id="250377148"
  data-you-follow="false"
  data-follows-you="false"
  data-you-block="false"


data-reply-to-users-json="[{&quot;id_str&quot;:&quot;250377148&quot;,&quot;screen_name&quot;:&quot;mohammed_attya&quot;,&quot;name&quot;:&quot;Mohammed Attya&quot;,&quot;emojified_name&quot;:{&quot;text&quot;:&quot;Mohammed Attya&quot;,&quot;emojified_text_as_html&quot;:&quot;Mohammed Attya&quot;}}]"







data-disclosure-type=""












 data-tfb-view="/i/tfb/v1/quick_promote/901090554267787264"
    >

    <div class="context">


    </div>

    <div class="content">





      <div class="stream-item-header">
          <a  class="account-group js-account-group js-action-profile js-user-profile-link js-nav" href="/mohammed_attya" data-user-id="250377148">
    <img class="avatar js-action-profile-avatar" src="https://pbs.twimg.com/profile_images/878491891733549056/3urQeq4j_bigger.jpg" alt="">
    <span class="FullNameGroup">
      <strong class="fullname show-popup-with-id " >Mohammed Attya</strong><span>&rlm;</span><span class="UserBadges"></span><span class="UserNameBreak">&nbsp;</span></span><span class="username u-dir" dir="ltr" >@<b>mohammed_attya</b></span></a>


        <small class="time">
  <a href="/mohammed_attya/status/901090554267787264" class="tweet-timestamp js-permalink js-nav js-tooltip" title="4:35 PM - 25 Aug 2017"  data-conversation-id="901090554267787264"><span class="_timestamp js-short-timestamp " data-aria-label-part="last" data-time="1503671704" data-time-ms="1503671704000" data-long-form="true">Aug 25</span></a>
</small>

          <div class="ProfileTweet-action ProfileTweet-action--more js-more-ProfileTweet-actions">
    <div class="dropdown">
  <button class="ProfileTweet-actionButton u-textUserColorHover dropdown-toggle js-dropdown-toggle" type="button">
      <div class="IconContainer js-tooltip" title="More">
        <span class="Icon Icon--caretDownLight Icon--small"></span>
        <span class="u-hiddenVisually">More</span>
      </div>
  </button>
  <div class="dropdown-menu is-autoCentered">
  <div class="dropdown-caret">
    <div class="caret-outer"></div>
    <div class="caret-inner"></div>
  </div>
  <ul>
      <li class="share-via-dm js-actionShareViaDM" data-nav="share_tweet_dm">
        <button type="button" class="dropdown-link">Share via Direct Message</button>
      </li>

      <li class="copy-link-to-tweet js-actionCopyLinkToTweet">
        <button type="button" class="dropdown-link">Copy link to Tweet</button>
      </li>
      <li class="embed-link js-actionEmbedTweet" data-nav="embed_tweet">
        <button type="button" class="dropdown-link">Embed Tweet</button>
      </li>
      <li class="user-pin-tweet js-actionPinTweet" data-nav="user_pin_tweet">
        <button type="button" class="dropdown-link">Pin to your profile page</button>
      </li>
      <li class="user-unpin-tweet js-actionPinTweet" data-nav="user_unpin_tweet">
        <button type="button" class="dropdown-link">Unpin from profile page</button>
      </li>
      <li class="js-actionDelete">
        <button type="button" class="dropdown-link">Delete Tweet</button>
      </li>
      <li class="dropdown-divider"></li>
      <li class="js-actionMomentMakerAddTweetToOtherMoment MomentMakerAddTweetToOtherMoment">
        <button type="button" class="dropdown-link">Add to other Moment</button>
      </li>
      <li class="js-actionMomentMakerCreateMoment">
        <button type="button" class="dropdown-link">Add to new Moment</button>
      </li>
  </ul>
</div>
</div>

  </div>

      </div>





          <p class="u-hiddenVisually" aria-hidden="true" data-aria-label-part="1">Mohammed Attya Retweeted Ya5abar | ياخبر</p>


<div class="js-tweet-text-container">
  <p class="TweetTextSize TweetTextSize--normal js-tweet-text tweet-text tweet-text-rtl" lang="ar" data-aria-label-part="4">الف مبروك للابطال
عقبال شباب تحت 19 سنة يجيبوا المركز الاول<a href="https://t.co/eRWYQxCpsB" rel="nofollow noopener" dir="ltr" data-expanded-url="https://twitter.com/Ya5abar/status/901089435940532224" class="twitter-timeline-link u-hidden" target="_blank" title="https://twitter.com/Ya5abar/status/901089435940532224" ><span class="tco-ellipsis"></span><span class="invisible">https://</span><span class="js-display-url">twitter.com/Ya5abar/status</span><span class="invisible">/901089435940532224</span><span class="tco-ellipsis"><span class="invisible">&nbsp;</span>…</span></a></p>
</div>


<p class="u-hiddenVisually" aria-hidden="true" data-aria-label-part="3">Mohammed Attya added,</p>

      <div class="QuoteTweet


    u-block js-tweet-details-fixer">
  <div class="QuoteTweet-container">
    <a class="QuoteTweet-link js-nav" href="/Ya5abar/status/901089435940532224" data-conversation-id="901089435940532224" aria-hidden="true"
       >
    </a>
    <div class="QuoteTweet-innerContainer u-cf js-permalink js-media-container"
      data-item-id="901089435940532224"
      data-item-type="tweet"
      data-screen-name="Ya5abar"
      data-user-id="2956875556"
      href="/Ya5abar/status/901089435940532224"
      data-conversation-id="901089435940532224"
      tabindex="0">
      <div class="tweet-content">
            <div class="QuoteMedia">
      <div class="QuoteMedia-container js-quote-media-container">
          <div class="QuoteMedia-singlePhoto">
    <div class="QuoteMedia-photoContainer js-quote-photo"
  data-image-url="https://pbs.twimg.com/media/DIFQDzpXoAAde0V.jpg"

  data-element-context="platform_photo_card"
    style="background-color:rgba(38,27,29,1.0);"
    data-dominant-color="[38,27,29]"
>
  <img data-aria-label-part src="https://pbs.twimg.com/media/DIFQDzpXoAAde0V.jpg" alt=""
      style="height: 100%; left: -45px;"
>
</div>

</div>


      </div>
  </div>

        <div class="QuoteTweet-authorAndText u-alignTop">

  <div class="QuoteTweet-originalAuthor u-cf u-textTruncate stream-item-header account-group js-user-profile-link">
    <b class="QuoteTweet-fullname u-linkComplex-target">Ya5abar | ياخبر</b><span class="UserBadges"><span class="Icon Icon--verified"><span class="u-hiddenVisually">Verified account</span></span></span><span class="UserNameBreak">&nbsp;</span><span class="username u-dir" dir="ltr" >@<b>Ya5abar</b></span>
  </div>


          <div class="QuoteTweet-text tweet-text u-dir js-ellipsis"
            lang="ar"
            data-aria-label-part="2"
            dir="rtl"
            ><span data-query-source="hashtag_click" class="twitter-hashtag pretty-link js-nav" dir="rtl" ><s>#</s><b>ياخبر</b></span>|منتخب <span data-query-source="hashtag_click" class="twitter-hashtag pretty-link js-nav" dir="rtl" ><s>#</s><b>مصر</b></span> للكرة الطائرة يهزم اليابان 4/0 ويحصد المركز الخامس فى بطولة العالم <span class="twitter-timeline-link u-hidden" data-pre-embedded="true" dir="ltr" >pic.twitter.com/9gHC9ua7xl</span></div>
        </div>
      </div>
    </div>
  </div>
</div>



        <button type="button"
      class="btn-link js-translate-tweet translate-button u-textUserColorHover"
      data-nav="translate_tweet"
    >
    <span class="Icon Icon--translator"></span>Translate from Arabic
  </button>
  <div class="tweet-translation-container">
  <div class="spinner tweet-translation-loading" title="Loading..."></div>
  <div class="tweet-translation needs-translation" data-dest-lang="en">
    <div class="translation-attribution">
      <span>Translated from <span class="tweet-language">Arabic</span> by <a class="attribution-logo" href="http://aka.ms/Twitter2BingTranslator"><span class="invisible">Bing</span></a></span>
        <button type="button" class="btn-link js-translation-feedback-button" data-nav="">Wrong translation?</button>
    </div>
    <p class="tweet-translation-text"></p>
  </div>
</div>










      <div class="stream-item-footer">

      <div class="ProfileTweet-actionCountList u-hiddenVisually">


    <span class="ProfileTweet-action--reply u-hiddenVisually">
      <span class="ProfileTweet-actionCount" aria-hidden="true" data-tweet-stat-count="0">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-reply-count-aria-901090554267787264" >0 replies</span>
      </span>
    </span>
    <span class="ProfileTweet-action--retweet u-hiddenVisually">
      <span class="ProfileTweet-actionCount" aria-hidden="true" data-tweet-stat-count="0">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-retweet-count-aria-901090554267787264" >0 retweets</span>
      </span>
    </span>
    <span class="ProfileTweet-action--favorite u-hiddenVisually">
      <span class="ProfileTweet-actionCount" aria-hidden="true" data-tweet-stat-count="0">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-favorite-count-aria-901090554267787264" >0 likes</span>
      </span>
    </span>
  </div>

  <div class="ProfileTweet-actionList js-actions" role="group" aria-label="Tweet actions">
    <div class="ProfileTweet-action ProfileTweet-action--reply">
  <button class="ProfileTweet-actionButton js-actionButton js-actionReply"
    data-modal="ProfileTweet-reply" type="button"
    aria-describedby="profile-tweet-action-reply-count-aria-901090554267787264">
    <div class="IconContainer js-tooltip" title="Reply">
      <span class="Icon Icon--medium Icon--reply"></span>
      <span class="u-hiddenVisually">Reply</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero ">
        <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
      </span>
  </button>
</div>

    <div class="ProfileTweet-action ProfileTweet-action--retweet js-toggleState js-toggleRt">
  <button class="ProfileTweet-actionButton  js-actionButton js-actionRetweet"

    data-modal="ProfileTweet-retweet"
    type="button"
    aria-describedby="profile-tweet-action-retweet-count-aria-901090554267787264">
    <div class="IconContainer js-tooltip" title="Retweet">
      <span class="Icon Icon--medium Icon--retweet"></span>
      <span class="u-hiddenVisually">Retweet</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
  </span>

  </button><button class="ProfileTweet-actionButtonUndo js-actionButton js-actionRetweet" data-modal="ProfileTweet-retweet" type="button">
    <div class="IconContainer js-tooltip" title="Undo retweet">
      <span class="Icon Icon--medium Icon--retweet"></span>
      <span class="u-hiddenVisually">Retweeted</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
  </span>

  </button>
</div>


    <div class="ProfileTweet-action ProfileTweet-action--favorite js-toggleState">
  <button class="ProfileTweet-actionButton js-actionButton js-actionFavorite" type="button"
    aria-describedby="profile-tweet-action-favorite-count-aria-901090554267787264">
    <div class="IconContainer js-tooltip" title="Like">
      <span role="presentation" class="Icon Icon--heart Icon--medium"></span>
      <div class="HeartAnimation"></div>
      <span class="u-hiddenVisually">Like</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
  </span>

  </button><button class="ProfileTweet-actionButtonUndo ProfileTweet-action--unfavorite u-linkClean js-actionButton js-actionFavorite" type="button">
    <div class="IconContainer js-tooltip" title="Undo like">
      <span role="presentation" class="Icon Icon--heart Icon--medium"></span>
      <div class="HeartAnimation"></div>
      <span class="u-hiddenVisually">Liked</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
  </span>

  </button>
</div>





  <div class="ProfileTweet-action ProfileTweet-action--analytics">
    <button class="ProfileTweet-actionButton u-textUserColorHover js-actionButton js-actionQuickPromote" type="button">
      <div class="IconContainer js-tooltip" title="View Tweet activity">
        <span class="Icon Icon--medium Icon--analytics"></span>
        <span class="u-hiddenVisually">View Tweet activity</span>
      </div>
    </button>
  </div>

  </div>

</div>







    </div>
  </div>




<div class="dismiss-module">
  <div class="dismissed-module">
      <div class="feedback-action" data-feedback-type="DontLike">
        <div class="action-confirmation">Thanks. Twitter will use this to make your timeline better. <span class="undo-action u-textUserColor">Undo</span></div>
      </div>
  </div>
</div>

</li>

      <li class="js-stream-item stream-item stream-item
" data-item-id="901076019871981569"
id="stream-item-tweet-901076019871981569"
data-item-type="tweet"
 data-suggestion-json="{&quot;suggestion_details&quot;:{},&quot;tweet_ids&quot;:&quot;901076019871981569&quot;,&quot;scribe_component&quot;:&quot;tweet&quot;}">




  <div class="tweet js-stream-tweet js-actionable-tweet js-profile-popup-actionable dismissible-content
       original-tweet js-original-tweet

       my-tweet
"

data-tweet-id="901076019871981569"
data-item-id="901076019871981569"
data-permalink-path="/mohammed_attya/status/901076019871981569"
data-conversation-id="901076019871981569"



data-tweet-nonce="901076019871981569-4172851c-7c15-4d61-8b2b-2418761cb527"
data-tweet-stat-initialized="true"






  data-screen-name="mohammed_attya" data-name="Mohammed Attya" data-user-id="250377148"
  data-you-follow="false"
  data-follows-you="false"
  data-you-block="false"


data-reply-to-users-json="[{&quot;id_str&quot;:&quot;250377148&quot;,&quot;screen_name&quot;:&quot;mohammed_attya&quot;,&quot;name&quot;:&quot;Mohammed Attya&quot;,&quot;emojified_name&quot;:{&quot;text&quot;:&quot;Mohammed Attya&quot;,&quot;emojified_text_as_html&quot;:&quot;Mohammed Attya&quot;}}]"







data-disclosure-type=""












 data-tfb-view="/i/tfb/v1/quick_promote/901076019871981569"
    >

    <div class="context">


    </div>

    <div class="content">





      <div class="stream-item-header">
          <a  class="account-group js-account-group js-action-profile js-user-profile-link js-nav" href="/mohammed_attya" data-user-id="250377148">
    <img class="avatar js-action-profile-avatar" src="https://pbs.twimg.com/profile_images/878491891733549056/3urQeq4j_bigger.jpg" alt="">
    <span class="FullNameGroup">
      <strong class="fullname show-popup-with-id " data-aria-label-part>Mohammed Attya</strong><span>&rlm;</span><span class="UserBadges"></span><span class="UserNameBreak">&nbsp;</span></span><span class="username u-dir" dir="ltr" data-aria-label-part>@<b>mohammed_attya</b></span></a>


        <small class="time">
  <a href="/mohammed_attya/status/901076019871981569" class="tweet-timestamp js-permalink js-nav js-tooltip" title="3:37 PM - 25 Aug 2017"  data-conversation-id="901076019871981569"><span class="_timestamp js-short-timestamp " data-aria-label-part="last" data-time="1503668238" data-time-ms="1503668238000" data-long-form="true">Aug 25</span></a>
</small>

          <div class="ProfileTweet-action ProfileTweet-action--more js-more-ProfileTweet-actions">
    <div class="dropdown">
  <button class="ProfileTweet-actionButton u-textUserColorHover dropdown-toggle js-dropdown-toggle" type="button">
      <div class="IconContainer js-tooltip" title="More">
        <span class="Icon Icon--caretDownLight Icon--small"></span>
        <span class="u-hiddenVisually">More</span>
      </div>
  </button>
  <div class="dropdown-menu is-autoCentered">
  <div class="dropdown-caret">
    <div class="caret-outer"></div>
    <div class="caret-inner"></div>
  </div>
  <ul>
      <li class="share-via-dm js-actionShareViaDM" data-nav="share_tweet_dm">
        <button type="button" class="dropdown-link">Share via Direct Message</button>
      </li>

      <li class="copy-link-to-tweet js-actionCopyLinkToTweet">
        <button type="button" class="dropdown-link">Copy link to Tweet</button>
      </li>
      <li class="embed-link js-actionEmbedTweet" data-nav="embed_tweet">
        <button type="button" class="dropdown-link">Embed Tweet</button>
      </li>
      <li class="user-pin-tweet js-actionPinTweet" data-nav="user_pin_tweet">
        <button type="button" class="dropdown-link">Pin to your profile page</button>
      </li>
      <li class="user-unpin-tweet js-actionPinTweet" data-nav="user_unpin_tweet">
        <button type="button" class="dropdown-link">Unpin from profile page</button>
      </li>
      <li class="js-actionDelete">
        <button type="button" class="dropdown-link">Delete Tweet</button>
      </li>
      <li class="dropdown-divider"></li>
      <li class="js-actionMomentMakerAddTweetToOtherMoment MomentMakerAddTweetToOtherMoment">
        <button type="button" class="dropdown-link">Add to other Moment</button>
      </li>
      <li class="js-actionMomentMakerCreateMoment">
        <button type="button" class="dropdown-link">Add to new Moment</button>
      </li>
  </ul>
</div>
</div>

  </div>

      </div>





        <div class="js-tweet-text-container">
  <p class="TweetTextSize TweetTextSize--normal js-tweet-text tweet-text tweet-text-rtl" lang="ar" data-aria-label-part="0">منتخب مصر هيلعب النهارده في ربع نهائي كأس العالم للكرة الطائرة رجال تحت 19 سنة</p>
</div>


        <button type="button"
      class="btn-link js-translate-tweet translate-button u-textUserColorHover"
      data-nav="translate_tweet"
    >
    <span class="Icon Icon--translator"></span>Translate from Arabic
  </button>
  <div class="tweet-translation-container">
  <div class="spinner tweet-translation-loading" title="Loading..."></div>
  <div class="tweet-translation needs-translation" data-dest-lang="en">
    <div class="translation-attribution">
      <span>Translated from <span class="tweet-language">Arabic</span> by <a class="attribution-logo" href="http://aka.ms/Twitter2BingTranslator"><span class="invisible">Bing</span></a></span>
        <button type="button" class="btn-link js-translation-feedback-button" data-nav="">Wrong translation?</button>
    </div>
    <p class="tweet-translation-text"></p>
  </div>
</div>










      <div class="stream-item-footer">

      <div class="ProfileTweet-actionCountList u-hiddenVisually">


    <span class="ProfileTweet-action--reply u-hiddenVisually">
      <span class="ProfileTweet-actionCount" aria-hidden="true" data-tweet-stat-count="0">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-reply-count-aria-901076019871981569" >0 replies</span>
      </span>
    </span>
    <span class="ProfileTweet-action--retweet u-hiddenVisually">
      <span class="ProfileTweet-actionCount" aria-hidden="true" data-tweet-stat-count="0">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-retweet-count-aria-901076019871981569" >0 retweets</span>
      </span>
    </span>
    <span class="ProfileTweet-action--favorite u-hiddenVisually">
      <span class="ProfileTweet-actionCount" aria-hidden="true" data-tweet-stat-count="0">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-favorite-count-aria-901076019871981569" >0 likes</span>
      </span>
    </span>
  </div>

  <div class="ProfileTweet-actionList js-actions" role="group" aria-label="Tweet actions">
    <div class="ProfileTweet-action ProfileTweet-action--reply">
  <button class="ProfileTweet-actionButton js-actionButton js-actionReply"
    data-modal="ProfileTweet-reply" type="button"
    aria-describedby="profile-tweet-action-reply-count-aria-901076019871981569">
    <div class="IconContainer js-tooltip" title="Reply">
      <span class="Icon Icon--medium Icon--reply"></span>
      <span class="u-hiddenVisually">Reply</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero ">
        <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
      </span>
  </button>
</div>

    <div class="ProfileTweet-action ProfileTweet-action--retweet js-toggleState js-toggleRt">
  <button class="ProfileTweet-actionButton  js-actionButton js-actionRetweet"

    data-modal="ProfileTweet-retweet"
    type="button"
    aria-describedby="profile-tweet-action-retweet-count-aria-901076019871981569">
    <div class="IconContainer js-tooltip" title="Retweet">
      <span class="Icon Icon--medium Icon--retweet"></span>
      <span class="u-hiddenVisually">Retweet</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
  </span>

  </button><button class="ProfileTweet-actionButtonUndo js-actionButton js-actionRetweet" data-modal="ProfileTweet-retweet" type="button">
    <div class="IconContainer js-tooltip" title="Undo retweet">
      <span class="Icon Icon--medium Icon--retweet"></span>
      <span class="u-hiddenVisually">Retweeted</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
  </span>

  </button>
</div>


    <div class="ProfileTweet-action ProfileTweet-action--favorite js-toggleState">
  <button class="ProfileTweet-actionButton js-actionButton js-actionFavorite" type="button"
    aria-describedby="profile-tweet-action-favorite-count-aria-901076019871981569">
    <div class="IconContainer js-tooltip" title="Like">
      <span role="presentation" class="Icon Icon--heart Icon--medium"></span>
      <div class="HeartAnimation"></div>
      <span class="u-hiddenVisually">Like</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
  </span>

  </button><button class="ProfileTweet-actionButtonUndo ProfileTweet-action--unfavorite u-linkClean js-actionButton js-actionFavorite" type="button">
    <div class="IconContainer js-tooltip" title="Undo like">
      <span role="presentation" class="Icon Icon--heart Icon--medium"></span>
      <div class="HeartAnimation"></div>
      <span class="u-hiddenVisually">Liked</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
  </span>

  </button>
</div>





  <div class="ProfileTweet-action ProfileTweet-action--analytics">
    <button class="ProfileTweet-actionButton u-textUserColorHover js-actionButton js-actionQuickPromote" type="button">
      <div class="IconContainer js-tooltip" title="View Tweet activity">
        <span class="Icon Icon--medium Icon--analytics"></span>
        <span class="u-hiddenVisually">View Tweet activity</span>
      </div>
    </button>
  </div>

  </div>

</div>







    </div>
  </div>




<div class="dismiss-module">
  <div class="dismissed-module">
      <div class="feedback-action" data-feedback-type="DontLike">
        <div class="action-confirmation">Thanks. Twitter will use this to make your timeline better. <span class="undo-action u-textUserColor">Undo</span></div>
      </div>
  </div>
</div>

</li>

      <li class="js-stream-item stream-item stream-item
" data-item-id="901074473851523072"
id="stream-item-tweet-901074473851523072"
data-item-type="tweet"
 data-suggestion-json="{&quot;suggestion_details&quot;:{},&quot;tweet_ids&quot;:&quot;901074473851523072&quot;,&quot;scribe_component&quot;:&quot;tweet&quot;}">




  <div class="tweet js-stream-tweet js-actionable-tweet js-profile-popup-actionable dismissible-content
       original-tweet js-original-tweet

       my-tweet
"

data-tweet-id="901074473851523072"
data-item-id="901074473851523072"
data-permalink-path="/mohammed_attya/status/901074473851523072"
data-conversation-id="901074302174449664"
 data-is-reply-to="true"
 data-has-parent-tweet="true"

data-tweet-nonce="901074473851523072-b1844f7d-6c83-4113-ba0d-05bc7a16637e"
data-tweet-stat-initialized="true"






  data-screen-name="mohammed_attya" data-name="Mohammed Attya" data-user-id="250377148"
  data-you-follow="false"
  data-follows-you="false"
  data-you-block="false"


data-reply-to-users-json="[{&quot;id_str&quot;:&quot;250377148&quot;,&quot;screen_name&quot;:&quot;mohammed_attya&quot;,&quot;name&quot;:&quot;Mohammed Attya&quot;,&quot;emojified_name&quot;:{&quot;text&quot;:&quot;Mohammed Attya&quot;,&quot;emojified_text_as_html&quot;:&quot;Mohammed Attya&quot;}}]"







data-disclosure-type=""












 data-tfb-view="/i/tfb/v1/quick_promote/901074473851523072"
    >

    <div class="context">


    </div>

    <div class="content">





      <div class="stream-item-header">
          <a  class="account-group js-account-group js-action-profile js-user-profile-link js-nav" href="/mohammed_attya" data-user-id="250377148">
    <img class="avatar js-action-profile-avatar" src="https://pbs.twimg.com/profile_images/878491891733549056/3urQeq4j_bigger.jpg" alt="">
    <span class="FullNameGroup">
      <strong class="fullname show-popup-with-id " data-aria-label-part>Mohammed Attya</strong><span>&rlm;</span><span class="UserBadges"></span><span class="UserNameBreak">&nbsp;</span></span><span class="username u-dir" dir="ltr" data-aria-label-part>@<b>mohammed_attya</b></span></a>


        <small class="time">
  <a href="/mohammed_attya/status/901074473851523072" class="tweet-timestamp js-permalink js-nav js-tooltip" title="3:31 PM - 25 Aug 2017"  data-conversation-id="901074302174449664"><span class="_timestamp js-short-timestamp " data-aria-label-part="last" data-time="1503667870" data-time-ms="1503667870000" data-long-form="true">Aug 25</span></a>
</small>

          <div class="ProfileTweet-action ProfileTweet-action--more js-more-ProfileTweet-actions">
    <div class="dropdown">
  <button class="ProfileTweet-actionButton u-textUserColorHover dropdown-toggle js-dropdown-toggle" type="button">
      <div class="IconContainer js-tooltip" title="More">
        <span class="Icon Icon--caretDownLight Icon--small"></span>
        <span class="u-hiddenVisually">More</span>
      </div>
  </button>
  <div class="dropdown-menu is-autoCentered">
  <div class="dropdown-caret">
    <div class="caret-outer"></div>
    <div class="caret-inner"></div>
  </div>
  <ul>
      <li class="share-via-dm js-actionShareViaDM" data-nav="share_tweet_dm">
        <button type="button" class="dropdown-link">Share via Direct Message</button>
      </li>

      <li class="copy-link-to-tweet js-actionCopyLinkToTweet">
        <button type="button" class="dropdown-link">Copy link to Tweet</button>
      </li>
      <li class="embed-link js-actionEmbedTweet" data-nav="embed_tweet">
        <button type="button" class="dropdown-link">Embed Tweet</button>
      </li>
      <li class="user-pin-tweet js-actionPinTweet" data-nav="user_pin_tweet">
        <button type="button" class="dropdown-link">Pin to your profile page</button>
      </li>
      <li class="user-unpin-tweet js-actionPinTweet" data-nav="user_unpin_tweet">
        <button type="button" class="dropdown-link">Unpin from profile page</button>
      </li>
      <li class="js-actionDelete">
        <button type="button" class="dropdown-link">Delete Tweet</button>
      </li>
      <li class="dropdown-divider"></li>
      <li class="js-actionMomentMakerAddTweetToOtherMoment MomentMakerAddTweetToOtherMoment">
        <button type="button" class="dropdown-link">Add to other Moment</button>
      </li>
      <li class="js-actionMomentMakerCreateMoment">
        <button type="button" class="dropdown-link">Add to new Moment</button>
      </li>
  </ul>
</div>
</div>

  </div>

      </div>



        <div class="ReplyingToContextBelowAuthor" data-aria-label-part>
    Replying to <a class="pretty-link js-user-profile-link" href="/mohammed_attya" data-user-id="250377148" rel="noopener" dir="ltr"><span class="username u-dir" dir="ltr" >@<b>mohammed_attya</b></span></a>



</div>



        <div class="js-tweet-text-container">
  <p class="TweetTextSize TweetTextSize--normal js-tweet-text tweet-text tweet-text-rtl" lang="ar" data-aria-label-part="0">الفوز ب 4 اشواط و الشوط من 15
مش زي الكبار الفوز ب 3 اشواط و الشوط من 25</p>
</div>


        <button type="button"
      class="btn-link js-translate-tweet translate-button u-textUserColorHover"
      data-nav="translate_tweet"
    >
    <span class="Icon Icon--translator"></span>Translate from Arabic
  </button>
  <div class="tweet-translation-container">
  <div class="spinner tweet-translation-loading" title="Loading..."></div>
  <div class="tweet-translation needs-translation" data-dest-lang="en">
    <div class="translation-attribution">
      <span>Translated from <span class="tweet-language">Arabic</span> by <a class="attribution-logo" href="http://aka.ms/Twitter2BingTranslator"><span class="invisible">Bing</span></a></span>
        <button type="button" class="btn-link js-translation-feedback-button" data-nav="">Wrong translation?</button>
    </div>
    <p class="tweet-translation-text"></p>
  </div>
</div>










      <div class="stream-item-footer">

      <div class="ProfileTweet-actionCountList u-hiddenVisually">


    <span class="ProfileTweet-action--reply u-hiddenVisually">
      <span class="ProfileTweet-actionCount" aria-hidden="true" data-tweet-stat-count="0">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-reply-count-aria-901074473851523072" >0 replies</span>
      </span>
    </span>
    <span class="ProfileTweet-action--retweet u-hiddenVisually">
      <span class="ProfileTweet-actionCount" aria-hidden="true" data-tweet-stat-count="0">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-retweet-count-aria-901074473851523072" >0 retweets</span>
      </span>
    </span>
    <span class="ProfileTweet-action--favorite u-hiddenVisually">
      <span class="ProfileTweet-actionCount" aria-hidden="true" data-tweet-stat-count="0">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-favorite-count-aria-901074473851523072" >0 likes</span>
      </span>
    </span>
  </div>

  <div class="ProfileTweet-actionList js-actions" role="group" aria-label="Tweet actions">
    <div class="ProfileTweet-action ProfileTweet-action--reply">
  <button class="ProfileTweet-actionButton js-actionButton js-actionReply"
    data-modal="ProfileTweet-reply" type="button"
    aria-describedby="profile-tweet-action-reply-count-aria-901074473851523072">
    <div class="IconContainer js-tooltip" title="Reply">
      <span class="Icon Icon--medium Icon--reply"></span>
      <span class="u-hiddenVisually">Reply</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero ">
        <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
      </span>
  </button>
</div>

    <div class="ProfileTweet-action ProfileTweet-action--retweet js-toggleState js-toggleRt">
  <button class="ProfileTweet-actionButton  js-actionButton js-actionRetweet"

    data-modal="ProfileTweet-retweet"
    type="button"
    aria-describedby="profile-tweet-action-retweet-count-aria-901074473851523072">
    <div class="IconContainer js-tooltip" title="Retweet">
      <span class="Icon Icon--medium Icon--retweet"></span>
      <span class="u-hiddenVisually">Retweet</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
  </span>

  </button><button class="ProfileTweet-actionButtonUndo js-actionButton js-actionRetweet" data-modal="ProfileTweet-retweet" type="button">
    <div class="IconContainer js-tooltip" title="Undo retweet">
      <span class="Icon Icon--medium Icon--retweet"></span>
      <span class="u-hiddenVisually">Retweeted</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
  </span>

  </button>
</div>


    <div class="ProfileTweet-action ProfileTweet-action--favorite js-toggleState">
  <button class="ProfileTweet-actionButton js-actionButton js-actionFavorite" type="button"
    aria-describedby="profile-tweet-action-favorite-count-aria-901074473851523072">
    <div class="IconContainer js-tooltip" title="Like">
      <span role="presentation" class="Icon Icon--heart Icon--medium"></span>
      <div class="HeartAnimation"></div>
      <span class="u-hiddenVisually">Like</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
  </span>

  </button><button class="ProfileTweet-actionButtonUndo ProfileTweet-action--unfavorite u-linkClean js-actionButton js-actionFavorite" type="button">
    <div class="IconContainer js-tooltip" title="Undo like">
      <span role="presentation" class="Icon Icon--heart Icon--medium"></span>
      <div class="HeartAnimation"></div>
      <span class="u-hiddenVisually">Liked</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
  </span>

  </button>
</div>





  <div class="ProfileTweet-action ProfileTweet-action--analytics">
    <button class="ProfileTweet-actionButton u-textUserColorHover js-actionButton js-actionQuickPromote" type="button">
      <div class="IconContainer js-tooltip" title="View Tweet activity">
        <span class="Icon Icon--medium Icon--analytics"></span>
        <span class="u-hiddenVisually">View Tweet activity</span>
      </div>
    </button>
  </div>

  </div>

</div>







    </div>
  </div>




<div class="dismiss-module">
  <div class="dismissed-module">
      <div class="feedback-action" data-feedback-type="DontLike">
        <div class="action-confirmation">Thanks. Twitter will use this to make your timeline better. <span class="undo-action u-textUserColor">Undo</span></div>
      </div>
  </div>
</div>

</li>

      <li class="js-stream-item stream-item stream-item
" data-item-id="901074302174449664"
id="stream-item-tweet-901074302174449664"
data-item-type="tweet"
 data-suggestion-json="{&quot;suggestion_details&quot;:{},&quot;tweet_ids&quot;:&quot;901074302174449664&quot;,&quot;scribe_component&quot;:&quot;tweet&quot;}">




  <div class="tweet js-stream-tweet js-actionable-tweet js-profile-popup-actionable dismissible-content
       original-tweet js-original-tweet

       my-tweet
"

data-tweet-id="901074302174449664"
data-item-id="901074302174449664"
data-permalink-path="/mohammed_attya/status/901074302174449664"
data-conversation-id="901074302174449664"



data-tweet-nonce="901074302174449664-2ab32277-6040-41fc-a6f2-31eb0f3492d9"
data-tweet-stat-initialized="true"






  data-screen-name="mohammed_attya" data-name="Mohammed Attya" data-user-id="250377148"
  data-you-follow="false"
  data-follows-you="false"
  data-you-block="false"


data-reply-to-users-json="[{&quot;id_str&quot;:&quot;250377148&quot;,&quot;screen_name&quot;:&quot;mohammed_attya&quot;,&quot;name&quot;:&quot;Mohammed Attya&quot;,&quot;emojified_name&quot;:{&quot;text&quot;:&quot;Mohammed Attya&quot;,&quot;emojified_text_as_html&quot;:&quot;Mohammed Attya&quot;}}]"







data-disclosure-type=""












 data-tfb-view="/i/tfb/v1/quick_promote/901074302174449664"
    >

    <div class="context">


    </div>

    <div class="content">





      <div class="stream-item-header">
          <a  class="account-group js-account-group js-action-profile js-user-profile-link js-nav" href="/mohammed_attya" data-user-id="250377148">
    <img class="avatar js-action-profile-avatar" src="https://pbs.twimg.com/profile_images/878491891733549056/3urQeq4j_bigger.jpg" alt="">
    <span class="FullNameGroup">
      <strong class="fullname show-popup-with-id " data-aria-label-part>Mohammed Attya</strong><span>&rlm;</span><span class="UserBadges"></span><span class="UserNameBreak">&nbsp;</span></span><span class="username u-dir" dir="ltr" data-aria-label-part>@<b>mohammed_attya</b></span></a>


        <small class="time">
  <a href="/mohammed_attya/status/901074302174449664" class="tweet-timestamp js-permalink js-nav js-tooltip" title="3:30 PM - 25 Aug 2017"  data-conversation-id="901074302174449664"><span class="_timestamp js-short-timestamp " data-aria-label-part="last" data-time="1503667829" data-time-ms="1503667829000" data-long-form="true">Aug 25</span></a>
</small>

          <div class="ProfileTweet-action ProfileTweet-action--more js-more-ProfileTweet-actions">
    <div class="dropdown">
  <button class="ProfileTweet-actionButton u-textUserColorHover dropdown-toggle js-dropdown-toggle" type="button">
      <div class="IconContainer js-tooltip" title="More">
        <span class="Icon Icon--caretDownLight Icon--small"></span>
        <span class="u-hiddenVisually">More</span>
      </div>
  </button>
  <div class="dropdown-menu is-autoCentered">
  <div class="dropdown-caret">
    <div class="caret-outer"></div>
    <div class="caret-inner"></div>
  </div>
  <ul>
      <li class="share-via-dm js-actionShareViaDM" data-nav="share_tweet_dm">
        <button type="button" class="dropdown-link">Share via Direct Message</button>
      </li>

      <li class="copy-link-to-tweet js-actionCopyLinkToTweet">
        <button type="button" class="dropdown-link">Copy link to Tweet</button>
      </li>
      <li class="embed-link js-actionEmbedTweet" data-nav="embed_tweet">
        <button type="button" class="dropdown-link">Embed Tweet</button>
      </li>
      <li class="user-pin-tweet js-actionPinTweet" data-nav="user_pin_tweet">
        <button type="button" class="dropdown-link">Pin to your profile page</button>
      </li>
      <li class="user-unpin-tweet js-actionPinTweet" data-nav="user_unpin_tweet">
        <button type="button" class="dropdown-link">Unpin from profile page</button>
      </li>
      <li class="js-actionDelete">
        <button type="button" class="dropdown-link">Delete Tweet</button>
      </li>
      <li class="dropdown-divider"></li>
      <li class="js-actionMomentMakerAddTweetToOtherMoment MomentMakerAddTweetToOtherMoment">
        <button type="button" class="dropdown-link">Add to other Moment</button>
      </li>
      <li class="js-actionMomentMakerCreateMoment">
        <button type="button" class="dropdown-link">Add to new Moment</button>
      </li>
  </ul>
</div>
</div>

  </div>

      </div>





        <div class="js-tweet-text-container">
  <p class="TweetTextSize TweetTextSize--normal js-tweet-text tweet-text tweet-text-rtl" lang="ar" data-aria-label-part="0">منتخب مصر للكرة الطائرة تحت 23 سنة بيلاعب اليابان على المركز الخامس
متقدمين عليهم 2-صفر
البطولة بتتلعب في مصر</p>
</div>


        <button type="button"
      class="btn-link js-translate-tweet translate-button u-textUserColorHover"
      data-nav="translate_tweet"
    >
    <span class="Icon Icon--translator"></span>Translate from Arabic
  </button>
  <div class="tweet-translation-container">
  <div class="spinner tweet-translation-loading" title="Loading..."></div>
  <div class="tweet-translation needs-translation" data-dest-lang="en">
    <div class="translation-attribution">
      <span>Translated from <span class="tweet-language">Arabic</span> by <a class="attribution-logo" href="http://aka.ms/Twitter2BingTranslator"><span class="invisible">Bing</span></a></span>
        <button type="button" class="btn-link js-translation-feedback-button" data-nav="">Wrong translation?</button>
    </div>
    <p class="tweet-translation-text"></p>
  </div>
</div>










      <div class="stream-item-footer">

      <div class="ProfileTweet-actionCountList u-hiddenVisually">


    <span class="ProfileTweet-action--reply u-hiddenVisually">
      <span class="ProfileTweet-actionCount"  data-tweet-stat-count="1">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-reply-count-aria-901074302174449664" data-aria-label-part>1 reply</span>
      </span>
    </span>
    <span class="ProfileTweet-action--retweet u-hiddenVisually">
      <span class="ProfileTweet-actionCount" aria-hidden="true" data-tweet-stat-count="0">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-retweet-count-aria-901074302174449664" >0 retweets</span>
      </span>
    </span>
    <span class="ProfileTweet-action--favorite u-hiddenVisually">
      <span class="ProfileTweet-actionCount" aria-hidden="true" data-tweet-stat-count="0">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-favorite-count-aria-901074302174449664" >0 likes</span>
      </span>
    </span>
  </div>

  <div class="ProfileTweet-actionList js-actions" role="group" aria-label="Tweet actions">
    <div class="ProfileTweet-action ProfileTweet-action--reply">
  <button class="ProfileTweet-actionButton js-actionButton js-actionReply"
    data-modal="ProfileTweet-reply" type="button"
    aria-describedby="profile-tweet-action-reply-count-aria-901074302174449664">
    <div class="IconContainer js-tooltip" title="Reply">
      <span class="Icon Icon--medium Icon--reply"></span>
      <span class="u-hiddenVisually">Reply</span>
    </div>
      <span class="ProfileTweet-actionCount ">
        <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true">1</span>
      </span>
  </button>
</div>

    <div class="ProfileTweet-action ProfileTweet-action--retweet js-toggleState js-toggleRt">
  <button class="ProfileTweet-actionButton  js-actionButton js-actionRetweet"

    data-modal="ProfileTweet-retweet"
    type="button"
    aria-describedby="profile-tweet-action-retweet-count-aria-901074302174449664">
    <div class="IconContainer js-tooltip" title="Retweet">
      <span class="Icon Icon--medium Icon--retweet"></span>
      <span class="u-hiddenVisually">Retweet</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
  </span>

  </button><button class="ProfileTweet-actionButtonUndo js-actionButton js-actionRetweet" data-modal="ProfileTweet-retweet" type="button">
    <div class="IconContainer js-tooltip" title="Undo retweet">
      <span class="Icon Icon--medium Icon--retweet"></span>
      <span class="u-hiddenVisually">Retweeted</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
  </span>

  </button>
</div>


    <div class="ProfileTweet-action ProfileTweet-action--favorite js-toggleState">
  <button class="ProfileTweet-actionButton js-actionButton js-actionFavorite" type="button"
    aria-describedby="profile-tweet-action-favorite-count-aria-901074302174449664">
    <div class="IconContainer js-tooltip" title="Like">
      <span role="presentation" class="Icon Icon--heart Icon--medium"></span>
      <div class="HeartAnimation"></div>
      <span class="u-hiddenVisually">Like</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
  </span>

  </button><button class="ProfileTweet-actionButtonUndo ProfileTweet-action--unfavorite u-linkClean js-actionButton js-actionFavorite" type="button">
    <div class="IconContainer js-tooltip" title="Undo like">
      <span role="presentation" class="Icon Icon--heart Icon--medium"></span>
      <div class="HeartAnimation"></div>
      <span class="u-hiddenVisually">Liked</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
  </span>

  </button>
</div>





  <div class="ProfileTweet-action ProfileTweet-action--analytics">
    <button class="ProfileTweet-actionButton u-textUserColorHover js-actionButton js-actionQuickPromote" type="button">
      <div class="IconContainer js-tooltip" title="View Tweet activity">
        <span class="Icon Icon--medium Icon--analytics"></span>
        <span class="u-hiddenVisually">View Tweet activity</span>
      </div>
    </button>
  </div>

  </div>

</div>







    </div>
  </div>




<div class="dismiss-module">
  <div class="dismissed-module">
      <div class="feedback-action" data-feedback-type="DontLike">
        <div class="action-confirmation">Thanks. Twitter will use this to make your timeline better. <span class="undo-action u-textUserColor">Undo</span></div>
      </div>
  </div>
</div>

</li>

      <li class="js-stream-item stream-item stream-item
" data-item-id="900659878817148928"
id="stream-item-tweet-900659878817148928"
data-item-type="tweet"
 data-suggestion-json="{&quot;suggestion_details&quot;:{},&quot;tweet_ids&quot;:&quot;900659878817148928&quot;,&quot;scribe_component&quot;:&quot;tweet&quot;}">




  <div class="tweet js-stream-tweet js-actionable-tweet js-profile-popup-actionable dismissible-content
       original-tweet js-original-tweet

       retweeted has-cards  has-content
"

data-tweet-id="900659878817148928"
data-item-id="900659878817148928"
data-permalink-path="/NN_04_11/status/900659878817148928"
data-conversation-id="900659878817148928"



data-tweet-nonce="900659878817148928-6451858e-8b5a-47e9-b570-f28460b7a463"




 data-my-retweet-id="900787635618283521"
    data-retweet-id="900787635618283521"
     data-retweeter="mohammed_attya"


  data-screen-name="NN_04_11" data-name="نهي ♏" data-user-id="263577226"
  data-you-follow="false"
  data-follows-you="false"
  data-you-block="false"


data-reply-to-users-json="[{&quot;id_str&quot;:&quot;263577226&quot;,&quot;screen_name&quot;:&quot;NN_04_11&quot;,&quot;name&quot;:&quot;\u0646\u0647\u064a \u264f&quot;,&quot;emojified_name&quot;:{&quot;text&quot;:&quot;\u0646\u0647\u064a \u264f&quot;,&quot;emojified_text_as_html&quot;:&quot;\u0646\u0647\u064a \u003cspan class=\&quot;Emoji Emoji--forLinks\&quot; style=\&quot;background-image:url(&#39;https:\/\/abs.twimg.com\/emoji\/v2\/72x72\/264f.png&#39;)\&quot; title=\&quot;Scorpius\&quot; aria-label=\&quot;Emoji: Scorpius\&quot;\u003e&amp;nbsp;\u003c\/span\u003e\u003cspan class=\&quot;visuallyhidden\&quot; aria-hidden=\&quot;true\&quot;\u003e\u264f\u003c\/span\u003e&quot;}}]"







data-disclosure-type=""




 data-has-cards="true"








    >

    <div class="context">

          <div class="tweet-context with-icn

    ">

      <span class="Icon Icon--small Icon--retweeted"></span>



            <span class="js-retweet-text">
                <a class="pretty-link js-user-profile-link" href="/mohammed_attya" data-user-id="250377148" rel="noopener"><b>You</b></a> Retweeted
            </span>





    </div>

    </div>

    <div class="content">





      <div class="stream-item-header">
          <a  class="account-group js-account-group js-action-profile js-user-profile-link js-nav" href="/NN_04_11" data-user-id="263577226">
    <img class="avatar js-action-profile-avatar" src="https://pbs.twimg.com/profile_images/901764218172702721/tHyqM2cZ_bigger.jpg" alt="">
    <span class="FullNameGroup">
      <strong class="fullname show-popup-with-id fullname-rtl" data-aria-label-part>نهي <span class="Emoji Emoji--forLinks" style="background-image:url("https://abs.twimg.com/emoji/v2/72x72/264f.png")" title="Scorpius" aria-label="Emoji: Scorpius">&nbsp;</span><span class="visuallyhidden" aria-hidden="true">♏</span></strong><span>&rlm;</span><span class="UserBadges"></span><span class="UserNameBreak">&nbsp;</span></span><span class="username u-dir" dir="ltr" data-aria-label-part>@<b>NN_04_11</b></span></a>


        <small class="time">
  <a href="/NN_04_11/status/900659878817148928" class="tweet-timestamp js-permalink js-nav js-tooltip" title="12:03 PM - 24 Aug 2017"  data-conversation-id="900659878817148928"><span class="_timestamp js-short-timestamp " data-aria-label-part="last" data-time="1503569023" data-time-ms="1503569023000" data-long-form="true">Aug 24</span></a>
</small>

          <div class="ProfileTweet-action ProfileTweet-action--more js-more-ProfileTweet-actions">
    <div class="dropdown">
  <button class="ProfileTweet-actionButton u-textUserColorHover dropdown-toggle js-dropdown-toggle" type="button">
      <div class="IconContainer js-tooltip" title="More">
        <span class="Icon Icon--caretDownLight Icon--small"></span>
        <span class="u-hiddenVisually">More</span>
      </div>
  </button>
  <div class="dropdown-menu is-autoCentered">
  <div class="dropdown-caret">
    <div class="caret-outer"></div>
    <div class="caret-inner"></div>
  </div>
  <ul>

      <li class="copy-link-to-tweet js-actionCopyLinkToTweet">
        <button type="button" class="dropdown-link">Copy link to Tweet</button>
      </li>
      <li class="embed-link js-actionEmbedTweet" data-nav="embed_tweet">
        <button type="button" class="dropdown-link">Embed Tweet</button>
      </li>
        <li class="embed-link js-actionEmbedVideo" data-nav="embed_video">
          <button type="button" class="dropdown-link">Embed Video</button>
        </li>
          <li class="mute-user-item"><button type="button" class="dropdown-link">Mute <span class="username u-dir" dir="ltr" >@<b>NN_04_11</b></span></button></li>
    <li class="unmute-user-item"><button type="button" class="dropdown-link">Unmute <span class="username u-dir" dir="ltr" >@<b>NN_04_11</b></span></button></li>

        <li class="block-link js-actionBlock" data-nav="block">
          <button type="button" class="dropdown-link">Block <span class="username u-dir" dir="ltr" >@<b>NN_04_11</b></span></button>
        </li>
        <li class="unblock-link js-actionUnblock" data-nav="unblock">
          <button type="button" class="dropdown-link">Unblock <span class="username u-dir" dir="ltr" >@<b>NN_04_11</b></span></button>
        </li>
      <li class="report-link js-actionReport" data-nav="report">
        <button type="button" class="dropdown-link">


            Report Tweet
        </button>
      </li>
      <li class="dropdown-divider"></li>
      <li class="js-actionMomentMakerAddTweetToOtherMoment MomentMakerAddTweetToOtherMoment">
        <button type="button" class="dropdown-link">Add to other Moment</button>
      </li>
      <li class="js-actionMomentMakerCreateMoment">
        <button type="button" class="dropdown-link">Add to new Moment</button>
      </li>
  </ul>
</div>
</div>

  </div>

      </div>





        <div class="js-tweet-text-container">
  <p class="TweetTextSize TweetTextSize--normal js-tweet-text tweet-text tweet-text-rtl" lang="ar" data-aria-label-part="0">حد قذر اخد صورة البنت دى ونزلها فى جروب&quot;كيف تشقطين ذكرا&quot;وعليها تعليق&quot;البسوا عريان زيي وانتم تتخطبوا&quot; وانتم زى البهايم بتشيروا الصورة عادى..<a href="https://t.co/w2Ajp9txCG" class="twitter-timeline-link u-hidden" data-pre-embedded="true" dir="ltr" >pic.twitter.com/w2Ajp9txCG</a></p>
</div>


        <button type="button"
      class="btn-link js-translate-tweet translate-button u-textUserColorHover"
      data-nav="translate_tweet"
    >
    <span class="Icon Icon--translator"></span>Translate from Arabic
  </button>
  <div class="tweet-translation-container">
  <div class="spinner tweet-translation-loading" title="Loading..."></div>
  <div class="tweet-translation needs-translation" data-dest-lang="en">
    <div class="translation-attribution">
      <span>Translated from <span class="tweet-language">Arabic</span> by <a class="attribution-logo" href="http://aka.ms/Twitter2BingTranslator"><span class="invisible">Bing</span></a></span>
        <button type="button" class="btn-link js-translation-feedback-button" data-nav="">Wrong translation?</button>
    </div>
    <p class="tweet-translation-text"></p>
  </div>
</div>



            <div class="AdaptiveMediaOuterContainer">
    <div class="AdaptiveMedia
        allow-expansion


        is-generic-video


        "
      >
      <div class="AdaptiveMedia-container">
          <div class="AdaptiveMedia-video">
  <div class="AdaptiveMedia-videoContainer">
      <div class="PlayableMedia PlayableMedia--video">

  <div
    class="PlayableMedia-player

      "
    data-playable-media-url=""

      data-border-top-left-radius=""
      data-border-top-right-radius=""
      data-border-bottom-left-radius=""
      data-border-bottom-right-radius=""
    style="padding-bottom: 100.0%; background-image:url("https://pbs.twimg.com/ext_tw_video_thumb/900659168553705476/pu/img/H77ZNufJM0-2c1gU.jpg")">
  </div>



</div>

  </div>
</div>

      </div>
    </div>
  </div>








      <div class="stream-item-footer">

      <div class="ProfileTweet-actionCountList u-hiddenVisually">


    <span class="ProfileTweet-action--reply u-hiddenVisually">
      <span class="ProfileTweet-actionCount"  data-tweet-stat-count="290">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-reply-count-aria-900659878817148928" data-aria-label-part>290 replies</span>
      </span>
    </span>
    <span class="ProfileTweet-action--retweet u-hiddenVisually">
      <span class="ProfileTweet-actionCount"  data-tweet-stat-count="2525">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-retweet-count-aria-900659878817148928" data-aria-label-part>2,525 retweets</span>
      </span>
    </span>
    <span class="ProfileTweet-action--favorite u-hiddenVisually">
      <span class="ProfileTweet-actionCount"  data-tweet-stat-count="1547">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-favorite-count-aria-900659878817148928" data-aria-label-part>1,547 likes</span>
      </span>
    </span>
  </div>

  <div class="ProfileTweet-actionList js-actions" role="group" aria-label="Tweet actions">
    <div class="ProfileTweet-action ProfileTweet-action--reply">
  <button class="ProfileTweet-actionButton js-actionButton js-actionReply"
    data-modal="ProfileTweet-reply" type="button"
    aria-describedby="profile-tweet-action-reply-count-aria-900659878817148928">
    <div class="IconContainer js-tooltip" title="Reply">
      <span class="Icon Icon--medium Icon--reply"></span>
      <span class="u-hiddenVisually">Reply</span>
    </div>
      <span class="ProfileTweet-actionCount ">
        <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
      </span>
  </button>
</div>

    <div class="ProfileTweet-action ProfileTweet-action--retweet js-toggleState js-toggleRt">
  <button class="ProfileTweet-actionButton  js-actionButton js-actionRetweet"

    data-modal="ProfileTweet-retweet"
    type="button"
    aria-describedby="profile-tweet-action-retweet-count-aria-900659878817148928">
    <div class="IconContainer js-tooltip" title="Retweet">
      <span class="Icon Icon--medium Icon--retweet"></span>
      <span class="u-hiddenVisually">Retweet</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
  </span>

  </button><button class="ProfileTweet-actionButtonUndo js-actionButton js-actionRetweet" data-modal="ProfileTweet-retweet" type="button">
    <div class="IconContainer js-tooltip" title="Undo retweet">
      <span class="Icon Icon--medium Icon--retweet"></span>
      <span class="u-hiddenVisually">Retweeted</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
  </span>

  </button>
</div>


    <div class="ProfileTweet-action ProfileTweet-action--favorite js-toggleState">
  <button class="ProfileTweet-actionButton js-actionButton js-actionFavorite" type="button"
    aria-describedby="profile-tweet-action-favorite-count-aria-900659878817148928">
    <div class="IconContainer js-tooltip" title="Like">
      <span role="presentation" class="Icon Icon--heart Icon--medium"></span>
      <div class="HeartAnimation"></div>
      <span class="u-hiddenVisually">Like</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
  </span>

  </button><button class="ProfileTweet-actionButtonUndo ProfileTweet-action--unfavorite u-linkClean js-actionButton js-actionFavorite" type="button">
    <div class="IconContainer js-tooltip" title="Undo like">
      <span role="presentation" class="Icon Icon--heart Icon--medium"></span>
      <div class="HeartAnimation"></div>
      <span class="u-hiddenVisually">Liked</span>
    </div>
      <span class="ProfileTweet-actionCount">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
  </span>

  </button>
</div>


      <div class="ProfileTweet-action ProfileTweet-action--dm">
    <button class="ProfileTweet-actionButton u-textUserColorHover js-actionButton js-actionShareViaDM"
      type="button"
      data-nav="share_tweet_dm"
    >
      <div class="IconContainer js-tooltip" title="Direct message">
        <span class="Icon Icon--medium Icon--dm"></span>
        <span class="u-hiddenVisually">Direct message</span>
      </div>
    </button>
  </div>




  </div>

</div>







    </div>
  </div>




<div class="dismiss-module">
  <div class="dismissed-module">
      <div class="feedback-action" data-feedback-type="DontLike">
        <div class="action-confirmation">Thanks. Twitter will use this to make your timeline better. <span class="undo-action u-textUserColor">Undo</span></div>
      </div>
  </div>
</div>

</li>

      <li class="js-stream-item stream-item stream-item
" data-item-id="899752560772358144"
id="stream-item-tweet-899752560772358144"
data-item-type="tweet"
 data-suggestion-json="{&quot;suggestion_details&quot;:{},&quot;tweet_ids&quot;:&quot;899752560772358144&quot;,&quot;scribe_component&quot;:&quot;tweet&quot;}">




  <div class="tweet js-stream-tweet js-actionable-tweet js-profile-popup-actionable dismissible-content
       original-tweet js-original-tweet

       my-tweet has-cards  has-content
"

data-tweet-id="899752560772358144"
data-item-id="899752560772358144"
data-permalink-path="/mohammed_attya/status/899752560772358144"
data-conversation-id="899752560772358144"



data-tweet-nonce="899752560772358144-d7c2c12c-1f1e-4799-b413-17411ded4e37"
data-tweet-stat-initialized="true"






  data-screen-name="mohammed_attya" data-name="Mohammed Attya" data-user-id="250377148"
  data-you-follow="false"
  data-follows-you="false"
  data-you-block="false"


data-reply-to-users-json="[{&quot;id_str&quot;:&quot;250377148&quot;,&quot;screen_name&quot;:&quot;mohammed_attya&quot;,&quot;name&quot;:&quot;Mohammed Attya&quot;,&quot;emojified_name&quot;:{&quot;text&quot;:&quot;Mohammed Attya&quot;,&quot;emojified_text_as_html&quot;:&quot;Mohammed Attya&quot;}}]"







data-disclosure-type=""




 data-has-cards="true"







 data-tfb-view="/i/tfb/v1/quick_promote/899752560772358144"
    >

    <div class="context">


    </div>

    <div class="content">





      <div class="stream-item-header">
          <a  class="account-group js-account-group js-action-profile js-user-profile-link js-nav" href="/mohammed_attya" data-user-id="250377148">
    <img class="avatar js-action-profile-avatar" src="https://pbs.twimg.com/profile_images/878491891733549056/3urQeq4j_bigger.jpg" alt="">
    <span class="FullNameGroup">
      <strong class="fullname show-popup-with-id " data-aria-label-part>Mohammed Attya</strong><span>&rlm;</span><span class="UserBadges"></span><span class="UserNameBreak">&nbsp;</span></span><span class="username u-dir" dir="ltr" data-aria-label-part>@<b>mohammed_attya</b></span></a>


        <small class="time">
  <a href="/mohammed_attya/status/899752560772358144" class="tweet-timestamp js-permalink js-nav js-tooltip" title="11:58 PM - 21 Aug 2017"  data-conversation-id="899752560772358144"><span class="_timestamp js-short-timestamp " data-aria-label-part="last" data-time="1503352701" data-time-ms="1503352701000" data-long-form="true">Aug 21</span></a>
</small>

          <div class="ProfileTweet-action ProfileTweet-action--more js-more-ProfileTweet-actions">
    <div class="dropdown">
  <button class="ProfileTweet-actionButton u-textUserColorHover dropdown-toggle js-dropdown-toggle" type="button">
      <div class="IconContainer js-tooltip" title="More">
        <span class="Icon Icon--caretDownLight Icon--small"></span>
        <span class="u-hiddenVisually">More</span>
      </div>
  </button>
  <div class="dropdown-menu is-autoCentered">
  <div class="dropdown-caret">
    <div class="caret-outer"></div>
    <div class="caret-inner"></div>
  </div>
  <ul>
      <li class="share-via-dm js-actionShareViaDM" data-nav="share_tweet_dm">
        <button type="button" class="dropdown-link">Share via Direct Message</button>
      </li>

      <li class="copy-link-to-tweet js-actionCopyLinkToTweet">
        <button type="button" class="dropdown-link">Copy link to Tweet</button>
      </li>
      <li class="embed-link js-actionEmbedTweet" data-nav="embed_tweet">
        <button type="button" class="dropdown-link">Embed Tweet</button>
      </li>
      <li class="user-pin-tweet js-actionPinTweet" data-nav="user_pin_tweet">
        <button type="button" class="dropdown-link">Pin to your profile page</button>
      </li>
      <li class="user-unpin-tweet js-actionPinTweet" data-nav="user_unpin_tweet">
        <button type="button" class="dropdown-link">Unpin from profile page</button>
      </li>
      <li class="js-actionDelete">
        <button type="button" class="dropdown-link">Delete Tweet</button>
      </li>
      <li class="dropdown-divider"></li>
      <li class="js-actionMomentMakerAddTweetToOtherMoment MomentMakerAddTweetToOtherMoment">
        <button type="button" class="dropdown-link">Add to other Moment</button>
      </li>
      <li class="js-actionMomentMakerCreateMoment">
        <button type="button" class="dropdown-link">Add to new Moment</button>
      </li>
  </ul>
</div>
</div>

  </div>

      </div>





        <div class="js-tweet-text-container">
  <p class="TweetTextSize TweetTextSize--normal js-tweet-text tweet-text tweet-text-rtl" lang="ar" data-aria-label-part="0">من اول الفيديوهات اللى نشرتها على الفيسبوك هو فيديو قصيدة &quot;الجيش العربي فين&quot;<a href="https://t.co/xd4ktpxPGj" class="twitter-timeline-link u-hidden" data-pre-embedded="true" dir="ltr" >pic.twitter.com/xd4ktpxPGj</a></p>
</div>


        <button type="button"
      class="btn-link js-translate-tweet translate-button u-textUserColorHover"
      data-nav="translate_tweet"
    >
    <span class="Icon Icon--translator"></span>Translate from Arabic
  </button>
  <div class="tweet-translation-container">
  <div class="spinner tweet-translation-loading" title="Loading..."></div>
  <div class="tweet-translation needs-translation" data-dest-lang="en">
    <div class="translation-attribution">
      <span>Translated from <span class="tweet-language">Arabic</span> by <a class="attribution-logo" href="http://aka.ms/Twitter2BingTranslator"><span class="invisible">Bing</span></a></span>
        <button type="button" class="btn-link js-translation-feedback-button" data-nav="">Wrong translation?</button>
    </div>
    <p class="tweet-translation-text"></p>
  </div>
</div>



            <div class="AdaptiveMediaOuterContainer">
    <div class="AdaptiveMedia


        is-square



        "
      >
      <div class="AdaptiveMedia-container">
          <div class="AdaptiveMedia-singlePhoto"
    style="padding-top: calc(1.7777777777777777 * 100% - 0.5px);"
>
    <div class="AdaptiveMedia-photoContainer js-adaptive-photo "
  data-image-url="https://pbs.twimg.com/media/DHyQK2tXsAczNvB.jpg"


  data-element-context="platform_photo_card"
    style="background-color:rgba(37,51,64,1.0);"
    data-dominant-color="[37,51,64]"
>
  <img data-aria-label-part src="https://pbs.twimg.com/media/DHyQK2tXsAczNvB.jpg" alt=""
      style="width: 100%; top: -0px;"
>
</div>


</div>
      </div>
    </div>
  </div>








      <div class="stream-item-footer">

      <div class="ProfileTweet-actionCountList u-hiddenVisually">


    <span class="ProfileTweet-action--reply u-hiddenVisually">
      <span class="ProfileTweet-actionCount" aria-hidden="true" data-tweet-stat-count="0">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-reply-count-aria-899752560772358144" >0 replies</span>
      </span>
    </span>
    <span class="ProfileTweet-action--retweet u-hiddenVisually">
      <span class="ProfileTweet-actionCount" aria-hidden="true" data-tweet-stat-count="0">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-retweet-count-aria-899752560772358144" >0 retweets</span>
      </span>
    </span>
    <span class="ProfileTweet-action--favorite u-hiddenVisually">
      <span class="ProfileTweet-actionCount" aria-hidden="true" data-tweet-stat-count="0">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-favorite-count-aria-899752560772358144" >0 likes</span>
      </span>
    </span>
  </div>

  <div class="ProfileTweet-actionList js-actions" role="group" aria-label="Tweet actions">
    <div class="ProfileTweet-action ProfileTweet-action--reply">
  <button class="ProfileTweet-actionButton js-actionButton js-actionReply"
    data-modal="ProfileTweet-reply" type="button"
    aria-describedby="profile-tweet-action-reply-count-aria-899752560772358144">
    <div class="IconContainer js-tooltip" title="Reply">
      <span class="Icon Icon--medium Icon--reply"></span>
      <span class="u-hiddenVisually">Reply</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero ">
        <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
      </span>
  </button>
</div>

    <div class="ProfileTweet-action ProfileTweet-action--retweet js-toggleState js-toggleRt">
  <button class="ProfileTweet-actionButton  js-actionButton js-actionRetweet"

    data-modal="ProfileTweet-retweet"
    type="button"
    aria-describedby="profile-tweet-action-retweet-count-aria-899752560772358144">
    <div class="IconContainer js-tooltip" title="Retweet">
      <span class="Icon Icon--medium Icon--retweet"></span>
      <span class="u-hiddenVisually">Retweet</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
  </span>

  </button><button class="ProfileTweet-actionButtonUndo js-actionButton js-actionRetweet" data-modal="ProfileTweet-retweet" type="button">
    <div class="IconContainer js-tooltip" title="Undo retweet">
      <span class="Icon Icon--medium Icon--retweet"></span>
      <span class="u-hiddenVisually">Retweeted</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
  </span>

  </button>
</div>


    <div class="ProfileTweet-action ProfileTweet-action--favorite js-toggleState">
  <button class="ProfileTweet-actionButton js-actionButton js-actionFavorite" type="button"
    aria-describedby="profile-tweet-action-favorite-count-aria-899752560772358144">
    <div class="IconContainer js-tooltip" title="Like">
      <span role="presentation" class="Icon Icon--heart Icon--medium"></span>
      <div class="HeartAnimation"></div>
      <span class="u-hiddenVisually">Like</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
  </span>

  </button><button class="ProfileTweet-actionButtonUndo ProfileTweet-action--unfavorite u-linkClean js-actionButton js-actionFavorite" type="button">
    <div class="IconContainer js-tooltip" title="Undo like">
      <span role="presentation" class="Icon Icon--heart Icon--medium"></span>
      <div class="HeartAnimation"></div>
      <span class="u-hiddenVisually">Liked</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
  </span>

  </button>
</div>





  <div class="ProfileTweet-action ProfileTweet-action--analytics">
    <button class="ProfileTweet-actionButton u-textUserColorHover js-actionButton js-actionQuickPromote" type="button">
      <div class="IconContainer js-tooltip" title="View Tweet activity">
        <span class="Icon Icon--medium Icon--analytics"></span>
        <span class="u-hiddenVisually">View Tweet activity</span>
      </div>
    </button>
  </div>

  </div>

</div>







    </div>
  </div>




<div class="dismiss-module">
  <div class="dismissed-module">
      <div class="feedback-action" data-feedback-type="DontLike">
        <div class="action-confirmation">Thanks. Twitter will use this to make your timeline better. <span class="undo-action u-textUserColor">Undo</span></div>
      </div>
  </div>
</div>

</li>

      <li class="js-stream-item stream-item stream-item
" data-item-id="899733729945546752"
id="stream-item-tweet-899733729945546752"
data-item-type="tweet"
 data-suggestion-json="{&quot;suggestion_details&quot;:{},&quot;tweet_ids&quot;:&quot;899733729945546752&quot;,&quot;scribe_component&quot;:&quot;tweet&quot;}">




  <div class="tweet js-stream-tweet js-actionable-tweet js-profile-popup-actionable dismissible-content
       original-tweet js-original-tweet

       my-tweet
"

data-tweet-id="899733729945546752"
data-item-id="899733729945546752"
data-permalink-path="/mohammed_attya/status/899733729945546752"
data-conversation-id="899733729945546752"



data-tweet-nonce="899733729945546752-374b88bf-232a-4b67-adfe-bddebca2b390"
data-tweet-stat-initialized="true"






  data-screen-name="mohammed_attya" data-name="Mohammed Attya" data-user-id="250377148"
  data-you-follow="false"
  data-follows-you="false"
  data-you-block="false"


data-reply-to-users-json="[{&quot;id_str&quot;:&quot;250377148&quot;,&quot;screen_name&quot;:&quot;mohammed_attya&quot;,&quot;name&quot;:&quot;Mohammed Attya&quot;,&quot;emojified_name&quot;:{&quot;text&quot;:&quot;Mohammed Attya&quot;,&quot;emojified_text_as_html&quot;:&quot;Mohammed Attya&quot;}}]"







data-disclosure-type=""












 data-tfb-view="/i/tfb/v1/quick_promote/899733729945546752"
    >

    <div class="context">


    </div>

    <div class="content">





      <div class="stream-item-header">
          <a  class="account-group js-account-group js-action-profile js-user-profile-link js-nav" href="/mohammed_attya" data-user-id="250377148">
    <img class="avatar js-action-profile-avatar" src="https://pbs.twimg.com/profile_images/878491891733549056/3urQeq4j_bigger.jpg" alt="">
    <span class="FullNameGroup">
      <strong class="fullname show-popup-with-id " data-aria-label-part>Mohammed Attya</strong><span>&rlm;</span><span class="UserBadges"></span><span class="UserNameBreak">&nbsp;</span></span><span class="username u-dir" dir="ltr" data-aria-label-part>@<b>mohammed_attya</b></span></a>


        <small class="time">
  <a href="/mohammed_attya/status/899733729945546752" class="tweet-timestamp js-permalink js-nav js-tooltip" title="10:43 PM - 21 Aug 2017"  data-conversation-id="899733729945546752"><span class="_timestamp js-short-timestamp " data-aria-label-part="last" data-time="1503348212" data-time-ms="1503348212000" data-long-form="true">Aug 21</span></a>
</small>

          <div class="ProfileTweet-action ProfileTweet-action--more js-more-ProfileTweet-actions">
    <div class="dropdown">
  <button class="ProfileTweet-actionButton u-textUserColorHover dropdown-toggle js-dropdown-toggle" type="button">
      <div class="IconContainer js-tooltip" title="More">
        <span class="Icon Icon--caretDownLight Icon--small"></span>
        <span class="u-hiddenVisually">More</span>
      </div>
  </button>
  <div class="dropdown-menu is-autoCentered">
  <div class="dropdown-caret">
    <div class="caret-outer"></div>
    <div class="caret-inner"></div>
  </div>
  <ul>
      <li class="share-via-dm js-actionShareViaDM" data-nav="share_tweet_dm">
        <button type="button" class="dropdown-link">Share via Direct Message</button>
      </li>

      <li class="copy-link-to-tweet js-actionCopyLinkToTweet">
        <button type="button" class="dropdown-link">Copy link to Tweet</button>
      </li>
      <li class="embed-link js-actionEmbedTweet" data-nav="embed_tweet">
        <button type="button" class="dropdown-link">Embed Tweet</button>
      </li>
      <li class="user-pin-tweet js-actionPinTweet" data-nav="user_pin_tweet">
        <button type="button" class="dropdown-link">Pin to your profile page</button>
      </li>
      <li class="user-unpin-tweet js-actionPinTweet" data-nav="user_unpin_tweet">
        <button type="button" class="dropdown-link">Unpin from profile page</button>
      </li>
      <li class="js-actionDelete">
        <button type="button" class="dropdown-link">Delete Tweet</button>
      </li>
      <li class="dropdown-divider"></li>
      <li class="js-actionMomentMakerAddTweetToOtherMoment MomentMakerAddTweetToOtherMoment">
        <button type="button" class="dropdown-link">Add to other Moment</button>
      </li>
      <li class="js-actionMomentMakerCreateMoment">
        <button type="button" class="dropdown-link">Add to new Moment</button>
      </li>
  </ul>
</div>
</div>

  </div>

      </div>





        <div class="js-tweet-text-container">
  <p class="TweetTextSize TweetTextSize--normal js-tweet-text tweet-text" lang="en" data-aria-label-part="0">Android Oreo</p>
</div>












      <div class="stream-item-footer">

      <div class="ProfileTweet-actionCountList u-hiddenVisually">


    <span class="ProfileTweet-action--reply u-hiddenVisually">
      <span class="ProfileTweet-actionCount" aria-hidden="true" data-tweet-stat-count="0">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-reply-count-aria-899733729945546752" >0 replies</span>
      </span>
    </span>
    <span class="ProfileTweet-action--retweet u-hiddenVisually">
      <span class="ProfileTweet-actionCount" aria-hidden="true" data-tweet-stat-count="0">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-retweet-count-aria-899733729945546752" >0 retweets</span>
      </span>
    </span>
    <span class="ProfileTweet-action--favorite u-hiddenVisually">
      <span class="ProfileTweet-actionCount" aria-hidden="true" data-tweet-stat-count="0">
        <span class="ProfileTweet-actionCountForAria" id="profile-tweet-action-favorite-count-aria-899733729945546752" >0 likes</span>
      </span>
    </span>
  </div>

  <div class="ProfileTweet-actionList js-actions" role="group" aria-label="Tweet actions">
    <div class="ProfileTweet-action ProfileTweet-action--reply">
  <button class="ProfileTweet-actionButton js-actionButton js-actionReply"
    data-modal="ProfileTweet-reply" type="button"
    aria-describedby="profile-tweet-action-reply-count-aria-899733729945546752">
    <div class="IconContainer js-tooltip" title="Reply">
      <span class="Icon Icon--medium Icon--reply"></span>
      <span class="u-hiddenVisually">Reply</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero ">
        <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
      </span>
  </button>
</div>

    <div class="ProfileTweet-action ProfileTweet-action--retweet js-toggleState js-toggleRt">
  <button class="ProfileTweet-actionButton  js-actionButton js-actionRetweet"

    data-modal="ProfileTweet-retweet"
    type="button"
    aria-describedby="profile-tweet-action-retweet-count-aria-899733729945546752">
    <div class="IconContainer js-tooltip" title="Retweet">
      <span class="Icon Icon--medium Icon--retweet"></span>
      <span class="u-hiddenVisually">Retweet</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
  </span>

  </button><button class="ProfileTweet-actionButtonUndo js-actionButton js-actionRetweet" data-modal="ProfileTweet-retweet" type="button">
    <div class="IconContainer js-tooltip" title="Undo retweet">
      <span class="Icon Icon--medium Icon--retweet"></span>
      <span class="u-hiddenVisually">Retweeted</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
  </span>

  </button>
</div>


    <div class="ProfileTweet-action ProfileTweet-action--favorite js-toggleState">
  <button class="ProfileTweet-actionButton js-actionButton js-actionFavorite" type="button"
    aria-describedby="profile-tweet-action-favorite-count-aria-899733729945546752">
    <div class="IconContainer js-tooltip" title="Like">
      <span role="presentation" class="Icon Icon--heart Icon--medium"></span>
      <div class="HeartAnimation"></div>
      <span class="u-hiddenVisually">Like</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
  </span>

  </button><button class="ProfileTweet-actionButtonUndo ProfileTweet-action--unfavorite u-linkClean js-actionButton js-actionFavorite" type="button">
    <div class="IconContainer js-tooltip" title="Undo like">
      <span role="presentation" class="Icon Icon--heart Icon--medium"></span>
      <div class="HeartAnimation"></div>
      <span class="u-hiddenVisually">Liked</span>
    </div>
      <span class="ProfileTweet-actionCount ProfileTweet-actionCount--isZero">
    <span class="ProfileTweet-actionCountForPresentation" aria-hidden="true"></span>
  </span>

  </button>
</div>





  <div class="ProfileTweet-action ProfileTweet-action--analytics">
    <button class="ProfileTweet-actionButton u-textUserColorHover js-actionButton js-actionQuickPromote" type="button">
      <div class="IconContainer js-tooltip" title="View Tweet activity">
        <span class="Icon Icon--medium Icon--analytics"></span>
        <span class="u-hiddenVisually">View Tweet activity</span>
      </div>
    </button>
  </div>

  </div>

</div>







    </div>
  </div>




<div class="dismiss-module">
  <div class="dismissed-module">
      <div class="feedback-action" data-feedback-type="DontLike">
        <div class="action-confirmation">Thanks. Twitter will use this to make your timeline better. <span class="undo-action u-textUserColor">Undo</span></div>
      </div>
  </div>
</div>

</li>

















        </ol>
        <div class="stream-footer ">
  <div class="timeline-end has-items has-more-items">
      <div class="stream-end">
    <div class="stream-end-inner">
        <span class="Icon Icon--large Icon--logo"></span>

      <p class="empty-text">

          You haven&#39;t Tweeted yet.
      </p>

        <p><button type="button" class="btn-link back-to-top hidden">Back to top &uarr;</button></p>
    </div>
  </div>


    <div class="stream-loading">
  <div class="stream-end-inner">
    <span class="spinner" title="Loading..."></span>
  </div>
</div>

  </div>
</div>
<div class="stream-fail-container">
    <div class="js-stream-whale-end stream-whale-end stream-placeholder centered-placeholder">
  <div class="stream-end-inner">
    <h2 class="title">Loading seems to be taking a while.</h2>
    <p>
      Twitter may be over capacity or experiencing a momentary hiccup. <a role="button" href="#" class="try-again-after-whale">Try again</a> or visit <a target="_blank" href="http://status.twitter.com" rel="noopener">Twitter Status</a> for more information.
    </p>
  </div>
</div>
</div>

      <ol class="hidden-replies-container"></ol>
    </div>
  </div>
    </div>


              </div>

                  <div class="Grid-cell u-size1of3">
                    <div class="Grid Grid--withGutter">
                      <div class="Grid-cell">
                        <div class="ProfileSidebar ProfileSidebar--withRightAlignment">
                          <div class="MoveableModule">

<div class="SidebarCommonModules">
    <div class="TweetImpressionsModule clear">
  <div class="TweetImpressionsModule-heading">Your Tweet activity</div>
      <p>Your Tweets earned <strong>2,126 impressions</strong> over the last <strong>week</strong></p>
  <div class="TweetImpressionsModule-chartArea">
      <div class="TweetImpressionsModule-chart">
          <div class="TweetImpressionsModule-chartBar">
            <div style="height:27.158069610595703px;"></div>
            <div class="TweetImpressionsModule-promoted" style="height:0.0px;"></div>
            <div class="TweetImpressionsModule-organic" style="height:44.8419303894043px;"></div>
            <div class="TweetImpressionsModule-barchart-tooltip
                TweetImpressionsModule-barchart-tooltip-opensleft

">
              <table>
  <thead>
    <tr>
      <td colspan="3" class="TweetImpressionsModule-barchart-tooltipdate">Thursday, Aug 24</td>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td class="TweetImpressionsModule-barchart-tooltipcolor">
        <div class="TweetImpressionsModule-barchart-colorfill organic">&nbsp;</div>
      </td>
      <td class="TweetImpressionsModule-barchart-tooltipvalue">385 impressions</td>
    </tr>
  </tbody>
</table>

            </div>
          </div>
          <div class="TweetImpressionsModule-chartBar">
            <div style="height:2.0px;"></div>
            <div class="TweetImpressionsModule-promoted" style="height:0.0px;"></div>
            <div class="TweetImpressionsModule-organic" style="height:70.0px;"></div>
            <div class="TweetImpressionsModule-barchart-tooltip
                TweetImpressionsModule-barchart-tooltip-opensleft

">
              <table>
  <thead>
    <tr>
      <td colspan="3" class="TweetImpressionsModule-barchart-tooltipdate">Friday, Aug 25</td>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td class="TweetImpressionsModule-barchart-tooltipcolor">
        <div class="TweetImpressionsModule-barchart-colorfill organic">&nbsp;</div>
      </td>
      <td class="TweetImpressionsModule-barchart-tooltipvalue">601 impressions</td>
    </tr>
  </tbody>
</table>

            </div>
          </div>
          <div class="TweetImpressionsModule-chartBar">
            <div style="height:17.141429901123047px;"></div>
            <div class="TweetImpressionsModule-promoted" style="height:0.0px;"></div>
            <div class="TweetImpressionsModule-organic" style="height:54.85857009887695px;"></div>
            <div class="TweetImpressionsModule-barchart-tooltip
                TweetImpressionsModule-barchart-tooltip-opensleft

">
              <table>
  <thead>
    <tr>
      <td colspan="3" class="TweetImpressionsModule-barchart-tooltipdate">Saturday, Aug 26</td>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td class="TweetImpressionsModule-barchart-tooltipcolor">
        <div class="TweetImpressionsModule-barchart-colorfill organic">&nbsp;</div>
      </td>
      <td class="TweetImpressionsModule-barchart-tooltipvalue">471 impressions</td>
    </tr>
  </tbody>
</table>

            </div>
          </div>
          <div class="TweetImpressionsModule-chartBar">
            <div style="height:17.956741333007812px;"></div>
            <div class="TweetImpressionsModule-promoted" style="height:0.0px;"></div>
            <div class="TweetImpressionsModule-organic" style="height:54.04325866699219px;"></div>
            <div class="TweetImpressionsModule-barchart-tooltip
                TweetImpressionsModule-barchart-tooltip-opensright

                 TweetImpressionsModule-barchart-tooltip-rightAligned
">
              <table>
  <thead>
    <tr>
      <td colspan="3" class="TweetImpressionsModule-barchart-tooltipdate">Sunday, Aug 27</td>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td class="TweetImpressionsModule-barchart-tooltipcolor">
        <div class="TweetImpressionsModule-barchart-colorfill organic">&nbsp;</div>
      </td>
      <td class="TweetImpressionsModule-barchart-tooltipvalue">464 impressions</td>
    </tr>
  </tbody>
</table>

            </div>
          </div>
          <div class="TweetImpressionsModule-chartBar">
            <div style="height:58.722129821777344px;"></div>
            <div class="TweetImpressionsModule-promoted" style="height:0.0px;"></div>
            <div class="TweetImpressionsModule-organic" style="height:13.277870178222656px;"></div>
            <div class="TweetImpressionsModule-barchart-tooltip
                TweetImpressionsModule-barchart-tooltip-opensright

                 TweetImpressionsModule-barchart-tooltip-rightAligned
">
              <table>
  <thead>
    <tr>
      <td colspan="3" class="TweetImpressionsModule-barchart-tooltipdate">Monday, Aug 28</td>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td class="TweetImpressionsModule-barchart-tooltipcolor">
        <div class="TweetImpressionsModule-barchart-colorfill organic">&nbsp;</div>
      </td>
      <td class="TweetImpressionsModule-barchart-tooltipvalue">114 impressions</td>
    </tr>
  </tbody>
</table>

            </div>
          </div>
          <div class="TweetImpressionsModule-chartBar">
            <div style="height:61.40099906921387px;"></div>
            <div class="TweetImpressionsModule-promoted" style="height:0.0px;"></div>
            <div class="TweetImpressionsModule-organic" style="height:10.599000930786133px;"></div>
            <div class="TweetImpressionsModule-barchart-tooltip
                TweetImpressionsModule-barchart-tooltip-opensright

                 TweetImpressionsModule-barchart-tooltip-rightAligned
">
              <table>
  <thead>
    <tr>
      <td colspan="3" class="TweetImpressionsModule-barchart-tooltipdate">Tuesday, Aug 29</td>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td class="TweetImpressionsModule-barchart-tooltipcolor">
        <div class="TweetImpressionsModule-barchart-colorfill organic">&nbsp;</div>
      </td>
      <td class="TweetImpressionsModule-barchart-tooltipvalue">91 impressions</td>
    </tr>
  </tbody>
</table>

            </div>
          </div>
          <div class="TweetImpressionsModule-chartBar">
            <div style="height:72.0px;"></div>
            <div class="TweetImpressionsModule-promoted" style="height:0.0px;"></div>
            <div class="TweetImpressionsModule-organic" style="height:0.0px;"></div>
            <div class="TweetImpressionsModule-barchart-tooltip
                TweetImpressionsModule-barchart-tooltip-opensright

                 TweetImpressionsModule-barchart-tooltip-rightAligned
">
              <table>
  <thead>
    <tr>
      <td colspan="3" class="TweetImpressionsModule-barchart-tooltipdate">Wednesday, Aug 30</td>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td class="TweetImpressionsModule-barchart-tooltipcolor">
        <div class="TweetImpressionsModule-barchart-colorfill organic">&nbsp;</div>
      </td>
      <td class="TweetImpressionsModule-barchart-tooltipvalue">0 impressions</td>
    </tr>
  </tbody>
</table>

            </div>
          </div>
      </div>
      <div class="TweetImpressionsModule-xAxis"></div>
      <div class="TweetImpressionsModule-text">
        <div class="u-floatLeft">Aug 24</div>
        <div class="u-floatRight">Aug 30</div>
      </div>
  </div>
  <div class="TweetImpressionsModule-analyticsLink"><a href="//analytics.twitter.com/user/mohammed_attya/tweets?filter=top&amp;origin=im&amp;ref=gl-an-tw-im-desktop" target="_blank">View your top Tweets</a></div>
</div>




        <div class="module wtf-module js-wtf-module roaming-module"
  >
  <div class="flex-module">
    <div class="flex-module-header">
      <h3>Who to follow</h3>
      <small>&middot; </small>
      <button type="button" class="btn-link js-refresh-suggestions"><small>Refresh</small></button>
      <small class="view-all">&middot; <a class="js-view-all-link js-nav" href="/who_to_follow/suggestions" data-element-term="view_all_link">View all</a></small>
    </div>

    <div class="js-recommended-followers dashboard-user-recommendations flex-module-inner" data-section-id="wtf">
    </div>
  </div>


    <div class="flex-module import-prompt">
      <div class="flex-module-footer u-table">
        <a href="/who_to_follow/import" class="js-tooltip u-tableCell u-alignMiddle" title="Find people you know">
          <span class="Icon Icon--people Icon--small"></span><span class="u-hiddenVisually">Find people you know</span>
        </a>
        <a class="u-tableCell u-alignMiddle remove-discover-pymk" href="/who_to_follow/import">Find people you know</a>
      </div>
  </div>

</div>


    <div class="module Trends trends hidden">
  <div class="trends-inner">
    <div class="flex-module trends-container ">
  <div class="flex-module-header">

    <h3><span class="trend-location js-trend-location">false</span></h3>
  </div>
  <div class="flex-module-inner">
    <ul class="trend-items js-trends">
    </ul>
  </div>
</div>

  </div>
</div>


  <div class="Footer module roaming-module Footer--slim Footer--blankBackground"
  >
  <div class="flex-module">
    <div class="flex-module-inner js-items-container">
      <ul class="u-cf">
        <li class="Footer-item Footer-copyright copyright">&copy; 2017 Twitter</li>
        <li class="Footer-item"><a class="Footer-link" href="/about" rel="noopener">About</a></li>
        <li class="Footer-item"><a class="Footer-link" href="//support.twitter.com" rel="noopener">Help Center</a></li>
        <li class="Footer-item"><a class="Footer-link" href="/tos" rel="noopener">Terms</a></li>
        <li class="Footer-item"><a class="Footer-link" href="/privacy" rel="noopener">Privacy policy</a></li>
        <li class="Footer-item"><a class="Footer-link" href="//support.twitter.com/articles/20170514" rel="noopener">Cookies</a></li>
        <li class="Footer-item"><a class="Footer-link" href="//support.twitter.com/articles/20170451" rel="noopener">Ads info</a></li>
      </ul>
    </div>
  </div>

</div>
</div>

                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

            </div>
          </div>

        </div>
      </div>
    </div>

    <div id="trends_dialog" class="trends-dialog modal-container">
  <div class="modal draggable">
    <div class="modal-content">
      <button type="button" class="modal-btn modal-close js-close">
  <span class="Icon Icon--close Icon--medium">
    <span class="visuallyhidden">Close</span>
  </span>
</button>

      <div class="modal-header">
          <h3 class="modal-title">
            Trends

          </h3>
      </div>

      <div class="modal-body">

          <div class="trends-personalized content-placeholder">
  <h2 class="title">Trends tailored just for you.</h2>
  <p>Trends offer a unique way to get closer to what you care about. They are tailored for you based on your location and who you follow.</p>
  <p class="placeholder-actions">
    <button type="button" class="EdgeButton EdgeButton--secondary customize-by-location">Change</button><button type="button" class="EdgeButton EdgeButton--primary done">Keep tailored Trends</button>
  </p>
</div>

        <div class="trends-dialog-error">
          <p></p>
        </div>

        <div class="trends-wrapper" id="trends_dialog_content">

          <div class="loading">
            <span class="spinner-bigger"></span>
          </div>
        </div>
      </div>
        <div class="modal-footer trends-by-location">
            <button type="button" class="EdgeButton EdgeButton--secondary select-default" data-personalized="true">Get tailored Trends</button>
<button type="button" class="EdgeButton EdgeButton--primary done">Done</button>

        </div>
    </div>
  </div>
</div>

        <div id="avatar_confirm_remove_dialog" class="modal-container profile-edit-message hidden">
  <div class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Remove photo?</h3>
      </div>
      <div class="modal-body">
        <p class="profile-message">Your profile photo helps people identify you.<br>Do you really want to remove it?</p>
      </div>
      <div class="modal-footer">
        <button class="EdgeButton EdgeButton--primary ok-btn">Remove photo</button>
        <button class="EdgeButton EdgeButton--tertiary cancel-btn js-close">Cancel</button>
      </div>
    </div>
  </div>
</div>
        <div id="header_confirm_remove_dialog" class="modal-container profile-edit-message hidden">
  <div class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Remove header photo?</h3>
      </div>
      <div class="modal-body">
        <p class="profile-message">Your header photo makes your profile more personal.<br>Do you really want to remove it?</p>
      </div>
      <div class="modal-footer">
        <button class="EdgeButton EdgeButton--primary ok-btn">Remove header</button>
        <button class="EdgeButton EdgeButton--tertiary cancel-btn js-close">Cancel</button>
      </div>
    </div>
  </div>
</div>

<div id="profile_image_upload_dialog" class="modal-container image-upload-dialog">
  <div class="modal draggable profile-avatar-modal">
    <div class="modal-content">
      <button type="button" class="modal-btn modal-close js-close">
  <span class="Icon Icon--close Icon--medium">
    <span class="visuallyhidden">Close</span>
  </span>
</button>

      <div class="modal-header">
        <h3 class="modal-title">Position and size your photo</h3>
      </div>
      <div class="modal-body">
        <div class="upload-frame image-upload-webcam" style="display: none;">
          <div class="webcam-container"></div>
          <canvas class="webcam-canvas" height="240" width="320" style="display:none"></canvas>
          <video autoplay></video>
          <div class="video-overlay">
            <div class="u-textCenter video-countdown"></div>
          </div>
          <div class="video-permission-explain" style="display:none">
            <h4>Starting webcam...</h4>
            <br/>
              Choose "accept" or "allow" in your browser to continue taking a photo for your Twitter profile.
          </div>
          <div class="video-permission-fail" style="display:none">
            Uh oh...it looks like you don"t have a webcam or we were unable to get permission to use your webcam. Please close this window and upload a photo instead.
          </div>
        </div>
        <div class="upload-frame clearfix image-upload-crop" style="display: none">
          <span class="spinner-bigger crop-spinner" style="display: none"></span>
          <div class="crop-zone cropper-avatar-size">
            <div class="cropper-mask">
              <div class="cropper-overlay"></div>
              <img class="crop-image" alt="Mohammed Attya">
            </div>
            <div class="cropper-slider-outer">
              <span class="Icon Icon--imageCrop Icon--small u-alignBottom u-colorDeepGray"></span><div class="cropper-slider"></div><span class="Icon Icon--imageCrop Icon--large u-alignBottom u-colorDeepGray"></span>
            </div>
          </div>
        </div>
        <div style="display: none;">
          <canvas class="drawsurface"></canvas>
        </div>
        <div class="upload-frame clearfix image-upload-spinner"></div>
      </div>

      <div class="modal-footer">
        <button type="button" class="EdgeButton EdgeButton--tertiary profile-image-cancel js-close">Cancel</button>
        <button type="button" class="EdgeButton EdgeButton--secondary profile-image-previous" style="display: none">Retake Photo</button>
        <button type="button" class="EdgeButton EdgeButton--primary profile-image-capture-webcam" disabled style="display: none">Take picture</button>
        <button type="button" class="EdgeButton EdgeButton--primary profile-image-save" style="display: none">Apply</button>
      </div>
    </div>
  </div>
</div>


          </div>
        </div>
    </div>
    <div class="alert-messages hidden" id="message-drawer">
    <div class="message ">
  <div class="message-inside">
    <span class="message-text"></span>
      <a role="button" class="Icon Icon--close Icon--medium dismiss" href="#">
        <span class="visuallyhidden">Dismiss</span>
      </a>
  </div>
</div>
</div>




<div class="gallery-overlay"></div>
<div class="Gallery with-tweet">
  <style class="Gallery-styles"></style>
  <div class="Gallery-closeTarget"></div>
  <div class="Gallery-content">
    <button type="button" class="modal-btn modal-close modal-close-fixed js-close">
  <span class="Icon Icon--close Icon--large">
    <span class="visuallyhidden">Close</span>
  </span>
</button>

    <div class="Gallery-media"></div>
    <div class="GalleryNav GalleryNav--prev">
      <span class="GalleryNav-handle GalleryNav-handle--prev">
        <span class="Icon Icon--caretLeft Icon--large">
          <span class="u-hiddenVisually">
            Previous
          </span>
        </span>
      </span>
    </div>
    <div class="GalleryNav GalleryNav--next">
      <span class="GalleryNav-handle GalleryNav-handle--next">
        <span class="Icon Icon--caretRight Icon--large">
          <span class="u-hiddenVisually">
            Next
          </span>
        </span>
      </span>
    </div>
    <div class="GalleryTweet with-hover"></div>
  </div>
</div>



<div class="modal-overlay"></div>

<div id="profile-hover-container"></div>

  <div id="dm_dialog" class="DMDialog modal-container" style="display: none;">
  <div class="modal is-autoPosition">
    <div class="DMActivity DMActivity--open DMInbox js-ariaDocument u-chromeOverflowFix">
  <div class="DMActivity-header">

    <h2 class="DMActivity-title js-ariaTitle">
      Direct Messages
    </h2>

    <div class="DMActivity-toolbar">
          <button type="button" class="DMInbox-toolbar EdgeButton EdgeButton--small EdgeButton--secondary EdgeButton--icon mark-all-read js-tooltip" title="Mark all as read">
      <span class="Icon Icon--markAllRead"></span>
      <span class="u-hiddenVisually">Mark all as read</span>
    </button>
    <button type="button" class="DMInbox-toolbar DMComposeButton EdgeButton EdgeButton--small EdgeButton--primary dm-new-button js-initial-focus">
      <span>New Message</span>
    </button>

      <button type="button" class="DMActivity-close js-close u-textUserColorHover">
        <span class="Icon Icon--close Icon--medium"></span>
        <span class="u-hiddenVisually">Close</span>
      </button>
    </div>
  </div>

  <div class="DMActivity-container">
    <div class="DMActivity-notice">
      <div class="DMNotice DMNotice--error DMErrorBar" style="display: none;">
  <div class="DMNotice-message">    <div class="DMErrorBar-text"></div>
</div>
  <div class="DMNotice-actions u-emptyHide"></div>
    <button type="button" class="DMNotice-dismiss">
      <span class="Icon Icon--close"></span>
      <span class="u-hiddenVisually">Dismiss</span>
    </button>
</div>

      <div class="DMNotice DMNotice--toast " style="display: none;">
  <div class="DMNotice-message"></div>
  <div class="DMNotice-actions u-emptyHide"></div>
    <button type="button" class="DMNotice-dismiss">
      <span class="Icon Icon--close"></span>
      <span class="u-hiddenVisually">Dismiss</span>
    </button>
</div>

          <div class="DMNotice DMNotice--explicitDismiss DMNotificationsPermissionRequest" style="display: none;">
  <div class="DMNotice-message">    Would you like to receive browser notifications such as Messages, Follows, and Likes?
</div>
  <div class="DMNotice-actions u-emptyHide">    <button type="button" class="DMNotificationsPermissionRequest-later EdgeButton EdgeButton--tertiary js-prompt-later">Maybe later</button>
    <button type="button" class="DMNotificationsPermissionRequest-accept EdgeButton EdgeButton--secondary js-prompt-accept">Enable notifications</button>
</div>
</div>


    </div>

    <div class="DMActivity-body js-ariaBody ">
          <div class="DMInbox-content u-scrollY">
      <div class="DMInbox-tab u-hidden">
  <ul class="DMInbox-tabToggle">
    <h3>
      <li class="DMInbox-tabToggleItem DMInbox-inboxTab is-active">
        <a role="button" class="DMInbox-tabCopy">Inbox</a>
      </li>
      <li class="DMInbox-tabToggleItem DMInbox-requestTab">
        <a role="button" class="DMInbox-tabCopy">Requests</a>
      </li>
    </h3>
  </ul>
</div>
      <div class="DMInbox-primary">
        <ul class="DMInbox-conversations"></ul>
      </div>
      <div class="DMInbox-secondary u-hidden">
        <div class="DMInbox-secondaryInboxCopy">
          This is where you"ll see messages from people you don"t follow. They won"t know you"ve seen them until you accept it.
        </div>
        <ul class="DMInbox-untrustedConversations"></ul>
      </div>
      <div class="DMInbox-spinner">
        <div class="DMSpinner"></div>
      </div>
      <div class="DMInbox-empty">
        <div class="DMEmptyState">
  <h2 class="DMEmptyState-header">
    Send a message, get a message
  </h2>

  <div class="DMEmptyState-details">
    <p>Direct Messages are private conversations between you and other people on Twitter. Share Tweets, media, and more!</p>
  </div>

  <div class="DMEmptyState-cta">
    <button type="button" class="EdgeButton EdgeButton--primary dm-new-button">
      Start a conversation
    </button>
  </div>
</div>

      </div>
    </div>

    </div>

    <div class="DMActivity-footer u-emptyHide"></div>
  </div>
</div>


    <div class="DMDock">
      <div class="DMDock-compose">
        <div class="DMActivity DMCompose js-ariaDocument u-chromeOverflowFix">
  <div class="DMActivity-header">
      <div class="DMActivity-navigation">
        <button type="button" class="DMActivity-back u-textUserColorHover" to-inbox>
          <span class="Icon Icon--caretLeft u-linkComplex-target Icon--medium"></span>
          <span class="u-hiddenVisually">Back to inbox</span>
        </button>
      </div>

    <h2 class="DMActivity-title js-ariaTitle">
          New Message

    </h2>

    <div class="DMActivity-toolbar">

      <button type="button" class="DMActivity-close js-close u-textUserColorHover">
        <span class="Icon Icon--close Icon--medium"></span>
        <span class="u-hiddenVisually">Close</span>
      </button>
    </div>
  </div>

  <div class="DMActivity-container">
    <div class="DMActivity-notice">
      <div class="DMNotice DMNotice--error DMErrorBar" style="display: none;">
  <div class="DMNotice-message">    <div class="DMErrorBar-text"></div>
</div>
  <div class="DMNotice-actions u-emptyHide"></div>
    <button type="button" class="DMNotice-dismiss">
      <span class="Icon Icon--close"></span>
      <span class="u-hiddenVisually">Dismiss</span>
    </button>
</div>

      <div class="DMNotice DMNotice--toast " style="display: none;">
  <div class="DMNotice-message"></div>
  <div class="DMNotice-actions u-emptyHide"></div>
    <button type="button" class="DMNotice-dismiss">
      <span class="Icon Icon--close"></span>
      <span class="u-hiddenVisually">Dismiss</span>
    </button>
</div>


    </div>

    <div class="DMActivity-body js-ariaBody ">
          <div class=" DMDialogTypeahead">
  <span class="DMTypeaheadHeader">Send message to:</span>
  <ul class="TokenizedMultiselect-inputContainer">
    <li>
      <textarea class="TokenizedMultiselect-input twttr-directmessage-input js-initial-focus dm-to-input" aria-autocomplete="list" aria-expanded="true" rows="1" type="text" placeholder="Enter a name"></textarea>
    </li>
  </ul>
  <ul id="DMComposeTypeaheadSuggestions" class="DMTypeaheadSuggestions u-scrollY" role="listbox"></ul>
</div>

    </div>

    <div class="DMActivity-footer u-emptyHide">    <div class="DMButtonBar">
      <button type="button" class="EdgeButton EdgeButton--primary dm-initiate-conversation">Next</button>
    </div>
</div>
  </div>
</div>

      </div>

      <div class="DMDock-conversations">
        <div class="DMConversationContainer">
          <div class="DMActivity DMConversation js-ariaDocument u-chromeOverflowFix">
  <div class="DMActivity-header">
      <div class="DMActivity-navigation">
        <button type="button" class="DMActivity-back u-textUserColorHover" to-inbox>
          <span class="Icon Icon--caretLeft u-linkComplex-target Icon--medium"></span>
          <span class="u-hiddenVisually">Back to inbox</span>
        </button>
      </div>

    <h2 class="DMActivity-title js-ariaTitle">
          <div class="DMUpdateAvatar" aria-haspopup="true" data-has-custom-avatar="false">
<div class="DMPopover DMPopover--center">
  <button class="DMPopover-button" aria-haspopup="true">
          <span class="u-hiddenVisually">Update group photo.</span>
      <div class="DMUpdateAvatar-avatar"></div>

  </button>
  <div class="DMPopover-content Caret Caret--top Caret--stroked ">
          <ul class="DMPopoverMenu u-textCenter js-focus-on-open u-dropdownUserColor" tabindex="-1" role="menu">
        <li class="DMUpdateAvatar-view">
          <button type="button" class="DMPopoverMenu-button">View photo</button>
        </li>
        <li class="DMUpdateAvatar-change">
          <button type="button" class="DMPopoverMenu-button">Upload photo</button>
        </li>
        <li class="DMUpdateAvatar-remove">
          <button type="button" class="DMPopoverMenu-button">Remove</button>
        </li>
      </ul>

      <div class="DMUpdateAvatar-photoSelector photo-selector" tabindex="-1" aria-hidden="true">
        <div class="image-selector">
          <input type="hidden" name="media_file_name" class="file-name">
          <input type="hidden" name="media_data_empty" class="file-data">
          <label class="t1-label">
            <span class="u-hiddenVisually">Add Photo</span>
            <input type="file" name="media_empty" class="file-input js-tooltip" accept="image/*" tabindex="-1" title="Add Photo">
          </label>
        </div>
      </div>

  </div>
</div></div>
    <div class="DMUpdateName u-textTruncate">
  <div class="DMUpdateName-header account-group">
    <span class="DMUpdateName-name u-textTruncate"></span><span class="UserBadges"><span class="Icon Icon--verified js-verified hidden"><span class="u-hiddenVisually">Verified account</span></span><span class="Icon Icon--protected js-protected hidden"><span class="u-hiddenVisually">Protected Tweets</span></span></span>
  </div>
  <div class="DMUpdateName-screenName u-textTruncate"></div>
  <div class="DMUpdateName-controls">
    <span class="DMUpdateName-spinner DMSpinner"></span>
    <div class="DMUpdateName-form input-group u-hidden">
      <input type="text" class="DMUpdateName-input" aria-label="Edit group name" />
      <button class="DMUpdateName-confirm u-textUserColorLight">
        <span class="Icon Icon--check"></span>
        <span class="u-hiddenVisually">Save group name</span>
      </button>
    </div>
  </div>
</div>


    </h2>

    <div class="DMActivity-toolbar">
            <button class="DMConversation-convoSettings dm-to-convoSettings u-textUserColorHover" title="Manage subscriptions">
        <span class="Icon Icon--info Icon--medium"></span>
        <span class="u-hiddenVisually">Conversation Settings</span>
      </button>


      <button type="button" class="DMActivity-close js-close u-textUserColorHover">
        <span class="Icon Icon--close Icon--medium"></span>
        <span class="u-hiddenVisually">Close</span>
      </button>
    </div>
  </div>

  <div class="DMActivity-container">
    <div class="DMActivity-notice">
      <div class="DMNotice DMNotice--error DMErrorBar" style="display: none;">
  <div class="DMNotice-message">    <div class="DMErrorBar-text"></div>
</div>
  <div class="DMNotice-actions u-emptyHide"></div>
    <button type="button" class="DMNotice-dismiss">
      <span class="Icon Icon--close"></span>
      <span class="u-hiddenVisually">Dismiss</span>
    </button>
</div>

      <div class="DMNotice DMNotice--toast " style="display: none;">
  <div class="DMNotice-message"></div>
  <div class="DMNotice-actions u-emptyHide"></div>
    <button type="button" class="DMNotice-dismiss">
      <span class="Icon Icon--close"></span>
      <span class="u-hiddenVisually">Dismiss</span>
    </button>
</div>

          <div class="DMNotice  DMDeleteMessage" style="display: none;">
  <div class="DMNotice-message">    Are you sure you want to delete this message?
</div>
  <div class="DMNotice-actions u-emptyHide">    <button type="button" class="DMDeleteMessage-cancel EdgeButton EdgeButton--tertiary">Cancel</button>
    <button type="button" class="DMDeleteMessage-confirm EdgeButton EdgeButton--danger js-initial-focus">Delete</button>
</div>
</div>

    <div class="DMNotice  DMReportMessage" style="display: none;">
  <div class="DMNotice-message">    The message will be deleted from your conversation view.
</div>
  <div class="DMNotice-actions u-emptyHide">    <button type="button" class="DMReportMessage-cancel EdgeButton EdgeButton--tertiary">Cancel</button>
    <button type="button" class="DMReportMessage-spam EdgeButton EdgeButton--secondary">It"s spam</button>
    <button type="button" class="DMReportMessage-abuse EdgeButton EdgeButton--secondary">It"s abusive</button>
</div>
</div>

    <div class="DMNotice  DMResendMessage DMNotice--error" style="display: none;">
  <div class="DMNotice-message">    <div class="DMResendMessage-errorText">
      <div class="DMResendMessage-defaultErrorMessage">
        Failed to send this message
      </div>
      <div class="DMResendMessage-customErrorMessage"></div>
    </div>
    <div class="DMResendMessage-messageTextContainer u-textTruncate">
      Message text: <span class="DMResendMessage-messageText"></span>
    </div>
    <textarea aria-hidden="true" class="DMResendMessage-messageCopyContainer visuallyhidden"></textarea>
</div>
  <div class="DMNotice-actions u-emptyHide">    <button type="button" class="DMResendMessage-cancel EdgeButton EdgeButton--tertiary">Discard</button>
    <button type="button" class="DMResendMessage-copy EdgeButton EdgeButton--secondary">Copy message text</button>
    <button type="button" class="DMResendMessage-confirm js-initial-focus EdgeButton EdgeButton--secondary">Retry</button>
</div>
    <button type="button" class="DMNotice-dismiss">
      <span class="Icon Icon--close"></span>
      <span class="u-hiddenVisually">Dismiss</span>
    </button>
</div>



    </div>

    <div class="DMActivity-body js-ariaBody DMConversation-container">
          <div class="DMConversation-newMessagesPillContainer u-table">
      <div class="DMConversation-newMessagesPill is-hidden">
        <span class="Icon Icon--arrowDown"></span> New messages
      </div>
    </div>
    <div class="DMConversation-scrollContainer js-modal-scrollable">
      <span class="DMConversation-spinner DMSpinner"></span>
      <div class="DMConversation-content dm-convo js-dm-conversation"></div>
      <div class="DMConversation-typingIndicator u-hidden"><div class="DMTypingIndicator">
  <div class="DMTypingIndicator-avatarsContainer"></div>
  <div class="DMTypingIndicator-messageBubble">
    <div class="TypingIndicatorMessageBubble is-hidden Caret Caret--left">
  <div class="TypingIndicatorMessageBubble-dotContainer">
    <div class="TypingIndicatorMessageBubble-dot"></div>
    <div class="TypingIndicatorMessageBubble-dot"></div>
    <div class="TypingIndicatorMessageBubble-dot"></div>
  </div>
</div>
  </div>
</div></div>
    </div>

    </div>

    <div class="DMActivity-footer u-emptyHide">    <div class="DMConversation-sendingStateIndicator u-bgUserColorLightest u-textUserColorLight" style="display: none"></div>


    <div class="DMConversation-trustRequest u-hidden"></div>

    <div class="DMConversation-composer u-bgUserColorLightest">
        <form class="DMComposer tweet-form" target="dm-post-iframe" action="//upload.twitter.com/i/media/upload.iframe" method="post" enctype="multipart/form-data">
  <input type="hidden" name="authenticity_token" class="auth-token">

  <div class="DMComposer-container u-borderUserColorLighter">
    <div class="DMComposer-attachment">
      <div class="DMComposer-tweet">
        <div class="modal-tweet"></div>
<button class="DMComposer-discardTweet">
  <span class="Icon Icon--close"></span>
  <span class="u-hiddenVisually">Remove Tweet attachment</span>
</button>

      </div>

      <div class="DMComposer-media">
        <div class="thumbnail-container">
  <div class="thumbnail-wrapper">
    <div class="ComposerThumbnails"></div>
    <div class="preview-message">
      <button type="button" class="start-tagging js-open-user-select no-users u-borderUserColorLight u-textUserColor" disabled>
        <span class="Icon Icon--me Icon--small"></span>
        <span class="tagged-users">
          Who"s in these photos?
        </span>
      </button>
    </div>
    <div class="js-attribution attribution"></div>
    <div class="ComposerVideoInfo u-hidden"></div>
  </div>
</div>
<div class="photo-tagging-container user-select-container dropdown-menu hidden">
  <div class="tagging-dropdown">
    <div class="dropdown-caret center">
      <div class="caret-outer"></div>
      <div class="caret-inner"></div>
    </div>
    <div class="photo-tagging-controls user-select-controls">
      <label class="t1-label">
        <span class="Icon Icon--search nav-search"></span>
        <span class="u-hiddenVisually">Users in this photo</span>
        <input class="js-initial-focus" type="text" placeholder="Search and tag up to 10 people">
      </label>
    </div>
    <div class="typeahead-container">



<div role="listbox" class="dropdown-menu typeahead">
  <div aria-hidden="true" class="dropdown-caret">
    <div class="caret-outer"></div>
    <div class="caret-inner"></div>
  </div>
  <div role="presentation" class="dropdown-inner js-typeahead-results">
      <div role="presentation" class="typeahead-recent-searches">
  <h3 id="recent-searches-heading" class="typeahead-category-title recent-searches-title">Recent searches</h3><button type="button" tabindex="-1" class="btn-link clear-recent-searches">Clear All</button>
  <ul role="presentation" class="typeahead-items recent-searches-list">

    <li role="presentation" class="typeahead-item typeahead-recent-search-item">
      <span class="Icon Icon--close" aria-hidden="true"><span class="visuallyhidden">Remove</span></span>
      <a role="option" aria-describedby="recent-searches-heading" class="js-nav" href="" data-search-query="" data-query-source="" data-ds="recent_search" tabindex="-1"></a>
    </li>
  </ul>
</div>

    <div role="presentation" class="typeahead-saved-searches">
  <h3 id="saved-searches-heading" class="typeahead-category-title saved-searches-title">Saved searches</h3>
  <ul role="presentation" class="typeahead-items saved-searches-list">

    <li role="presentation" class="typeahead-item typeahead-saved-search-item">
      <span class="Icon Icon--close" aria-hidden="true"><span class="visuallyhidden">Remove</span></span>
      <a role="option" aria-describedby="saved-searches-heading" class="js-nav" href="" data-search-query="" data-query-source="" data-ds="saved_search" tabindex="-1"></a>
    </li>
  </ul>
</div>

    <ul role="presentation" class="typeahead-items typeahead-topics">

  <li role="presentation" class="typeahead-item typeahead-topic-item">
    <a role="option" class="js-nav" href="" data-search-query="" data-query-source="typeahead_click" data-ds="topics" tabindex="-1"></a>
  </li>
</ul>
    <ul role="presentation" class="typeahead-items typeahead-accounts social-context js-typeahead-accounts">

  <li role="presentation" data-user-id="" data-user-screenname="" data-remote="true" data-score="" class="typeahead-item typeahead-account-item js-selectable">

    <a role="option" class="js-nav" data-query-source="typeahead_click" data-search-query="" data-ds="account">
      <div class="js-selectable typeahead-in-conversation hidden">
        <span class="Icon Icon--follower Icon--small"></span>
        <span class="typeahead-in-conversation-text">In this conversation</span>
      </div>
      <img class="avatar size32" alt="">
      <span class="typeahead-user-item-info account-group">
        <span class="fullname"></span><span class="UserBadges"><span class="Icon Icon--verified js-verified hidden"><span class="u-hiddenVisually">Verified account</span></span><span class="Icon Icon--protected js-protected hidden"><span class="u-hiddenVisually">Protected Tweets</span></span></span><span class="UserNameBreak">&nbsp;</span><span class="username u-dir" dir="ltr">@<b></b></span>
      </span>
      <span class="typeahead-social-context"></span>
    </a>
  </li>
  <li role="presentation" class="js-selectable typeahead-accounts-shortcut js-shortcut"><a role="option" class="js-nav" href="" data-search-query="" data-query-source="typeahead_click" data-shortcut="true" data-ds="account_search"></a></li>
</ul>

    <ul role="presentation" class="typeahead-items typeahead-trend-locations-list">

  <li role="presentation" class="typeahead-item typeahead-trend-locations-item"><a role="option" class="js-nav" href="" data-ds="trend_location" data-search-query="" tabindex="-1"></a></li>
</ul>

<div role="presentation" class="typeahead-user-select">
  <div role="presentation" class="typeahead-empty-suggestions">
    Suggested users
  </div>
  <ul role="presentation" class="typeahead-items typeahead-selected js-typeahead-selected">

    <li role="presentation" data-user-id="" data-user-screenname="" data-remote="true" data-score="" class="typeahead-item typeahead-selected-item js-selectable">

      <a role="option" class="js-nav" data-query-source="typeahead_click" data-search-query="" data-ds="account">
        <img class="avatar size32" alt="">
        <span class="typeahead-user-item-info account-group">
          <span class="select-status deselect-user js-deselect-user Icon Icon--check"></span>
          <span class="select-status select-disabled Icon Icon--unfollow"></span>
          <span class="fullname"></span><span class="UserBadges"><span class="Icon Icon--verified js-verified hidden"><span class="u-hiddenVisually">Verified account</span></span><span class="Icon Icon--protected js-protected hidden"><span class="u-hiddenVisually">Protected Tweets</span></span></span><span class="UserNameBreak">&nbsp;</span><span class="username u-dir" dir="ltr">@<b></b></span>
        </span>
      </a>
    </li>
    <li role="presentation" class="typeahead-selected-end"></li>
  </ul>

  <ul role="presentation" class="typeahead-items typeahead-accounts js-typeahead-accounts">

    <li role="presentation" data-user-id="" data-user-screenname="" data-remote="true" data-score="" class="typeahead-item typeahead-account-item js-selectable">

      <a role="option" class="js-nav" data-query-source="typeahead_click" data-search-query="" data-ds="account">
        <img class="avatar size32" alt="">
        <span class="typeahead-user-item-info account-group">
          <span class="select-status deselect-user js-deselect-user Icon Icon--check"></span>
          <span class="select-status select-disabled Icon Icon--unfollow"></span>
          <span class="fullname"></span><span class="UserBadges"><span class="Icon Icon--verified js-verified hidden"><span class="u-hiddenVisually">Verified account</span></span><span class="Icon Icon--protected js-protected hidden"><span class="u-hiddenVisually">Protected Tweets</span></span></span><span class="UserNameBreak">&nbsp;</span><span class="username u-dir" dir="ltr">@<b></b></span>
        </span>
      </a>
    </li>
    <li role="presentation" class="typeahead-accounts-end"></li>
  </ul>
</div>

    <div role="presentation" class="typeahead-dm-conversations">
  <ul role="presentation" class="typeahead-items typeahead-dm-conversation-items">
    <li role="presentation" class="typeahead-item typeahead-dm-conversation-item">
      <a role="option" tabindex="-1"></a>
    </li>
  </ul>
</div>
  </div>
</div>

    </div>
  </div>
</div>

      </div>
    </div>

    <div class="ComposerDragHelp">
  <span class="ComposerDragHelp-text"></span>
</div>

<div class="RichEditor RichEditor--emojiPicker ">

  <div class="RichEditor-mozillaCursorWorkaround">&nbsp;</div>
  <div class="RichEditor-container u-borderRadiusInherit">

    <div class="RichEditor-scrollContainer u-borderRadiusInherit">
              <div class="DMComposer-editor tweet-box rich-editor js-initial-focus is-showPlaceholder" data-default-placeholder="Start a new message" data-attachment-placeholder="Add a comment..." data-from-message-me-card-placeholder="Send a private message" id="tweet-box-dm-conversation" aria-label="Direct message text" contenteditable="true" spellcheck="true" role="textbox" aria-multiline="false"></div>

      <div class="RichEditor-pictographs" aria-hidden="true"></div>
    </div>

            <div class="RichEditor-rightItems RichEditor-bottomItems">
          <div class="EmojiPicker dropdown is-loading">
  <button type="button" class="EmojiPicker-trigger js-dropdown-toggle js-tooltip u-textUserColorHover"
      title="Add emoji" data-delay="150">
    <span class="Icon Icon--smiley"></span>
    <span class="text u-hiddenVisually">
      Add emoji
    </span>
  </button>
  <div class="EmojiPicker-dropdownMenu dropdown-menu" tabindex="-1">
    <div class="EmojiPicker-content Caret Caret--stroked"></div>
  </div>
</div>

        </div>

  </div>
  <div class="RichEditor-mozillaCursorWorkaround">&nbsp;</div>
</div>
  </div>

  <div class="TweetBoxExtras">
    <div class="DMComposer-gifSearch TweetBoxExtras-item">
      <div class="FoundMediaSearch found-media-search dropdown">
  <button class="btn js-found-media-search-trigger js-dropdown-toggle icon-btn js-tooltip" type="button"
      title="Add a GIF" data-delay="150">
    <span class="Icon Icon--gif Icon--large"></span>
    <span class="text u-hiddenVisually">
      Add a GIF
    </span>
  </button>
  <div class="FoundMediaSearch-dropdownMenu dropdown-menu" tabindex="-1">
    <div class="FoundMediaSearch-content Caret Caret--stroked">
      <div class="FoundMediaSearch-query">
        <input class="FoundMediaSearch-queryInput" type="text" autocomplete="off" placeholder="Search for a GIF">
        <span class="Icon Icon--search"></span>
      </div>
      <div class="FoundMediaSearch-results">
        <div class="FoundMediaSearch-items"></div>
        <div class="FoundMediaSearch-pagination"></div>
      </div>
    </div>
  </div>
</div>

    </div>

    <div class="DMComposer-mediaPicker TweetBoxExtras-item">
      <div class="photo-selector">
  <button aria-hidden="true" class="btn icon-btn js-tooltip" type="button" tabindex="-1" data-original-title="Add photos or video">
      <span class="tweet-camera Icon Icon--media"></span>
    <span class="text add-photo-label u-hiddenVisually">Add photos or video</span>
  </button>
  <div class="image-selector">
    <input type="hidden" name="media_data_empty" class="file-data">
    <div class="multi-photo-data-container hidden">
    </div>
    <label class="t1-label">
      <span class="visuallyhidden">Add photos or video</span>
      <input type="file" name="media_empty" accept="image/gif,image/jpeg,image/jpg,image/png" multiple
          class="file-input js-tooltip" data-original-title="Add photos or video" data-delay="150">
    </label>
  </div>
</div>

    </div>
    <div class="DMComposer-quickReplyDismiss"><div class="QuickReplyDismiss" aria-hidden="true">
  <button type="button" class="QuickReplyDismiss-icon btn icon-btn">
    <span class="Icon Icon--close"></span>
    <span class="u-hiddenVisually">Close</span>
  </button>
</div>
</div>
  </div>

  <div class="DMComposer-send">
    <button class="EdgeButton EdgeButton--primary tweet-action disabled" type="button">
      <span class="button-text messaging-text">Send</span>
    </button>
  </div>
</form>

      <div class="DMConversation-quickReply"></div>
    </div>

    <div class="DMConversation-readonly">
      <div class="DMConversation-readOnlyFooter">
        You can no longer send messages to this person. <a href="https://support.twitter.com/articles/14606#faq" target="_blank" class="learn-more" rel="noopener">Learn more</a>
      </div>
    </div>
    <div class="DMConversation-feedback">
      <div class="DMFeedback">
  <button type="button" class="DMFeedback-dismiss">
    <span class="Icon Icon--close"></span>
    <span class="u-hiddenVisually">Dismiss</span>
  </button>
  <iframe
    class="B2CFeedback"
    data-current-view=""
    scrolling="no"
    frameborder="0"
    height="0"
    src="">
  </iframe>
</div>

    </div>
</div>
  </div>
</div>

          <div class="DMActivity DMAddParticipants js-ariaDocument u-chromeOverflowFix">
  <div class="DMActivity-header">
      <div class="DMActivity-navigation">
        <button type="button" class="DMActivity-back u-textUserColorHover"     to-convoSettings

>
          <span class="Icon Icon--caretLeft u-linkComplex-target Icon--medium"></span>
          <span class="u-hiddenVisually">Back to inbox</span>
        </button>
      </div>

    <h2 class="DMActivity-title js-ariaTitle">
          Add People

    </h2>

    <div class="DMActivity-toolbar">

      <button type="button" class="DMActivity-close js-close u-textUserColorHover">
        <span class="Icon Icon--close Icon--medium"></span>
        <span class="u-hiddenVisually">Close</span>
      </button>
    </div>
  </div>

  <div class="DMActivity-container">
    <div class="DMActivity-notice">
      <div class="DMNotice DMNotice--error DMErrorBar" style="display: none;">
  <div class="DMNotice-message">    <div class="DMErrorBar-text"></div>
</div>
  <div class="DMNotice-actions u-emptyHide"></div>
    <button type="button" class="DMNotice-dismiss">
      <span class="Icon Icon--close"></span>
      <span class="u-hiddenVisually">Dismiss</span>
    </button>
</div>

      <div class="DMNotice DMNotice--toast " style="display: none;">
  <div class="DMNotice-message"></div>
  <div class="DMNotice-actions u-emptyHide"></div>
    <button type="button" class="DMNotice-dismiss">
      <span class="Icon Icon--close"></span>
      <span class="u-hiddenVisually">Dismiss</span>
    </button>
</div>


    </div>

    <div class="DMActivity-body js-ariaBody DMAddParticipants-container js-initial-focus">
          <div class="DMAddParticipants-spinner">
      <div class="DMSpinner"></div>
    </div>
<div class="DMAddParticipants-content DMDialogTypeahead">
  <span class="DMTypeaheadHeader">Send message to:</span>
  <ul class="TokenizedMultiselect-inputContainer">
    <li>
      <textarea class="TokenizedMultiselect-input twttr-directmessage-input js-initial-focus dm-to-input" aria-autocomplete="list" aria-expanded="true" rows="1" type="text" placeholder="Enter a name"></textarea>
    </li>
  </ul>
  <ul id="DMComposeTypeaheadSuggestions" class="DMTypeaheadSuggestions u-scrollY" role="listbox"></ul>
</div>
    </div>

    <div class="DMActivity-footer u-emptyHide">    <div class="DMButtonBar">
      <button type="button" class="EdgeButton EdgeButton--primary DMAddParticipants-done">Done</button>
    </div>
</div>
  </div>
</div>



            <div class="DMActivity DMConversationSettings js-ariaDocument u-chromeOverflowFix">
  <div class="DMActivity-header">
      <div class="DMActivity-navigation">
        <button type="button" class="DMActivity-back u-textUserColorHover" to-convo>
          <span class="Icon Icon--caretLeft u-linkComplex-target Icon--medium"></span>
          <span class="u-hiddenVisually">Back to inbox</span>
        </button>
      </div>

    <h2 class="DMActivity-title js-ariaTitle">

    </h2>

    <div class="DMActivity-toolbar">
      <div class="DMConversationSettings-dropdown u-posRelative u-textLeft u-textUserColorHover"></div>
      <button type="button" class="DMActivity-close js-close u-textUserColorHover">
        <span class="Icon Icon--close Icon--medium"></span>
        <span class="u-hiddenVisually">Close</span>
      </button>
    </div>
  </div>

  <div class="DMActivity-container">
    <div class="DMActivity-notice">
      <div class="DMNotice DMNotice--error DMErrorBar" style="display: none;">
  <div class="DMNotice-message">    <div class="DMErrorBar-text"></div>
</div>
  <div class="DMNotice-actions u-emptyHide"></div>
    <button type="button" class="DMNotice-dismiss">
      <span class="Icon Icon--close"></span>
      <span class="u-hiddenVisually">Dismiss</span>
    </button>
</div>

      <div class="DMNotice DMNotice--toast " style="display: none;">
  <div class="DMNotice-message"></div>
  <div class="DMNotice-actions u-emptyHide"></div>
    <button type="button" class="DMNotice-dismiss">
      <span class="Icon Icon--close"></span>
      <span class="u-hiddenVisually">Dismiss</span>
    </button>
</div>

          <div class="DMNotice  DMDeleteConversation" style="display: none;">
  <div class="DMNotice-message">    <span class="DMDeleteConversation-message">
      This conversation history will be deleted from your inbox.
    </span>
</div>
  <div class="DMNotice-actions u-emptyHide">    <button type="button" class="DMDeleteConversation-cancel EdgeButton EdgeButton--tertiary">Cancel</button>
    <button type="button" class="DMDeleteConversation-confirm EdgeButton EdgeButton--danger js-initial-focus">Leave</button>
</div>
</div>


    </div>

    <div class="DMActivity-body js-ariaBody DMConversationSettings-container js-initial-focus flex-module u-scrollY">
          <div class="DMConversationSettings-avatar">
      <div class="DMUpdateAvatar" aria-haspopup="true" data-has-custom-avatar="false">
<div class="DMPopover DMPopover--center">
  <button class="DMPopover-button" aria-haspopup="true">
          <span class="u-hiddenVisually">Update group photo.</span>
      <div class="DMUpdateAvatar-avatar"></div>

  </button>
  <div class="DMPopover-content Caret Caret--top Caret--stroked ">
          <ul class="DMPopoverMenu u-textCenter js-focus-on-open u-dropdownUserColor" tabindex="-1" role="menu">
        <li class="DMUpdateAvatar-view">
          <button type="button" class="DMPopoverMenu-button">View photo</button>
        </li>
        <li class="DMUpdateAvatar-change">
          <button type="button" class="DMPopoverMenu-button">Upload photo</button>
        </li>
        <li class="DMUpdateAvatar-remove">
          <button type="button" class="DMPopoverMenu-button">Remove</button>
        </li>
      </ul>

      <div class="DMUpdateAvatar-photoSelector photo-selector" tabindex="-1" aria-hidden="true">
        <div class="image-selector">
          <input type="hidden" name="media_file_name" class="file-name">
          <input type="hidden" name="media_data_empty" class="file-data">
          <label class="t1-label">
            <span class="u-hiddenVisually">Add Photo</span>
            <input type="file" name="media_empty" class="file-input js-tooltip" accept="image/*" tabindex="-1" title="Add Photo">
          </label>
        </div>
      </div>

  </div>
</div></div>
    </div>

    <div class="DMConversationSettings-name">
      <div class="DMUpdateName u-textTruncate">
  <div class="DMUpdateName-header account-group">
    <span class="DMUpdateName-name u-textTruncate"></span><span class="UserBadges"><span class="Icon Icon--verified js-verified hidden"><span class="u-hiddenVisually">Verified account</span></span><span class="Icon Icon--protected js-protected hidden"><span class="u-hiddenVisually">Protected Tweets</span></span></span>
  </div>
  <div class="DMUpdateName-screenName u-textTruncate"></div>
  <div class="DMUpdateName-controls">
    <span class="DMUpdateName-spinner DMSpinner"></span>
    <div class="DMUpdateName-form input-group u-hidden">
      <input type="text" class="DMUpdateName-input" aria-label="Edit group name" />
      <button class="DMUpdateName-confirm u-textUserColorLight">
        <span class="Icon Icon--check"></span>
        <span class="u-hiddenVisually">Save group name</span>
      </button>
    </div>
  </div>
</div>

    </div>

    <div class="DMConversationSettings-notifications">
      <h3>Notifications</h3>
      <label class="t1-label checkbox">
        <input type="checkbox" name="dm[toggle_notifications]">Mute notifications
      </label>
      <p class="DMConversationSettings-notificationsFooter t1-infotext"></p>
    </div>

    <div class="DMConversationSettings-subscriptions">
      <h3>Subscriptions</h3>
      <label class="t1-label checkbox">
        <input type="checkbox" name="dm[toggle_subscriptions]">Subscribe to updates
      </label>
      <p class="DMConversationSettings-subscriptionsFooter t1-infotext"></p>
    </div>

    <div class="DMConversationSettings-participants u-flexColumn u-flexGrow"></div>

    </div>

    <div class="DMActivity-footer u-emptyHide">    <div class="DMConversationSettings-footer u-flexRow u-bgUserColorLightest">
      <button type="button" class="EdgeButton EdgeButton--secondary js-actionReportConversation"></button>
      <button type="button" class="EdgeButton EdgeButton--tertiary js-actionDeleteConversation"></button>
    </div>
</div>
  </div>
</div>

        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal-container dm-media-container">
  <div class="modal draggable dm-media">
    <div class="modal-header">
      <button type="button" class="modal-btn modal-close js-close">
  <span class="Icon Icon--close Icon--medium">
    <span class="visuallyhidden">Close</span>
  </span>
</button>

      <h2 class="modal-title">Photo</h2>
    </div>
    <div class="dm-media-preview"></div>
  </div>
</div>

<div class="modal-container dm-mute-notifications-dialog">
  <div class="modal modal-medium draggable">
    <div class="modal-content">
      <button type="button" class="modal-btn modal-close js-close">
  <span class="Icon Icon--close Icon--medium">
    <span class="visuallyhidden">Close</span>
  </span>
</button>


      <div class="modal-header">
        <h3 class="modal-title">Mute Notifications</h3>
      </div>

      <div class="modal-body">
        <fieldset class="control-group">
          <legend class="t1-label control-label">Mute notifications duration</legend>
          <div class="controls">
            <label class="t1-label radio">
              <input type="radio" value="1" name="dm[mute_notifications_duration]">For 1 hour
            </label>
            <label class="t1-label radio">
              <input type="radio" value="2" name="dm[mute_notifications_duration]">For 8 hours
            </label>
            <label class="t1-label radio">
              <input type="radio" value="3" name="dm[mute_notifications_duration]">For 1 week
            </label>
            <label class="t1-label radio">
              <input type="radio" value="0" name="dm[mute_notifications_duration]">Forever
            </label>
          </div>
        </fieldset>
      </div>

      <div class="modal-footer submit-section">
        <button class="EdgeButton EdgeButton--tertiary js-close">Cancel</button>
        <button class="EdgeButton EdgeButton--primary modal-submit js-close">Mute</button>
      </div>
    </div>
  </div>
</div>



<div id="goto-user-dialog" class="modal-container">
  <div class="modal modal-small draggable">
    <div class="modal-content">
      <button type="button" class="modal-btn modal-close js-close">
  <span class="Icon Icon--close Icon--medium">
    <span class="visuallyhidden">Close</span>
  </span>
</button>


      <div class="modal-header">
        <h3 class="modal-title">Go to a person"s profile</h3>
      </div>

      <div class="modal-body">
        <div class="modal-inner">
          <form class="t1-form goto-user-form">
            <input class="input-block username-input" type="text" placeholder="Start typing a name to jump to a profile" aria-label="User">



<div role="listbox" class="dropdown-menu typeahead">
  <div aria-hidden="true" class="dropdown-caret">
    <div class="caret-outer"></div>
    <div class="caret-inner"></div>
  </div>
  <div role="presentation" class="dropdown-inner js-typeahead-results">
      <div role="presentation" class="typeahead-recent-searches">
  <h3 id="recent-searches-heading" class="typeahead-category-title recent-searches-title">Recent searches</h3><button type="button" tabindex="-1" class="btn-link clear-recent-searches">Clear All</button>
  <ul role="presentation" class="typeahead-items recent-searches-list">

    <li role="presentation" class="typeahead-item typeahead-recent-search-item">
      <span class="Icon Icon--close" aria-hidden="true"><span class="visuallyhidden">Remove</span></span>
      <a role="option" aria-describedby="recent-searches-heading" class="js-nav" href="" data-search-query="" data-query-source="" data-ds="recent_search" tabindex="-1"></a>
    </li>
  </ul>
</div>

    <div role="presentation" class="typeahead-saved-searches">
  <h3 id="saved-searches-heading" class="typeahead-category-title saved-searches-title">Saved searches</h3>
  <ul role="presentation" class="typeahead-items saved-searches-list">

    <li role="presentation" class="typeahead-item typeahead-saved-search-item">
      <span class="Icon Icon--close" aria-hidden="true"><span class="visuallyhidden">Remove</span></span>
      <a role="option" aria-describedby="saved-searches-heading" class="js-nav" href="" data-search-query="" data-query-source="" data-ds="saved_search" tabindex="-1"></a>
    </li>
  </ul>
</div>

    <ul role="presentation" class="typeahead-items typeahead-topics">

  <li role="presentation" class="typeahead-item typeahead-topic-item">
    <a role="option" class="js-nav" href="" data-search-query="" data-query-source="typeahead_click" data-ds="topics" tabindex="-1"></a>
  </li>
</ul>
    <ul role="presentation" class="typeahead-items typeahead-accounts social-context js-typeahead-accounts">

  <li role="presentation" data-user-id="" data-user-screenname="" data-remote="true" data-score="" class="typeahead-item typeahead-account-item js-selectable">

    <a role="option" class="js-nav" data-query-source="typeahead_click" data-search-query="" data-ds="account">
      <div class="js-selectable typeahead-in-conversation hidden">
        <span class="Icon Icon--follower Icon--small"></span>
        <span class="typeahead-in-conversation-text">In this conversation</span>
      </div>
      <img class="avatar size32" alt="">
      <span class="typeahead-user-item-info account-group">
        <span class="fullname"></span><span class="UserBadges"><span class="Icon Icon--verified js-verified hidden"><span class="u-hiddenVisually">Verified account</span></span><span class="Icon Icon--protected js-protected hidden"><span class="u-hiddenVisually">Protected Tweets</span></span></span><span class="UserNameBreak">&nbsp;</span><span class="username u-dir" dir="ltr">@<b></b></span>
      </span>
      <span class="typeahead-social-context"></span>
    </a>
  </li>
  <li role="presentation" class="js-selectable typeahead-accounts-shortcut js-shortcut"><a role="option" class="js-nav" href="" data-search-query="" data-query-source="typeahead_click" data-shortcut="true" data-ds="account_search"></a></li>
</ul>

    <ul role="presentation" class="typeahead-items typeahead-trend-locations-list">

  <li role="presentation" class="typeahead-item typeahead-trend-locations-item"><a role="option" class="js-nav" href="" data-ds="trend_location" data-search-query="" tabindex="-1"></a></li>
</ul>

<div role="presentation" class="typeahead-user-select">
  <div role="presentation" class="typeahead-empty-suggestions">
    Suggested users
  </div>
  <ul role="presentation" class="typeahead-items typeahead-selected js-typeahead-selected">

    <li role="presentation" data-user-id="" data-user-screenname="" data-remote="true" data-score="" class="typeahead-item typeahead-selected-item js-selectable">

      <a role="option" class="js-nav" data-query-source="typeahead_click" data-search-query="" data-ds="account">
        <img class="avatar size32" alt="">
        <span class="typeahead-user-item-info account-group">
          <span class="select-status deselect-user js-deselect-user Icon Icon--check"></span>
          <span class="select-status select-disabled Icon Icon--unfollow"></span>
          <span class="fullname"></span><span class="UserBadges"><span class="Icon Icon--verified js-verified hidden"><span class="u-hiddenVisually">Verified account</span></span><span class="Icon Icon--protected js-protected hidden"><span class="u-hiddenVisually">Protected Tweets</span></span></span><span class="UserNameBreak">&nbsp;</span><span class="username u-dir" dir="ltr">@<b></b></span>
        </span>
      </a>
    </li>
    <li role="presentation" class="typeahead-selected-end"></li>
  </ul>

  <ul role="presentation" class="typeahead-items typeahead-accounts js-typeahead-accounts">

    <li role="presentation" data-user-id="" data-user-screenname="" data-remote="true" data-score="" class="typeahead-item typeahead-account-item js-selectable">

      <a role="option" class="js-nav" data-query-source="typeahead_click" data-search-query="" data-ds="account">
        <img class="avatar size32" alt="">
        <span class="typeahead-user-item-info account-group">
          <span class="select-status deselect-user js-deselect-user Icon Icon--check"></span>
          <span class="select-status select-disabled Icon Icon--unfollow"></span>
          <span class="fullname"></span><span class="UserBadges"><span class="Icon Icon--verified js-verified hidden"><span class="u-hiddenVisually">Verified account</span></span><span class="Icon Icon--protected js-protected hidden"><span class="u-hiddenVisually">Protected Tweets</span></span></span><span class="UserNameBreak">&nbsp;</span><span class="username u-dir" dir="ltr">@<b></b></span>
        </span>
      </a>
    </li>
    <li role="presentation" class="typeahead-accounts-end"></li>
  </ul>
</div>

    <div role="presentation" class="typeahead-dm-conversations">
  <ul role="presentation" class="typeahead-items typeahead-dm-conversation-items">
    <li role="presentation" class="typeahead-item typeahead-dm-conversation-item">
      <a role="option" tabindex="-1"></a>
    </li>
  </ul>
</div>
  </div>
</div>

          </form>
        </div>
      </div>

    </div>
  </div>
</div>

<div id="quick-promote-dialog" class="QuickPromoteDialog modal-container">
  <div class="modal draggable">
    <div class="modal-content">
      <button type="button" class="modal-btn modal-close modal-close-fixed js-close">
  <span class="Icon Icon--close Icon--large">
    <span class="visuallyhidden">Close</span>
  </span>
</button>

      <div class="modal-header">
        <h3 class="modal-title">Promote this Tweet</h3>
      </div>
      <div class="modal-body">
        <div class="quick-promote-view-container">
          <div class="media">
            <iframe
              class="quick-promote-iframe js-initial-focus"
              scrolling="no"
              frameborder="0"
              src="">
            </iframe>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<div id="block-user-dialog" class="modal-container">
  <div class="modal draggable">
    <div class="modal-content">
      <button type="button" class="modal-btn modal-close js-close">
  <span class="Icon Icon--close Icon--medium">
    <span class="visuallyhidden">Close</span>
  </span>
</button>


      <div class="modal-header">
        <h3 class="modal-title">Block</h3>
      </div>

      <div class="tweet-loading">
  <div class="spinner-bigger"></div>
</div>

      <div class="modal-body modal-tweet"></div>

      <div class="modal-footer">
        <button class="EdgeButton EdgeButton--tertiary cancel-action js-close">Cancel</button>
        <button class="EdgeButton EdgeButton--danger block-action">Block</button>
      </div>
    </div>
  </div>
</div>







<div id="geo-enabled-dropdown">
  <div tabindex="-1">
  <div class="dropdown-caret">
    <span class="caret-outer"></span>
    <span class="caret-inner"></span>
  </div>
  <div>
    <div class="geo-query-location">
      <input class="GeoSearch-queryInput" type="text" autocomplete="off" placeholder="Search for a neighborhood or city">
      <span class="Icon Icon--search"></span>
    </div>
    <div class="geo-dropdown-status"></div>
    <ul class="GeoSearch-dropdownMenu"></ul>
  </div>
</div>

</div>


<div id="location-picker-dialog" class="LocationPickerDialog modal-container">
  <div class="LocationPickerDialog-modal modal draggable">
    <div class="modal-content">
      <button type="button" class="modal-btn modal-close js-close">
  <span class="Icon Icon--close Icon--medium">
    <span class="visuallyhidden">Close</span>
  </span>
</button>

      <div class="modal-header LocationPickerDialog-header">
        <h3 class="modal-title LocationPickerDialog-title">Share Location</h3>
      </div>
      <div class="modal-body LocationPickerDialog-body">
        <div class="LocationPickerDialog-container">
          <iframe
            class="LocationPickerDialog-iframe js-initial-focus"
            scrolling="no"
            frameborder="0">
          </iframe>
        </div>
      </div>

      <div class="LocationPickerDialog-footer">
        <div class="LocationPickerDialog-suggestedLocation">
          <p class="LocationPickerDialog-mainAddressLine u-hidden u-truncateText"></p>
          <p class="LocationPickerDialog-secondaryAddressLine u-hidden u-truncateText"></p>

          <div class="LocationPickerDialog-foursquareVendorInfo u-hidden">
            <img class="LocationPickerDialog-foursquareLogo" src="https://abs.twimg.com/a/1503707773/img/search/ic_places_foursquare_logo.png" alt="Foursquare" />
          </div>

          <div class="LocationPickerDialog-yelpVendorInfo u-hidden">
            <span class="LocationPickerDialog-yelpText">Results from </span>
            <img class="LocationPickerDialog-logo--yelpLogo" src="https://abs.twimg.com/a/1503707773/img/search/ic_places_yelp_logo.png" alt="Yelp" />
          </div>
        </div>
        <button id="location-picker-submit-button" class="EdgeButton EdgeButton--primary">Send</button>
      </div>
    </div>
  </div>
</div>


  <div id="list-membership-dialog" class="modal-container">
  <div class="modal modal-small draggable">
    <div class="modal-content">
      <button type="button" class="modal-btn modal-close js-close">
  <span class="Icon Icon--close Icon--medium">
    <span class="visuallyhidden">Close</span>
  </span>
</button>

      <div class="modal-header">
        <h3 class="modal-title">Your lists</h3>
      </div>
      <div class="modal-body">
        <div class="list-membership-content"></div>
        <span class="spinner lists-spinner" title="Loading&hellip;"></span>
      </div>
    </div>
  </div>
</div>
  <div id="list-operations-dialog" class="modal-container">
  <div class="modal modal-medium draggable">
    <div class="modal-content">
      <button type="button" class="modal-btn modal-close js-close">
  <span class="Icon Icon--close Icon--medium">
    <span class="visuallyhidden">Close</span>
  </span>
</button>

      <div class="modal-header">
        <h3 class="modal-title">Create a new list</h3>
      </div>
      <div class="modal-body">
        <div class="list-editor">
  <div class="field">
    <label class="t1-label" for="list-name">List name</label>
    <input id="list-name" type="text" class="text" name="name" value="" />
  </div>
  <hr/>

  <div class="field">
    <label class="t1-label" for="list-description">Description</label>
    <textarea id="list-description" name="description"></textarea>
    <span class="help-text">Under 100 characters, optional</span>
  </div>
  <hr/>

  <fieldset class="field">
    <legend class="t1-legend">Privacy</legend>
    <div class="options">
      <label class="t1-label" for="list-public-radio">
        <input class="radio" type="radio" name="mode" id="list-public-radio" value="public" checked="checked"  />
        <b>Public</b> &middot; Anyone can follow this list
      </label>
      <label class="t1-label" for="list-private-radio">
        <input class="radio" type="radio" name="mode" id="list-private-radio" value="private"  />
        <b>Private</b> &middot; Only you can access this list
      </label>
    </div>
  </fieldset>
  <hr/>

  <div class="list-editor-save">
    <button type="button" class="EdgeButton EdgeButton--secondary update-list-button" data-list-id="">Save list</button>
  </div>
</div>

      </div>
    </div>
  </div>
</div>

<div id="activity-popup-dialog" class="modal-container">
  <div class="modal draggable">
    <div class="modal-content clearfix">
      <button type="button" class="modal-btn modal-close js-close">
  <span class="Icon Icon--close Icon--medium">
    <span class="visuallyhidden">Close</span>
  </span>
</button>


      <div class="modal-header">
        <h3 class="modal-title"></h3>
      </div>

      <div class="modal-body">
        <div class="tweet-loading">
  <div class="spinner-bigger"></div>
</div>

        <div class="activity-popup-dialog-content modal-tweet clearfix"></div>
        <div class="loading">
          <span class="spinner-bigger"></span>
        </div>
        <div class="activity-popup-dialog-users clearfix"></div>
        <div class="activity-popup-dialog-footer"></div>
      </div>
    </div>
  </div>
</div>


  <div id="spam_challenge_dialog" class="modal-container">
  <div class="modal draggable">
    <div class="modal-content">
      <button type="button" class="modal-btn modal-close js-close">
  <span class="Icon Icon--close Icon--medium">
    <span class="visuallyhidden">Close</span>
  </span>
</button>

      <div class="modal-header">
        <h3 class="modal-title">Security challenge</h3>
      </div>
      <div class="modal-body">
        <p class="modal-body-text">
          <h3 class="modal-text">There appears to be some suspicious activity on your account. In order to continue, please solve the challenge below.
</h3>
        </p>
        <div id="captcha-challenge-form"></div>
      </div>
      <div class="modal-footer">
        <button class="btn js-close" id="confirm_dialog_cancel_button">Cancel</button>
        <button type="button" id="recaptcha_submit" class="btn">Submit</button>
      </div>
    </div>
  </div>
</div>




<div id="copy-link-to-tweet-dialog" class="modal-container">
  <div class="modal modal-medium draggable">
    <div class="modal-content">
      <button type="button" class="modal-btn modal-close js-close">
  <span class="Icon Icon--close Icon--medium">
    <span class="visuallyhidden">Close</span>
  </span>
</button>

      <div class="modal-header">
        <h3 class="modal-title">Copy link to Tweet</h3>
      </div>
      <div class="modal-body">
        <div class="copy-link-to-tweet-container">
          <label class="t1-label">
            <p class="copy-link-to-tweet-instructions">Here"s the URL for this Tweet. Copy it to easily share with friends.</p>
            <textarea class="link-to-tweet-destination js-initial-focus u-dir" dir="ltr" readonly></textarea>
          </label>
        </div>
      </div>
    </div>
  </div>
</div>


<div id="embed-tweet-dialog" class="modal-container">
  <div class="modal modal-medium draggable">
    <div class="modal-content">
      <button type="button" class="modal-btn modal-close js-close">
  <span class="Icon Icon--close Icon--medium">
    <span class="visuallyhidden">Close</span>
  </span>
</button>

      <div class="modal-header">
        <h3 class="modal-title embed-tweet-title">Embed this Tweet</h3>
        <h3 class="modal-title embed-video-title">Embed this Video</h3>
      </div>
      <div class="modal-body">
        <div class="embed-code-container">
  <p class="embed-tweet-instructions">Add this Tweet to your website by copying the code below. <a href="https://dev.twitter.com/docs/embedded-tweets" rel="noopener">Learn more</a></p>
  <p class="embed-video-instructions">Add this video to your website by copying the code below. <a href="https://dev.twitter.com/docs/embedded-tweets" rel="noopener">Learn more</a></p>
  <form class="t1-form">

    <div class="embed-destination-wrapper">
      <div class="embed-overlay embed-overlay-spinner"><div class="embed-overlay-content"></div></div>
      <div class="embed-overlay embed-overlay-error">
        <p class="embed-overlay-content">Hmm, there was a problem reaching the server. <button type="button" class="btn-link retry-embed">Try again?</button></p>
      </div>
      <textarea class="embed-destination js-initial-focus"></textarea>
      <div class="embed-options">
        <div class="embed-include-parent-tweet">
          <label class="t1-label" for="include-parent-tweet">
            <input type="checkbox" id="include-parent-tweet" class="include-parent-tweet" checked>
            Include parent Tweet
          </label>
        </div>
        <div class="embed-include-card">
          <label class="t1-label" for="include-card">
            <input type="checkbox" id="include-card" class="include-card" checked>
            Include media
          </label>
        </div>
      </div>
    </div>
  </form>
  <p class="embed-tweet-description">By embedding Twitter content in your website or app, you are agreeing to the Twitter <a href="https://dev.twitter.com/overview/terms/agreement" rel="noopener">Developer Agreement</a> and <a href="https://dev.twitter.com/overview/terms/policy" rel="noopener">Developer Policy</a>.</p>
  <h3 class="embed-preview-header">Preview</h3>
  <div class="embed-preview">
  </div>
</div>

      </div>
    </div>
  </div>
</div>


<div id="why-this-ad-dialog" class="modal-container why-this-ad-dialog">
  <div class="modal modal-large draggable">
    <div class="modal-content">
      <button type="button" class="modal-btn modal-close js-close">
  <span class="Icon Icon--close Icon--medium">
    <span class="visuallyhidden">Close</span>
  </span>
</button>

      <div class="modal-header">
        <h3 class="modal-title why-this-ad-title">Why you"re seeing this ad</h3>
      </div>
      <div class="why-this-ad-content">
        <div class="why-this-ad-spinner">
          <div class="spinner-bigger"></div>
        </div>
        <iframe id="why-this-ad-frame" class="hidden" aria-hidden="true" scrolling="auto">
        </iframe>
      </div>
    </div>
  </div>
</div>


  <div id="global-tweet-dialog" class="modal-container">
  <div class="modal draggable">
    <div class="modal-content">
      <button type="button" class="modal-btn modal-close js-close">
  <span class="Icon Icon--close Icon--medium">
    <span class="visuallyhidden">Close</span>
  </span>
</button>

      <div class="modal-header">
        <h3 class="modal-title"></h3>
      </div>
      <div class="tweet-loading">
  <div class="spinner-bigger"></div>
</div>

      <div class="modal-body modal-tweet"></div>
      <div class="modal-tweet-form-container">
          <form class="t1-form tweet-form"
  method="post"
  target="tweet-post-iframe"
  action="//upload.twitter.com/i/tweet/create_with_media.iframe"
  enctype="multipart/form-data"
  data-poll-composer-rows="3"
>

  <div class="reply-users">Replying to <button type="button" class="btn-link reply-users-btn js-tooltip" data-original-title="Select who gets your reply"></button>
</div>

  <div class="tweet-content">
      <img class="inline-reply-user-image avatar size32" src="https://pbs.twimg.com/profile_images/878491891733549056/3urQeq4j_normal.jpg" alt="Mohammed Attya">
    <div class="ComposerDragHelp">
  <span class="ComposerDragHelp-text"></span>
</div>
    <span class="visuallyhidden" id="tweet-box-global-label">Tweet text</span>

<div class="RichEditor RichEditor--emojiPicker ">

  <div class="RichEditor-mozillaCursorWorkaround">&nbsp;</div>
  <div class="RichEditor-container u-borderRadiusInherit">



<div role="listbox" class="dropdown-menu typeahead">
  <div aria-hidden="true" class="dropdown-caret">
    <div class="caret-outer"></div>
    <div class="caret-inner"></div>
  </div>
  <div role="presentation" class="dropdown-inner js-typeahead-results">
      <div role="presentation" class="typeahead-recent-searches">
  <h3 id="recent-searches-heading" class="typeahead-category-title recent-searches-title">Recent searches</h3><button type="button" tabindex="-1" class="btn-link clear-recent-searches">Clear All</button>
  <ul role="presentation" class="typeahead-items recent-searches-list">

    <li role="presentation" class="typeahead-item typeahead-recent-search-item">
      <span class="Icon Icon--close" aria-hidden="true"><span class="visuallyhidden">Remove</span></span>
      <a role="option" aria-describedby="recent-searches-heading" class="js-nav" href="" data-search-query="" data-query-source="" data-ds="recent_search" tabindex="-1"></a>
    </li>
  </ul>
</div>

    <div role="presentation" class="typeahead-saved-searches">
  <h3 id="saved-searches-heading" class="typeahead-category-title saved-searches-title">Saved searches</h3>
  <ul role="presentation" class="typeahead-items saved-searches-list">

    <li role="presentation" class="typeahead-item typeahead-saved-search-item">
      <span class="Icon Icon--close" aria-hidden="true"><span class="visuallyhidden">Remove</span></span>
      <a role="option" aria-describedby="saved-searches-heading" class="js-nav" href="" data-search-query="" data-query-source="" data-ds="saved_search" tabindex="-1"></a>
    </li>
  </ul>
</div>

    <ul role="presentation" class="typeahead-items typeahead-topics">

  <li role="presentation" class="typeahead-item typeahead-topic-item">
    <a role="option" class="js-nav" href="" data-search-query="" data-query-source="typeahead_click" data-ds="topics" tabindex="-1"></a>
  </li>
</ul>
    <ul role="presentation" class="typeahead-items typeahead-accounts social-context js-typeahead-accounts">

  <li role="presentation" data-user-id="" data-user-screenname="" data-remote="true" data-score="" class="typeahead-item typeahead-account-item js-selectable">

    <a role="option" class="js-nav" data-query-source="typeahead_click" data-search-query="" data-ds="account">
      <div class="js-selectable typeahead-in-conversation hidden">
        <span class="Icon Icon--follower Icon--small"></span>
        <span class="typeahead-in-conversation-text">In this conversation</span>
      </div>
      <img class="avatar size32" alt="">
      <span class="typeahead-user-item-info account-group">
        <span class="fullname"></span><span class="UserBadges"><span class="Icon Icon--verified js-verified hidden"><span class="u-hiddenVisually">Verified account</span></span><span class="Icon Icon--protected js-protected hidden"><span class="u-hiddenVisually">Protected Tweets</span></span></span><span class="UserNameBreak">&nbsp;</span><span class="username u-dir" dir="ltr">@<b></b></span>
      </span>
      <span class="typeahead-social-context"></span>
    </a>
  </li>
  <li role="presentation" class="js-selectable typeahead-accounts-shortcut js-shortcut"><a role="option" class="js-nav" href="" data-search-query="" data-query-source="typeahead_click" data-shortcut="true" data-ds="account_search"></a></li>
</ul>

    <ul role="presentation" class="typeahead-items typeahead-trend-locations-list">

  <li role="presentation" class="typeahead-item typeahead-trend-locations-item"><a role="option" class="js-nav" href="" data-ds="trend_location" data-search-query="" tabindex="-1"></a></li>
</ul>

<div role="presentation" class="typeahead-user-select">
  <div role="presentation" class="typeahead-empty-suggestions">
    Suggested users
  </div>
  <ul role="presentation" class="typeahead-items typeahead-selected js-typeahead-selected">

    <li role="presentation" data-user-id="" data-user-screenname="" data-remote="true" data-score="" class="typeahead-item typeahead-selected-item js-selectable">

      <a role="option" class="js-nav" data-query-source="typeahead_click" data-search-query="" data-ds="account">
        <img class="avatar size32" alt="">
        <span class="typeahead-user-item-info account-group">
          <span class="select-status deselect-user js-deselect-user Icon Icon--check"></span>
          <span class="select-status select-disabled Icon Icon--unfollow"></span>
          <span class="fullname"></span><span class="UserBadges"><span class="Icon Icon--verified js-verified hidden"><span class="u-hiddenVisually">Verified account</span></span><span class="Icon Icon--protected js-protected hidden"><span class="u-hiddenVisually">Protected Tweets</span></span></span><span class="UserNameBreak">&nbsp;</span><span class="username u-dir" dir="ltr">@<b></b></span>
        </span>
      </a>
    </li>
    <li role="presentation" class="typeahead-selected-end"></li>
  </ul>

  <ul role="presentation" class="typeahead-items typeahead-accounts js-typeahead-accounts">

    <li role="presentation" data-user-id="" data-user-screenname="" data-remote="true" data-score="" class="typeahead-item typeahead-account-item js-selectable">

      <a role="option" class="js-nav" data-query-source="typeahead_click" data-search-query="" data-ds="account">
        <img class="avatar size32" alt="">
        <span class="typeahead-user-item-info account-group">
          <span class="select-status deselect-user js-deselect-user Icon Icon--check"></span>
          <span class="select-status select-disabled Icon Icon--unfollow"></span>
          <span class="fullname"></span><span class="UserBadges"><span class="Icon Icon--verified js-verified hidden"><span class="u-hiddenVisually">Verified account</span></span><span class="Icon Icon--protected js-protected hidden"><span class="u-hiddenVisually">Protected Tweets</span></span></span><span class="UserNameBreak">&nbsp;</span><span class="username u-dir" dir="ltr">@<b></b></span>
        </span>
      </a>
    </li>
    <li role="presentation" class="typeahead-accounts-end"></li>
  </ul>
</div>

    <div role="presentation" class="typeahead-dm-conversations">
  <ul role="presentation" class="typeahead-items typeahead-dm-conversation-items">
    <li role="presentation" class="typeahead-item typeahead-dm-conversation-item">
      <a role="option" tabindex="-1"></a>
    </li>
  </ul>
</div>
  </div>
</div>


    <div class="RichEditor-scrollContainer u-borderRadiusInherit">
              <div
          aria-labelledby="tweet-box-global-label"
          name="tweet"
          id="tweet-box-global"
          class="tweet-box rich-editor"
          contenteditable="true"
          spellcheck="true"
          role="textbox"
          aria-multiline="true"
          data-placeholder-default="What’s happening?"
          data-placeholder-poll-composer-on="Ask a question..."
          data-placeholder-reply="Tweet your reply"
        ></div>

      <div class="RichEditor-pictographs" aria-hidden="true"></div>
    </div>

            <div class="RichEditor-rightItems RichEditor-bottomItems">
            <div class="EmojiPicker dropdown is-loading">
  <button type="button" class="EmojiPicker-trigger js-dropdown-toggle js-tooltip u-textUserColorHover"
      title="Add emoji" data-delay="150">
    <span class="Icon Icon--smiley"></span>
    <span class="text u-hiddenVisually">
      Add emoji
    </span>
  </button>
  <div class="EmojiPicker-dropdownMenu dropdown-menu" tabindex="-1">
    <div class="EmojiPicker-content Caret Caret--stroked"></div>
  </div>
</div>

        </div>

  </div>
  <div class="RichEditor-mozillaCursorWorkaround">&nbsp;</div>
</div>


    <textarea aria-hidden="true" class="tweet-box-shadow hidden" name="status"></textarea>

    <div class="TweetBoxAttachments">

      <div class="thumbnail-container">
  <div class="thumbnail-wrapper">
    <div class="ComposerThumbnails"></div>
    <div class="preview-message">
      <button type="button" class="start-tagging js-open-user-select no-users u-borderUserColorLight u-textUserColor" disabled>
        <span class="Icon Icon--me Icon--small"></span>
        <span class="tagged-users">
          Who"s in these photos?
        </span>
      </button>
    </div>
    <div class="js-attribution attribution"></div>
    <div class="ComposerVideoInfo u-hidden"></div>
  </div>
</div>
<div class="photo-tagging-container user-select-container dropdown-menu hidden">
  <div class="tagging-dropdown">
    <div class="dropdown-caret center">
      <div class="caret-outer"></div>
      <div class="caret-inner"></div>
    </div>
    <div class="photo-tagging-controls user-select-controls">
      <label class="t1-label">
        <span class="Icon Icon--search nav-search"></span>
        <span class="u-hiddenVisually">Users in this photo</span>
        <input class="js-initial-focus" type="text" placeholder="Search and tag up to 10 people">
      </label>
    </div>
    <div class="typeahead-container">



<div role="listbox" class="dropdown-menu typeahead">
  <div aria-hidden="true" class="dropdown-caret">
    <div class="caret-outer"></div>
    <div class="caret-inner"></div>
  </div>
  <div role="presentation" class="dropdown-inner js-typeahead-results">
      <div role="presentation" class="typeahead-recent-searches">
  <h3 id="recent-searches-heading" class="typeahead-category-title recent-searches-title">Recent searches</h3><button type="button" tabindex="-1" class="btn-link clear-recent-searches">Clear All</button>
  <ul role="presentation" class="typeahead-items recent-searches-list">

    <li role="presentation" class="typeahead-item typeahead-recent-search-item">
      <span class="Icon Icon--close" aria-hidden="true"><span class="visuallyhidden">Remove</span></span>
      <a role="option" aria-describedby="recent-searches-heading" class="js-nav" href="" data-search-query="" data-query-source="" data-ds="recent_search" tabindex="-1"></a>
    </li>
  </ul>
</div>

    <div role="presentation" class="typeahead-saved-searches">
  <h3 id="saved-searches-heading" class="typeahead-category-title saved-searches-title">Saved searches</h3>
  <ul role="presentation" class="typeahead-items saved-searches-list">

    <li role="presentation" class="typeahead-item typeahead-saved-search-item">
      <span class="Icon Icon--close" aria-hidden="true"><span class="visuallyhidden">Remove</span></span>
      <a role="option" aria-describedby="saved-searches-heading" class="js-nav" href="" data-search-query="" data-query-source="" data-ds="saved_search" tabindex="-1"></a>
    </li>
  </ul>
</div>

    <ul role="presentation" class="typeahead-items typeahead-topics">

  <li role="presentation" class="typeahead-item typeahead-topic-item">
    <a role="option" class="js-nav" href="" data-search-query="" data-query-source="typeahead_click" data-ds="topics" tabindex="-1"></a>
  </li>
</ul>
    <ul role="presentation" class="typeahead-items typeahead-accounts social-context js-typeahead-accounts">

  <li role="presentation" data-user-id="" data-user-screenname="" data-remote="true" data-score="" class="typeahead-item typeahead-account-item js-selectable">

    <a role="option" class="js-nav" data-query-source="typeahead_click" data-search-query="" data-ds="account">
      <div class="js-selectable typeahead-in-conversation hidden">
        <span class="Icon Icon--follower Icon--small"></span>
        <span class="typeahead-in-conversation-text">In this conversation</span>
      </div>
      <img class="avatar size32" alt="">
      <span class="typeahead-user-item-info account-group">
        <span class="fullname"></span><span class="UserBadges"><span class="Icon Icon--verified js-verified hidden"><span class="u-hiddenVisually">Verified account</span></span><span class="Icon Icon--protected js-protected hidden"><span class="u-hiddenVisually">Protected Tweets</span></span></span><span class="UserNameBreak">&nbsp;</span><span class="username u-dir" dir="ltr">@<b></b></span>
      </span>
      <span class="typeahead-social-context"></span>
    </a>
  </li>
  <li role="presentation" class="js-selectable typeahead-accounts-shortcut js-shortcut"><a role="option" class="js-nav" href="" data-search-query="" data-query-source="typeahead_click" data-shortcut="true" data-ds="account_search"></a></li>
</ul>

    <ul role="presentation" class="typeahead-items typeahead-trend-locations-list">

  <li role="presentation" class="typeahead-item typeahead-trend-locations-item"><a role="option" class="js-nav" href="" data-ds="trend_location" data-search-query="" tabindex="-1"></a></li>
</ul>

<div role="presentation" class="typeahead-user-select">
  <div role="presentation" class="typeahead-empty-suggestions">
    Suggested users
  </div>
  <ul role="presentation" class="typeahead-items typeahead-selected js-typeahead-selected">

    <li role="presentation" data-user-id="" data-user-screenname="" data-remote="true" data-score="" class="typeahead-item typeahead-selected-item js-selectable">

      <a role="option" class="js-nav" data-query-source="typeahead_click" data-search-query="" data-ds="account">
        <img class="avatar size32" alt="">
        <span class="typeahead-user-item-info account-group">
          <span class="select-status deselect-user js-deselect-user Icon Icon--check"></span>
          <span class="select-status select-disabled Icon Icon--unfollow"></span>
          <span class="fullname"></span><span class="UserBadges"><span class="Icon Icon--verified js-verified hidden"><span class="u-hiddenVisually">Verified account</span></span><span class="Icon Icon--protected js-protected hidden"><span class="u-hiddenVisually">Protected Tweets</span></span></span><span class="UserNameBreak">&nbsp;</span><span class="username u-dir" dir="ltr">@<b></b></span>
        </span>
      </a>
    </li>
    <li role="presentation" class="typeahead-selected-end"></li>
  </ul>

  <ul role="presentation" class="typeahead-items typeahead-accounts js-typeahead-accounts">

    <li role="presentation" data-user-id="" data-user-screenname="" data-remote="true" data-score="" class="typeahead-item typeahead-account-item js-selectable">

      <a role="option" class="js-nav" data-query-source="typeahead_click" data-search-query="" data-ds="account">
        <img class="avatar size32" alt="">
        <span class="typeahead-user-item-info account-group">
          <span class="select-status deselect-user js-deselect-user Icon Icon--check"></span>
          <span class="select-status select-disabled Icon Icon--unfollow"></span>
          <span class="fullname"></span><span class="UserBadges"><span class="Icon Icon--verified js-verified hidden"><span class="u-hiddenVisually">Verified account</span></span><span class="Icon Icon--protected js-protected hidden"><span class="u-hiddenVisually">Protected Tweets</span></span></span><span class="UserNameBreak">&nbsp;</span><span class="username u-dir" dir="ltr">@<b></b></span>
        </span>
      </a>
    </li>
    <li role="presentation" class="typeahead-accounts-end"></li>
  </ul>
</div>

    <div role="presentation" class="typeahead-dm-conversations">
  <ul role="presentation" class="typeahead-items typeahead-dm-conversation-items">
    <li role="presentation" class="typeahead-item typeahead-dm-conversation-item">
      <a role="option" tabindex="-1"></a>
    </li>
  </ul>
</div>
  </div>
</div>

    </div>
  </div>
</div>



      <div class="CardComposer">
          <div class="PollingCardComposer u-hidden"
  data-poll-min-duration="5" data-poll-max-duration="10080"
>
  <div class="PollingCardComposer-option PollingCardComposer-option1" data-option-index="0">
    <input type="radio" class= "PollingCardComposer-optionRadio" disabled>
    <div class="PollingCardComposer-optionInput is-singleLine is-plainText u-borderUserColorLightFocus" contenteditable="true" spellcheck="true" role="textbox" data-placeholder="Choice 1"></div>
    <div style="clear: both"></div>
  </div>
  <div class="PollingCardComposer-option PollingCardComposer-option2" data-option-index="1">
    <input type="radio" class= "PollingCardComposer-optionRadio" disabled>
    <div class="PollingCardComposer-optionInput is-singleLine is-plainText u-borderUserColorLightFocus" contenteditable="true" spellcheck="true" role="textbox" data-placeholder="Choice 2"></div>
    <div style="clear: both"></div>
  </div>
  <div class="PollingCardComposer-option PollingCardComposer-option3" data-option-index="2">
    <input type="radio" class= "PollingCardComposer-optionRadio" disabled>
    <div class="PollingCardComposer-optionInput is-singleLine is-plainText u-borderUserColorLightFocus" contenteditable="true" spellcheck="true" role="textbox" data-placeholder="Choice 3 (optional)"></div>
    <button type="button" class="PollingCardComposer-removeOption">
      <span class="Icon Icon--close"></span>
    </button>
    <div style="clear: both"></div>
  </button>
  </div>
  <div class="PollingCardComposer-option PollingCardComposer-option4" data-option-index="3">
    <input type="radio" class= "PollingCardComposer-optionRadio" disabled>
    <div class="PollingCardComposer-optionInput is-singleLine is-plainText u-borderUserColorLightFocus" contenteditable="true" spellcheck="true" role="textbox" data-placeholder="Choice 4 (optional)"></div>
    <button type="button" class="PollingCardComposer-removeOption">
      <span class="Icon Icon--close"></span>
    </button>
    <div style="clear: both"></div>
  </div>
  <button type="button" class="PollingCardComposer-addOption u-textUserColor">
    <span>+</span>&nbsp;<span>Add a choice</span>
  </button>
  <div class="PollingCardComposer-pollDuration">
    <span class="PollingCardComposer-durationLabel">Poll length:&nbsp;</span>
    <button type="button" class="PollingCardComposer-defaultDuration u-textUserColor">1 day</button>
    <div class="PollingCardComposer-customDuration">
      <span class="PollingCardComposer-customDuration--daysLabel">Days</span>
      <select class="PollingCardComposer-customDuration--days u-borderUserColorLight" data-duration-target="days">
        <option value="0">0</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
      </select>
      <spann class="PollingCardComposer-customDuration--hoursLabel">Hours</span>
      <select class="PollingCardComposer-customDuration--hours u-borderUserColorLight" data-duration-target="hours">
        <option value="0">0</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
        <option value="8">8</option>
        <option value="9">9</option>
        <option value="10">10</option>
        <option value="11">11</option>
        <option value="12">12</option>
        <option value="13">13</option>
        <option value="14">14</option>
        <option value="15">15</option>
        <option value="16">16</option>
        <option value="17">17</option>
        <option value="18">18</option>
        <option value="19">19</option>
        <option value="20">20</option>
        <option value="21">21</option>
        <option value="22">22</option>
        <option value="23">23</option>
      </select>
      <spann class="PollingCardComposer-customDuration--minutesLabel">Min</span>
      <select class="PollingCardComposer-customDuration--minutes u-borderUserColorLight" data-duration-target="minutes">
        <option value="0">0</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
        <option value="8">8</option>
        <option value="9">9</option>
        <option value="10">10</option>
        <option value="11">11</option>
        <option value="12">12</option>
        <option value="13">13</option>
        <option value="14">14</option>
        <option value="15">15</option>
        <option value="16">16</option>
        <option value="17">17</option>
        <option value="18">18</option>
        <option value="19">19</option>
        <option value="20">20</option>
        <option value="21">21</option>
        <option value="22">22</option>
        <option value="23">23</option>
        <option value="24">24</option>
        <option value="25">25</option>
        <option value="26">26</option>
        <option value="27">27</option>
        <option value="28">28</option>
        <option value="29">29</option>
        <option value="30">30</option>
        <option value="31">31</option>
        <option value="32">32</option>
        <option value="33">33</option>
        <option value="34">34</option>
        <option value="35">35</option>
        <option value="36">36</option>
        <option value="37">37</option>
        <option value="38">38</option>
        <option value="39">39</option>
        <option value="40">40</option>
        <option value="41">41</option>
        <option value="42">42</option>
        <option value="43">43</option>
        <option value="44">44</option>
        <option value="45">45</option>
        <option value="46">46</option>
        <option value="47">47</option>
        <option value="48">48</option>
        <option value="49">49</option>
        <option value="50">50</option>
        <option value="51">51</option>
        <option value="52">52</option>
        <option value="53">53</option>
        <option value="54">54</option>
        <option value="55">55</option>
        <option value="56">56</option>
        <option value="57">57</option>
        <option value="58">58</option>
        <option value="59">59</option>
      </select>
    </div>
  </div>
  <button type="button" class="PollingCardComposer-remove u-textUserColor">
    <span>Remove poll</span>
  </button>
</div>

      </div>


      <div class="tweet-box-overlay"></div>
    </div>
  </div>

  <div class="TweetBoxToolbar">
    <div class="TweetBoxExtras tweet-box-extras">
      <span class="TweetBoxExtras-item TweetBox-mediaPicker"><div class="photo-selector">
  <button aria-hidden="true" class="btn icon-btn js-tooltip" type="button" tabindex="-1" data-original-title="Add photos or video">
      <span class="tweet-camera Icon Icon--media"></span>
    <span class="text add-photo-label u-hiddenVisually">Add photos or video</span>
  </button>
  <div class="image-selector">
    <input type="hidden" name="media_data_empty" class="file-data">
    <div class="multi-photo-data-container hidden">
    </div>
    <label class="t1-label">
      <span class="visuallyhidden">Add photos or video</span>
      <input type="file" name="media_empty" accept="image/gif,image/jpeg,image/jpg,image/png" multiple
          class="file-input js-tooltip" data-original-title="Add photos or video" data-delay="150">
    </label>
  </div>
</div>
</span>

      <span class="TweetBoxExtras-item"><div class="FoundMediaSearch found-media-search dropdown">
  <button class="btn js-found-media-search-trigger js-dropdown-toggle icon-btn js-tooltip" type="button"
      title="Add a GIF" data-delay="150">
    <span class="Icon Icon--gif Icon--large"></span>
    <span class="text u-hiddenVisually">
      Add a GIF
    </span>
  </button>
  <div class="FoundMediaSearch-dropdownMenu dropdown-menu" tabindex="-1">
    <div class="FoundMediaSearch-content Caret Caret--stroked">
      <div class="FoundMediaSearch-query">
        <input class="FoundMediaSearch-queryInput" type="text" autocomplete="off" placeholder="Search for a GIF">
        <span class="Icon Icon--search"></span>
      </div>
      <div class="FoundMediaSearch-results">
        <div class="FoundMediaSearch-items"></div>
        <div class="FoundMediaSearch-pagination"></div>
      </div>
    </div>
  </div>
</div>
</span>

      <span class="TweetBoxExtras-item"><div class="PollCreator">
  <button class="btn icon-btn PollCreator-btn js-tooltip" type="button" title="Add poll"
      data-delay="150">
    <span class="PollCreator-icon Icon Icon--pollBar"></span>
    <span class="text PollCreator-label u-hiddenVisually">Poll</span>
  </button>
</div>
</span>


      <span class="TweetBoxExtras-item"><div class="geo-picker dropdown">
  <button class="btn js-geo-search-trigger geo-picker-btn icon-btn js-tooltip" type="button" data-delay="150">
    <span class="Icon Icon--geo"></span>
    <span class="text geo-status u-hiddenVisually">Add location</span>
  </button>
  <span class="dropdown-container dropdown-menu"></span>
  <input type="hidden" name="place_id">
</div>
</span>

      <div class="TweetBoxUploadProgress">
  <div class="TweetBoxUploadProgress-uploading">
    Uploading
    <div class="TweetBoxUploadProgress-bar">
      <div class="TweetBoxUploadProgress-barPosition"></div>
    </div>
  </div>
  <div class="TweetBoxUploadProgress-processing">
    Processing
    <div class="TweetBoxUploadProgress-spinner Spinner Spinner--size14"></div>
  </div>
</div>
    </div>

    <div class="TweetBoxToolbar-tweetButton tweet-button">
        <span class="tweet-counter">140</span>
      <button class="tweet-action disabled EdgeButton EdgeButton--primary js-tweet-btn" type="button" disabled>
  <span class="button-text tweeting-text">
    Tweet
  </span>
  <span class="button-text replying-text">
    Reply
  </span>
</button>

    </div>
  </div>
</form>

      </div>
    </div>
  </div>
</div>

  <div id="retweet-tweet-dialog" class="RetweetDialog modal-container">
  <div class="RetweetDialog-modal modal draggable">
    <div class="modal-content">
      <button type="button" class="modal-btn modal-close js-close">
  <span class="Icon Icon--close Icon--medium">
    <span class="visuallyhidden">Close</span>
  </span>
</button>


      <div class="modal-header">
        <h3 class="modal-title">Retweet this to your followers?</h3>
      </div>

        <form class="t1-form tweet-form condensed RetweetDialog-tweetForm isWithoutComment">
          <div class="RetweetDialog-commentBox">
            <span class="visuallyhidden" id="retweet-with-comment-label">Optional comment for Retweet</span>
            <div class="tweet-content">
<div class="RichEditor RichEditor--emojiPicker ">

  <div class="RichEditor-mozillaCursorWorkaround">&nbsp;</div>
  <div class="RichEditor-container u-borderRadiusInherit">



<div role="listbox" class="dropdown-menu typeahead">
  <div aria-hidden="true" class="dropdown-caret">
    <div class="caret-outer"></div>
    <div class="caret-inner"></div>
  </div>
  <div role="presentation" class="dropdown-inner js-typeahead-results">
      <div role="presentation" class="typeahead-recent-searches">
  <h3 id="recent-searches-heading" class="typeahead-category-title recent-searches-title">Recent searches</h3><button type="button" tabindex="-1" class="btn-link clear-recent-searches">Clear All</button>
  <ul role="presentation" class="typeahead-items recent-searches-list">

    <li role="presentation" class="typeahead-item typeahead-recent-search-item">
      <span class="Icon Icon--close" aria-hidden="true"><span class="visuallyhidden">Remove</span></span>
      <a role="option" aria-describedby="recent-searches-heading" class="js-nav" href="" data-search-query="" data-query-source="" data-ds="recent_search" tabindex="-1"></a>
    </li>
  </ul>
</div>

    <div role="presentation" class="typeahead-saved-searches">
  <h3 id="saved-searches-heading" class="typeahead-category-title saved-searches-title">Saved searches</h3>
  <ul role="presentation" class="typeahead-items saved-searches-list">

    <li role="presentation" class="typeahead-item typeahead-saved-search-item">
      <span class="Icon Icon--close" aria-hidden="true"><span class="visuallyhidden">Remove</span></span>
      <a role="option" aria-describedby="saved-searches-heading" class="js-nav" href="" data-search-query="" data-query-source="" data-ds="saved_search" tabindex="-1"></a>
    </li>
  </ul>
</div>

    <ul role="presentation" class="typeahead-items typeahead-topics">

  <li role="presentation" class="typeahead-item typeahead-topic-item">
    <a role="option" class="js-nav" href="" data-search-query="" data-query-source="typeahead_click" data-ds="topics" tabindex="-1"></a>
  </li>
</ul>
    <ul role="presentation" class="typeahead-items typeahead-accounts social-context js-typeahead-accounts">

  <li role="presentation" data-user-id="" data-user-screenname="" data-remote="true" data-score="" class="typeahead-item typeahead-account-item js-selectable">

    <a role="option" class="js-nav" data-query-source="typeahead_click" data-search-query="" data-ds="account">
      <div class="js-selectable typeahead-in-conversation hidden">
        <span class="Icon Icon--follower Icon--small"></span>
        <span class="typeahead-in-conversation-text">In this conversation</span>
      </div>
      <img class="avatar size32" alt="">
      <span class="typeahead-user-item-info account-group">
        <span class="fullname"></span><span class="UserBadges"><span class="Icon Icon--verified js-verified hidden"><span class="u-hiddenVisually">Verified account</span></span><span class="Icon Icon--protected js-protected hidden"><span class="u-hiddenVisually">Protected Tweets</span></span></span><span class="UserNameBreak">&nbsp;</span><span class="username u-dir" dir="ltr">@<b></b></span>
      </span>
      <span class="typeahead-social-context"></span>
    </a>
  </li>
  <li role="presentation" class="js-selectable typeahead-accounts-shortcut js-shortcut"><a role="option" class="js-nav" href="" data-search-query="" data-query-source="typeahead_click" data-shortcut="true" data-ds="account_search"></a></li>
</ul>

    <ul role="presentation" class="typeahead-items typeahead-trend-locations-list">

  <li role="presentation" class="typeahead-item typeahead-trend-locations-item"><a role="option" class="js-nav" href="" data-ds="trend_location" data-search-query="" tabindex="-1"></a></li>
</ul>

<div role="presentation" class="typeahead-user-select">
  <div role="presentation" class="typeahead-empty-suggestions">
    Suggested users
  </div>
  <ul role="presentation" class="typeahead-items typeahead-selected js-typeahead-selected">

    <li role="presentation" data-user-id="" data-user-screenname="" data-remote="true" data-score="" class="typeahead-item typeahead-selected-item js-selectable">

      <a role="option" class="js-nav" data-query-source="typeahead_click" data-search-query="" data-ds="account">
        <img class="avatar size32" alt="">
        <span class="typeahead-user-item-info account-group">
          <span class="select-status deselect-user js-deselect-user Icon Icon--check"></span>
          <span class="select-status select-disabled Icon Icon--unfollow"></span>
          <span class="fullname"></span><span class="UserBadges"><span class="Icon Icon--verified js-verified hidden"><span class="u-hiddenVisually">Verified account</span></span><span class="Icon Icon--protected js-protected hidden"><span class="u-hiddenVisually">Protected Tweets</span></span></span><span class="UserNameBreak">&nbsp;</span><span class="username u-dir" dir="ltr">@<b></b></span>
        </span>
      </a>
    </li>
    <li role="presentation" class="typeahead-selected-end"></li>
  </ul>

  <ul role="presentation" class="typeahead-items typeahead-accounts js-typeahead-accounts">

    <li role="presentation" data-user-id="" data-user-screenname="" data-remote="true" data-score="" class="typeahead-item typeahead-account-item js-selectable">

      <a role="option" class="js-nav" data-query-source="typeahead_click" data-search-query="" data-ds="account">
        <img class="avatar size32" alt="">
        <span class="typeahead-user-item-info account-group">
          <span class="select-status deselect-user js-deselect-user Icon Icon--check"></span>
          <span class="select-status select-disabled Icon Icon--unfollow"></span>
          <span class="fullname"></span><span class="UserBadges"><span class="Icon Icon--verified js-verified hidden"><span class="u-hiddenVisually">Verified account</span></span><span class="Icon Icon--protected js-protected hidden"><span class="u-hiddenVisually">Protected Tweets</span></span></span><span class="UserNameBreak">&nbsp;</span><span class="username u-dir" dir="ltr">@<b></b></span>
        </span>
      </a>
    </li>
    <li role="presentation" class="typeahead-accounts-end"></li>
  </ul>
</div>

    <div role="presentation" class="typeahead-dm-conversations">
  <ul role="presentation" class="typeahead-items typeahead-dm-conversation-items">
    <li role="presentation" class="typeahead-item typeahead-dm-conversation-item">
      <a role="option" tabindex="-1"></a>
    </li>
  </ul>
</div>
  </div>
</div>


    <div class="RichEditor-scrollContainer u-borderRadiusInherit">
                        <div
                    aria-labelledby="retweet-with-comment-label"
                    id="retweet-with-comment"
                    class="tweet-box rich-editor"
                    contenteditable="true"
                    spellcheck="true"
                    role="textbox"
                    aria-multiline="true"
                    data-placeholder-default="Add a comment..."
                  ></div>

      <div class="RichEditor-pictographs" aria-hidden="true"></div>
    </div>

                      <div class="RichEditor-rightItems RichEditor-bottomItems">
                      <div class="EmojiPicker dropdown is-loading">
  <button type="button" class="EmojiPicker-trigger js-dropdown-toggle js-tooltip u-textUserColorHover"
      title="Add emoji" data-delay="150">
    <span class="Icon Icon--smiley"></span>
    <span class="text u-hiddenVisually">
      Add emoji
    </span>
  </button>
  <div class="EmojiPicker-dropdownMenu dropdown-menu" tabindex="-1">
    <div class="EmojiPicker-content Caret Caret--stroked"></div>
  </div>
</div>

                  </div>

  </div>
  <div class="RichEditor-mozillaCursorWorkaround">&nbsp;</div>
</div>
            </div>
          </div>

          <div class="RetweetDialog-footer u-cf">
            <div class="tweet-loading">
  <div class="spinner-bigger"></div>
</div>

            <div class="RetweetDialog-tweet modal-body modal-tweet tweet"></div>
            <div class="tweet-button">
                <span class="RetweetDialog-counter tweet-counter">140</span>
              <button class="EdgeButton EdgeButton--primary retweet-action" type="button">
                <span class="RetweetDialog-retweetActionLabel">
                  Retweet
                </span>
                <span class="RetweetDialog-tweetActionLabel">
                  Tweet
                </span>
              </button>
            </div>
          </div>
        </form>
    </div>
  </div>
</div>


<div id="block-dialog" class="modal-container block-dialog">
  <div class="modal modal-medium draggable">
    <div class="modal-content">
      <button type="button" class="modal-btn modal-close js-close">
  <span class="Icon Icon--close Icon--medium">
    <span class="visuallyhidden">Close</span>
  </span>
</button>

      <div class="modal-header">
        <h3 class="modal-title report-title">Block</h3>
        <input type="hidden" id="current-user-id" value="250377148">
      </div>
      <div class="block-section controls modal-body">
        <span class="label-head block-user-label"></span>
        <p class="block-user-description"><span class="block-user-text"></span> <a href="https://support.twitter.com/articles/117063-blocking-people-on-twitter" target="_blank" rel="noopener">Learn more</a> about what it means to block an account.</p>
      </div>
      <div class="modal-footer submit-section">
        <button class="EdgeButton EdgeButton--danger block-button">Block</button>
        <button class="EdgeButton EdgeButton--tertiary cancel-action js-close">Cancel</button>
      </div>
    </div>
  </div>
</div>


<div id="report-dialog" class="modal-container report-dialog">
  <div class="modal modal-medium draggable">
    <div class="modal-content">
      <button type="button" class="modal-btn modal-close js-close">
  <span class="Icon Icon--close Icon--medium">
    <span class="visuallyhidden">Close</span>
  </span>
</button>

      <div class="modal-header">
        <h3 class="modal-title report-title">Report</h3>
        <input type="hidden" id="current-user-id" value="250377148">
      </div>
        <div class="new-report-flow-form">
          <iframe id="new-report-flow-frame" class="new-report-flow-report" src="" scrolling="auto">
          </iframe>
          <div id="new-report-flow-control" class="modal-body submit-section">
            <div class="clearfix">
              <button id="report-flow-button-back" class="EdgeButton EdgeButton--tertiary new-report-flow-back-button" type="button">Back</button>
              <button id="report-flow-button-next" class="EdgeButton EdgeButton--primary new-report-flow-next-button" type="button">
                <span class="next-text">Next</span>
                <span class="add-text">Add<span class="tweet-number"></span></span>
              </button>
              <button class="EdgeButton EdgeButton--primary new-report-flow-done-button" type="button">Done</button>
              <button class="EdgeButton EdgeButton--tertiary new-report-flow-close-button" type="button">Close</button>
            </div>
          </div>
        </div>
    </div>
  </div>
</div>

  <div id="block-list-export-dialog" class="modal-container">
  <div class="modal modal-medium draggable">
    <div class="modal-content">
      <button type="button" class="modal-btn modal-close js-close">
  <span class="Icon Icon--close Icon--medium">
    <span class="visuallyhidden">Close</span>
  </span>
</button>

      <div class="modal-header">
        <h3 class="modal-title export-list-title">Export your list</h3>
        <br><br>
        <strong class="export-header-title">
          Confirm the accounts that you want to export

        </strong>
        <br><br>
        <p class="export-header-text">We will create a .csv file and save it on your computer. You may share the file, and others will be able to import this list of blocked accounts.</p>
      </div>
      <br>
      <div class="modal-body users-section">
        <div class="stream-container" data-cursor="">
            <span class="user-timeline">
              <label class="t1-label" for="include-imported-block">
                <input id="include-imported-block" type="checkbox" value="include_imported_block" checked>
                <span id="include-imported-block-text">Include all of my imported blocked accounts</span>
              </label>
              <ol class="stream-items js-navigable-stream" id="stream-items-id"></ol>
              <div class="stream-footer ">
  <div class="timeline-end  ">
      <div class="stream-end">
    <div class="stream-end-inner">

      <p class="empty-text">


      </p>

        <p><button type="button" class="btn-link back-to-top hidden">Back to top &uarr;</button></p>
    </div>
  </div>


    <div class="stream-loading">
  <div class="stream-end-inner">
    <span class="spinner" title="Loading..."></span>
  </div>
</div>

  </div>
</div>
<div class="stream-fail-container">
</div>

            </span>
          <span class="processing-bar">
            <span class="spinner" title="Loading..."></span>
          </span>
        </div>
      </div>
      <div class="modal-footer">
        <button class="EdgeButton EdgeButton--primary export-action">Export</button><button class="EdgeButton EdgeButton--primary js-close done-btn">Done</button><button class="EdgeButton EdgeButton--tertiary cancel-action js-close">Cancel</button>
      </div>
    </div>
  </div>
</div>

  <div id="block-list-import-dialog" class="modal-container">
  <div class="modal modal-medium draggable">
    <div class="modal-content">
      <button type="button" class="modal-btn modal-close js-close">
  <span class="Icon Icon--close Icon--medium">
    <span class="visuallyhidden">Close</span>
  </span>
</button>

      <div class="modal-header">
        <h3 class="modal-title">Import a list</h3>
        <input type="hidden" id="current-user-id" value="250377148">
        <br>
        <strong class="import-header-title"></strong>
        <br>
        <p class="import-header-text"></p>
      </div>
      <div class="controls modal-body">
        <div class="file-uploader">
          <div class="file-upload-section">
            <input type="file" name="filename" id="filename" accept=".csv">
            <div class="upload-icon Icon Icon--extraLarge Icon--attachFile"></div>
            <div class="uploaded-file">Attach a file to upload</div>
          </div>
          <div id="error-message"></div>
        </div>
        <div class="processing-text">
           <span class="spinner" title="Loading..."></span>
        </div>
        <div class="name-list">
          <div id="imported-user-name-list">
          </div>
        </div>
      </div>
      <div class="modal-footer submit-section">
        <div class="clearfix">
          <button class="EdgeButton EdgeButton--tertiary js-initial-focus cancel-action js-close">Cancel</button><button class="EdgeButton EdgeButton--primary import-button">Preview</button><button class="EdgeButton EdgeButton--danger block-button">Block</button><button class="EdgeButton EdgeButton--tertiary done-button js-close">Done</button>
        </div>
      </div>
    </div>
  </div>
</div>

  <div id="age-gate-dialog" class="modal-container">
  <div class="modal draggable">
    <div class="modal-content">
      <button type="button" class="modal-btn modal-close js-close">
  <span class="Icon Icon--close Icon--medium">
    <span class="visuallyhidden">Close</span>
  </span>
</button>

      <div class="modal-header">
        <h3 class="modal-title">Enter your age</h3>
      </div>

      <div class="modal-body">
        <div class="age-gate-container">
          <p>To follow this account, you must meet minimum legal age requirements. Please supply your date of birth.</p>
          <div class="age-gate-header">
            <p>Select your date of birth:</p>
          </div>
          <label class="u-hiddenVisually" for="age-gate-year">Year</label><select class="t1-select" id="age-gate-year"></select>
          <label class="u-hiddenVisually" for="age-gate-month">Month</label><select class="t1-select" id="age-gate-month"></select>
          <label class="u-hiddenVisually" for="age-gate-day">Day</label><select class="t1-select" id="age-gate-day"></select>
          <span class="age-gate-error hidden">
            <span class="icon error-x"></span>Required
          </span>
          <div class="age-gate-bottom">
            <span class="age-gate-privacy">
              <a href="/privacy" rel="noopener">Privacy policy</a>
            </span>
          </div>
        </div>
      </div>

      <div class="modal-footer age-gate-footer">
        <button id="age-gate-dialog-submit-button" class="EdgeButton EdgeButton--primary age-gate-submit">Done</button>
      </div>
    </div>
  </div>
</div>

  <div id="sms-confirmation-dialog" class="modal-container sms-confirmation-dialog">
  <div class="modal modal-medium draggable">
    <div class="modal-content">
      <button type="button" class="modal-btn modal-close js-close">
  <span class="Icon Icon--close Icon--medium">
    <span class="visuallyhidden">Close</span>
  </span>
</button>

      <div id="sms-confirmation-begin-modal">
  <div class="modal-header">
    <h3 class="modal-title">Enter your phone number</h3>
  </div>
  <div class="modal-body">
    <div class="settings-alert" id="sms-alert-box">
      <span id="sms-alert-close" class="Icon Icon--close Icon--smallest close"></span>
      <h4 id="sms-alert-message"></h4>
    </div>
    <form id="sms-confirmation-begin-form" class="t1-form form-horizontal sms-form">
      <input type="hidden" id="authenticity_token" name="authenticity_token" value="a587a1e6111389fec0f83e815383d80da89d6907">
      <p>Enter your phone number in the box below, and we"ll send you a confirmation code.</p>
      <div class="control-group" id="choose-country">
        <label for="device_country_code" class="t1-label control-label">Country/region</label>
        <div class="controls">
          <select class="t1-select" id="device_country_code" name="device[country_code]">
                <option value="93" >Afghanistan</option>
                <option value="355" >Albania</option>
                <option value="213" >Algeria</option>
                <option value="1" >American Samoa</option>
                <option value="376" >Andorra</option>
                <option value="244" >Angola</option>
                <option value="1" >Anguilla</option>
                <option value="1" >Antigua &amp; Barbuda</option>
                <option value="54" >Argentina</option>
                <option value="374" >Armenia</option>
                <option value="297" >Aruba</option>
                <option value="61" >Australia</option>
                <option value="43" >Austria</option>
                <option value="994" >Azerbaijan</option>
                <option value="1" >Bahamas</option>
                <option value="973" >Bahrain</option>
                <option value="880" >Bangladesh</option>
                <option value="1" >Barbados</option>
                <option value="375" >Belarus</option>
                <option value="32" >Belgium</option>
                <option value="501" >Belize</option>
                <option value="229" >Benin</option>
                <option value="1" >Bermuda</option>
                <option value="975" >Bhutan</option>
                <option value="591" >Bolivia</option>
                <option value="387" >Bosnia &amp; Herzegovina</option>
                <option value="267" >Botswana</option>
                <option value="55" >Brazil</option>
                <option value="1" >British Virgin Islands</option>
                <option value="673" >Brunei</option>
                <option value="359" >Bulgaria</option>
                <option value="226" >Burkina Faso</option>
                <option value="257" >Burundi</option>
                <option value="855" >Cambodia</option>
                <option value="237" >Cameroon</option>
                <option value="1" >Canada</option>
                <option value="238" >Cape Verde</option>
                <option value="599" >Caribbean Netherlands</option>
                <option value="1" >Cayman Islands</option>
                <option value="236" >Central African Republic</option>
                <option value="235" >Chad</option>
                <option value="56" >Chile</option>
                <option value="86" >China</option>
                <option value="57" >Colombia</option>
                <option value="269" >Comoros</option>
                <option value="242" >Congo - Brazzaville</option>
                <option value="243" >Congo - Kinshasa</option>
                <option value="682" >Cook Islands</option>
                <option value="506" >Costa Rica</option>
                <option value="385" >Croatia</option>
                <option value="599" >Curaçao</option>
                <option value="357" >Cyprus</option>
                <option value="420" >Czech Republic</option>
                <option value="225" >Côte d’Ivoire</option>
                <option value="45" >Denmark</option>
                <option value="253" >Djibouti</option>
                <option value="1" >Dominica</option>
                <option value="1" >Dominican Republic</option>
                <option value="593" >Ecuador</option>
                <option value="20" selected>Egypt</option>
                <option value="503" >El Salvador</option>
                <option value="240" >Equatorial Guinea</option>
                <option value="291" >Eritrea</option>
                <option value="372" >Estonia</option>
                <option value="251" >Ethiopia</option>
                <option value="500" >Falkland Islands</option>
                <option value="298" >Faroe Islands</option>
                <option value="679" >Fiji</option>
                <option value="358" >Finland</option>
                <option value="33" >France</option>
                <option value="594" >French Guiana</option>
                <option value="689" >French Polynesia</option>
                <option value="241" >Gabon</option>
                <option value="220" >Gambia</option>
                <option value="995" >Georgia</option>
                <option value="49" >Germany</option>
                <option value="233" >Ghana</option>
                <option value="350" >Gibraltar</option>
                <option value="30" >Greece</option>
                <option value="299" >Greenland</option>
                <option value="1" >Grenada</option>
                <option value="590" >Guadeloupe</option>
                <option value="1" >Guam</option>
                <option value="502" >Guatemala</option>
                <option value="224" >Guinea</option>
                <option value="245" >Guinea-Bissau</option>
                <option value="592" >Guyana</option>
                <option value="509" >Haiti</option>
                <option value="504" >Honduras</option>
                <option value="852" >Hong Kong SAR China</option>
                <option value="36" >Hungary</option>
                <option value="354" >Iceland</option>
                <option value="91" >India</option>
                <option value="62" >Indonesia</option>
                <option value="870" >Inmarsat</option>
                <option value="98" >Iran</option>
                <option value="964" >Iraq</option>
                <option value="353" >Ireland</option>
                <option value="881" >Iridium</option>
                <option value="44" >Isle of Man</option>
                <option value="972" >Israel</option>
                <option value="39" >Italy</option>
                <option value="1" >Jamaica</option>
                <option value="81" >Japan</option>
                <option value="44" >Jersey</option>
                <option value="962" >Jordan</option>
                <option value="7" >Kazakhstan</option>
                <option value="254" >Kenya</option>
                <option value="686" >Kiribati</option>
                <option value="386" >Kosovo</option>
                <option value="965" >Kuwait</option>
                <option value="996" >Kyrgyzstan</option>
                <option value="856" >Laos</option>
                <option value="371" >Latvia</option>
                <option value="961" >Lebanon</option>
                <option value="266" >Lesotho</option>
                <option value="231" >Liberia</option>
                <option value="218" >Libya</option>
                <option value="423" >Liechtenstein</option>
                <option value="370" >Lithuania</option>
                <option value="352" >Luxembourg</option>
                <option value="853" >Macau SAR China</option>
                <option value="389" >Macedonia</option>
                <option value="261" >Madagascar</option>
                <option value="265" >Malawi</option>
                <option value="60" >Malaysia</option>
                <option value="960" >Maldives</option>
                <option value="223" >Mali</option>
                <option value="356" >Malta</option>
                <option value="596" >Martinique</option>
                <option value="222" >Mauritania</option>
                <option value="230" >Mauritius</option>
                <option value="262" >Mayotte</option>
                <option value="52" >Mexico</option>
                <option value="691" >Micronesia</option>
                <option value="373" >Moldova</option>
                <option value="377" >Monaco</option>
                <option value="976" >Mongolia</option>
                <option value="382" >Montenegro</option>
                <option value="1" >Montserrat</option>
                <option value="212" >Morocco</option>
                <option value="258" >Mozambique</option>
                <option value="95" >Myanmar (Burma)</option>
                <option value="264" >Namibia</option>
                <option value="674" >Nauru</option>
                <option value="977" >Nepal</option>
                <option value="31" >Netherlands</option>
                <option value="687" >New Caledonia</option>
                <option value="64" >New Zealand</option>
                <option value="505" >Nicaragua</option>
                <option value="227" >Niger</option>
                <option value="234" >Nigeria</option>
                <option value="672" >Norfolk Island</option>
                <option value="1" >Northern Mariana Islands</option>
                <option value="47" >Norway</option>
                <option value="968" >Oman</option>
                <option value="92" >Pakistan</option>
                <option value="970" >Palestinian Territories</option>
                <option value="507" >Panama</option>
                <option value="675" >Papua New Guinea</option>
                <option value="595" >Paraguay</option>
                <option value="51" >Peru</option>
                <option value="63" >Philippines</option>
                <option value="48" >Poland</option>
                <option value="351" >Portugal</option>
                <option value="1" >Puerto Rico</option>
                <option value="974" >Qatar</option>
                <option value="40" >Romania</option>
                <option value="7" >Russia</option>
                <option value="250" >Rwanda</option>
                <option value="262" >Réunion</option>
                <option value="685" >Samoa</option>
                <option value="378" >San Marino</option>
                <option value="966" >Saudi Arabia</option>
                <option value="221" >Senegal</option>
                <option value="381" >Serbia</option>
                <option value="248" >Seychelles</option>
                <option value="232" >Sierra Leone</option>
                <option value="65" >Singapore</option>
                <option value="1" >Sint Maarten</option>
                <option value="421" >Slovakia</option>
                <option value="386" >Slovenia</option>
                <option value="677" >Solomon Islands</option>
                <option value="252" >Somalia</option>
                <option value="27" >South Africa</option>
                <option value="82" >South Korea</option>
                <option value="211" >South Sudan</option>
                <option value="34" >Spain</option>
                <option value="94" >Sri Lanka</option>
                <option value="1" >St. Kitts &amp; Nevis</option>
                <option value="1" >St. Lucia</option>
                <option value="590" >St. Martin</option>
                <option value="1" >St. Vincent &amp; Grenadines</option>
                <option value="597" >Suriname</option>
                <option value="268" >Swaziland</option>
                <option value="46" >Sweden</option>
                <option value="41" >Switzerland</option>
                <option value="239" >São Tomé &amp; Príncipe</option>
                <option value="886" >Taiwan</option>
                <option value="992" >Tajikistan</option>
                <option value="255" >Tanzania</option>
                <option value="66" >Thailand</option>
                <option value="882" >Thuraya</option>
                <option value="670" >Timor-Leste</option>
                <option value="228" >Togo</option>
                <option value="676" >Tonga</option>
                <option value="1" >Trinidad &amp; Tobago</option>
                <option value="216" >Tunisia</option>
                <option value="90" >Turkey</option>
                <option value="993" >Turkmenistan</option>
                <option value="1" >Turks &amp; Caicos Islands</option>
                <option value="688" >Tuvalu</option>
                <option value="1" >U.S. Virgin Islands</option>
                <option value="256" >Uganda</option>
                <option value="380" >Ukraine</option>
                <option value="971" >United Arab Emirates</option>
                <option value="44" >United Kingdom</option>
                <option value="1" >United States</option>
                <option value="598" >Uruguay</option>
                <option value="998" >Uzbekistan</option>
                <option value="678" >Vanuatu</option>
                <option value="58" >Venezuela</option>
                <option value="84" >Vietnam</option>
                <option value="967" >Yemen</option>
                <option value="260" >Zambia</option>
                <option value="263" >Zimbabwe</option>
          </select>
        </div>
      </div>
      <div class="control-group">
        <label for="phone_number" class="t1-label control-label">Phone number</label>
        <div class="controls">
          <div class="input-prepend">
            <span class="add-on" id="country_code_display">+20</span>
            <input type="hidden" id="country_code" name="country_code" value="20">
            <input class="input-medium" name="phone_number" id="phone_number" value="">
          </div>
        </div>
      </div>
     <div>
      Standard text message charges may apply depending on your mobile carrier. Your number will not be shown publicly. At first, others will be able to find you by your phone number; however, you can change your privacy settings at any time.
     </div>
    </form>
  </div><!-- modal body -->
  <div class="modal-footer">
    <button id="send_verification_pin" class="EdgeButton EdgeButton--primary" type="submit" disabled>Continue</button>
  </div>
</div>

      <div id="sms-confirmation-complete-modal">
  <div class="modal-header">
    <h3 class="modal-title">Check your phone</h3>
  </div>
  <div class="modal-body">
    <div class="settings-alert" id="sms-alert-box">
      <span id="sms-alert-close" class="Icon Icon--close Icon--smallest close"></span>
      <h4 id="sms-alert-message"></h4>
    </div>
    <p>We’ve texted a code to +<span id="phone_number_to_verify"></span>. Enter the code below to confirm your identity and unlock your account.</p>
  </div>
  <div class="modal-body">
    <form id="sms-confirmation-complete-form" class="t1-form form-horizontal sms-form">
      <input type="hidden" name="device_type" value="phone">
      <input type="hidden" id="authenticity_token" name="authenticity_token" value="a587a1e6111389fec0f83e815383d80da89d6907">
      <div class="control-group" id="numeric_pin">
        <label for="numeric_pin_raw" class="t1-label control-label numeric-pin-label">Confirmation Code</label>
        <div class="controls">
          <input id="numeric_pin_raw">
        </div>
      </div>
      <div class="form-actions">
        <p><button type="button" class="btn-link" id="resend_code">Resend code</button></p>
      </div>
    </form>
  </div><!-- modal body -->
  <div class="modal-footer">
    <button id="device_verify" class="EdgeButton EdgeButton--primary" type="submit" disabled>Confirm identity</button>
  </div>
</div>
    </div>
  </div>
</div>

  <div id="bouncer-dialog" class="modal-container">
  <div class="modal modal-medium draggable">
    <div class="modal-content" aria-live="assertive">
      <button type="button" class="modal-btn modal-close js-close">
  <span class="Icon Icon--close Icon--medium">
    <span class="visuallyhidden">Close</span>
  </span>
</button>

      <div class="modal-header"><h3 class="modal-title">&nbsp;</h3></div>
      <div class="BouncerSpinner">
        <div class="BouncerSpinner-image"></div>
      </div>
      <div class="BouncerContent">
        <iframe src="" id="bouncer-flow"></iframe>
      </div>
    </div>
  </div>
</div>

  <div id="delete-tweet-dialog" class="modal-container">
  <div class="modal draggable">
    <div class="modal-content">
      <button type="button" class="modal-btn modal-close js-close">
  <span class="Icon Icon--close Icon--medium">
    <span class="visuallyhidden">Close</span>
  </span>
</button>


      <div class="modal-header">
        <h3 class="modal-title">Are you sure you want to delete this Tweet?</h3>
      </div>

      <div class="tweet-loading">
  <div class="spinner-bigger"></div>
</div>

      <div class="modal-body modal-tweet"></div>

      <div class="modal-footer">
        <button class="EdgeButton EdgeButton--tertiary cancel-action js-close">Cancel</button>
        <button class="EdgeButton EdgeButton--danger delete-action">Delete</button>
      </div>
    </div>
  </div>
</div>

  <div id="generic-notification-dialog" class="modal-container GenericNotificationDialog">
  <div class="close-modal-background-target"></div>
  <div class="GenericNotificationDialog-modal modal is-autoPosition">
    <div class="GenericNotificationDialog-contentContainer">
      <button type="button" class="GenericNotificationDialog-close modal-btn js-close modal-close">
        <span class="Icon Icon--close Icon--large"></span>
      </button>
      <div class="GenericNotificationDialog-modalContent modal-content">
          <iframe id="generic-notification-frame" class="GenericNotificationDialog-iframe" scrolling="auto" src></iframe>
      </div>
    </div>
  </div>
</div>


<div id="leadgen-confirm-dialog" class="modal-container">
  <div class="modal draggable">
    <div class="modal-content">
      <button type="button" class="modal-btn modal-close js-close">
  <span class="Icon Icon--close Icon--medium">
    <span class="visuallyhidden">Close</span>
  </span>
</button>

      <div class="modal-header">
        <h3 class="modal-title">Confirmation</h3>
      </div>
      <div class="modal-body">
        <div class="leadgen-card-container">
          <div class="media">
            <iframe
              class="cards2-promotion-iframe"
              scrolling="no"
              frameborder="0"
              src="">
            </iframe>
          </div>
        </div>
        <div class="js-macaw-cards-iframe-container" data-card-name="promotion">
        </div>
      </div>
    </div>
  </div>
</div>


<div id="auth-webview-dialog" class="AuthWebViewDialog modal-container">
  <div class="modal draggable">
    <div class="modal-content">
      <button type="button" class="modal-btn modal-close modal-close-fixed js-close">
  <span class="Icon Icon--close Icon--large">
    <span class="visuallyhidden">Close</span>
  </span>
</button>

      <div class="modal-header">
        <h3 class="modal-title">&nbsp;</h3>
      </div>
      <div class="modal-body">
        <div class="auth-webview-view-container">
          <div class="media">
            <iframe
              class="auth-webview-card-iframe js-initial-focus"
              scrolling="no"
              frameborder="0"
              width="590px"
              height="500px"
              src="">
            </iframe>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>



<div id="promptbird-modal-prompt" class="modal-container">
  <div class="modal">

    <button type="button" class="modal-btn js-promptDismiss modal-close js-close">
      <span class="Icon Icon--close Icon--medium">
        <span class="visuallyhidden">Close</span>
      </span>
    </button>
    <div class="modal-content"></div>
  </div>
</div>


<div id="ui-walkthrough-dialog" class="modal-container UIWalkthrough">
  <div class="UIWalkthrough-clickBlocker"></div>
  <div class="modal modal-small">
    <div class="UIWalkthrough-caret"></div>
    <div class="modal-content">
      <div class="modal-body">
        <div class="UIWalkthrough-header">
          <span class="UIWalkthrough-stepProgress"></span>
          <button class="UIWalkthrough-skip js-close">
            Skip all
          </button>
        </div>




<div class="UIWalkthrough-step UIWalkthrough-step--welcome">
  <h3 class="UIWalkthrough-title">
    <span class="Icon Icon--home UIWalkthrough-icon"></span>
    Welcome home!
  </h3>
  <p class="UIWalkthrough-message">This timeline is where you’ll spend most of your time, getting instant updates about what matters to you.</p>
</div>



<div class="UIWalkthrough-step UIWalkthrough-step--unfollow">
  <h3 class="UIWalkthrough-title">
    <span class="Icon Icon--smileRating1Fill UIWalkthrough-icon"></span>
    Tweets not working for you?
  </h3>
  <p class="UIWalkthrough-message">
    Hover over the profile pic and click the Following button to unfollow any account.
  </p>
</div>

<div class="UIWalkthrough-step UIWalkthrough-step--like">

  <h3 class="UIWalkthrough-title">
    <span class="Icon Icon--heart UIWalkthrough-icon"></span>
    Say a lot with a little
  </h3>
  <p class="UIWalkthrough-message">
    When you see a Tweet you love, tap the heart — it lets  the person who wrote it know you shared the love.
  </p>
</div>

<div class="UIWalkthrough-step UIWalkthrough-step--retweet">
  <h3 class="UIWalkthrough-title">
    <span class="Icon Icon--retweet UIWalkthrough-icon"></span>
    Spread the word
  </h3>
  <p class="UIWalkthrough-message">
    The fastest way to share someone else’s Tweet with your followers is with a Retweet. Tap the icon to send it instantly.
  </p>
</div>

<div class="UIWalkthrough-step UIWalkthrough-step--reply">
  <h3 class="UIWalkthrough-title">
    <span class="Icon Icon--reply UIWalkthrough-icon"></span>
    Join the conversation
  </h3>
  <p class="UIWalkthrough-message">
    Add your thoughts about any Tweet with a Reply. Find a topic you’re passionate about, and jump right in.
  </p>
</div>



<div class="UIWalkthrough-step UIWalkthrough-step--trends">
  <h3 class="UIWalkthrough-title">
    <span class="Icon Icon--discover UIWalkthrough-icon"></span>
    Learn the latest
  </h3>
  <p class="UIWalkthrough-message">
    Get instant insight into what people are talking about now.
  </p>
</div>

<div class="UIWalkthrough-step UIWalkthrough-step--wtf">
  <h3 class="UIWalkthrough-title">
    <span class="Icon Icon--follow UIWalkthrough-icon"></span>
    Get more of what you love
  </h3>
  <p class="UIWalkthrough-message">
    Follow more accounts to get instant updates about topics you care about.
  </p>
</div>

<div class="UIWalkthrough-step UIWalkthrough-step--search">
  <h3 class="UIWalkthrough-title">
    <span class="Icon Icon--search UIWalkthrough-icon"></span>
    Find what"s happening
  </h3>
  <p class="UIWalkthrough-message">
    See the latest conversations about any topic instantly.
  </p>
</div>

<div class="UIWalkthrough-step UIWalkthrough-step--moments">
  <h3 class="UIWalkthrough-title">
    <span class="Icon Icon--lightning UIWalkthrough-icon"></span>
    Never miss a Moment
  </h3>
  <p class="UIWalkthrough-message">
    Catch up instantly on the best stories happening as they unfold.
  </p>
</div>
      </div>

      <div class="modal-footer">
        <button class="EdgeButton EdgeButton--tertiary u-floatLeft plain-btn UIWalkthrough-button js-previous-step">Back</button>
        <button class="EdgeButton EdgeButton--secondary UIWalkthrough-button js-next-step js-initial-focus">Next</button>
      </div>
    </div>
  </div>
</div>


  <div id="translation-feedback-dialog" class="TranslationFeedbackDialog modal-container">
  <div class="modal modal-medium draggable">
    <div class="modal-content">
      <button type="button" class="modal-btn modal-close js-close">
  <span class="Icon Icon--close Icon--medium">
    <span class="visuallyhidden">Close</span>
  </span>
</button>

      <div class="modal-header">
        <h3 class="modal-title embed-tweet-title">Translation Feedback</h3>
      </div>
      <div class="modal-body modal-tweet tweet"></div>
      <div class="modal-body TranslationFeedbackDialog-form">
        <div class="TranslationFeedbackDialog-sourceLanguage">
          <label for="source_lang">Translated from</label>
          <select class="t1-select" id="source_lang" name="source_lang">
              <option value="ar">Arabic</option>
              <option value="en-gb">British English</option>
              <option value="bg">Bulgarian</option>
              <option value="ca">Catalan</option>
              <option value="cs">Czech</option>
              <option value="da">Danish</option>
              <option value="nl">Dutch</option>
              <option value="et">Estonian</option>
              <option value="fi">Finnish</option>
              <option value="fr">French</option>
              <option value="de">German</option>
              <option value="el">Greek</option>
              <option value="ht">Haitian Creole</option>
              <option value="he">Hebrew</option>
              <option value="hi">Hindi</option>
              <option value="hu">Hungarian</option>
              <option value="id">Indonesian</option>
              <option value="it">Italian</option>
              <option value="ja">Japanese</option>
              <option value="ko">Korean</option>
              <option value="lv">Latvian</option>
              <option value="lt">Lithuanian</option>
              <option value="no">Norwegian</option>
              <option value="fa">Persian</option>
              <option value="pl">Polish</option>
              <option value="pt">Portuguese</option>
              <option value="ro">Romanian</option>
              <option value="ru">Russian</option>
              <option value="zh-cn">Simplified Chinese</option>
              <option value="sk">Slovak</option>
              <option value="sl">Slovenian</option>
              <option value="es">Spanish</option>
              <option value="sv">Swedish</option>
              <option value="th">Thai</option>
              <option value="zh-tw">Traditional Chinese</option>
              <option value="tr">Turkish</option>
              <option value="uk">Ukrainian</option>
              <option value="ur">Urdu</option>
              <option value="vi">Vietnamese</option>
          </select>
        </div>

        <textarea class="TranslationFeedbackDialog-translationInput js-initial-focus"></textarea>

        Your feedback will be used to improve translation quality. Thank you.
      </div>
      <div class="modal-footer">
        <button class="EdgeButton EdgeButton--tertiary cancel-action js-close">Cancel</button>
        <button class="EdgeButton EdgeButton--primary modal-submit">Submit</button>
      </div>
    </div>
  </div>
</div>


  <div id="moments-summary-list-dialog" class="MomentCapsuleSummaryListDialog modal-container">
  <div class="modal modal-medium">
    <div class="modal-content">
      <div class="modal-header MomentCapsuleSummaryListDialog-header">
        <div class="MomentCapsuleSummaryListDialog-title">Add to Moment</div>

        <button type="button" class="EdgeButton EdgeButton--primary EdgeButton--small MomentCapsuleSummaryListDialog-button MomentCapsuleSummaryListDialog-button--newMoment">New Moment</button>

        <button type="button" class="modal-close js-close MomentCapsuleSummaryListDialog-button u-textUserColorHover">
          <span class="Icon Icon--close Icon--medium">
            <span class="visuallyhidden">Close</span>
          </span>
        </button>
      </div>

      <div class="modal-body">
        <div class="MomentCapsuleSummaryListDialog-listContainer">
          <div class="MomentCapsuleSummaryListDialog-items">

          </div>
          <span class="spinner" title="Loading..."></span>
        </div>
      </div>
    </div>
  </div>
</div>


<div id="create-custom-timeline-dialog" class="modal-container"></div>
<div id="edit-custom-timeline-dialog" class="modal-container"></div>
<div id="curate-dialog" class="modal-container"></div>
<div id="media-edit-dialog" class="modal-container"></div>


      <div class="PermalinkOverlay PermalinkOverlay-with-background " id="permalink-overlay">
  <div class="PermalinkProfile-dismiss modal-close-fixed">
    <span class="Icon Icon--close"></span>
  </div>
  <button class="PermalinkOverlay-next PermalinkOverlay-button u-posFixed js-next" type="button">
    <span class="Icon Icon--caretLeft Icon--large"></span>
    <span class="u-hiddenVisually">Next Tweet from user</span>
  </button>
  <div class="PermalinkOverlay-modal">
    <div class="PermalinkOverlay-spinnerContainer u-hidden">
      <div class="PermalinkOverlay-spinner"></div>
    </div>
    <div class="PermalinkOverlay-content">
      <div class="PermalinkOverlay-body"
>
      </div>
    </div>
  </div>
</div>

    <div class="hidden" id="hidden-content">
  <iframe aria-hidden="true" class="tweet-post-iframe" name="tweet-post-iframe"></iframe>
  <iframe aria-hidden="true" class="dm-post-iframe" name="dm-post-iframe"></iframe>


  <div id="inline-reply-tweetbox">
      <form class="t1-form tweet-form condensed"
  method="post"
  target="tweet-post-iframe"
  action="//upload.twitter.com/i/tweet/create_with_media.iframe"
  enctype="multipart/form-data"
  data-poll-composer-rows="3"
>

  <div class="reply-users">Replying to <button type="button" class="btn-link reply-users-btn js-tooltip" data-original-title="Select who gets your reply"></button>
</div>

  <div class="tweet-content">
      <img class="inline-reply-user-image avatar size32" src="https://pbs.twimg.com/profile_images/878491891733549056/3urQeq4j_normal.jpg" alt="Mohammed Attya">
    <div class="ComposerDragHelp">
  <span class="ComposerDragHelp-text"></span>
</div>
    <span class="visuallyhidden" id="tweet-box-template-label">Tweet text</span>

<div class="RichEditor RichEditor--emojiPicker ">

  <div class="RichEditor-mozillaCursorWorkaround">&nbsp;</div>
  <div class="RichEditor-container u-borderRadiusInherit">



<div role="listbox" class="dropdown-menu typeahead">
  <div aria-hidden="true" class="dropdown-caret">
    <div class="caret-outer"></div>
    <div class="caret-inner"></div>
  </div>
  <div role="presentation" class="dropdown-inner js-typeahead-results">
      <div role="presentation" class="typeahead-recent-searches">
  <h3 id="recent-searches-heading" class="typeahead-category-title recent-searches-title">Recent searches</h3><button type="button" tabindex="-1" class="btn-link clear-recent-searches">Clear All</button>
  <ul role="presentation" class="typeahead-items recent-searches-list">

    <li role="presentation" class="typeahead-item typeahead-recent-search-item">
      <span class="Icon Icon--close" aria-hidden="true"><span class="visuallyhidden">Remove</span></span>
      <a role="option" aria-describedby="recent-searches-heading" class="js-nav" href="" data-search-query="" data-query-source="" data-ds="recent_search" tabindex="-1"></a>
    </li>
  </ul>
</div>

    <div role="presentation" class="typeahead-saved-searches">
  <h3 id="saved-searches-heading" class="typeahead-category-title saved-searches-title">Saved searches</h3>
  <ul role="presentation" class="typeahead-items saved-searches-list">

    <li role="presentation" class="typeahead-item typeahead-saved-search-item">
      <span class="Icon Icon--close" aria-hidden="true"><span class="visuallyhidden">Remove</span></span>
      <a role="option" aria-describedby="saved-searches-heading" class="js-nav" href="" data-search-query="" data-query-source="" data-ds="saved_search" tabindex="-1"></a>
    </li>
  </ul>
</div>

    <ul role="presentation" class="typeahead-items typeahead-topics">

  <li role="presentation" class="typeahead-item typeahead-topic-item">
    <a role="option" class="js-nav" href="" data-search-query="" data-query-source="typeahead_click" data-ds="topics" tabindex="-1"></a>
  </li>
</ul>
    <ul role="presentation" class="typeahead-items typeahead-accounts social-context js-typeahead-accounts">

  <li role="presentation" data-user-id="" data-user-screenname="" data-remote="true" data-score="" class="typeahead-item typeahead-account-item js-selectable">

    <a role="option" class="js-nav" data-query-source="typeahead_click" data-search-query="" data-ds="account">
      <div class="js-selectable typeahead-in-conversation hidden">
        <span class="Icon Icon--follower Icon--small"></span>
        <span class="typeahead-in-conversation-text">In this conversation</span>
      </div>
      <img class="avatar size32" alt="">
      <span class="typeahead-user-item-info account-group">
        <span class="fullname"></span><span class="UserBadges"><span class="Icon Icon--verified js-verified hidden"><span class="u-hiddenVisually">Verified account</span></span><span class="Icon Icon--protected js-protected hidden"><span class="u-hiddenVisually">Protected Tweets</span></span></span><span class="UserNameBreak">&nbsp;</span><span class="username u-dir" dir="ltr">@<b></b></span>
      </span>
      <span class="typeahead-social-context"></span>
    </a>
  </li>
  <li role="presentation" class="js-selectable typeahead-accounts-shortcut js-shortcut"><a role="option" class="js-nav" href="" data-search-query="" data-query-source="typeahead_click" data-shortcut="true" data-ds="account_search"></a></li>
</ul>

    <ul role="presentation" class="typeahead-items typeahead-trend-locations-list">

  <li role="presentation" class="typeahead-item typeahead-trend-locations-item"><a role="option" class="js-nav" href="" data-ds="trend_location" data-search-query="" tabindex="-1"></a></li>
</ul>

<div role="presentation" class="typeahead-user-select">
  <div role="presentation" class="typeahead-empty-suggestions">
    Suggested users
  </div>
  <ul role="presentation" class="typeahead-items typeahead-selected js-typeahead-selected">

    <li role="presentation" data-user-id="" data-user-screenname="" data-remote="true" data-score="" class="typeahead-item typeahead-selected-item js-selectable">

      <a role="option" class="js-nav" data-query-source="typeahead_click" data-search-query="" data-ds="account">
        <img class="avatar size32" alt="">
        <span class="typeahead-user-item-info account-group">
          <span class="select-status deselect-user js-deselect-user Icon Icon--check"></span>
          <span class="select-status select-disabled Icon Icon--unfollow"></span>
          <span class="fullname"></span><span class="UserBadges"><span class="Icon Icon--verified js-verified hidden"><span class="u-hiddenVisually">Verified account</span></span><span class="Icon Icon--protected js-protected hidden"><span class="u-hiddenVisually">Protected Tweets</span></span></span><span class="UserNameBreak">&nbsp;</span><span class="username u-dir" dir="ltr">@<b></b></span>
        </span>
      </a>
    </li>
    <li role="presentation" class="typeahead-selected-end"></li>
  </ul>

  <ul role="presentation" class="typeahead-items typeahead-accounts js-typeahead-accounts">

    <li role="presentation" data-user-id="" data-user-screenname="" data-remote="true" data-score="" class="typeahead-item typeahead-account-item js-selectable">

      <a role="option" class="js-nav" data-query-source="typeahead_click" data-search-query="" data-ds="account">
        <img class="avatar size32" alt="">
        <span class="typeahead-user-item-info account-group">
          <span class="select-status deselect-user js-deselect-user Icon Icon--check"></span>
          <span class="select-status select-disabled Icon Icon--unfollow"></span>
          <span class="fullname"></span><span class="UserBadges"><span class="Icon Icon--verified js-verified hidden"><span class="u-hiddenVisually">Verified account</span></span><span class="Icon Icon--protected js-protected hidden"><span class="u-hiddenVisually">Protected Tweets</span></span></span><span class="UserNameBreak">&nbsp;</span><span class="username u-dir" dir="ltr">@<b></b></span>
        </span>
      </a>
    </li>
    <li role="presentation" class="typeahead-accounts-end"></li>
  </ul>
</div>

    <div role="presentation" class="typeahead-dm-conversations">
  <ul role="presentation" class="typeahead-items typeahead-dm-conversation-items">
    <li role="presentation" class="typeahead-item typeahead-dm-conversation-item">
      <a role="option" tabindex="-1"></a>
    </li>
  </ul>
</div>
  </div>
</div>


    <div class="RichEditor-scrollContainer u-borderRadiusInherit">
              <div
          aria-labelledby="tweet-box-template-label"
          name="tweet"
          id="tweet-box-template"
          class="tweet-box rich-editor"
          contenteditable="true"
          spellcheck="true"
          role="textbox"
          aria-multiline="true"
          data-placeholder-default="What’s happening?"
          data-placeholder-poll-composer-on="Ask a question..."
          data-placeholder-reply="Tweet your reply"
        ></div>

      <div class="RichEditor-pictographs" aria-hidden="true"></div>
    </div>

            <div class="RichEditor-rightItems RichEditor-bottomItems">
            <div class="EmojiPicker dropdown is-loading">
  <button type="button" class="EmojiPicker-trigger js-dropdown-toggle js-tooltip u-textUserColorHover"
      title="Add emoji" data-delay="150">
    <span class="Icon Icon--smiley"></span>
    <span class="text u-hiddenVisually">
      Add emoji
    </span>
  </button>
  <div class="EmojiPicker-dropdownMenu dropdown-menu" tabindex="-1">
    <div class="EmojiPicker-content Caret Caret--stroked"></div>
  </div>
</div>

        </div>

  </div>
  <div class="RichEditor-mozillaCursorWorkaround">&nbsp;</div>
</div>


    <textarea aria-hidden="true" class="tweet-box-shadow hidden" name="status"></textarea>

    <div class="TweetBoxAttachments">

      <div class="thumbnail-container">
  <div class="thumbnail-wrapper">
    <div class="ComposerThumbnails"></div>
    <div class="preview-message">
      <button type="button" class="start-tagging js-open-user-select no-users u-borderUserColorLight u-textUserColor" disabled>
        <span class="Icon Icon--me Icon--small"></span>
        <span class="tagged-users">
          Who"s in these photos?
        </span>
      </button>
    </div>
    <div class="js-attribution attribution"></div>
    <div class="ComposerVideoInfo u-hidden"></div>
  </div>
</div>
<div class="photo-tagging-container user-select-container dropdown-menu hidden">
  <div class="tagging-dropdown">
    <div class="dropdown-caret center">
      <div class="caret-outer"></div>
      <div class="caret-inner"></div>
    </div>
    <div class="photo-tagging-controls user-select-controls">
      <label class="t1-label">
        <span class="Icon Icon--search nav-search"></span>
        <span class="u-hiddenVisually">Users in this photo</span>
        <input class="js-initial-focus" type="text" placeholder="Search and tag up to 10 people">
      </label>
    </div>
    <div class="typeahead-container">



<div role="listbox" class="dropdown-menu typeahead">
  <div aria-hidden="true" class="dropdown-caret">
    <div class="caret-outer"></div>
    <div class="caret-inner"></div>
  </div>
  <div role="presentation" class="dropdown-inner js-typeahead-results">
      <div role="presentation" class="typeahead-recent-searches">
  <h3 id="recent-searches-heading" class="typeahead-category-title recent-searches-title">Recent searches</h3><button type="button" tabindex="-1" class="btn-link clear-recent-searches">Clear All</button>
  <ul role="presentation" class="typeahead-items recent-searches-list">

    <li role="presentation" class="typeahead-item typeahead-recent-search-item">
      <span class="Icon Icon--close" aria-hidden="true"><span class="visuallyhidden">Remove</span></span>
      <a role="option" aria-describedby="recent-searches-heading" class="js-nav" href="" data-search-query="" data-query-source="" data-ds="recent_search" tabindex="-1"></a>
    </li>
  </ul>
</div>

    <div role="presentation" class="typeahead-saved-searches">
  <h3 id="saved-searches-heading" class="typeahead-category-title saved-searches-title">Saved searches</h3>
  <ul role="presentation" class="typeahead-items saved-searches-list">

    <li role="presentation" class="typeahead-item typeahead-saved-search-item">
      <span class="Icon Icon--close" aria-hidden="true"><span class="visuallyhidden">Remove</span></span>
      <a role="option" aria-describedby="saved-searches-heading" class="js-nav" href="" data-search-query="" data-query-source="" data-ds="saved_search" tabindex="-1"></a>
    </li>
  </ul>
</div>

    <ul role="presentation" class="typeahead-items typeahead-topics">

  <li role="presentation" class="typeahead-item typeahead-topic-item">
    <a role="option" class="js-nav" href="" data-search-query="" data-query-source="typeahead_click" data-ds="topics" tabindex="-1"></a>
  </li>
</ul>
    <ul role="presentation" class="typeahead-items typeahead-accounts social-context js-typeahead-accounts">

  <li role="presentation" data-user-id="" data-user-screenname="" data-remote="true" data-score="" class="typeahead-item typeahead-account-item js-selectable">

    <a role="option" class="js-nav" data-query-source="typeahead_click" data-search-query="" data-ds="account">
      <div class="js-selectable typeahead-in-conversation hidden">
        <span class="Icon Icon--follower Icon--small"></span>
        <span class="typeahead-in-conversation-text">In this conversation</span>
      </div>
      <img class="avatar size32" alt="">
      <span class="typeahead-user-item-info account-group">
        <span class="fullname"></span><span class="UserBadges"><span class="Icon Icon--verified js-verified hidden"><span class="u-hiddenVisually">Verified account</span></span><span class="Icon Icon--protected js-protected hidden"><span class="u-hiddenVisually">Protected Tweets</span></span></span><span class="UserNameBreak">&nbsp;</span><span class="username u-dir" dir="ltr">@<b></b></span>
      </span>
      <span class="typeahead-social-context"></span>
    </a>
  </li>
  <li role="presentation" class="js-selectable typeahead-accounts-shortcut js-shortcut"><a role="option" class="js-nav" href="" data-search-query="" data-query-source="typeahead_click" data-shortcut="true" data-ds="account_search"></a></li>
</ul>

    <ul role="presentation" class="typeahead-items typeahead-trend-locations-list">

  <li role="presentation" class="typeahead-item typeahead-trend-locations-item"><a role="option" class="js-nav" href="" data-ds="trend_location" data-search-query="" tabindex="-1"></a></li>
</ul>

<div role="presentation" class="typeahead-user-select">
  <div role="presentation" class="typeahead-empty-suggestions">
    Suggested users
  </div>
  <ul role="presentation" class="typeahead-items typeahead-selected js-typeahead-selected">

    <li role="presentation" data-user-id="" data-user-screenname="" data-remote="true" data-score="" class="typeahead-item typeahead-selected-item js-selectable">

      <a role="option" class="js-nav" data-query-source="typeahead_click" data-search-query="" data-ds="account">
        <img class="avatar size32" alt="">
        <span class="typeahead-user-item-info account-group">
          <span class="select-status deselect-user js-deselect-user Icon Icon--check"></span>
          <span class="select-status select-disabled Icon Icon--unfollow"></span>
          <span class="fullname"></span><span class="UserBadges"><span class="Icon Icon--verified js-verified hidden"><span class="u-hiddenVisually">Verified account</span></span><span class="Icon Icon--protected js-protected hidden"><span class="u-hiddenVisually">Protected Tweets</span></span></span><span class="UserNameBreak">&nbsp;</span><span class="username u-dir" dir="ltr">@<b></b></span>
        </span>
      </a>
    </li>
    <li role="presentation" class="typeahead-selected-end"></li>
  </ul>

  <ul role="presentation" class="typeahead-items typeahead-accounts js-typeahead-accounts">

    <li role="presentation" data-user-id="" data-user-screenname="" data-remote="true" data-score="" class="typeahead-item typeahead-account-item js-selectable">

      <a role="option" class="js-nav" data-query-source="typeahead_click" data-search-query="" data-ds="account">
        <img class="avatar size32" alt="">
        <span class="typeahead-user-item-info account-group">
          <span class="select-status deselect-user js-deselect-user Icon Icon--check"></span>
          <span class="select-status select-disabled Icon Icon--unfollow"></span>
          <span class="fullname"></span><span class="UserBadges"><span class="Icon Icon--verified js-verified hidden"><span class="u-hiddenVisually">Verified account</span></span><span class="Icon Icon--protected js-protected hidden"><span class="u-hiddenVisually">Protected Tweets</span></span></span><span class="UserNameBreak">&nbsp;</span><span class="username u-dir" dir="ltr">@<b></b></span>
        </span>
      </a>
    </li>
    <li role="presentation" class="typeahead-accounts-end"></li>
  </ul>
</div>

    <div role="presentation" class="typeahead-dm-conversations">
  <ul role="presentation" class="typeahead-items typeahead-dm-conversation-items">
    <li role="presentation" class="typeahead-item typeahead-dm-conversation-item">
      <a role="option" tabindex="-1"></a>
    </li>
  </ul>
</div>
  </div>
</div>

    </div>
  </div>
</div>



      <div class="CardComposer">
          <div class="PollingCardComposer u-hidden"
  data-poll-min-duration="5" data-poll-max-duration="10080"
>
  <div class="PollingCardComposer-option PollingCardComposer-option1" data-option-index="0">
    <input type="radio" class= "PollingCardComposer-optionRadio" disabled>
    <div class="PollingCardComposer-optionInput is-singleLine is-plainText u-borderUserColorLightFocus" contenteditable="true" spellcheck="true" role="textbox" data-placeholder="Choice 1"></div>
    <div style="clear: both"></div>
  </div>
  <div class="PollingCardComposer-option PollingCardComposer-option2" data-option-index="1">
    <input type="radio" class= "PollingCardComposer-optionRadio" disabled>
    <div class="PollingCardComposer-optionInput is-singleLine is-plainText u-borderUserColorLightFocus" contenteditable="true" spellcheck="true" role="textbox" data-placeholder="Choice 2"></div>
    <div style="clear: both"></div>
  </div>
  <div class="PollingCardComposer-option PollingCardComposer-option3" data-option-index="2">
    <input type="radio" class= "PollingCardComposer-optionRadio" disabled>
    <div class="PollingCardComposer-optionInput is-singleLine is-plainText u-borderUserColorLightFocus" contenteditable="true" spellcheck="true" role="textbox" data-placeholder="Choice 3 (optional)"></div>
    <button type="button" class="PollingCardComposer-removeOption">
      <span class="Icon Icon--close"></span>
    </button>
    <div style="clear: both"></div>
  </button>
  </div>
  <div class="PollingCardComposer-option PollingCardComposer-option4" data-option-index="3">
    <input type="radio" class= "PollingCardComposer-optionRadio" disabled>
    <div class="PollingCardComposer-optionInput is-singleLine is-plainText u-borderUserColorLightFocus" contenteditable="true" spellcheck="true" role="textbox" data-placeholder="Choice 4 (optional)"></div>
    <button type="button" class="PollingCardComposer-removeOption">
      <span class="Icon Icon--close"></span>
    </button>
    <div style="clear: both"></div>
  </div>
  <button type="button" class="PollingCardComposer-addOption u-textUserColor">
    <span>+</span>&nbsp;<span>Add a choice</span>
  </button>
  <div class="PollingCardComposer-pollDuration">
    <span class="PollingCardComposer-durationLabel">Poll length:&nbsp;</span>
    <button type="button" class="PollingCardComposer-defaultDuration u-textUserColor">1 day</button>
    <div class="PollingCardComposer-customDuration">
      <span class="PollingCardComposer-customDuration--daysLabel">Days</span>
      <select class="PollingCardComposer-customDuration--days u-borderUserColorLight" data-duration-target="days">
        <option value="0">0</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
      </select>
      <spann class="PollingCardComposer-customDuration--hoursLabel">Hours</span>
      <select class="PollingCardComposer-customDuration--hours u-borderUserColorLight" data-duration-target="hours">
        <option value="0">0</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
        <option value="8">8</option>
        <option value="9">9</option>
        <option value="10">10</option>
        <option value="11">11</option>
        <option value="12">12</option>
        <option value="13">13</option>
        <option value="14">14</option>
        <option value="15">15</option>
        <option value="16">16</option>
        <option value="17">17</option>
        <option value="18">18</option>
        <option value="19">19</option>
        <option value="20">20</option>
        <option value="21">21</option>
        <option value="22">22</option>
        <option value="23">23</option>
      </select>
      <spann class="PollingCardComposer-customDuration--minutesLabel">Min</span>
      <select class="PollingCardComposer-customDuration--minutes u-borderUserColorLight" data-duration-target="minutes">
        <option value="0">0</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
        <option value="8">8</option>
        <option value="9">9</option>
        <option value="10">10</option>
        <option value="11">11</option>
        <option value="12">12</option>
        <option value="13">13</option>
        <option value="14">14</option>
        <option value="15">15</option>
        <option value="16">16</option>
        <option value="17">17</option>
        <option value="18">18</option>
        <option value="19">19</option>
        <option value="20">20</option>
        <option value="21">21</option>
        <option value="22">22</option>
        <option value="23">23</option>
        <option value="24">24</option>
        <option value="25">25</option>
        <option value="26">26</option>
        <option value="27">27</option>
        <option value="28">28</option>
        <option value="29">29</option>
        <option value="30">30</option>
        <option value="31">31</option>
        <option value="32">32</option>
        <option value="33">33</option>
        <option value="34">34</option>
        <option value="35">35</option>
        <option value="36">36</option>
        <option value="37">37</option>
        <option value="38">38</option>
        <option value="39">39</option>
        <option value="40">40</option>
        <option value="41">41</option>
        <option value="42">42</option>
        <option value="43">43</option>
        <option value="44">44</option>
        <option value="45">45</option>
        <option value="46">46</option>
        <option value="47">47</option>
        <option value="48">48</option>
        <option value="49">49</option>
        <option value="50">50</option>
        <option value="51">51</option>
        <option value="52">52</option>
        <option value="53">53</option>
        <option value="54">54</option>
        <option value="55">55</option>
        <option value="56">56</option>
        <option value="57">57</option>
        <option value="58">58</option>
        <option value="59">59</option>
      </select>
    </div>
  </div>
  <button type="button" class="PollingCardComposer-remove u-textUserColor">
    <span>Remove poll</span>
  </button>
</div>

      </div>


      <div class="tweet-box-overlay"></div>
    </div>
  </div>

  <div class="TweetBoxToolbar">
    <div class="TweetBoxExtras tweet-box-extras">
      <span class="TweetBoxExtras-item TweetBox-mediaPicker"><div class="photo-selector">
  <button aria-hidden="true" class="btn icon-btn js-tooltip" type="button" tabindex="-1" data-original-title="Add photos or video">
      <span class="tweet-camera Icon Icon--media"></span>
    <span class="text add-photo-label u-hiddenVisually">Add photos or video</span>
  </button>
  <div class="image-selector">
    <input type="hidden" name="media_data_empty" class="file-data">
    <div class="multi-photo-data-container hidden">
    </div>
    <label class="t1-label">
      <span class="visuallyhidden">Add photos or video</span>
      <input type="file" name="media_empty" accept="image/gif,image/jpeg,image/jpg,image/png" multiple
          class="file-input js-tooltip" data-original-title="Add photos or video" data-delay="150">
    </label>
  </div>
</div>
</span>

      <span class="TweetBoxExtras-item"><div class="FoundMediaSearch found-media-search dropdown">
  <button class="btn js-found-media-search-trigger js-dropdown-toggle icon-btn js-tooltip" type="button"
      title="Add a GIF" data-delay="150">
    <span class="Icon Icon--gif Icon--large"></span>
    <span class="text u-hiddenVisually">
      Add a GIF
    </span>
  </button>
  <div class="FoundMediaSearch-dropdownMenu dropdown-menu" tabindex="-1">
    <div class="FoundMediaSearch-content Caret Caret--stroked">
      <div class="FoundMediaSearch-query">
        <input class="FoundMediaSearch-queryInput" type="text" autocomplete="off" placeholder="Search for a GIF">
        <span class="Icon Icon--search"></span>
      </div>
      <div class="FoundMediaSearch-results">
        <div class="FoundMediaSearch-items"></div>
        <div class="FoundMediaSearch-pagination"></div>
      </div>
    </div>
  </div>
</div>
</span>

      <span class="TweetBoxExtras-item"><div class="PollCreator">
  <button class="btn icon-btn PollCreator-btn js-tooltip" type="button" title="Add poll"
      data-delay="150">
    <span class="PollCreator-icon Icon Icon--pollBar"></span>
    <span class="text PollCreator-label u-hiddenVisually">Poll</span>
  </button>
</div>
</span>


      <span class="TweetBoxExtras-item"><div class="geo-picker dropdown">
  <button class="btn js-geo-search-trigger geo-picker-btn icon-btn js-tooltip" type="button" data-delay="150">
    <span class="Icon Icon--geo"></span>
    <span class="text geo-status u-hiddenVisually">Add location</span>
  </button>
  <span class="dropdown-container dropdown-menu"></span>
  <input type="hidden" name="place_id">
</div>
</span>

      <div class="TweetBoxUploadProgress">
  <div class="TweetBoxUploadProgress-uploading">
    Uploading
    <div class="TweetBoxUploadProgress-bar">
      <div class="TweetBoxUploadProgress-barPosition"></div>
    </div>
  </div>
  <div class="TweetBoxUploadProgress-processing">
    Processing
    <div class="TweetBoxUploadProgress-spinner Spinner Spinner--size14"></div>
  </div>
</div>
    </div>

    <div class="TweetBoxToolbar-tweetButton tweet-button">
        <span class="tweet-counter">140</span>
      <button class="tweet-action disabled EdgeButton EdgeButton--primary js-tweet-btn" type="button" disabled>
  <span class="button-text tweeting-text">
    Tweet
  </span>
  <span class="button-text replying-text">
    Reply
  </span>
</button>

    </div>
  </div>
</form>

  </div>
</div>

    <script nonce="NRtPTJsIBHvTBXdB7Np/ag==" id="track-ttft-body-script">
  if(window.ttft){
    window.ttft.recordMilestone("page", document.getElementById("swift-page-name").getAttribute("content"));
    window.ttft.recordMilestone("section", document.getElementById("swift-section-name").getAttribute("content"));
    window.ttft.recordMilestone("client_record_time", window.ttft.now());
  }
</script>


      <input type="hidden" id="init-data" class="json-data" value="{&quot;keyboardShortcuts&quot;:[{&quot;name&quot;:&quot;Actions&quot;,&quot;description&quot;:&quot;Shortcuts for common actions.&quot;,&quot;shortcuts&quot;:[{&quot;keys&quot;:[&quot;n&quot;],&quot;description&quot;:&quot;New Tweet&quot;},{&quot;keys&quot;:[&quot;l&quot;],&quot;description&quot;:&quot;Like&quot;},{&quot;keys&quot;:[&quot;r&quot;],&quot;description&quot;:&quot;Reply&quot;},{&quot;keys&quot;:[&quot;t&quot;],&quot;description&quot;:&quot;Retweet&quot;},{&quot;keys&quot;:[&quot;m&quot;],&quot;description&quot;:&quot;Direct message&quot;},{&quot;keys&quot;:[&quot;u&quot;],&quot;description&quot;:&quot;Mute User&quot;},{&quot;keys&quot;:[&quot;b&quot;],&quot;description&quot;:&quot;Block User&quot;},{&quot;keys&quot;:[&quot;Enter&quot;],&quot;description&quot;:&quot;Open Tweet details&quot;},{&quot;keys&quot;:[&quot;o&quot;],&quot;description&quot;:&quot;Expand photo&quot;},{&quot;keys&quot;:[&quot;\/&quot;],&quot;description&quot;:&quot;Search&quot;},{&quot;keys&quot;:[&quot;Ctrl&quot;,&quot;Enter&quot;],&quot;description&quot;:&quot;Send Tweet&quot;}]},{&quot;name&quot;:&quot;Navigation&quot;,&quot;description&quot;:&quot;Shortcuts for navigating between items in timelines.&quot;,&quot;shortcuts&quot;:[{&quot;keys&quot;:[&quot;?&quot;],&quot;description&quot;:&quot;This menu&quot;},{&quot;keys&quot;:[&quot;j&quot;],&quot;description&quot;:&quot;Next Tweet&quot;},{&quot;keys&quot;:[&quot;k&quot;],&quot;description&quot;:&quot;Previous Tweet&quot;},{&quot;keys&quot;:[&quot;Space&quot;],&quot;description&quot;:&quot;Page down&quot;},{&quot;keys&quot;:[&quot;.&quot;],&quot;description&quot;:&quot;Load new Tweets&quot;}]},{&quot;name&quot;:&quot;Timelines&quot;,&quot;description&quot;:&quot;Shortcuts for navigating to different timelines or pages.&quot;,&quot;shortcuts&quot;:[{&quot;keys&quot;:[&quot;g&quot;,&quot;h&quot;],&quot;description&quot;:&quot;Home&quot;},{&quot;keys&quot;:[&quot;g&quot;,&quot;o&quot;],&quot;description&quot;:&quot;Moments&quot;},{&quot;keys&quot;:[&quot;g&quot;,&quot;n&quot;],&quot;description&quot;:&quot;Notifications&quot;},{&quot;keys&quot;:[&quot;g&quot;,&quot;r&quot;],&quot;description&quot;:&quot;Mentions&quot;},{&quot;keys&quot;:[&quot;g&quot;,&quot;p&quot;],&quot;description&quot;:&quot;Profile&quot;},{&quot;keys&quot;:[&quot;g&quot;,&quot;l&quot;],&quot;description&quot;:&quot;Likes&quot;},{&quot;keys&quot;:[&quot;g&quot;,&quot;i&quot;],&quot;description&quot;:&quot;Lists&quot;},{&quot;keys&quot;:[&quot;g&quot;,&quot;m&quot;],&quot;description&quot;:&quot;Messages&quot;},{&quot;keys&quot;:[&quot;g&quot;,&quot;s&quot;],&quot;description&quot;:&quot;Settings&quot;},{&quot;keys&quot;:[&quot;g&quot;,&quot;u&quot;],&quot;description&quot;:&quot;Go to user\u2026&quot;}]}],&quot;baseFoucClass&quot;:&quot;swift-loading&quot;,&quot;bodyFoucClassNames&quot;:&quot;swift-loading no-nav-banners&quot;,&quot;assetsBasePath&quot;:&quot;https:\/\/abs.twimg.com\/a\/1503707773\/&quot;,&quot;assetVersionKey&quot;:&quot;fa5339&quot;,&quot;emojiAssetsPath&quot;:&quot;https:\/\/abs.twimg.com\/emoji\/v2\/72x72\/&quot;,&quot;environment&quot;:&quot;production&quot;,&quot;formAuthenticityToken&quot;:&quot;a587a1e6111389fec0f83e815383d80da89d6907&quot;,&quot;loggedIn&quot;:true,&quot;screenName&quot;:&quot;mohammed_attya&quot;,&quot;fullName&quot;:&quot;Mohammed Attya&quot;,&quot;userId&quot;:&quot;250377148&quot;,&quot;guestId&quot;:&quot;150210167144284660&quot;,&quot;needsPhoneVerification&quot;:false,&quot;allowAdsPersonalization&quot;:false,&quot;scribeBufferSize&quot;:3,&quot;pageName&quot;:&quot;me&quot;,&quot;sectionName&quot;:&quot;profile&quot;,&quot;scribeParameters&quot;:{&quot;lang&quot;:&quot;en&quot;},&quot;recaptchaApiUrl&quot;:&quot;https:\/\/www.google.com\/recaptcha\/api\/js\/recaptcha_ajax.js&quot;,&quot;internalReferer&quot;:null,&quot;geoEnabled&quot;:true,&quot;typeaheadData&quot;:{&quot;accounts&quot;:{&quot;enabled&quot;:true,&quot;localQueriesEnabled&quot;:true,&quot;remoteQueriesEnabled&quot;:true,&quot;limit&quot;:6},&quot;trendLocations&quot;:{&quot;enabled&quot;:true},&quot;dmConversations&quot;:{&quot;enabled&quot;:true},&quot;followedSearches&quot;:{&quot;enabled&quot;:false},&quot;savedSearches&quot;:{&quot;enabled&quot;:true,&quot;items&quot;:[{&quot;name&quot;:&quot;#\u0646\u0635\u0627\u0626\u062d_\u0644\u0644\u0642\u0631\u0627\u0621&quot;,&quot;id_str&quot;:&quot;329847082&quot;,&quot;search_query_source&quot;:&quot;saved_search_click&quot;,&quot;query&quot;:&quot;#\u0646\u0635\u0627\u0626\u062d_\u0644\u0644\u0642\u0631\u0627\u0621&quot;,&quot;saved_search_path&quot;:&quot;\/search?q=%23%D9%86%D8%B5%D8%A7%D8%A6%D8%AD_%D9%84%D9%84%D9%82%D8%B1%D8%A7%D8%A1&amp;src=savs&quot;,&quot;id&quot;:&quot;329847082&quot;}]},&quot;dmAccounts&quot;:{&quot;enabled&quot;:true,&quot;localQueriesEnabled&quot;:true,&quot;remoteQueriesEnabled&quot;:true,&quot;onlyDMable&quot;:true},&quot;mediaTagAccounts&quot;:{&quot;enabled&quot;:true,&quot;localQueriesEnabled&quot;:true,&quot;remoteQueriesEnabled&quot;:true,&quot;onlyShowUsersWithCanMediaTag&quot;:false,&quot;currentUserId&quot;:250377148},&quot;selectedUsers&quot;:{&quot;enabled&quot;:true},&quot;prefillUsers&quot;:{&quot;enabled&quot;:true},&quot;topics&quot;:{&quot;enabled&quot;:true,&quot;localQueriesEnabled&quot;:false,&quot;remoteQueriesEnabled&quot;:true,&quot;prefetchLimit&quot;:500,&quot;limit&quot;:4},&quot;concierge&quot;:{&quot;enabled&quot;:false,&quot;localQueriesEnabled&quot;:false,&quot;remoteQueriesEnabled&quot;:false,&quot;prefetchLimit&quot;:500,&quot;limit&quot;:6},&quot;recentSearches&quot;:{&quot;enabled&quot;:true},&quot;hashtags&quot;:{&quot;enabled&quot;:true,&quot;localQueriesEnabled&quot;:false,&quot;remoteQueriesEnabled&quot;:true,&quot;prefetchLimit&quot;:500},&quot;useIndexedDB&quot;:false,&quot;showSearchAccountSocialContext&quot;:true,&quot;showDebugInfo&quot;:false,&quot;useThrottle&quot;:true,&quot;accountsOnTop&quot;:false,&quot;remoteDebounceInterval&quot;:300,&quot;remoteThrottleInterval&quot;:300,&quot;tweetContextEnabled&quot;:false,&quot;fullNameMatchingInCompose&quot;:true,&quot;topicsWithFiltersEnabled&quot;:true},&quot;dm&quot;:{&quot;notifications&quot;:false,&quot;usePushForNotifications&quot;:true,&quot;participant_max&quot;:50,&quot;welcome_message_add_to_conversation_enabled&quot;:true,&quot;poll_options&quot;:{&quot;foreground_poll_interval&quot;:3000,&quot;burst_poll_interval&quot;:3000,&quot;burst_poll_duration&quot;:300000,&quot;max_poll_interval&quot;:60000},&quot;card_prefetch&quot;:true,&quot;card_prefetch_interval_in_seconds&quot;:2000,&quot;dm_quick_reply_options_panel_dismiss_in_ms&quot;:2000,&quot;dm_convo_settings_page_enabled&quot;:true,&quot;open_dm_enabled&quot;:true},&quot;autoplayDisabled&quot;:true,&quot;pushStatePageLimit&quot;:500000,&quot;routes&quot;:{&quot;profile&quot;:&quot;\/mohammed_attya&quot;},&quot;pushState&quot;:true,&quot;viewContainer&quot;:&quot;#page-container&quot;,&quot;href&quot;:&quot;\/mohammed_attya&quot;,&quot;searchPathWithQuery&quot;:&quot;\/search?q=query&amp;src=typd&quot;,&quot;composeAltText&quot;:false,&quot;night_mode_activated&quot;:false,&quot;night_mode_available&quot;:false,&quot;user_color&quot;:&quot;1B95E0&quot;,&quot;deciders&quot;:{&quot;custom_timeline_curation&quot;:false,&quot;native_notifications&quot;:true,&quot;disable_ajax_datatype_default_to_text&quot;:false,&quot;dm_polling_frequency_in_seconds&quot;:3000,&quot;dm_granular_mute_controls&quot;:true,&quot;enable_media_tag_prefetch&quot;:true,&quot;enableMacawNymizerConversionLanding&quot;:false,&quot;hqImageUploads&quot;:false,&quot;live_pipeline_consume&quot;:true,&quot;mqImageUploads&quot;:false,&quot;partnerIdSyncEnabled&quot;:true,&quot;sruMediaCategory&quot;:true,&quot;photoSruGifLimitMb&quot;:15,&quot;promoted_logging_force_post&quot;:true,&quot;promoted_video_logging_enabled&quot;:true,&quot;pushState&quot;:true,&quot;emojiNewCategory&quot;:false,&quot;contentEditablePlainTextOnly&quot;:false,&quot;web_client_api_stats&quot;:false,&quot;web_perftown_stats&quot;:true,&quot;web_perftown_ttft&quot;:false,&quot;web_client_events_ttft&quot;:true,&quot;log_push_state_ttft_metrics&quot;:true,&quot;web_sru_stats&quot;:false,&quot;web_upload_video&quot;:true,&quot;web_upload_video_advanced&quot;:false,&quot;upload_video_size&quot;:500,&quot;useVmapVariants&quot;:false,&quot;autoplayPreviewPreroll&quot;:true,&quot;moments_home_module&quot;:false,&quot;moments_lohp_enabled&quot;:true,&quot;enableNativePush&quot;:true,&quot;autoSubscribeNativePush&quot;:false,&quot;allowWebPushVapidUpgrade&quot;:true,&quot;stickersInteractivity&quot;:true,&quot;stickersInteractivityDuringLoading&quot;:true,&quot;stickersExperience&quot;:true,&quot;dynamic_video_ads_include_long_videos&quot;:true,&quot;push_state_size&quot;:1000,&quot;live_video_media_control_enabled&quot;:false,&quot;use_api_for_retweet_and_unretweet&quot;:false,&quot;use_api_for_follow_and_unfollow&quot;:true,&quot;edge_probe_enabled&quot;:false,&quot;like_over_http_client&quot;:true,&quot;enable_tweetstorm_creation&quot;:false,&quot;dm_report_webview_macaw_swift_enabled&quot;:true,&quot;dm_convo_settings_page_enabled&quot;:true,&quot;dm_secondary_inbox&quot;:false,&quot;page_title_unread_notification_count&quot;:false},&quot;experiments&quot;:{},&quot;toasts_dm&quot;:true,&quot;toasts_timeline&quot;:false,&quot;toasts_dm_poll_scale&quot;:60,&quot;defaultNotificationIcon&quot;:&quot;https:\/\/abs.twimg.com\/a\/1503707773\/img\/t1\/mobile\/wp7_app_icon.png&quot;,&quot;promptbirdData&quot;:{&quot;promptbirdEnabled&quot;:false,&quot;immediateTriggers&quot;:[&quot;PullToRefresh&quot;,&quot;Navigate&quot;],&quot;format&quot;:&quot;ProfileSelf&quot;},&quot;pageContext&quot;:&quot;profile&quot;,&quot;deviceEnabled&quot;:true,&quot;hasPushDevice&quot;:true,&quot;smsDeviceVerified&quot;:true,&quot;skipAutoSignupDialog&quot;:false,&quot;shouldReplaceSignupWithLogin&quot;:true,&quot;hashflagBaseUrl&quot;:&quot;https:\/\/abs.twimg.com\/hashflags\/&quot;,&quot;activeHashflags&quot;:{&quot;المسافر&quot;:&quot;Almo_Emojiv2\/Almo_Emojiv2.png&quot;,&quot;loveisland&quot;:&quot;Love_Island_Emojiv2\/Love_Island_Emojiv2.png&quot;,&quot;ボディソープきれた&quot;:&quot;unilever_emojiv3\/unilever_emojiv3.png&quot;,&quot;영화마더&quot;:&quot;Mother_Emojiv2\/Mother_Emojiv2.png&quot;,&quot;mersalteaser&quot;:&quot;MersalEmoji\/MersalEmoji.png&quot;,&quot;ourhealthynyc&quot;:&quot;NYC_health_Emoji\/NYC_health_Emoji.png&quot;,&quot;thetick&quot;:&quot;The_Tick_Emoji\/The_Tick_Emoji.png&quot;,&quot;partiucelebreak&quot;:&quot;Kit_Kat_Emoji_v2\/Kit_Kat_Emoji_v2.png&quot;,&quot;itishappeningagain&quot;:&quot;twin_peaks_emojiv3\/twin_peaks_emojiv3.png&quot;,&quot;avici&quot;:&quot;Avicii_emojiv2\/Avicii_emojiv2.png&quot;,&quot;twitter4me&quot;:&quot;Twitter4Me_Emoji\/Twitter4Me_Emoji.png&quot;,&quot;julioregalado&quot;:&quot;Soriana_MX_EMoji\/Soriana_MX_EMoji.png&quot;,&quot;powerplayleague&quot;:&quot;InsideEdge_Emojiv2\/InsideEdge_Emojiv2.png&quot;,&quot;twinpeakssundays&quot;:&quot;twin_peaks_emojiv3\/twin_peaks_emojiv3.png&quot;,&quot;letsbelonelytogether&quot;:&quot;Avicii_emojiv2\/Avicii_emojiv2.png&quot;,&quot;delhirains&quot;:&quot;Monsoon_Emoji\/Monsoon_Emoji.png&quot;,&quot;xfactor2017&quot;:&quot;XFactorAug2017\/XFactorAug2017.png&quot;,&quot;i61&quot;:&quot;Insomnia61_v2\/Insomnia61_v2.png&quot;,&quot;goldencircleday&quot;:&quot;KingsmanGoldenCircle_v3\/KingsmanGoldenCircle_v3.png&quot;,&quot;undrip&quot;:&quot;UIPD_Emojiv2\/UIPD_Emojiv2.png&quot;,&quot;mersaltheme&quot;:&quot;MersalEmoji\/MersalEmoji.png&quot;,&quot;getshorty&quot;:&quot;Get_Shorty_Emoji\/Get_Shorty_Emoji.png&quot;,&quot;ellen15&quot;:&quot;Ellen15_emoji\/Ellen15_emoji.png&quot;,&quot;spideyreturns&quot;:&quot;SpiderMan_Emoji\/SpiderMan_Emoji.png&quot;,&quot;wnba&quot;:&quot;WMBA_Emoji\/WMBA_Emoji.png&quot;,&quot;monsoon2017&quot;:&quot;Monsoon_Emoji\/Monsoon_Emoji.png&quot;,&quot;مهرجان&quot;:&quot;GEA_Emoji\/GEA_Emoji.png&quot;,&quot;iemshanghai&quot;:&quot;IEM_2017_Emoji\/IEM_2017_Emoji.png&quot;,&quot;fortheloveoflearning&quot;:&quot;Apple_Edu_Emojiv3\/Apple_Edu_Emojiv3.png&quot;,&quot;avicii&quot;:&quot;Avicii_emojiv2\/Avicii_emojiv2.png&quot;,&quot;periscope&quot;:&quot;Periscope\/Periscope.png&quot;,&quot;larocaxsiri&quot;:&quot;RockxSiri_Emojiv5_N\/RockxSiri_Emojiv5_N.png&quot;,&quot;runtheneighborhood&quot;:&quot;NBA_2K_EMOJI\/NBA_2K_EMOJI.png&quot;,&quot;almosafer&quot;:&quot;Almo_Emojiv2\/Almo_Emojiv2.png&quot;,&quot;exidelife&quot;:&quot;Exide_Emoji\/Exide_Emoji.png&quot;,&quot;indiarains&quot;:&quot;Monsoon_Emoji\/Monsoon_Emoji.png&quot;,&quot;chennairains&quot;:&quot;Monsoon_Emoji\/Monsoon_Emoji.png&quot;,&quot;רובוטריקים5&quot;:&quot;Transformerts_Emoji\/Transformerts_Emoji.png&quot;,&quot;thexfactor&quot;:&quot;XFactorAug2017\/XFactorAug2017.png&quot;,&quot;transparentamazon&quot;:&quot;Transparent_emoji\/Transparent_emoji.png&quot;,&quot;cgd&quot;:&quot;CelebsGoDating_emoji\/CelebsGoDating_emoji.png&quot;,&quot;esloneny&quot;:&quot;ESL_Live_Emojiv2\/ESL_Live_Emojiv2.png&quot;,&quot;mercuryprize&quot;:&quot;Mercury_Prize_Emoji\/Mercury_Prize_Emoji.png&quot;,&quot;وناسة&quot;:&quot;GEA_Emoji\/GEA_Emoji.png&quot;,&quot;castinggh&quot;:&quot;BB_2017_Emoji\/BB_2017_Emoji.png&quot;,&quot;prazeremdescobrir&quot;:&quot;Caprese_Emojiv2\/Caprese_Emojiv2.png&quot;,&quot;lovetwitter&quot;:&quot;LoveTwitter\/LoveTwitter.png&quot;,&quot;eslny&quot;:&quot;ESL_Live_Emojiv2\/ESL_Live_Emojiv2.png&quot;,&quot;insideedge&quot;:&quot;InsideEdge_Emojiv2\/InsideEdge_Emojiv2.png&quot;,&quot;mobily&quot;:&quot;Mobily_Emoji\/Mobily_Emoji.png&quot;,&quot;forlove&quot;:&quot;Caesar_Emoji_Two\/Caesar_Emoji_Two.png&quot;,&quot;teamspidey&quot;:&quot;SpiderMan_Emoji\/SpiderMan_Emoji.png&quot;,&quot;トランスフォーマー&quot;:&quot;Transformerts_Emoji\/Transformerts_Emoji.png&quot;,&quot;chrisodowd&quot;:&quot;Get_Shorty_Emoji\/Get_Shorty_Emoji.png&quot;,&quot;elife&quot;:&quot;Mobily_Emoji\/Mobily_Emoji.png&quot;,&quot;badisbred&quot;:&quot;Animal_Emoji\/Animal_Emoji.png&quot;,&quot;أنا_المسافر&quot;:&quot;Almo_Emojiv2\/Almo_Emojiv2.png&quot;,&quot;電影母親&quot;:&quot;Mother_Emojiv2\/Mother_Emojiv2.png&quot;,&quot;mersalmusiclive&quot;:&quot;MersalEmoji\/MersalEmoji.png&quot;,&quot;transformers5&quot;:&quot;Transformerts_Emoji\/Transformerts_Emoji.png&quot;,&quot;xfbootcamp&quot;:&quot;XFactorAug2017\/XFactorAug2017.png&quot;,&quot;aviciilonelytogether&quot;:&quot;Avicii_emojiv2\/Avicii_emojiv2.png&quot;,&quot;трансформеры5&quot;:&quot;Transformerts_Emoji\/Transformerts_Emoji.png&quot;,&quot;thewebhead&quot;:&quot;SpiderMan_Emoji\/SpiderMan_Emoji.png&quot;,&quot;badape&quot;:&quot;Caesar_Emoji_Two\/Caesar_Emoji_Two.png&quot;,&quot;ifood&quot;:&quot;Ifood_Emoji\/Ifood_Emoji.png&quot;,&quot;loveislandaftersun&quot;:&quot;Love_Island_Emojiv2\/Love_Island_Emojiv2.png&quot;,&quot;wannasprite&quot;:&quot;Sprite_Emoji_Summer\/Sprite_Emoji_Summer.png&quot;,&quot;destappagol&quot;:&quot;Coca_Cola_Emoji_Dest\/Coca_Cola_Emoji_Dest.png&quot;,&quot;ifoodsalva&quot;:&quot;Ifood_Emoji\/Ifood_Emoji.png&quot;,&quot;twinpeakspremiere&quot;:&quot;twin_peaks_emojiv3\/twin_peaks_emojiv3.png&quot;,&quot;wnbalive&quot;:&quot;WMBA_Emoji\/WMBA_Emoji.png&quot;,&quot;ダヴうるおいお届け便&quot;:&quot;unilever_emojiv3\/unilever_emojiv3.png&quot;,&quot;f3plus&quot;:&quot;OPPO_emojiv5_extend\/OPPO_emojiv5_extend.png&quot;,&quot;visitpandora&quot;:&quot;Disney_Visit_Pandora_emoji_ext\/Disney_Visit_Pandora_emoji_ext.png&quot;,&quot;mersaltrailer&quot;:&quot;MersalEmoji\/MersalEmoji.png&quot;,&quot;destiny2&quot;:&quot;destiny2\/destiny2.png&quot;,&quot;praqualquerfome&quot;:&quot;Ifood_Emoji\/Ifood_Emoji.png&quot;,&quot;gameofthrones&quot;:&quot;HBO_GoT\/HBO_GoT.png&quot;,&quot;oppof3plus&quot;:&quot;OPPO_emojiv5_extend\/OPPO_emojiv5_extend.png&quot;,&quot;caviaris&quot;:&quot;SpotifyRapCaviar_v2\/SpotifyRapCaviar_v2.png&quot;,&quot;elúltimocaballero&quot;:&quot;Transformerts_Emoji\/Transformerts_Emoji.png&quot;,&quot;elmoreleonard&quot;:&quot;Get_Shorty_Emoji\/Get_Shorty_Emoji.png&quot;,&quot;nuevotiguan&quot;:&quot;Tiguan_Emoji\/Tiguan_Emoji.png&quot;,&quot;eurobasket2017&quot;:&quot;Euro_Basket\/Euro_Basket.png&quot;,&quot;judwaa2kidussehra&quot;:&quot;Judwa2_Movie_Emoji\/Judwa2_Movie_Emoji.png&quot;,&quot;castinggh18&quot;:&quot;BB_GH_Emoji\/BB_GH_Emoji.png&quot;,&quot;nba2k&quot;:&quot;NBA_2K_EMOJI\/NBA_2K_EMOJI.png&quot;,&quot;apnapan&quot;:&quot;Uber_indiav2\/Uber_indiav2.png&quot;,&quot;sienteelsabor&quot;:&quot;Coca_Cola_Emoji_Dest\/Coca_Cola_Emoji_Dest.png&quot;,&quot;احجز_الحين_وادفع_بعدين&quot;:&quot;Almo_Emojiv2\/Almo_Emojiv2.png&quot;,&quot;díapueblosindígenas&quot;:&quot;UIPD_Emojiv2\/UIPD_Emojiv2.png&quot;,&quot;milesdaly&quot;:&quot;Get_Shorty_Emoji\/Get_Shorty_Emoji.png&quot;,&quot;ابتسامة&quot;:&quot;GEA_Emoji\/GEA_Emoji.png&quot;,&quot;ghrevolution&quot;:&quot;BB_2017_Emoji\/BB_2017_Emoji.png&quot;,&quot;gamebehindthegame&quot;:&quot;InsideEdge_Emojiv2\/InsideEdge_Emojiv2.png&quot;,&quot;dhden17&quot;:&quot;Dreamhack_Emoji_Final\/Dreamhack_Emoji_Final.png&quot;,&quot;oonchihaibuilding&quot;:&quot;Judwa2_Movie_Emoji\/Judwa2_Movie_Emoji.png&quot;,&quot;rapcaviarlive&quot;:&quot;SpotifyRapCaviar_v2\/SpotifyRapCaviar_v2.png&quot;,&quot;eslonenewyork&quot;:&quot;ESL_Live_Emojiv2\/ESL_Live_Emojiv2.png&quot;,&quot;autochtones&quot;:&quot;UIPD_Emojiv2\/UIPD_Emojiv2.png&quot;,&quot;مرح&quot;:&quot;GEA_Emoji\/GEA_Emoji.png&quot;,&quot;eslonenyc2017&quot;:&quot;ESL_Live_Emojiv2\/ESL_Live_Emojiv2.png&quot;,&quot;oppof3&quot;:&quot;OPPO_emojiv5_extend\/OPPO_emojiv5_extend.png&quot;,&quot;insecurehbo&quot;:&quot;HBO_Emoji\/HBO_Emoji.png&quot;,&quot;6chairchallenge&quot;:&quot;XFactorAug2017\/XFactorAug2017.png&quot;,&quot;raqi&quot;:&quot;Mobily_Emoji\/Mobily_Emoji.png&quot;,&quot;btw17&quot;:&quot;GermanElection2017\/GermanElection2017.png&quot;,&quot;willtnt&quot;:&quot;Will_TV_Emoij\/Will_TV_Emoij.png&quot;,&quot;votrevie&quot;:&quot;Percy_Extensionv2\/Percy_Extensionv2.png&quot;,&quot;gotmvp&quot;:&quot;Amazon_GoT_Emojiv4\/Amazon_GoT_Emojiv4.png&quot;,&quot;mothermovieph&quot;:&quot;Mother_Emojiv2\/Mother_Emojiv2.png&quot;,&quot;agentcooper&quot;:&quot;twin_peaks_emojiv3\/twin_peaks_emojiv3.png&quot;,&quot;spidermanmovie&quot;:&quot;SpiderMan_Emoji\/SpiderMan_Emoji.png&quot;,&quot;sixchairchallenge&quot;:&quot;XFactorAug2017\/XFactorAug2017.png&quot;,&quot;மெர்சல்&quot;:&quot;MersalEmoji\/MersalEmoji.png&quot;,&quot;golive&quot;:&quot;GoLive_Emoji\/GoLive_Emoji.png&quot;,&quot;نقاطي&quot;:&quot;Mobily_Emoji\/Mobily_Emoji.png&quot;,&quot;ايلايف&quot;:&quot;Mobily_Emoji\/Mobily_Emoji.png&quot;,&quot;tiguan&quot;:&quot;Tiguan_Emoji\/Tiguan_Emoji.png&quot;,&quot;ويك_إند&quot;:&quot;GEA_Emoji\/GEA_Emoji.png&quot;,&quot;celebsgodatingseries3&quot;:&quot;CelebsGoDating_emoji\/CelebsGoDating_emoji.png&quot;,&quot;masterchefazteca&quot;:&quot;MasterChefMX\/MasterChefMX.png&quot;,&quot;makeeverydeathcount&quot;:&quot;HappyDD_Emojiv2\/HappyDD_Emojiv2.png&quot;,&quot;somosindígenas&quot;:&quot;UIPD_Emojiv2\/UIPD_Emojiv2.png&quot;,&quot;courseàlaviecibc&quot;:&quot;CIBC_Emojiv2\/CIBC_Emojiv2.png&quot;,&quot;eslnyc&quot;:&quot;ESL_Live_Emojiv2\/ESL_Live_Emojiv2.png&quot;,&quot;indigenousday&quot;:&quot;UIPD_Emojiv2\/UIPD_Emojiv2.png&quot;,&quot;dhatx17&quot;:&quot;Dreamhack_Emoji_Final\/Dreamhack_Emoji_Final.png&quot;,&quot;dbt1gh&quot;:&quot;BB_2017_Emoji\/BB_2017_Emoji.png&quot;,&quot;conversepublicaccess&quot;:&quot;ConversePublicAccess_v2\/ConversePublicAccess_v2.png&quot;,&quot;entelmediafest&quot;:&quot;MoxEntel_emoji\/MoxEntel_emoji.png&quot;,&quot;واجد&quot;:&quot;Mobily_Emoji\/Mobily_Emoji.png&quot;,&quot;gbbo&quot;:&quot;GBBO_2017_v3\/GBBO_2017_v3.png&quot;,&quot;rocketsiri&quot;:&quot;RockxSiri_Emojiv5_N\/RockxSiri_Emojiv5_N.png&quot;,&quot;maeofilme&quot;:&quot;Mother_Emojiv2\/Mother_Emojiv2.png&quot;,&quot;شركة_المسافر&quot;:&quot;Almo_Emojiv2\/Almo_Emojiv2.png&quot;,&quot;entraunoptimista&quot;:&quot;Entel_Vamos_Emoji_Extendv2\/Entel_Vamos_Emoji_Extendv2.png&quot;,&quot;islareality&quot;:&quot;LaIsla_MX_2017_v3\/LaIsla_MX_2017_v3.png&quot;,&quot;gowinx&quot;:&quot;Tab_Winx_Emoji\/Tab_Winx_Emoji.png&quot;,&quot;therockesiri&quot;:&quot;RockxSiri_Emojiv5_N\/RockxSiri_Emojiv5_N.png&quot;,&quot;bluesupreme&quot;:&quot;Nike_AL_Emoji\/Nike_AL_Emoji.png&quot;,&quot;iemsydney2017&quot;:&quot;IEM_2017_Emoji\/IEM_2017_Emoji.png&quot;,&quot;трансформери5&quot;:&quot;Transformerts_Emoji\/Transformerts_Emoji.png&quot;,&quot;rockesiri&quot;:&quot;RockxSiri_Emojiv5_N\/RockxSiri_Emojiv5_N.png&quot;,&quot;2k18&quot;:&quot;NBA_2K_EMOJI\/NBA_2K_EMOJI.png&quot;,&quot;judwaa2&quot;:&quot;Judwa2_Movie_Emoji\/Judwa2_Movie_Emoji.png&quot;,&quot;aviciiisback&quot;:&quot;Avicii_emojiv2\/Avicii_emojiv2.png&quot;,&quot;iemoakland2017&quot;:&quot;IEM_2017_Emoji\/IEM_2017_Emoji.png&quot;,&quot;islaelreality&quot;:&quot;LaIsla_MX_2017_v3\/LaIsla_MX_2017_v3.png&quot;,&quot;موبايلي&quot;:&quot;Mobily_Emoji\/Mobily_Emoji.png&quot;,&quot;feelthewins&quot;:&quot;YahooFantasyFootball2017\/YahooFantasyFootball2017.png&quot;,&quot;iemoakland&quot;:&quot;IEM_2017_Emoji\/IEM_2017_Emoji.png&quot;,&quot;سعادة&quot;:&quot;GEA_Emoji\/GEA_Emoji.png&quot;,&quot;masterchefmx&quot;:&quot;MasterChefMX\/MasterChefMX.png&quot;,&quot;laislaelreality&quot;:&quot;LaIsla_MX_2017_v3\/LaIsla_MX_2017_v3.png&quot;,&quot;superboost&quot;:&quot;Sky_Bet_Boost_Emoji\/Sky_Bet_Boost_Emoji.png&quot;,&quot;巨石强森xsiri&quot;:&quot;RockxSiri_Emojiv5_N\/RockxSiri_Emojiv5_N.png&quot;,&quot;almosafertravel&quot;:&quot;Almo_Emojiv2\/Almo_Emojiv2.png&quot;,&quot;cheddarlive&quot;:&quot;Cheddar_Emoji_v3\/Cheddar_Emoji_v3.png&quot;,&quot;winterishere&quot;:&quot;HBO_GoT\/HBO_GoT.png&quot;,&quot;granhermano18&quot;:&quot;BB_GH_Emoji\/BB_GH_Emoji.png&quot;,&quot;theneighborhood&quot;:&quot;NBA_2K_EMOJI\/NBA_2K_EMOJI.png&quot;,&quot;timchef&quot;:&quot;TimChef_emoji\/TimChef_emoji.png&quot;,&quot;madrelapelicula&quot;:&quot;Mother_Emojiv2\/Mother_Emojiv2.png&quot;,&quot;gala1gh&quot;:&quot;BB_2017_Emoji\/BB_2017_Emoji.png&quot;,&quot;soyindígena&quot;:&quot;UIPD_Emojiv2\/UIPD_Emojiv2.png&quot;,&quot;aviciiyoubelove&quot;:&quot;Avicii_emojiv2\/Avicii_emojiv2.png&quot;,&quot;datelaoportunidad&quot;:&quot;CO_Emoji\/CO_Emoji.png&quot;,&quot;hardestworkingdollar&quot;:&quot;HardestWorking_Emojiv3\/HardestWorking_Emojiv3.png&quot;,&quot;aatohsahi&quot;:&quot;Judwa2_Movie_Emoji\/Judwa2_Movie_Emoji.png&quot;,&quot;iempoland&quot;:&quot;IEM_2017_Emoji\/IEM_2017_Emoji.png&quot;,&quot;detroitmovie&quot;:&quot;Detroit_Emojiv2\/Detroit_Emojiv2.png&quot;,&quot;thalapathy61&quot;:&quot;MersalEmoji\/MersalEmoji.png&quot;,&quot;viveponiente&quot;:&quot;GoT_Spain_Emoji\/GoT_Spain_Emoji.png&quot;,&quot;eslcologne2017&quot;:&quot;ESL_Live_Emojiv2\/ESL_Live_Emojiv2.png&quot;,&quot;變形金剛終極戰士&quot;:&quot;Transformerts_Emoji\/Transformerts_Emoji.png&quot;,&quot;mumbaimavericks&quot;:&quot;InsideEdge_Emojiv2\/InsideEdge_Emojiv2.png&quot;,&quot;getbackching&quot;:&quot;Dial_Emoji1\/Dial_Emoji1.png&quot;,&quot;super6&quot;:&quot;Sky_Bet_6_Emoji\/Sky_Bet_6_Emoji.png&quot;,&quot;iemshanghai2017&quot;:&quot;IEM_2017_Emoji\/IEM_2017_Emoji.png&quot;,&quot;monsoon&quot;:&quot;Monsoon_Emoji\/Monsoon_Emoji.png&quot;,&quot;thewallcrawler&quot;:&quot;SpiderMan_Emoji\/SpiderMan_Emoji.png&quot;,&quot;aalaporaanthamizhan&quot;:&quot;MersalEmoji\/MersalEmoji.png&quot;,&quot;تطبيق_المسافر&quot;:&quot;Almo_Emojiv2\/Almo_Emojiv2.png&quot;,&quot;esmadre&quot;:&quot;Mother_Emojiv2\/Mother_Emojiv2.png&quot;,&quot;valg17&quot;:&quot;Norwegian_election_2017\/Norwegian_election_2017.png&quot;,&quot;dondelograndesucede&quot;:&quot;Tiguan_Emoji\/Tiguan_Emoji.png&quot;,&quot;cibcrunforthecure&quot;:&quot;CIBC_Emojiv2\/CIBC_Emojiv2.png&quot;,&quot;netneutrality&quot;:&quot;Net_Emoji\/Net_Emoji.png&quot;,&quot;dhfr17&quot;:&quot;Dreamhack_Emoji_Final\/Dreamhack_Emoji_Final.png&quot;,&quot;lovekaty&quot;:&quot;Katy_Perry_100M_emoji\/Katy_Perry_100M_emoji.png&quot;,&quot;canada150&quot;:&quot;Canada150_emojiv5\/Canada150_emojiv5.png&quot;,&quot;iem&quot;:&quot;IEM_2017_Emoji\/IEM_2017_Emoji.png&quot;,&quot;شارك_واربح_مع_المسافر&quot;:&quot;Almo_Emojiv2\/Almo_Emojiv2.png&quot;,&quot;cbb&quot;:&quot;BB_UK_Emoji2\/BB_UK_Emoji2.png&quot;,&quot;ahmedabadrains&quot;:&quot;Monsoon_Emoji\/Monsoon_Emoji.png&quot;,&quot;dhw17&quot;:&quot;Dreamhack_Emoji_Final\/Dreamhack_Emoji_Final.png&quot;,&quot;forfreedom&quot;:&quot;Caesar_Emoji_Two\/Caesar_Emoji_Two.png&quot;,&quot;transformers&quot;:&quot;Transformerts_Emoji\/Transformerts_Emoji.png&quot;,&quot;dbt3gh&quot;:&quot;BB_2017_Emoji\/BB_2017_Emoji.png&quot;,&quot;celebsgodating&quot;:&quot;CelebsGoDating_emoji\/CelebsGoDating_emoji.png&quot;,&quot;rayromano&quot;:&quot;Get_Shorty_Emoji\/Get_Shorty_Emoji.png&quot;,&quot;exidelifeinsurance&quot;:&quot;Exide_Emoji\/Exide_Emoji.png&quot;,&quot;توقع_مع_المسافر&quot;:&quot;Almo_Emojiv2\/Almo_Emojiv2.png&quot;,&quot;banorte&quot;:&quot;Banorte_Emoji\/Banorte_Emoji.png&quot;,&quot;xf2017&quot;:&quot;XFactorAug2017\/XFactorAug2017.png&quot;,&quot;мама&quot;:&quot;Mother_Emojiv2\/Mother_Emojiv2.png&quot;,&quot;edfpulse&quot;:&quot;EDF_Emoji\/EDF_Emoji.png&quot;,&quot;twinpeaks&quot;:&quot;twin_peaks_emojiv3\/twin_peaks_emojiv3.png&quot;,&quot;fitsyourlife&quot;:&quot;Percy_Extensionv2\/Percy_Extensionv2.png&quot;,&quot;mothermovieth&quot;:&quot;Mother_Emojiv2\/Mother_Emojiv2.png&quot;,&quot;lograndesucede&quot;:&quot;Tiguan_Emoji\/Tiguan_Emoji.png&quot;,&quot;madden18&quot;:&quot;Madden_Emojiv2\/Madden_Emojiv2.png&quot;,&quot;アイナナ2周年&quot;:&quot;AinanaEmoji\/AinanaEmoji.png&quot;,&quot;iemkatowice&quot;:&quot;IEM_2017_Emoji\/IEM_2017_Emoji.png&quot;,&quot;masterchefmex&quot;:&quot;MasterChefMX\/MasterChefMX.png&quot;,&quot;cgd2017&quot;:&quot;CelebsGoDating_emoji\/CelebsGoDating_emoji.png&quot;,&quot;laislareality&quot;:&quot;LaIsla_MX_2017_v3\/LaIsla_MX_2017_v3.png&quot;,&quot;eslone2017&quot;:&quot;ESL_Live_Emojiv2\/ESL_Live_Emojiv2.png&quot;,&quot;epix&quot;:&quot;Get_Shorty_Emoji\/Get_Shorty_Emoji.png&quot;,&quot;6añosdehieloyfuego&quot;:&quot;GoT_Spain_Emoji\/GoT_Spain_Emoji.png&quot;,&quot;celebritybigbrother&quot;:&quot;BB_UK_Emoji2\/BB_UK_Emoji2.png&quot;,&quot;warfortheplanet&quot;:&quot;Caesar_Emoji\/Caesar_Emoji.png&quot;,&quot;spidermanhomecoming&quot;:&quot;SpiderMan_Emoji\/SpiderMan_Emoji.png&quot;,&quot;2kproam&quot;:&quot;NBA_2K_EMOJI\/NBA_2K_EMOJI.png&quot;,&quot;cgdseries3&quot;:&quot;CelebsGoDating_emoji\/CelebsGoDating_emoji.png&quot;,&quot;voteformorepower&quot;:&quot;VoteForMorePower_Emoji\/VoteForMorePower_Emoji.png&quot;,&quot;converse&quot;:&quot;ConversePublicAccess_v2\/ConversePublicAccess_v2.png&quot;,&quot;chienbinhcuoicung&quot;:&quot;Transformerts_Emoji\/Transformerts_Emoji.png&quot;,&quot;xffinal&quot;:&quot;XFactorAug2017\/XFactorAug2017.png&quot;,&quot;happydeathday&quot;:&quot;HappyDD_Emojiv2\/HappyDD_Emojiv2.png&quot;,&quot;unlockunlimited&quot;:&quot;Live_Nation_Emoji_T\/Live_Nation_Emoji_T.png&quot;,&quot;بهجة&quot;:&quot;GEA_Emoji\/GEA_Emoji.png&quot;,&quot;imsorrytv&quot;:&quot;Sorry_Emoji\/Sorry_Emoji.png&quot;,&quot;cpwo&quot;:&quot;CP_CanadianWomensOpen\/CP_CanadianWomensOpen.png&quot;,&quot;evilbratt&quot;:&quot;Bratt_DM3_EMojiv2\/Bratt_DM3_EMojiv2.png&quot;,&quot;motherlefilm&quot;:&quot;Mother_Emojiv2\/Mother_Emojiv2.png&quot;,&quot;2kfirstlook&quot;:&quot;NBA_2K_EMOJI\/NBA_2K_EMOJI.png&quot;,&quot;movistarseries&quot;:&quot;GoT_Spain_Emoji\/GoT_Spain_Emoji.png&quot;,&quot;transformersfilmi&quot;:&quot;Transformerts_Emoji\/Transformerts_Emoji.png&quot;,&quot;eslonenyc&quot;:&quot;ESL_Live_Emojiv2\/ESL_Live_Emojiv2.png&quot;,&quot;tantanatan&quot;:&quot;Judwa2_Movie_Emoji\/Judwa2_Movie_Emoji.png&quot;,&quot;rapcaviar&quot;:&quot;SpotifyRapCaviar_v2\/SpotifyRapCaviar_v2.png&quot;,&quot;트랜스포머최후의기사&quot;:&quot;Transformerts_Emoji\/Transformerts_Emoji.png&quot;,&quot;findyourgrit&quot;:&quot;AG_emoji_Facev2\/AG_emoji_Facev2.png&quot;,&quot;johncena&quot;:&quot;AG_emoji_Facev2\/AG_emoji_Facev2.png&quot;,&quot;變形金剛5&quot;:&quot;Transformerts_Emoji\/Transformerts_Emoji.png&quot;,&quot;बारिश&quot;:&quot;Monsoon_Emoji\/Monsoon_Emoji.png&quot;,&quot;iem2017&quot;:&quot;IEM_2017_Emoji\/IEM_2017_Emoji.png&quot;,&quot;dipi2017&quot;:&quot;UIPD_Emojiv2\/UIPD_Emojiv2.png&quot;,&quot;blockbustermersal&quot;:&quot;MersalEmoji\/MersalEmoji.png&quot;,&quot;موبايلي1100&quot;:&quot;Mobily_Emoji\/Mobily_Emoji.png&quot;,&quot;videomusicawards&quot;:&quot;MTV2017VMA_emoji_v2\/MTV2017VMA_emoji_v2.png&quot;,&quot;neethanae&quot;:&quot;MersalEmoji\/MersalEmoji.png&quot;,&quot;過嚇&quot;:&quot;Mother_Emojiv2\/Mother_Emojiv2.png&quot;,&quot;dbt2gh&quot;:&quot;BB_2017_Emoji\/BB_2017_Emoji.png&quot;,&quot;theticksdcc&quot;:&quot;The_Tick_Emoji\/The_Tick_Emoji.png&quot;,&quot;eslone&quot;:&quot;ESL_Live_Emojiv2\/ESL_Live_Emojiv2.png&quot;,&quot;gh18&quot;:&quot;BB_GH_Emoji\/BB_GH_Emoji.png&quot;,&quot;nbaeleague&quot;:&quot;NBA_2K_EMOJI\/NBA_2K_EMOJI.png&quot;,&quot;finddialdude&quot;:&quot;Dial_Emoji2\/Dial_Emoji2.png&quot;,&quot;gots7&quot;:&quot;HBO_GoT\/HBO_GoT.png&quot;,&quot;eslonecgn&quot;:&quot;ESL_Live_Emojiv2\/ESL_Live_Emojiv2.png&quot;,&quot;herosalutes&quot;:&quot;Hero_salute_Emoji\/Hero_salute_Emoji.png&quot;,&quot;amoigualchocolate&quot;:&quot;Cacau_Show_2\/Cacau_Show_2.png&quot;,&quot;dhmtl17&quot;:&quot;Dreamhack_Emoji_Final\/Dreamhack_Emoji_Final.png&quot;,&quot;transformersfilmen&quot;:&quot;Transformerts_Emoji\/Transformerts_Emoji.png&quot;,&quot;mothermovie&quot;:&quot;Mother_Emojiv2\/Mother_Emojiv2.png&quot;,&quot;gala3gh&quot;:&quot;BB_2017_Emoji\/BB_2017_Emoji.png&quot;,&quot;gala2gh&quot;:&quot;BB_2017_Emoji\/BB_2017_Emoji.png&quot;,&quot;tsl100&quot;:&quot;MersalEmoji\/MersalEmoji.png&quot;,&quot;eslonegenting&quot;:&quot;ESL_Live_Emojiv2\/ESL_Live_Emojiv2.png&quot;,&quot;judgeshouses&quot;:&quot;XFactorAug2017\/XFactorAug2017.png&quot;,&quot;adamconover&quot;:&quot;adam_Emoji\/adam_Emoji.png&quot;,&quot;xfactor&quot;:&quot;XFactorAug2017\/XFactorAug2017.png&quot;,&quot;neqaty&quot;:&quot;Mobily_Emoji\/Mobily_Emoji.png&quot;,&quot;vamoschilectm&quot;:&quot;Entel_Vamos_Emoji_Extendv2\/Entel_Vamos_Emoji_Extendv2.png&quot;,&quot;الهيئة_العامة_للترفيه&quot;:&quot;GEA_Emoji\/GEA_Emoji.png&quot;,&quot;mobily1100&quot;:&quot;Mobily_Emoji\/Mobily_Emoji.png&quot;,&quot;cacomptepourmoi&quot;:&quot;AXA_Emoji\/AXA_Emoji.png&quot;,&quot;هالة_الأزرق&quot;:&quot;Nike_AL_Emoji\/Nike_AL_Emoji.png&quot;,&quot;heforshe&quot;:&quot;HeForShe_fixed\/HeForShe_fixed.png&quot;,&quot;insomnia61&quot;:&quot;Insomnia61_v2\/Insomnia61_v2.png&quot;,&quot;uberindia&quot;:&quot;Uber_indiav2\/Uber_indiav2.png&quot;,&quot;adamruins&quot;:&quot;adam_Emoji\/adam_Emoji.png&quot;,&quot;عروض_المسافر&quot;:&quot;Almo_Emojiv2\/Almo_Emojiv2.png&quot;,&quot;pueblosindígenas&quot;:&quot;UIPD_Emojiv2\/UIPD_Emojiv2.png&quot;,&quot;روزنامة_الترفيه&quot;:&quot;GEA_Emoji\/GEA_Emoji.png&quot;,&quot;valg2017&quot;:&quot;Norwegian_election_2017\/Norwegian_election_2017.png&quot;,&quot;名前入りリップ&quot;:&quot;Estee_Emoji\/Estee_Emoji.png&quot;,&quot;teamtick&quot;:&quot;The_Tick_Emoji\/The_Tick_Emoji.png&quot;,&quot;whatwouldichangeitto&quot;:&quot;Avicii_emojiv2\/Avicii_emojiv2.png&quot;,&quot;transparenttv&quot;:&quot;Transparent_emoji\/Transparent_emoji.png&quot;,&quot;forfamily&quot;:&quot;Caesar_Emoji_Two\/Caesar_Emoji_Two.png&quot;,&quot;masterchefmexico&quot;:&quot;MasterChefMX\/MasterChefMX.png&quot;,&quot;esloneny2017&quot;:&quot;ESL_Live_Emojiv2\/ESL_Live_Emojiv2.png&quot;,&quot;apnihigaadi&quot;:&quot;Uber_indiav2\/Uber_indiav2.png&quot;,&quot;dhvlc17&quot;:&quot;Dreamhack_Emoji_Final\/Dreamhack_Emoji_Final.png&quot;,&quot;coursealaviecibc&quot;:&quot;CIBC_Emojiv2\/CIBC_Emojiv2.png&quot;,&quot;alittlelesslonelytogether&quot;:&quot;Avicii_emojiv2\/Avicii_emojiv2.png&quot;,&quot;blackhistorymonth&quot;:&quot;BlackHistoryMonth\/BlackHistoryMonth.png&quot;,&quot;dhatl17&quot;:&quot;Dreamhack_Emoji_Final\/Dreamhack_Emoji_Final.png&quot;,&quot;eslhamburg&quot;:&quot;ESL_Live_Emojiv2\/ESL_Live_Emojiv2.png&quot;,&quot;vmas&quot;:&quot;MTV2017VMA_emoji_v2\/MTV2017VMA_emoji_v2.png&quot;,&quot;balthazarbratt&quot;:&quot;Bratt_DM3_EMojiv2\/Bratt_DM3_EMojiv2.png&quot;,&quot;eslonecologne2017&quot;:&quot;ESL_Live_Emojiv2\/ESL_Live_Emojiv2.png&quot;,&quot;deathday&quot;:&quot;HappyDD_Emojiv2\/HappyDD_Emojiv2.png&quot;,&quot;wemetontwitter&quot;:&quot;WeMetOnt_Emoji\/WeMetOnt_Emoji.png&quot;,&quot;newtwinpeaks&quot;:&quot;twin_peaks_emojiv3\/twin_peaks_emojiv3.png&quot;,&quot;nba2k18&quot;:&quot;NBA_2K_EMOJI\/NBA_2K_EMOJI.png&quot;,&quot;bundestagswahl2017&quot;:&quot;GermanElection2017\/GermanElection2017.png&quot;,&quot;iemsydney&quot;:&quot;IEM_2017_Emoji\/IEM_2017_Emoji.png&quot;,&quot;celebsgodating2017&quot;:&quot;CelebsGoDating_emoji\/CelebsGoDating_emoji.png&quot;,&quot;theprelude&quot;:&quot;NBA_2K_EMOJI\/NBA_2K_EMOJI.png&quot;,&quot;motherfilm&quot;:&quot;Mother_Emojiv2\/Mother_Emojiv2.png&quot;,&quot;loganlucky&quot;:&quot;LoganLucky_Emoji\/LoganLucky_Emoji.png&quot;,&quot;موبايلي_1100&quot;:&quot;Mobily_Emoji\/Mobily_Emoji.png&quot;,&quot;bengalururains&quot;:&quot;Monsoon_Emoji\/Monsoon_Emoji.png&quot;,&quot;twinpeaks2017&quot;:&quot;twin_peaks_emojiv3\/twin_peaks_emojiv3.png&quot;,&quot;روزنامة_العيد&quot;:&quot;GEA_Emoji\/GEA_Emoji.png&quot;,&quot;estransformers&quot;:&quot;Transformerts_Emoji\/Transformerts_Emoji.png&quot;,&quot;películamadre&quot;:&quot;Mother_Emojiv2\/Mother_Emojiv2.png&quot;,&quot;rockxsiri&quot;:&quot;RockxSiri_Emojiv5_N\/RockxSiri_Emojiv5_N.png&quot;,&quot;bhm&quot;:&quot;BlackHistoryMonth\/BlackHistoryMonth.png&quot;,&quot;werehistory&quot;:&quot;Caesar_Emoji_Two\/Caesar_Emoji_Two.png&quot;,&quot;cantstopwontstop&quot;:&quot;CantStopWontStopEmoji\/CantStopWontStopEmoji.png&quot;,&quot;goldencircle&quot;:&quot;KingsmanGoldenCircle_v3\/KingsmanGoldenCircle_v3.png&quot;,&quot;weareindigenous&quot;:&quot;UIPD_Emojiv2\/UIPD_Emojiv2.png&quot;,&quot;eslgenting&quot;:&quot;ESL_Live_Emojiv2\/ESL_Live_Emojiv2.png&quot;,&quot;bundestagswahl&quot;:&quot;GermanElection2017\/GermanElection2017.png&quot;,&quot;twinpeaksday&quot;:&quot;twin_peaks_emojiv3\/twin_peaks_emojiv3.png&quot;,&quot;hambreportusideasdnp&quot;:&quot;Danone_Emojiv2\/Danone_Emojiv2.png&quot;,&quot;spideygoals&quot;:&quot;SpiderMan_Emoji\/SpiderMan_Emoji.png&quot;,&quot;mersalarasan&quot;:&quot;MersalEmoji\/MersalEmoji.png&quot;,&quot;originalosinazúcar&quot;:&quot;Coca_Cola_Emoji_Dest\/Coca_Cola_Emoji_Dest.png&quot;,&quot;americangrit&quot;:&quot;AG_emoji_Facev2\/AG_emoji_Facev2.png&quot;,&quot;eslnewyork&quot;:&quot;ESL_Live_Emojiv2\/ESL_Live_Emojiv2.png&quot;,&quot;wajid&quot;:&quot;Mobily_Emoji\/Mobily_Emoji.png&quot;,&quot;blacklivesmatter&quot;:&quot;BlackHistoryMonth\/BlackHistoryMonth.png&quot;,&quot;bieninchismo&quot;:&quot;Corona_MX_Q3\/Corona_MX_Q3.png&quot;,&quot;lambasaath&quot;:&quot;Exide_Emoji\/Exide_Emoji.png&quot;,&quot;eslcologne&quot;:&quot;ESL_Live_Emojiv2\/ESL_Live_Emojiv2.png&quot;,&quot;eslonehamburg&quot;:&quot;ESL_Live_Emojiv2\/ESL_Live_Emojiv2.png&quot;,&quot;mobily_1100&quot;:&quot;Mobily_Emoji\/Mobily_Emoji.png&quot;,&quot;youbelove&quot;:&quot;Avicii_emojiv2\/Avicii_emojiv2.png&quot;,&quot;usopen&quot;:&quot;USOPENTennis2017\/USOPENTennis2017.png&quot;,&quot;راقي&quot;:&quot;Mobily_Emoji\/Mobily_Emoji.png&quot;,&quot;annefilmi&quot;:&quot;Mother_Emojiv2\/Mother_Emojiv2.png&quot;,&quot;mersal&quot;:&quot;MersalEmoji\/MersalEmoji.png&quot;,&quot;onestar&quot;:&quot;ConversePublicAccess_v2\/ConversePublicAccess_v2.png&quot;,&quot;mersalalbum&quot;:&quot;MersalEmoji\/MersalEmoji.png&quot;,&quot;membersgetit&quot;:&quot;Virgin_member_Emoji\/Virgin_member_Emoji.png&quot;,&quot;partnershipsforlife&quot;:&quot;Exide_Emoji\/Exide_Emoji.png&quot;,&quot;spidey&quot;:&quot;SpiderMan_Emoji\/SpiderMan_Emoji.png&quot;,&quot;скалаxsiri&quot;:&quot;RockxSiri_Emojiv5_N\/RockxSiri_Emojiv5_N.png&quot;,&quot;aviciiwithoutyou&quot;:&quot;Avicii_emojiv2\/Avicii_emojiv2.png&quot;,&quot;mexicanosfuertes&quot;:&quot;Banorte_Emoji\/Banorte_Emoji.png&quot;,&quot;المسافر_يغنيك&quot;:&quot;Almo_Emojiv2\/Almo_Emojiv2.png&quot;,&quot;xfactorfinal&quot;:&quot;XFactorAug2017\/XFactorAug2017.png&quot;,&quot;2kday&quot;:&quot;NBA_2K_EMOJI\/NBA_2K_EMOJI.png&quot;,&quot;hyderabadrains&quot;:&quot;Monsoon_Emoji\/Monsoon_Emoji.png&quot;,&quot;spideysquad&quot;:&quot;SpiderMan_Emoji\/SpiderMan_Emoji.png&quot;,&quot;mersaldiwali&quot;:&quot;MersalEmoji\/MersalEmoji.png&quot;,&quot;twinpeakssunday&quot;:&quot;twin_peaks_emojiv3\/twin_peaks_emojiv3.png&quot;,&quot;aviciifriendofmine&quot;:&quot;Avicii_emojiv2\/Avicii_emojiv2.png&quot;,&quot;heretocreate&quot;:&quot;Adidas_Emoji_HTCv2\/Adidas_Emoji_HTCv2.png&quot;,&quot;الترفيه&quot;:&quot;GEA_Emoji\/GEA_Emoji.png&quot;,&quot;vma&quot;:&quot;MTV2017VMA_emoji_v2\/MTV2017VMA_emoji_v2.png&quot;,&quot;اربح_مع_المسافر&quot;:&quot;Almo_Emojiv2\/Almo_Emojiv2.png&quot;,&quot;kingsman&quot;:&quot;KingsmanGoldenCircle_v3\/KingsmanGoldenCircle_v3.png&quot;,&quot;هيئة_الترفيه&quot;:&quot;GEA_Emoji\/GEA_Emoji.png&quot;,&quot;madrelapelícula&quot;:&quot;Mother_Emojiv2\/Mother_Emojiv2.png&quot;,&quot;hellaoutthere&quot;:&quot;HBO_Emoji\/HBO_Emoji.png&quot;,&quot;شاركونا_العيد&quot;:&quot;GEA_Emoji\/GEA_Emoji.png&quot;,&quot;lesmembresenprofitent&quot;:&quot;Virgin_member_Emoji\/Virgin_member_Emoji.png&quot;,&quot;notallheroesarehuman&quot;:&quot;Caesar_Emoji_Two\/Caesar_Emoji_Two.png&quot;,&quot;eurobasket&quot;:&quot;Euro_Basket\/Euro_Basket.png&quot;,&quot;adamruinseverything&quot;:&quot;adam_Emoji\/adam_Emoji.png&quot;,&quot;dhs17&quot;:&quot;Dreamhack_Emoji_Final\/Dreamhack_Emoji_Final.png&quot;,&quot;amazonoriginal&quot;:&quot;InsideEdge_Emojiv2\/InsideEdge_Emojiv2.png&quot;,&quot;アナザーエデン&quot;:&quot;GREE_Emoji_AEv2\/GREE_Emoji_AEv2.png&quot;,&quot;baarish&quot;:&quot;Monsoon_Emoji\/Monsoon_Emoji.png&quot;,&quot;coronasunsets&quot;:&quot;Corona_Sunset_Emoji_extended\/Corona_Sunset_Emoji_extended.png&quot;,&quot;موقع_المسافر&quot;:&quot;Almo_Emojiv2\/Almo_Emojiv2.png&quot;,&quot;rocaysiri&quot;:&quot;RockxSiri_Emojiv5_N\/RockxSiri_Emojiv5_N.png&quot;,&quot;المسافر_معك&quot;:&quot;Almo_Emojiv2\/Almo_Emojiv2.png&quot;,&quot;noticiasfindesemanaa3&quot;:&quot;Antena3_Emoji\/Antena3_Emoji.png&quot;,&quot;granhermanorevolution&quot;:&quot;BB_2017_Emoji\/BB_2017_Emoji.png&quot;,&quot;maachosingle&quot;:&quot;MersalEmoji\/MersalEmoji.png&quot;,&quot;originalosinazucar&quot;:&quot;Coca_Cola_Emoji_Dest\/Coca_Cola_Emoji_Dest.png&quot;,&quot;eslonecologne&quot;:&quot;ESL_Live_Emojiv2\/ESL_Live_Emojiv2.png&quot;,&quot;mumbairains&quot;:&quot;Monsoon_Emoji\/Monsoon_Emoji.png&quot;},&quot;birthdateData&quot;:{&quot;DOBCollectionEnabled&quot;:true,&quot;shouldShowVisibilityPopover&quot;:false,&quot;visibilitySettingsLearnMorePath&quot;:&quot;\/\/support.twitter.com\/articles\/20172733&quot;,&quot;maximumBirthdate&quot;:{&quot;day&quot;:8,&quot;month&quot;:2,&quot;year&quot;:1998}},&quot;profile_user&quot;:{&quot;id&quot;:250377148,&quot;id_str&quot;:&quot;250377148&quot;,&quot;name&quot;:&quot;Mohammed Attya&quot;,&quot;screen_name&quot;:&quot;mohammed_attya&quot;,&quot;location&quot;:&quot;Egypt&quot;,&quot;url&quot;:null,&quot;description&quot;:&quot;Web Developer, PHP, Laravel, Geek, Programming, Biking, Walking, Coffee, Reading, \ud83d\udeb2\ud83e\udd3e\ud83d\udd4b\ud83d\udcbe\ud83d\udcbb&quot;,&quot;protected&quot;:false,&quot;followers_count&quot;:324,&quot;friends_count&quot;:310,&quot;listed_count&quot;:11,&quot;created_at&quot;:&quot;Thu Feb 10 23:57:00 +0000 2011&quot;,&quot;favourites_count&quot;:446,&quot;utc_offset&quot;:7200,&quot;time_zone&quot;:&quot;Cairo&quot;,&quot;geo_enabled&quot;:true,&quot;verified&quot;:false,&quot;statuses_count&quot;:25605,&quot;lang&quot;:&quot;en&quot;,&quot;contributors_enabled&quot;:false,&quot;is_translator&quot;:false,&quot;is_translation_enabled&quot;:false,&quot;profile_background_color&quot;:&quot;85D8FF&quot;,&quot;profile_background_image_url&quot;:&quot;http:\/\/pbs.twimg.com\/profile_background_images\/259199508\/018.jpg&quot;,&quot;profile_background_image_url_https&quot;:&quot;https:\/\/pbs.twimg.com\/profile_background_images\/259199508\/018.jpg&quot;,&quot;profile_background_tile&quot;:false,&quot;profile_image_url&quot;:&quot;http:\/\/pbs.twimg.com\/profile_images\/878491891733549056\/3urQeq4j_normal.jpg&quot;,&quot;profile_image_url_https&quot;:&quot;https:\/\/pbs.twimg.com\/profile_images\/878491891733549056\/3urQeq4j_normal.jpg&quot;,&quot;profile_banner_url&quot;:&quot;https:\/\/pbs.twimg.com\/profile_banners\/250377148\/1495478263&quot;,&quot;profile_link_color&quot;:&quot;1B95E0&quot;,&quot;profile_sidebar_border_color&quot;:&quot;010C12&quot;,&quot;profile_sidebar_fill_color&quot;:&quot;BDFBFF&quot;,&quot;profile_text_color&quot;:&quot;000000&quot;,&quot;profile_use_background_image&quot;:true,&quot;has_extended_profile&quot;:true,&quot;default_profile&quot;:false,&quot;default_profile_image&quot;:false,&quot;following&quot;:false,&quot;follow_request_sent&quot;:false,&quot;notifications&quot;:false,&quot;business_profile_state&quot;:&quot;none&quot;,&quot;translator_type&quot;:&quot;regular&quot;},&quot;profileEditingCSSBundle&quot;:&quot;https:\/\/abs.twimg.com\/a\/1503707773\/css\/t1\/twitter_profile_editing.bundle.css&quot;,&quot;profile_id&quot;:250377148,&quot;business_profile&quot;:false,&quot;b2c_logged_out_support_indicators_enabled&quot;:true,&quot;business_profile_featured_collections_complete&quot;:false,&quot;cardsGallery&quot;:true,&quot;injectComposedTweets&quot;:true,&quot;inlineProfileEditing&quot;:true,&quot;isClusterFollowReplenishEnabled&quot;:false,&quot;autoplayEnabled&quot;:true,&quot;periscopeLiveStatusPollInterval&quot;:15000,&quot;trendsCacheKey&quot;:&quot;a1208c03ad&quot;,&quot;decider_personalized_trends&quot;:true,&quot;trendsEndpoint&quot;:&quot;\/i\/trends&quot;,&quot;wtfOptions&quot;:{&quot;pc&quot;:true,&quot;connections&quot;:true,&quot;limit&quot;:3,&quot;display_location&quot;:&quot;profile-sidebar&quot;,&quot;dismissable&quot;:true,&quot;similar_to_user_id&quot;:&quot;250377148&quot;},&quot;showSensitiveContent&quot;:true,&quot;autoPlayBalloonsAnimation&quot;:false,&quot;momentsNuxTooltipsEnabled&quot;:true,&quot;isCurrentUser&quot;:true,&quot;isSensitiveProfile&quot;:false,&quot;timeline_url&quot;:&quot;\/i\/profiles\/show\/mohammed_attya\/timeline\/tweets&quot;,&quot;initialState&quot;:{&quot;title&quot;:&quot;Mohammed Attya (@mohammed_attya) | Twitter&quot;,&quot;section&quot;:&quot;profile&quot;,&quot;module&quot;:&quot;app\/pages\/profile\/highline_landing&quot;,&quot;cache_ttl&quot;:300,&quot;body_class_names&quot;:&quot;three-col logged-in user-style-mohammed_attya enhanced-mini-profile ProfilePage ProfilePage--withWarning&quot;,&quot;doc_class_names&quot;:&quot;route-profile&quot;,&quot;route_name&quot;:&quot;profile&quot;,&quot;page_container_class_names&quot;:&quot;AppContent&quot;,&quot;ttft_navigation&quot;:false}}">



    <input type="hidden" class="swift-boot-module" value="app/pages/profile/highline_landing">
  <input type="hidden" id="swift-module-path" value="https://abs.twimg.com/k/swift/en">


    <script src="https://abs.twimg.com/k/en/init.en.00bc5bac2f4866212098.js" async></script>

  </body>
</html>
"}';
        $content = ['body' => $body];

        $moch = $this->getMockBuilder(CURL::class)
                     ->getMock();
        $moch->method('curl_content')
             ->willReturn($content);
        $url = "https://twitter.com/blabla";
        $profile = new ProfileImage($url, $moch);
        $actual = $profile->getProfileImage();
        $expected = "https://pbs.twimg.com/profile_images/878491891733549056/3urQeq4j_400x400.jpg";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_facebook_profile_image()
    {
        $content = ['body' => '{"page": href="https://www.facebook.com/wageeh.elaskary/about?lst=1395730028%3A1070002888%3A1503909635" data-tab-key="about">About<span class="_513x"></span></a></li><li><a class="_6-6" href="https://www.facebook.com/wageeh.elaskary/friends?lst=1395730028%3A1070002888%3A1503909635&amp;source_ref=pb_friends_tl" data-tab-key="friends">Friends<span class="_gs6"><span id="u_0_v">28 Mutual</span></span><span class="_513x"></span></a></li><li><a class="_6-6" href="https://www.facebook.com/wageeh.elaskary/photos?lst=1395730028%3A1070002888%3A1503909635&amp;source_ref=pb_friends_tl" data-tab-key="photos">Photos<span class="_513x"></span></a></li><li><div class="_6a uiPopover _6-6 _9rx" id="u_0_z"><a class="_9ry _p" href="#" aria-haspopup="true" aria-expanded="false" rel="toggle" role="button" id="u_0_10">More<i class="_bxy img sp_YBZXGwtTCEk sx_a0ab43"></i></a></div></li></ul></div><div class="name"><div class="photoContainer"><div><a class="profilePicThumb" href="https://www.facebook.com/photo.php?fbid=10212456436540521&amp;set=a.1791268456057.100352.1070002888&amp;type=3&amp;source=11&amp;referrer_profile_id=1070002888" rel="theater" id="u_0_u"><img class="profilePic img" alt="Wageeh El-Askary&#039;s profile photo, Image may contain: 1 person" src="https://scontent-cai1-1.xx.fbcdn.net/v/t1.0-1/p160x160/20604365_10212456436540521_6139684834067292116_n.jpg?oh=817871f0fcd0685275fa2e2d860b780e&amp;oe=5A1AAB88" /></a></div><meta content="https://scontent-cai1-1.xx.fbcdn.net/v/t1.0-1/p50x50/20604365_10212456436540521_6139684834067292116_n.jpg?oh=f30b7eb5ae4c35b99c91023e58ee78af&amp;oe=5A5CC5FB" itemprop="image" /></div></div></div></div></div></div>}'];

        $moch = $this->getMockBuilder(CURL::class)
                     ->getMock();
        $moch->method('curl_content')
             ->willReturn($content);
        $url = "https://www.facebook.com/blabla";
        $profile = new ProfileImage($url, $moch);
        $actual = $profile->getProfileImage();
        $expected = 'https://scontent-cai1-1.xx.fbcdn.net/v/t1.0-1/p160x160/20604365_10212456436540521_6139684834067292116_n.jpg?oh=817871f0fcd0685275fa2e2d860b780e&oe=5A1AAB88';
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_pinterest_profile_image()
    {
        $content = ['body' => '{><div class="_mn _29 _2f _mq _54" data-reactid="156"><div class="_0 _3i _2m _2f" style="background-color:#EFEFEF;padding-bottom:100%;" data-reactid="157"><img alt="S3Geeks - صعيدي جيكس" class="_mj _25 _3x _2h" src="https://s-media-cache-ak0.pinimg.com/avatars/s3geeks_1481571960_280.jpg" data-reactid="158"/></div><div class="_mr _2h _2k _2l _2j _2i" data-reactid="159"></div></div></div></div></div></div></div></div></div><div class="py1 mt2" data-reactid="160"><div class="_0 _3i _2m _11 _3x _jp _jq" style="max-width:800px;" data-reactid="161"><div class="px2" data-reactid="162"><div class="flex flex-wrap tabBar" data-reactid="163"> }'];

        $moch = $this->getMockBuilder(CURL::class)
                     ->getMock();
        $moch->method('curl_content')
             ->willReturn($content);
        $url = "https://www.pinterest.com/s3geeks/";
        $profile = new ProfileImage($url, $moch);
        $actual = $profile->getProfileImage();
        $expected = "https://s-media-cache-ak0.pinimg.com/avatars/s3geeks_1481571960_280.jpg";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_empty_string_from_non_pinterest_profile_image()
    {
        $content = ['body' => '{}'];

        $moch = $this->getMockBuilder(CURL::class)
                     ->getMock();
        $moch->method('curl_content')
             ->willReturn($content);
        $url = "https://www.pinterest.com/s3geeks/";
        $profile = new ProfileImage($url, $moch);
        $actual = $profile->getProfileImage();
        $expected = "";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_instagram_profile_image()
    {
        $content = ['body' => '{<header class="_mainc"><div class="_b0acm"><div class="_l8yre _qdmzb"><img class="_9bt3u" src="https://ig-s-a-a.akamaihd.net/h-ak-igx/t51.2885-19/s150x150/18579506_307756452990732_6384055099764768768_a.jpg"></div></div><div class="_o6mpc"><div class="_ienqf"><h1 class="_rf3jb notranslate" title="emycaramil">emycaramil</h1><span }'];

        $moch = $this->getMockBuilder(CURL::class)
                     ->getMock();
        $moch->method('curl_content')
             ->willReturn($content);
        $url = "https://www.instagram.com/blabla/";
        $profile = new ProfileImage($url, $moch);
        $actual = $profile->getProfileImage();
        $expected = "https://ig-s-a-a.akamaihd.net/h-ak-igx/t51.2885-19/s150x150/18579506_307756452990732_6384055099764768768_a.jpg";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_youtube_profile_image()
    {
        $content = ['body' => '{<div id="c4-header-bg-container" class="c4-visible-on-hover-container
          <a class="channel-header-profile-image-container spf-link" href="/user/JTechAppleTV">
      <img class="channel-header-profile-image" src="https://yt3.ggpht.com/-sm14n9qk1q4/AAAAAAAAAAI/AAAAAAAAAAA/eqSqnUmjC2k/s100-c-k-no-mo-rj-c0xffffff/photo.jpg" title="Justin Tse" alt="Justin Tse"> </a> </div> }'];

        $moch = $this->getMockBuilder(CURL::class)
                     ->getMock();
        $moch->method('curl_content')
             ->willReturn($content);
        $url = "https://www.youtube.com/user/JTechAppleTV";
        $profile = new ProfileImage($url, $moch);
        $actual = $profile->getProfileImage();
        $expected = "https://yt3.ggpht.com/-sm14n9qk1q4/AAAAAAAAAAI/AAAAAAAAAAA/eqSqnUmjC2k/s100-c-k-no-mo-rj-c0xffffff/photo.jpg";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_linkedin_profile_image()
    {
        $content = ['body' => '{"page":"request":"/voyager/api/configuration","status":200,"body":"bpr-guid-1605813"}
</code>
<img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" style="display: none" class="datalet-bpr-guid-1605813"><code style="display: none" id="bpr-guid-1605814">
  {&quot;data&quot;:{&quot;paidProducts&quot;:[&quot;SHOW_POST_A_JOB&quot;],&quot;companies&quot;:[],&quot;$deletedFields&quot;:[&quot;postJobsEnabled&quot;],&quot;memberGroup&quot;:&quot;FREE&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.common.Nav&quot;,&quot;$id&quot;:&quot;M8x5UY0Zt6eGdBCiy+iKhA&#61;&#61;,root&quot;},&quot;included&quot;:[]}
</code>
<code style="display: none" id="datalet-bpr-guid-1605814">
  {"request":"/voyager/api/nav","status":200,"body":"bpr-guid-1605814"}
</code>
<img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" style="display: none" class="datalet-bpr-guid-1605814"><code style="display: none" id="bpr-guid-1605815">
  {&quot;data&quot;:{&quot;canBrowseProfiles&quot;:false,&quot;$deletedFields&quot;:[],&quot;reactivationFeaturesEligible&quot;:false,&quot;canViewJobAnalytics&quot;:false,&quot;canViewWVMP&quot;:false,&quot;canViewCompanyInsights&quot;:false,&quot;$type&quot;:&quot;com.linkedin.voyager.premium.FeatureAccess&quot;,&quot;$id&quot;:&quot;rp3HQ6ObDeupY3gQ84tYJw&#61;&#61;,root&quot;},&quot;included&quot;:[]}
</code>
<code style="display: none" id="datalet-bpr-guid-1605815">
  {"request":"/voyager/api/premium/featureAccess?name\u003DreactivationFeaturesEligible","status":200,"body":"bpr-guid-1605815"}
</code>
<img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" style="display: none" class="datalet-bpr-guid-1605815"><code style="display: none" id="bpr-guid-1605816">
  {&quot;data&quot;:{&quot;shareVisibilityType&quot;:&quot;PUBLIC&quot;,&quot;$deletedFields&quot;:[],&quot;sharePublicVisibilityTooltipMessage&quot;:&quot;urn:li:fs_mySettings:287188536,sharePublicVisibilityTooltipMessage&quot;,&quot;entityUrn&quot;:&quot;urn:li:fs_mySettings:287188536&quot;,&quot;videoAutoPlay&quot;:&quot;ALWAYS&quot;,&quot;flagshipCrossLinkToJobSearchApp&quot;:true,&quot;discloseAsProfileViewer&quot;:&quot;DISCLOSE_FULL&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.common.MySettings&quot;},&quot;included&quot;:[{&quot;$deletedFields&quot;:[],&quot;start&quot;:72,&quot;length&quot;:10,&quot;type&quot;:{&quot;com.linkedin.pemberly.text.Hyperlink&quot;:&quot;urn:li:fs_mySettings:287188536,sharePublicVisibilityTooltipMessage,attributes,ac6526da-b357-44d0-9c47-8a2c7a41b718-1,type,com.linkedin.pemberly.text.Hyperlink&quot;},&quot;$type&quot;:&quot;com.linkedin.pemberly.text.Attribute&quot;,&quot;$id&quot;:&quot;urn:li:fs_mySettings:287188536,sharePublicVisibilityTooltipMessage,attributes,ac6526da-b357-44d0-9c47-8a2c7a41b718-1&quot;},{&quot;$deletedFields&quot;:[],&quot;start&quot;:21,&quot;length&quot;:6,&quot;type&quot;:{&quot;com.linkedin.pemberly.text.Bold&quot;:&quot;urn:li:fs_mySettings:287188536,sharePublicVisibilityTooltipMessage,attributes,ac6526da-b357-44d0-9c47-8a2c7a41b718-0,type,com.linkedin.pemberly.text.Bold&quot;},&quot;$type&quot;:&quot;com.linkedin.pemberly.text.Attribute&quot;,&quot;$id&quot;:&quot;urn:li:fs_mySettings:287188536,sharePublicVisibilityTooltipMessage,attributes,ac6526da-b357-44d0-9c47-8a2c7a41b718-0&quot;},{&quot;$deletedFields&quot;:[],&quot;attributes&quot;:[&quot;urn:li:fs_mySettings:287188536,sharePublicVisibilityTooltipMessage,attributes,ac6526da-b357-44d0-9c47-8a2c7a41b718-0&quot;,&quot;urn:li:fs_mySettings:287188536,sharePublicVisibilityTooltipMessage,attributes,ac6526da-b357-44d0-9c47-8a2c7a41b718-1&quot;],&quot;text&quot;:&quot;Now when you post to Public, it&#39;s visible to anyone on or off LinkedIn. Learn more.&quot;,&quot;$type&quot;:&quot;com.linkedin.pemberly.text.AttributedText&quot;,&quot;$id&quot;:&quot;urn:li:fs_mySettings:287188536,sharePublicVisibilityTooltipMessage&quot;},{&quot;$deletedFields&quot;:[],&quot;$type&quot;:&quot;com.linkedin.pemberly.text.Bold&quot;,&quot;$id&quot;:&quot;urn:li:fs_mySettings:287188536,sharePublicVisibilityTooltipMessage,attributes,ac6526da-b357-44d0-9c47-8a2c7a41b718-0,type,com.linkedin.pemberly.text.Bold&quot;},{&quot;$deletedFields&quot;:[],&quot;url&quot;:&quot;https://linkedin.com/help/linkedin/answer/82288?lang&#61;en&quot;,&quot;$type&quot;:&quot;com.linkedin.pemberly.text.Hyperlink&quot;,&quot;$id&quot;:&quot;urn:li:fs_mySettings:287188536,sharePublicVisibilityTooltipMessage,attributes,ac6526da-b357-44d0-9c47-8a2c7a41b718-1,type,com.linkedin.pemberly.text.Hyperlink&quot;}]}
</code>
<code style="display: none" id="datalet-bpr-guid-1605816">
  {"request":"/voyager/api/me/settings","status":200,"body":"bpr-guid-1605816"}
</code>
<img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" style="display: none" class="datalet-bpr-guid-1605816"><code style="display: none" id="bpr-guid-1605817">
  {&quot;data&quot;:{&quot;messagingTypingIndicators&quot;:&quot;ALL_DISABLED&quot;,&quot;allowOpenProfile&quot;:false,&quot;profilePictureVisibilitySetting&quot;:&quot;PUBLIC&quot;,&quot;$deletedFields&quot;:[],&quot;entityUrn&quot;:&quot;urn:li:fs_privacySettings:ACoAABEeJjgBirRpt0KbsGVTQQEI8hfjFcBzdMw&quot;,&quot;showPublicProfile&quot;:true,&quot;showPremiumSubscriberBadge&quot;:false,&quot;publicProfilePictureVisibilitySetting&quot;:&quot;PUBLIC&quot;,&quot;formerNameVisibilitySetting&quot;:&quot;PUBLIC&quot;,&quot;messagingSeenReceipts&quot;:&quot;ALL_DISABLED&quot;,&quot;allowProfileEditBroadcasts&quot;:true,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.PrivacySettings&quot;},&quot;included&quot;:[]}
</code>
<code style="display: none" id="datalet-bpr-guid-1605817">
  {"request":"/voyager/api/identity/profiles/ahmedriad1/privacySettings","status":200,"body":"bpr-guid-1605817"}
</code>
<img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" style="display: none" class="datalet-bpr-guid-1605817"><code style="display: none" id="bpr-guid-1605818">
  {&quot;data&quot;:{&quot;elements&quot;:[],&quot;paging&quot;:{&quot;total&quot;:0,&quot;count&quot;:10,&quot;start&quot;:0,&quot;links&quot;:[]}},&quot;included&quot;:[]}
</code>
<code style="display: none" id="datalet-bpr-guid-1605818">
  {"request":"/voyager/api/takeovers","status":200,"body":"bpr-guid-1605818"}
</code>
<img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" style="display: none" class="datalet-bpr-guid-1605818"><code style="display: none" id="bpr-guid-1605819">
  {&quot;data&quot;:{&quot;$deletedFields&quot;:[],&quot;premium&quot;:false,&quot;influencer&quot;:false,&quot;entityUrn&quot;:&quot;urn:li:fs_memberBadges:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8&quot;,&quot;openLink&quot;:false,&quot;jobSeeker&quot;:false,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.MemberBadges&quot;},&quot;included&quot;:[]}
</code>
<code style="display: none" id="datalet-bpr-guid-1605819">
  {"request":"/voyager/api/identity/profiles/ahmedriad1/memberBadges","status":200,"body":"bpr-guid-1605819"}
</code>
<img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" style="display: none" class="datalet-bpr-guid-1605819"><code style="display: none" id="bpr-guid-1605820">
  {&quot;data&quot;:{&quot;$deletedFields&quot;:[],&quot;publicContactInfo&quot;:&quot;qTJ8dtboEoV+oq/pm1xyeA&#61;&#61;,root,publicContactInfo&quot;,&quot;plainId&quot;:287188536,&quot;miniProfile&quot;:&quot;urn:li:fs_miniProfile:ACoAABEeJjgBirRpt0KbsGVTQQEI8hfjFcBzdMw&quot;,&quot;premiumSubscriber&quot;:false,&quot;$type&quot;:&quot;com.linkedin.voyager.common.Me&quot;,&quot;$id&quot;:&quot;qTJ8dtboEoV+oq/pm1xyeA&#61;&#61;,root&quot;},&quot;included&quot;:[{&quot;$deletedFields&quot;:[&quot;attribution&quot;],&quot;id&quot;:&quot;/AAEAAQAAAAAAAALDAAAAJDQ0MDE3MDhlLWEzMDgtNGEyNy05YTFkLWQyNDA0NGE0MDEzYw.jpg&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.common.MediaProcessorImage&quot;,&quot;$id&quot;:&quot;urn:li:fs_miniProfile:ACoAABEeJjgBirRpt0KbsGVTQQEI8hfjFcBzdMw,picture,com.linkedin.voyager.common.MediaProcessorImage&quot;},{&quot;firstName&quot;:&quot;Mohammed&quot;,&quot;lastName&quot;:&quot;Attya&quot;,&quot;$deletedFields&quot;:[&quot;backgroundImage&quot;],&quot;occupation&quot;:&quot;PHP Web Developer at Queen Tech Solutions&quot;,&quot;objectUrn&quot;:&quot;urn:li:member:287188536&quot;,&quot;entityUrn&quot;:&quot;urn:li:fs_miniProfile:ACoAABEeJjgBirRpt0KbsGVTQQEI8hfjFcBzdMw&quot;,&quot;publicIdentifier&quot;:&quot;mohammedattya&quot;,&quot;picture&quot;:{&quot;com.linkedin.voyager.common.MediaProcessorImage&quot;:&quot;urn:li:fs_miniProfile:ACoAABEeJjgBirRpt0KbsGVTQQEI8hfjFcBzdMw,picture,com.linkedin.voyager.common.MediaProcessorImage&quot;},&quot;trackingId&quot;:&quot;A/oTyZKfQOe19NZVpMMqsg&#61;&#61;&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.shared.MiniProfile&quot;},{&quot;$deletedFields&quot;:[],&quot;twitterHandles&quot;:[&quot;qTJ8dtboEoV+oq/pm1xyeA&#61;&#61;,root,publicContactInfo,twitterHandles,5249bf8f-ea2a-42a5-9a32-9bd9f63935f9-0&quot;],&quot;$type&quot;:&quot;com.linkedin.voyager.identity.shared.PublicContactInfo&quot;,&quot;$id&quot;:&quot;qTJ8dtboEoV+oq/pm1xyeA&#61;&#61;,root,publicContactInfo&quot;},{&quot;$deletedFields&quot;:[],&quot;name&quot;:&quot;mohammed_attya&quot;,&quot;credentialId&quot;:&quot;urn:li:member:287188536;111282560&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.shared.TwitterHandle&quot;,&quot;$id&quot;:&quot;qTJ8dtboEoV+oq/pm1xyeA&#61;&#61;,root,publicContactInfo,twitterHandles,5249bf8f-ea2a-42a5-9a32-9bd9f63935f9-0&quot;}]}
</code>
<code style="display: none" id="datalet-bpr-guid-1605820">
  {"request":"/voyager/api/me","status":200,"body":"bpr-guid-1605820"}
</code>
<img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" style="display: none" class="datalet-bpr-guid-1605820"><code style="display: none" id="bpr-guid-1605821">
  {&quot;data&quot;:{&quot;$deletedFields&quot;:[],&quot;followingInfo&quot;:&quot;urn:li:fs_followingInfo:urn:li:member:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8&quot;,&quot;distance&quot;:&quot;urn:li:fs_profileNetworkInfo:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,distance&quot;,&quot;entityUrn&quot;:&quot;urn:li:fs_profileNetworkInfo:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8&quot;,&quot;following&quot;:false,&quot;followable&quot;:true,&quot;followersCount&quot;:9568,&quot;connectionsCount&quot;:500,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.ProfileNetworkInfo&quot;},&quot;included&quot;:[{&quot;$deletedFields&quot;:[&quot;followingCount&quot;],&quot;entityUrn&quot;:&quot;urn:li:fs_followingInfo:urn:li:member:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8&quot;,&quot;following&quot;:false,&quot;followerCount&quot;:9568,&quot;$type&quot;:&quot;com.linkedin.voyager.common.FollowingInfo&quot;},{&quot;$deletedFields&quot;:[],&quot;value&quot;:&quot;DISTANCE_2&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.common.MemberDistance&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileNetworkInfo:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,distance&quot;}]}
</code>
<code style="display: none" id="datalet-bpr-guid-1605821">
  {"request":"/voyager/api/identity/profiles/ahmedriad1/networkinfo","status":200,"body":"bpr-guid-1605821"}
</code>
<img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" style="display: none" class="datalet-bpr-guid-1605821"><code style="display: none" id="bpr-guid-1605822">
  {&quot;data&quot;:{&quot;elements&quot;:[],&quot;paging&quot;:{&quot;count&quot;:10,&quot;start&quot;:0,&quot;links&quot;:[]}},&quot;included&quot;:[]}
</code>
<code style="display: none" id="datalet-bpr-guid-1605822">
  {"request":"/voyager/api/identity/profiles/ahmedriad1/treasuryMediaItems?q\u003DbackgroundMedia\u0026section\u003DEDUCATION","status":200,"body":"bpr-guid-1605822"}
</code>
<img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" style="display: none" class="datalet-bpr-guid-1605822"><code style="display: none" id="bpr-guid-1605823">
  {&quot;data&quot;:{&quot;elements&quot;:[],&quot;paging&quot;:{&quot;total&quot;:0,&quot;count&quot;:10,&quot;start&quot;:0,&quot;links&quot;:[]}},&quot;included&quot;:[]}
</code>
<code style="display: none" id="datalet-bpr-guid-1605823">
  {"request":"/voyager/api/identity/profiles/ahmedriad1/treasuryMedia?q\u003DsectionMedia\u0026sectionId\u003Dsummary","status":200,"body":"bpr-guid-1605823"}
</code>
<img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" style="display: none" class="datalet-bpr-guid-1605823"><code style="display: none" id="bpr-guid-1605824">
  {&quot;data&quot;:{&quot;elements&quot;:[],&quot;paging&quot;:{&quot;count&quot;:10,&quot;start&quot;:0,&quot;links&quot;:[]}},&quot;included&quot;:[]}
</code>
<code style="display: none" id="datalet-bpr-guid-1605824">
  {"request":"/voyager/api/identity/profiles/ahmedriad1/treasuryMediaItems?q\u003DbackgroundMedia\u0026section\u003DPOSITION","status":200,"body":"bpr-guid-1605824"}
</code>
<img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" style="display: none" class="datalet-bpr-guid-1605824"><code style="display: none" id="bpr-guid-1605825">
  {&quot;data&quot;:{&quot;$deletedFields&quot;:[&quot;actions&quot;],&quot;primaryAction&quot;:&quot;urn:li:fs_profileactions:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,primaryAction&quot;,&quot;entityUrn&quot;:&quot;urn:li:fs_profileactions:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8&quot;,&quot;secondaryAction&quot;:&quot;urn:li:fs_profileactions:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,secondaryAction&quot;,&quot;overflowActions&quot;:[&quot;urn:li:fs_profileactions:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,overflowActions,942664ad-9a6a-4c84-9c59-b8d7168bb3f7-0&quot;,&quot;urn:li:fs_profileactions:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,overflowActions,942664ad-9a6a-4c84-9c59-b8d7168bb3f7-1&quot;,&quot;urn:li:fs_profileactions:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,overflowActions,942664ad-9a6a-4c84-9c59-b8d7168bb3f7-2&quot;],&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.actions.ProfileActions&quot;},&quot;included&quot;:[{&quot;$deletedFields&quot;:[&quot;iweWarned&quot;,&quot;emailRequired&quot;],&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.actions.Connect&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileactions:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,primaryAction,action,com.linkedin.voyager.identity.profile.actions.Connect&quot;},{&quot;$deletedFields&quot;:[],&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.actions.Follow&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileactions:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,overflowActions,942664ad-9a6a-4c84-9c59-b8d7168bb3f7-0,action,com.linkedin.voyager.identity.profile.actions.Follow&quot;},{&quot;$deletedFields&quot;:[&quot;type&quot;],&quot;action&quot;:{&quot;com.linkedin.voyager.identity.profile.actions.Follow&quot;:&quot;urn:li:fs_profileactions:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,overflowActions,942664ad-9a6a-4c84-9c59-b8d7168bb3f7-0,action,com.linkedin.voyager.identity.profile.actions.Follow&quot;},&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.actions.ProfileAction&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileactions:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,overflowActions,942664ad-9a6a-4c84-9c59-b8d7168bb3f7-0&quot;},{&quot;$deletedFields&quot;:[&quot;type&quot;],&quot;action&quot;:{&quot;com.linkedin.voyager.identity.profile.actions.Report&quot;:&quot;urn:li:fs_profileactions:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,overflowActions,942664ad-9a6a-4c84-9c59-b8d7168bb3f7-2,action,com.linkedin.voyager.identity.profile.actions.Report&quot;},&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.actions.ProfileAction&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileactions:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,overflowActions,942664ad-9a6a-4c84-9c59-b8d7168bb3f7-2&quot;},{&quot;$deletedFields&quot;:[&quot;type&quot;],&quot;action&quot;:{&quot;com.linkedin.voyager.identity.profile.actions.SaveToPdf&quot;:&quot;urn:li:fs_profileactions:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,overflowActions,942664ad-9a6a-4c84-9c59-b8d7168bb3f7-1,action,com.linkedin.voyager.identity.profile.actions.SaveToPdf&quot;},&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.actions.ProfileAction&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileactions:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,overflowActions,942664ad-9a6a-4c84-9c59-b8d7168bb3f7-1&quot;},{&quot;$deletedFields&quot;:[&quot;type&quot;],&quot;action&quot;:{&quot;com.linkedin.voyager.identity.profile.actions.SendInMail&quot;:&quot;urn:li:fs_profileactions:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,secondaryAction,action,com.linkedin.voyager.identity.profile.actions.SendInMail&quot;},&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.actions.ProfileAction&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileactions:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,secondaryAction&quot;},{&quot;$deletedFields&quot;:[&quot;type&quot;],&quot;action&quot;:{&quot;com.linkedin.voyager.identity.profile.actions.Connect&quot;:&quot;urn:li:fs_profileactions:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,primaryAction,action,com.linkedin.voyager.identity.profile.actions.Connect&quot;},&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.actions.ProfileAction&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileactions:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,primaryAction&quot;},{&quot;$deletedFields&quot;:[],&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.actions.Report&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileactions:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,overflowActions,942664ad-9a6a-4c84-9c59-b8d7168bb3f7-2,action,com.linkedin.voyager.identity.profile.actions.Report&quot;},{&quot;$deletedFields&quot;:[],&quot;requestUrl&quot;:&quot;https://www.linkedin.com/profile/pdf?id&#61;ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8&amp;locale&#61;en_US&amp;pdfFileName&#61;AhmedRiadProfile&amp;authType&#61;name&amp;authToken&#61;vEn7&amp;disablePdfCompression&#61;true&amp;trk&#61;pdf_pro_full&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.actions.SaveToPdf&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileactions:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,overflowActions,942664ad-9a6a-4c84-9c59-b8d7168bb3f7-1,action,com.linkedin.voyager.identity.profile.actions.SaveToPdf&quot;},{&quot;$deletedFields&quot;:[],&quot;upsell&quot;:true,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.actions.SendInMail&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileactions:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,secondaryAction,action,com.linkedin.voyager.identity.profile.actions.SendInMail&quot;}]}
</code>
<code style="display: none" id="datalet-bpr-guid-1605825">
  {"request":"/voyager/api/identity/profiles/ahmedriad1/profileActions","status":200,"body":"bpr-guid-1605825"}
</code>
<img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" style="display: none" class="datalet-bpr-guid-1605825"><code style="display: none" id="bpr-guid-1605826">
  {&quot;data&quot;:{&quot;$deletedFields&quot;:[],&quot;versionTag&quot;:&quot;1249945760&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.VersionTag&quot;,&quot;$id&quot;:&quot;OFH9U2i1N5p1KjdEiNaTbQ&#61;&#61;,root&quot;},&quot;included&quot;:[]}
</code>
<code style="display: none" id="datalet-bpr-guid-1605826">
  {"request":"/voyager/api/identity/profiles/ACoAABEeJjgBirRpt0KbsGVTQQEI8hfjFcBzdMw/versionTag","status":200,"body":"bpr-guid-1605826"}
</code>
<img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" style="display: none" class="datalet-bpr-guid-1605826"><code style="display: none" id="bpr-guid-1605827">
  {&quot;data&quot;:{&quot;patentView&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,patentView&quot;,&quot;educationView&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,educationView&quot;,&quot;organizationView&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,organizationView&quot;,&quot;projectView&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,projectView&quot;,&quot;positionView&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,positionView&quot;,&quot;profile&quot;:&quot;urn:li:fs_profile:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8&quot;,&quot;languageView&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,languageView&quot;,&quot;certificationView&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,certificationView&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.ProfileView&quot;,&quot;$deletedFields&quot;:[&quot;entityLocale&quot;],&quot;testScoreView&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,testScoreView&quot;,&quot;volunteerCauseView&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,volunteerCauseView&quot;,&quot;entityUrn&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8&quot;,&quot;courseView&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,courseView&quot;,&quot;honorView&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,honorView&quot;,&quot;skillView&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,skillView&quot;,&quot;volunteerExperienceView&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,volunteerExperienceView&quot;,&quot;primaryLocale&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,primaryLocale&quot;,&quot;publicationView&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,publicationView&quot;},&quot;included&quot;:[{&quot;x&quot;:0.1603260869565217,&quot;y&quot;:0.42894745808913726,&quot;$deletedFields&quot;:[],&quot;$type&quot;:&quot;com.linkedin.common.Coordinate2D&quot;,&quot;$id&quot;:&quot;urn:li:fs_profile:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,pictureInfo,photoFilterEditInfo,topLeft&quot;},{&quot;x&quot;:0.1603260869565217,&quot;y&quot;:0.8590023170233065,&quot;$deletedFields&quot;:[],&quot;$type&quot;:&quot;com.linkedin.common.Coordinate2D&quot;,&quot;$id&quot;:&quot;urn:li:fs_profile:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,pictureInfo,photoFilterEditInfo,bottomLeft&quot;},{&quot;x&quot;:0.7853260869565217,&quot;y&quot;:0.8590023170233065,&quot;$deletedFields&quot;:[],&quot;$type&quot;:&quot;com.linkedin.common.Coordinate2D&quot;,&quot;$id&quot;:&quot;urn:li:fs_profile:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,pictureInfo,photoFilterEditInfo,bottomRight&quot;},{&quot;x&quot;:0.7853260869565217,&quot;y&quot;:0.42894745808913726,&quot;$deletedFields&quot;:[],&quot;$type&quot;:&quot;com.linkedin.common.Coordinate2D&quot;,&quot;$id&quot;:&quot;urn:li:fs_profile:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,pictureInfo,photoFilterEditInfo,topRight&quot;},{&quot;$deletedFields&quot;:[&quot;day&quot;],&quot;month&quot;:4,&quot;year&quot;:2015,&quot;$type&quot;:&quot;com.linkedin.common.Date&quot;,&quot;$id&quot;:&quot;urn:li:fs_position:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,708058012),timePeriod,startDate&quot;},{&quot;$deletedFields&quot;:[&quot;month&quot;,&quot;day&quot;],&quot;year&quot;:2008,&quot;$type&quot;:&quot;com.linkedin.common.Date&quot;,&quot;$id&quot;:&quot;urn:li:fs_education:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,253115464),timePeriod,startDate&quot;},{&quot;$deletedFields&quot;:[&quot;year&quot;],&quot;month&quot;:1,&quot;day&quot;:5,&quot;$type&quot;:&quot;com.linkedin.common.Date&quot;,&quot;$id&quot;:&quot;urn:li:fs_profile:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,birthDate&quot;},{&quot;$deletedFields&quot;:[&quot;month&quot;,&quot;day&quot;],&quot;year&quot;:2013,&quot;$type&quot;:&quot;com.linkedin.common.Date&quot;,&quot;$id&quot;:&quot;urn:li:fs_education:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,253115464),timePeriod,endDate&quot;},{&quot;$deletedFields&quot;:[&quot;day&quot;],&quot;month&quot;:3,&quot;year&quot;:2016,&quot;$type&quot;:&quot;com.linkedin.common.Date&quot;,&quot;$id&quot;:&quot;urn:li:fs_position:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,708058012),timePeriod,endDate&quot;},{&quot;$deletedFields&quot;:[&quot;day&quot;],&quot;month&quot;:12,&quot;year&quot;:2014,&quot;$type&quot;:&quot;com.linkedin.common.Date&quot;,&quot;$id&quot;:&quot;urn:li:fs_position:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,644675701),timePeriod,startDate&quot;},{&quot;$deletedFields&quot;:[&quot;day&quot;],&quot;month&quot;:3,&quot;year&quot;:2016,&quot;$type&quot;:&quot;com.linkedin.common.Date&quot;,&quot;$id&quot;:&quot;urn:li:fs_position:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,791486752),timePeriod,startDate&quot;},{&quot;$deletedFields&quot;:[&quot;day&quot;],&quot;month&quot;:11,&quot;year&quot;:2012,&quot;$type&quot;:&quot;com.linkedin.common.Date&quot;,&quot;$id&quot;:&quot;urn:li:fs_volunteerExperience:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,1955669207),timePeriod,endDate&quot;},{&quot;$deletedFields&quot;:[&quot;day&quot;],&quot;month&quot;:3,&quot;year&quot;:2015,&quot;$type&quot;:&quot;com.linkedin.common.Date&quot;,&quot;$id&quot;:&quot;urn:li:fs_position:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,644675701),timePeriod,endDate&quot;},{&quot;$deletedFields&quot;:[&quot;day&quot;],&quot;month&quot;:2,&quot;year&quot;:2012,&quot;$type&quot;:&quot;com.linkedin.common.Date&quot;,&quot;$id&quot;:&quot;urn:li:fs_position:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,612638849),timePeriod,endDate&quot;},{&quot;$deletedFields&quot;:[&quot;day&quot;],&quot;month&quot;:2,&quot;year&quot;:2011,&quot;$type&quot;:&quot;com.linkedin.common.Date&quot;,&quot;$id&quot;:&quot;urn:li:fs_position:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,612638849),timePeriod,startDate&quot;},{&quot;$deletedFields&quot;:[&quot;day&quot;],&quot;month&quot;:8,&quot;year&quot;:2012,&quot;$type&quot;:&quot;com.linkedin.common.Date&quot;,&quot;$id&quot;:&quot;urn:li:fs_volunteerExperience:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,1955669207),timePeriod,startDate&quot;},{&quot;country&quot;:&quot;US&quot;,&quot;language&quot;:&quot;en&quot;,&quot;$deletedFields&quot;:[&quot;variant&quot;],&quot;$type&quot;:&quot;com.linkedin.common.Locale&quot;,&quot;$id&quot;:&quot;urn:li:fs_profile:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,supportedLocales,3e2dd06a-7889-426c-88d1-9f5f7e5dd557-0&quot;},{&quot;country&quot;:&quot;US&quot;,&quot;$deletedFields&quot;:[&quot;variant&quot;],&quot;language&quot;:&quot;en&quot;,&quot;$type&quot;:&quot;com.linkedin.common.Locale&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,primaryLocale&quot;},{&quot;total&quot;:0,&quot;$deletedFields&quot;:[],&quot;start&quot;:0,&quot;count&quot;:10,&quot;links&quot;:[],&quot;$type&quot;:&quot;com.linkedin.restli.common.CollectionMetadata&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,projectView,paging&quot;},{&quot;total&quot;:0,&quot;$deletedFields&quot;:[],&quot;start&quot;:0,&quot;count&quot;:10,&quot;links&quot;:[],&quot;$type&quot;:&quot;com.linkedin.restli.common.CollectionMetadata&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,publicationView,paging&quot;},{&quot;total&quot;:0,&quot;$deletedFields&quot;:[],&quot;start&quot;:0,&quot;count&quot;:10,&quot;links&quot;:[],&quot;$type&quot;:&quot;com.linkedin.restli.common.CollectionMetadata&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,patentView,paging&quot;},{&quot;total&quot;:0,&quot;$deletedFields&quot;:[],&quot;start&quot;:0,&quot;count&quot;:10,&quot;links&quot;:[],&quot;$type&quot;:&quot;com.linkedin.restli.common.CollectionMetadata&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,organizationView,paging&quot;},{&quot;total&quot;:1,&quot;$deletedFields&quot;:[],&quot;start&quot;:0,&quot;count&quot;:3,&quot;links&quot;:[],&quot;$type&quot;:&quot;com.linkedin.restli.common.CollectionMetadata&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,educationView,paging&quot;},{&quot;total&quot;:0,&quot;$deletedFields&quot;:[],&quot;start&quot;:0,&quot;count&quot;:10,&quot;links&quot;:[],&quot;$type&quot;:&quot;com.linkedin.restli.common.CollectionMetadata&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,honorView,paging&quot;},{&quot;total&quot;:0,&quot;$deletedFields&quot;:[],&quot;start&quot;:0,&quot;count&quot;:10,&quot;links&quot;:[],&quot;$type&quot;:&quot;com.linkedin.restli.common.CollectionMetadata&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,testScoreView,paging&quot;},{&quot;total&quot;:4,&quot;$deletedFields&quot;:[],&quot;start&quot;:0,&quot;count&quot;:10,&quot;links&quot;:[],&quot;$type&quot;:&quot;com.linkedin.restli.common.CollectionMetadata&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,courseView,paging&quot;},{&quot;total&quot;:2,&quot;$deletedFields&quot;:[],&quot;start&quot;:0,&quot;count&quot;:10,&quot;links&quot;:[],&quot;$type&quot;:&quot;com.linkedin.restli.common.CollectionMetadata&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,languageView,paging&quot;},{&quot;total&quot;:1,&quot;$deletedFields&quot;:[],&quot;start&quot;:0,&quot;count&quot;:3,&quot;links&quot;:[],&quot;$type&quot;:&quot;com.linkedin.restli.common.CollectionMetadata&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,volunteerExperienceView,paging&quot;},{&quot;total&quot;:22,&quot;$deletedFields&quot;:[],&quot;start&quot;:0,&quot;count&quot;:4,&quot;links&quot;:[],&quot;$type&quot;:&quot;com.linkedin.restli.common.CollectionMetadata&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,skillView,paging&quot;},{&quot;total&quot;:4,&quot;$deletedFields&quot;:[],&quot;start&quot;:0,&quot;count&quot;:5,&quot;links&quot;:[],&quot;$type&quot;:&quot;com.linkedin.restli.common.CollectionMetadata&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,positionView,paging&quot;},{&quot;total&quot;:0,&quot;$deletedFields&quot;:[],&quot;start&quot;:0,&quot;count&quot;:10,&quot;links&quot;:[],&quot;$type&quot;:&quot;com.linkedin.restli.common.CollectionMetadata&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,certificationView,paging&quot;},{&quot;total&quot;:4,&quot;$deletedFields&quot;:[],&quot;start&quot;:0,&quot;count&quot;:4,&quot;links&quot;:[],&quot;$type&quot;:&quot;com.linkedin.restli.common.CollectionMetadata&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,volunteerCauseView,paging&quot;},{&quot;$deletedFields&quot;:[],&quot;endDate&quot;:&quot;urn:li:fs_position:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,708058012),timePeriod,endDate&quot;,&quot;startDate&quot;:&quot;urn:li:fs_position:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,708058012),timePeriod,startDate&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.common.DateRange&quot;,&quot;$id&quot;:&quot;urn:li:fs_position:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,708058012),timePeriod&quot;},{&quot;$deletedFields&quot;:[],&quot;endDate&quot;:&quot;urn:li:fs_education:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,253115464),timePeriod,endDate&quot;,&quot;startDate&quot;:&quot;urn:li:fs_education:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,253115464),timePeriod,startDate&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.common.DateRange&quot;,&quot;$id&quot;:&quot;urn:li:fs_education:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,253115464),timePeriod&quot;},{&quot;$deletedFields&quot;:[&quot;endDate&quot;],&quot;startDate&quot;:&quot;urn:li:fs_position:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,791486752),timePeriod,startDate&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.common.DateRange&quot;,&quot;$id&quot;:&quot;urn:li:fs_position:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,791486752),timePeriod&quot;},{&quot;$deletedFields&quot;:[],&quot;endDate&quot;:&quot;urn:li:fs_volunteerExperience:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,1955669207),timePeriod,endDate&quot;,&quot;startDate&quot;:&quot;urn:li:fs_volunteerExperience:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,1955669207),timePeriod,startDate&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.common.DateRange&quot;,&quot;$id&quot;:&quot;urn:li:fs_volunteerExperience:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,1955669207),timePeriod&quot;},{&quot;$deletedFields&quot;:[],&quot;endDate&quot;:&quot;urn:li:fs_position:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,644675701),timePeriod,endDate&quot;,&quot;startDate&quot;:&quot;urn:li:fs_position:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,644675701),timePeriod,startDate&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.common.DateRange&quot;,&quot;$id&quot;:&quot;urn:li:fs_position:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,644675701),timePeriod&quot;},{&quot;$deletedFields&quot;:[],&quot;endDate&quot;:&quot;urn:li:fs_position:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,612638849),timePeriod,endDate&quot;,&quot;startDate&quot;:&quot;urn:li:fs_position:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,612638849),timePeriod,startDate&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.common.DateRange&quot;,&quot;$id&quot;:&quot;urn:li:fs_position:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,612638849),timePeriod&quot;},{&quot;$deletedFields&quot;:[&quot;attribution&quot;],&quot;id&quot;:&quot;/AAEAAQAAAAAAAAxxAAAAJGY5MDlhNGMxLTdlYmUtNDhiMy1iMjQyLTI3Y2NhMmJlYjI4MA.jpg&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.common.MediaProcessorImage&quot;,&quot;$id&quot;:&quot;urn:li:fs_miniProfile:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,picture,com.linkedin.voyager.common.MediaProcessorImage&quot;},{&quot;$deletedFields&quot;:[&quot;attribution&quot;],&quot;id&quot;:&quot;/p/1/000/05a/1b0/0d31143.png&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.common.MediaProcessorImage&quot;,&quot;$id&quot;:&quot;urn:li:fs_miniCompany:980903,logo,com.linkedin.voyager.common.MediaProcessorImage&quot;},{&quot;$deletedFields&quot;:[&quot;attribution&quot;],&quot;id&quot;:&quot;/AAEAAQAAAAAAAAQAAAAAJDc3YTY0ZmFiLTg5YTItNGI2YS1hMjMwLTE4MDBhMzYwZWRhZg.jpg&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.common.MediaProcessorImage&quot;,&quot;$id&quot;:&quot;urn:li:fs_profile:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,backgroundImage,image,com.linkedin.voyager.common.MediaProcessorImage&quot;},{&quot;$deletedFields&quot;:[&quot;attribution&quot;],&quot;id&quot;:&quot;/AAEAAQAAAAAAAAQAAAAAJDc3YTY0ZmFiLTg5YTItNGI2YS1hMjMwLTE4MDBhMzYwZWRhZg.jpg&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.common.MediaProcessorImage&quot;,&quot;$id&quot;:&quot;urn:li:fs_miniProfile:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,backgroundImage,com.linkedin.voyager.common.MediaProcessorImage&quot;},{&quot;$deletedFields&quot;:[&quot;attribution&quot;],&quot;id&quot;:&quot;/p/3/005/0a4/3df/14bef65.png&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.common.MediaProcessorImage&quot;,&quot;$id&quot;:&quot;urn:li:fs_miniSchool:12171,logo,com.linkedin.voyager.common.MediaProcessorImage&quot;},{&quot;$deletedFields&quot;:[&quot;attribution&quot;],&quot;id&quot;:&quot;/AAEAAQAAAAAAAANSAAAAJGRhYjgwMTAyLTY0ODQtNDJiZS04MmE1LTNlNWY0ZjI5MTEyOQ.png&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.common.MediaProcessorImage&quot;,&quot;$id&quot;:&quot;urn:li:fs_miniCompany:9427362,logo,com.linkedin.voyager.common.MediaProcessorImage&quot;},{&quot;$deletedFields&quot;:[],&quot;countryCode&quot;:&quot;eg&quot;,&quot;postalCode&quot;:&quot;456&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.common.NormBasicLocation&quot;,&quot;$id&quot;:&quot;urn:li:fs_profile:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,location,basicLocation&quot;},{&quot;$deletedFields&quot;:[&quot;universalName&quot;],&quot;objectUrn&quot;:&quot;urn:li:company:9427362&quot;,&quot;entityUrn&quot;:&quot;urn:li:fs_miniCompany:9427362&quot;,&quot;name&quot;:&quot;AWTAD&quot;,&quot;showcase&quot;:false,&quot;active&quot;:true,&quot;logo&quot;:{&quot;com.linkedin.voyager.common.MediaProcessorImage&quot;:&quot;urn:li:fs_miniCompany:9427362,logo,com.linkedin.voyager.common.MediaProcessorImage&quot;},&quot;trackingId&quot;:&quot;a1Si2eTNQ9ijsvFTXkO/TA&#61;&#61;&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.entities.shared.MiniCompany&quot;},{&quot;$deletedFields&quot;:[&quot;universalName&quot;],&quot;objectUrn&quot;:&quot;urn:li:company:980903&quot;,&quot;entityUrn&quot;:&quot;urn:li:fs_miniCompany:980903&quot;,&quot;name&quot;:&quot;Al-Watania Poultry (Egypt)&quot;,&quot;showcase&quot;:false,&quot;active&quot;:true,&quot;logo&quot;:{&quot;com.linkedin.voyager.common.MediaProcessorImage&quot;:&quot;urn:li:fs_miniCompany:980903,logo,com.linkedin.voyager.common.MediaProcessorImage&quot;},&quot;trackingId&quot;:&quot;d9LYT4kvTra7hMA8xLfvJg&#61;&#61;&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.entities.shared.MiniCompany&quot;},{&quot;$deletedFields&quot;:[],&quot;objectUrn&quot;:&quot;urn:li:school:12171&quot;,&quot;entityUrn&quot;:&quot;urn:li:fs_miniSchool:12171&quot;,&quot;active&quot;:true,&quot;logo&quot;:{&quot;com.linkedin.voyager.common.MediaProcessorImage&quot;:&quot;urn:li:fs_miniSchool:12171,logo,com.linkedin.voyager.common.MediaProcessorImage&quot;},&quot;schoolName&quot;:&quot;Helwan University Cairo&quot;,&quot;trackingId&quot;:&quot;FeIpoZNrQauRpuF2hplD4A&#61;&#61;&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.entities.shared.MiniSchool&quot;},{&quot;image&quot;:{&quot;com.linkedin.voyager.common.MediaProcessorImage&quot;:&quot;urn:li:fs_profile:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,backgroundImage,image,com.linkedin.voyager.common.MediaProcessorImage&quot;},&quot;$deletedFields&quot;:[&quot;croppedImage&quot;,&quot;photoFilterEditInfo&quot;],&quot;cropInfo&quot;:&quot;urn:li:fs_profile:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,backgroundImage,cropInfo&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.BackgroundImage&quot;,&quot;$id&quot;:&quot;urn:li:fs_profile:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,backgroundImage&quot;},{&quot;$deletedFields&quot;:[],&quot;profileId&quot;:&quot;ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8&quot;,&quot;elements&quot;:[],&quot;paging&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,certificationView,paging&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.CertificationView&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,certificationView&quot;},{&quot;$deletedFields&quot;:[&quot;number&quot;,&quot;occupation&quot;],&quot;entityUrn&quot;:&quot;urn:li:fs_course:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,612638860)&quot;,&quot;name&quot;:&quot;Work shop  Labor law &amp; Social Insurance at HR Leaders&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.Course&quot;},{&quot;$deletedFields&quot;:[&quot;number&quot;,&quot;occupation&quot;],&quot;entityUrn&quot;:&quot;urn:li:fs_course:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,612638861)&quot;,&quot;name&quot;:&quot;Business Administration, Customer Service, Marketing, Sales, Soft skills&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.Course&quot;},{&quot;$deletedFields&quot;:[&quot;number&quot;,&quot;occupation&quot;],&quot;entityUrn&quot;:&quot;urn:li:fs_course:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,612638862)&quot;,&quot;name&quot;:&quot;&#92;&quot;ICDL&#92;&quot; at ISI&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.Course&quot;},{&quot;$deletedFields&quot;:[&quot;number&quot;,&quot;occupation&quot;],&quot;entityUrn&quot;:&quot;urn:li:fs_course:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,612638863)&quot;,&quot;name&quot;:&quot;Peach tree accounting application, Contract Accounting, Cost Accounting and Manual Accounting&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.Course&quot;},{&quot;$deletedFields&quot;:[],&quot;profileId&quot;:&quot;ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8&quot;,&quot;elements&quot;:[&quot;urn:li:fs_course:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,612638862)&quot;,&quot;urn:li:fs_course:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,612638861)&quot;,&quot;urn:li:fs_course:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,612638863)&quot;,&quot;urn:li:fs_course:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,612638860)&quot;],&quot;paging&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,courseView,paging&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.CourseView&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,courseView&quot;},{&quot;$deletedFields&quot;:[&quot;courses&quot;,&quot;projects&quot;,&quot;recommendations&quot;,&quot;honors&quot;,&quot;entityLocale&quot;,&quot;activities&quot;,&quot;fieldOfStudyUrn&quot;,&quot;testScores&quot;,&quot;degreeUrn&quot;],&quot;entityUrn&quot;:&quot;urn:li:fs_education:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,253115464)&quot;,&quot;school&quot;:&quot;urn:li:fs_miniSchool:12171&quot;,&quot;grade&quot;:&quot;Good&quot;,&quot;timePeriod&quot;:&quot;urn:li:fs_education:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,253115464),timePeriod&quot;,&quot;description&quot;:&quot;Accounting&quot;,&quot;degreeName&quot;:&quot;• BSc in Commerce and Business Administration in Helwan University&quot;,&quot;schoolName&quot;:&quot;Helwan University Cairo&quot;,&quot;fieldOfStudy&quot;:&quot;Accounting&quot;,&quot;schoolUrn&quot;:&quot;urn:li:fs_miniSchool:12171&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.Education&quot;},{&quot;$deletedFields&quot;:[],&quot;profileId&quot;:&quot;ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8&quot;,&quot;elements&quot;:[&quot;urn:li:fs_education:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,253115464)&quot;],&quot;paging&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,educationView,paging&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.EducationView&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,educationView&quot;},{&quot;$deletedFields&quot;:[],&quot;start&quot;:1001,&quot;end&quot;:5000,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.EmployeeCountRange&quot;,&quot;$id&quot;:&quot;urn:li:fs_position:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,612638849),company,employeeCountRange&quot;},{&quot;$deletedFields&quot;:[],&quot;start&quot;:51,&quot;end&quot;:200,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.EmployeeCountRange&quot;,&quot;$id&quot;:&quot;urn:li:fs_position:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,708058012),company,employeeCountRange&quot;},{&quot;$deletedFields&quot;:[],&quot;profileId&quot;:&quot;ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8&quot;,&quot;elements&quot;:[],&quot;paging&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,honorView,paging&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.HonorView&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,honorView&quot;},{&quot;$deletedFields&quot;:[],&quot;x&quot;:0,&quot;width&quot;:450,&quot;y&quot;:0,&quot;height&quot;:450,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.ImageCropInfo&quot;,&quot;$id&quot;:&quot;urn:li:fs_profile:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,pictureInfo,cropInfo&quot;},{&quot;$deletedFields&quot;:[],&quot;x&quot;:0,&quot;width&quot;:0,&quot;y&quot;:1,&quot;height&quot;:0,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.ImageCropInfo&quot;,&quot;$id&quot;:&quot;urn:li:fs_profile:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,backgroundImage,cropInfo&quot;},{&quot;$deletedFields&quot;:[],&quot;entityUrn&quot;:&quot;urn:li:fs_language:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,612638857)&quot;,&quot;name&quot;:&quot;Arabic&quot;,&quot;proficiency&quot;:&quot;NATIVE_OR_BILINGUAL&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.Language&quot;},{&quot;$deletedFields&quot;:[&quot;proficiency&quot;],&quot;entityUrn&quot;:&quot;urn:li:fs_language:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,1)&quot;,&quot;name&quot;:&quot;English&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.Language&quot;},{&quot;$deletedFields&quot;:[],&quot;profileId&quot;:&quot;ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8&quot;,&quot;elements&quot;:[&quot;urn:li:fs_language:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,612638857)&quot;,&quot;urn:li:fs_language:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,1)&quot;],&quot;paging&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,languageView,paging&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.LanguageView&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,languageView&quot;},{&quot;$deletedFields&quot;:[],&quot;profileId&quot;:&quot;ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8&quot;,&quot;elements&quot;:[],&quot;paging&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,organizationView,paging&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.OrganizationView&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,organizationView&quot;},{&quot;$deletedFields&quot;:[],&quot;profileId&quot;:&quot;ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8&quot;,&quot;elements&quot;:[],&quot;paging&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,patentView,paging&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.PatentView&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,patentView&quot;},{&quot;bottomLeft&quot;:&quot;urn:li:fs_profile:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,pictureInfo,photoFilterEditInfo,bottomLeft&quot;,&quot;saturation&quot;:0.0,&quot;$deletedFields&quot;:[],&quot;brightness&quot;:0.0,&quot;vignette&quot;:0.0,&quot;photoFilterType&quot;:&quot;ORIGINAL&quot;,&quot;bottomRight&quot;:&quot;urn:li:fs_profile:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,pictureInfo,photoFilterEditInfo,bottomRight&quot;,&quot;topLeft&quot;:&quot;urn:li:fs_profile:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,pictureInfo,photoFilterEditInfo,topLeft&quot;,&quot;contrast&quot;:0.0,&quot;topRight&quot;:&quot;urn:li:fs_profile:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,pictureInfo,photoFilterEditInfo,topRight&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.PhotoFilterEditInfo&quot;,&quot;$id&quot;:&quot;urn:li:fs_profile:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,pictureInfo,photoFilterEditInfo&quot;},{&quot;$deletedFields&quot;:[],&quot;croppedImage&quot;:&quot;/AAEAAQAAAAAAAAxxAAAAJGY5MDlhNGMxLTdlYmUtNDhiMy1iMjQyLTI3Y2NhMmJlYjI4MA.jpg&quot;,&quot;masterImage&quot;:&quot;/AAEAAQAAAAAAAAxxAAAAJGY5MDlhNGMxLTdlYmUtNDhiMy1iMjQyLTI3Y2NhMmJlYjI4MA.jpg&quot;,&quot;photoFilterEditInfo&quot;:&quot;urn:li:fs_profile:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,pictureInfo,photoFilterEditInfo&quot;,&quot;cropInfo&quot;:&quot;urn:li:fs_profile:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,pictureInfo,cropInfo&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.Picture&quot;,&quot;$id&quot;:&quot;urn:li:fs_profile:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,pictureInfo&quot;},{&quot;$deletedFields&quot;:[&quot;courses&quot;,&quot;projects&quot;,&quot;companyUrn&quot;,&quot;recommendations&quot;,&quot;honors&quot;,&quot;entityLocale&quot;,&quot;organizations&quot;,&quot;company&quot;],&quot;locationName&quot;:&quot;Egypt&quot;,&quot;entityUrn&quot;:&quot;urn:li:fs_position:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,791486752)&quot;,&quot;companyName&quot;:&quot;confidential&quot;,&quot;timePeriod&quot;:&quot;urn:li:fs_position:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,791486752),timePeriod&quot;,&quot;description&quot;:&quot;-&#92;tAnnounce through company internal communication channels vacancies available in the plan to attract right calibers.&#92;n-&#92;tGive assistance in Preparing Annual Manpower Plan.&#92;n-&#92;tUse different resources to post new vacancies to be able to attract the right calibers.&#92;n-&#92;tScreen received CVs to be able to conduct initial phone screening. &#92;n-&#92;tConduct interviews for different levels.&#92;n-&#92;tCall HR at other companies for reference checks process.&#92;n-&#92;tSend offer letters to the accepted candidates to ensure complete work operations. &#92;n-&#92;tFollow up the HR Officer to urge them to complete their Branches Vacant in the agreed dates. &#92;n-&#92;tUpdate the recruitment database regularly. &#92;n-&#92;tCoordinate with the Recruitment Supervisor all replacement, rotation, Promotion  and transfers &#92;n-&#92;tParticipate in the employment fairs and handling the CVs collected in order to support the bank database. &#92;n-&#92;tPrepare and send the welcoming e-mails for the new hired employees.&#92;n-&#92;tGive assistance in hiring new branches. &#92;n-&#92;tPresent all needed reports to the direct manager as needed on time with level of accuracy.&#92;n-&#92;tPerform any other assigned tasks as required by the direct manager within the same level of responsibility.&#92;n&quot;,&quot;title&quot;:&quot;Recruitment Specialist&quot;,&quot;region&quot;:&quot;urn:li:fs_region:(eg,0)&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.Position&quot;},{&quot;$deletedFields&quot;:[&quot;courses&quot;,&quot;projects&quot;,&quot;recommendations&quot;,&quot;honors&quot;,&quot;entityLocale&quot;,&quot;organizations&quot;,&quot;region&quot;],&quot;locationName&quot;:&quot;Cairo&quot;,&quot;entityUrn&quot;:&quot;urn:li:fs_position:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,612638849)&quot;,&quot;companyName&quot;:&quot;Al-Watania Poultry (Egypt)&quot;,&quot;timePeriod&quot;:&quot;urn:li:fs_position:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,612638849),timePeriod&quot;,&quot;description&quot;:&quot;•&#92;tResponsible for all costs the company (Poultry Farms).&#92;n•&#92;tMaking and design all finance books by excel sheet to Facilitate the provision of daily reports.&#92;n•&#92;tResponsible for all financial and accounting topics&#92;n•&#92;tRecording daily works (book keeping, banks reconciliation, accounts analysis, accounts payables and receivables, invoicing, etc...)&#92;n•&#92;tReview the monthly trial balance and sending the monthly results to Management.&#92;n•&#92;tPrepare monthly Income Statement.&#92;n•&#92;tPrepare financial management reports&#92;n&quot;,&quot;company&quot;:&quot;urn:li:fs_position:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,612638849),company&quot;,&quot;title&quot;:&quot;Accountant &amp; sales (in door)&quot;,&quot;companyUrn&quot;:&quot;urn:li:fs_miniCompany:980903&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.Position&quot;},{&quot;$deletedFields&quot;:[&quot;courses&quot;,&quot;projects&quot;,&quot;companyUrn&quot;,&quot;recommendations&quot;,&quot;honors&quot;,&quot;entityLocale&quot;,&quot;organizations&quot;,&quot;company&quot;,&quot;region&quot;],&quot;locationName&quot;:&quot;Cairo&quot;,&quot;entityUrn&quot;:&quot;urn:li:fs_position:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,644675701)&quot;,&quot;companyName&quot;:&quot;BROS Restaurant&quot;,&quot;timePeriod&quot;:&quot;urn:li:fs_position:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,644675701),timePeriod&quot;,&quot;description&quot;:&quot;•&#92;tUpdate and maintain employee payroll records&#92;n•&#92;tPrepare and reviewing the monthly attendance sheets for the employees in various departments.&#92;n•&#92;tFollow up and handling the vacation records for the employees and enter all leaves data on system.&#92;n•&#92;tMaintain the personnel filing system&#92;n•&#92;tEnsure all new staff hiring documents are ready prior to joining date.&#92;n•&#92;tRecorded revenue and expense accounts on financial program (Excel program)&#92;n•&#92;tReconcile monthly bank statements with company books.&#92;n•&#92;tRecording daily works (book keeping, banks reconciliation, accounts analysis, accounts payables and receivables, invoicing, etc…)&#92;n•&#92;tReview the monthly trial balance and sending the monthly results to Management.&#92;n•&#92;tPrepare monthly Income Statement.&#92;n•&#92;tPrepare financial management reports&#92;n&quot;,&quot;title&quot;:&quot;Accountant&amp; Personnel&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.Position&quot;},{&quot;$deletedFields&quot;:[&quot;courses&quot;,&quot;projects&quot;,&quot;recommendations&quot;,&quot;honors&quot;,&quot;entityLocale&quot;,&quot;organizations&quot;],&quot;locationName&quot;:&quot;Egypt&quot;,&quot;entityUrn&quot;:&quot;urn:li:fs_position:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,708058012)&quot;,&quot;companyName&quot;:&quot;AWTAD&quot;,&quot;timePeriod&quot;:&quot;urn:li:fs_position:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,708058012),timePeriod&quot;,&quot;description&quot;:&quot;•&#92;tAdministrate recruitment process (White &amp; Blue collars).&#92;n•&#92;tPost vacancy, review and screen CVs, interview applicants, evaluate applicant skills and make recommendations regarding applicant&#39;s qualifications. &#92;n•&#92;tAttend different events and employment Fairs. &#92;n•&#92;tMaintain and keep database of current CVs though employment fairs &#92;n•&#92;tControl Filling System (Hard/Soft copies), and make database for CVs and interviews&#92;n•&#92;tExecute any additional tasks when requested. &#92;n&quot;,&quot;company&quot;:&quot;urn:li:fs_position:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,708058012),company&quot;,&quot;title&quot;:&quot;Recruitment  Specialist&quot;,&quot;region&quot;:&quot;urn:li:fs_region:(eg,0)&quot;,&quot;companyUrn&quot;:&quot;urn:li:fs_miniCompany:9427362&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.Position&quot;},{&quot;employeeCountRange&quot;:&quot;urn:li:fs_position:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,612638849),company,employeeCountRange&quot;,&quot;$deletedFields&quot;:[],&quot;miniCompany&quot;:&quot;urn:li:fs_miniCompany:980903&quot;,&quot;industries&quot;:[&quot;Food Production&quot;],&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.PositionCompany&quot;,&quot;$id&quot;:&quot;urn:li:fs_position:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,612638849),company&quot;},{&quot;employeeCountRange&quot;:&quot;urn:li:fs_position:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,708058012),company,employeeCountRange&quot;,&quot;$deletedFields&quot;:[],&quot;miniCompany&quot;:&quot;urn:li:fs_miniCompany:9427362&quot;,&quot;industries&quot;:[&quot;Civic &amp; Social Organization&quot;],&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.PositionCompany&quot;,&quot;$id&quot;:&quot;urn:li:fs_position:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,708058012),company&quot;},{&quot;$deletedFields&quot;:[],&quot;profileId&quot;:&quot;ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8&quot;,&quot;elements&quot;:[&quot;urn:li:fs_position:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,791486752)&quot;,&quot;urn:li:fs_position:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,708058012)&quot;,&quot;urn:li:fs_position:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,644675701)&quot;,&quot;urn:li:fs_position:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,612638849)&quot;],&quot;paging&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,positionView,paging&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.PositionView&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,positionView&quot;},{&quot;summary&quot;:&quot;Responsible for assisting in the development and implementation of several Human Resources tasks to deliver and meet department’s objectives.&#92;nEnsure technical recruitment requests are executed within reasonable time frame.&#92;nMaintain relationships with hiring managers to stay abreast of current and future hiring and business needs.&#92;nOrganizes internal search and the process of external search.&#92;nAssists in the development of advertisements job descriptions; monitor the quality of the company job advertisements and ensure that they are flawless.&#92;nPosts advertisements online either on the companies’ or recruitment’s websites as well as in the newspapers/magazines and search for relevant and qualified candidates on various job boards (internally and externally on the different regional recruitment websites.)&#92;nActs as liaison with various recruitment sources such as advertisements, universities, recruitment agencies, external job boards, professional bodies, etc.&quot;,&quot;industryName&quot;:&quot;Human Resources&quot;,&quot;lastName&quot;:&quot;Riad&quot;,&quot;supportedLocales&quot;:[&quot;urn:li:fs_profile:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,supportedLocales,3e2dd06a-7889-426c-88d1-9f5f7e5dd557-0&quot;],&quot;locationName&quot;:&quot;Egypt&quot;,&quot;backgroundImage&quot;:&quot;urn:li:fs_profile:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,backgroundImage&quot;,&quot;versionTag&quot;:&quot;1783976268&quot;,&quot;pictureInfo&quot;:&quot;urn:li:fs_profile:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,pictureInfo&quot;,&quot;birthDate&quot;:&quot;urn:li:fs_profile:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,birthDate&quot;,&quot;industryUrn&quot;:&quot;urn:li:fs_industry:137&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.Profile&quot;,&quot;defaultLocale&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,primaryLocale&quot;,&quot;firstName&quot;:&quot;Ahmed&quot;,&quot;$deletedFields&quot;:[&quot;address&quot;,&quot;maidenName&quot;,&quot;phoneticLastName&quot;,&quot;contactInstructions&quot;,&quot;entityLocale&quot;,&quot;state&quot;,&quot;interests&quot;,&quot;phoneticFirstName&quot;],&quot;entityUrn&quot;:&quot;urn:li:fs_profile:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8&quot;,&quot;location&quot;:&quot;urn:li:fs_profile:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,location&quot;,&quot;miniProfile&quot;:&quot;urn:li:fs_miniProfile:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8&quot;,&quot;headline&quot;:&quot;Recruitment  at  confidential &quot;},{&quot;$deletedFields&quot;:[&quot;preferredGeoPlace&quot;],&quot;basicLocation&quot;:&quot;urn:li:fs_profile:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,location,basicLocation&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.ProfileLocation&quot;,&quot;$id&quot;:&quot;urn:li:fs_profile:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,location&quot;},{&quot;$deletedFields&quot;:[],&quot;profileId&quot;:&quot;ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8&quot;,&quot;elements&quot;:[],&quot;paging&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,projectView,paging&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.ProjectView&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,projectView&quot;},{&quot;$deletedFields&quot;:[],&quot;profileId&quot;:&quot;ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8&quot;,&quot;elements&quot;:[],&quot;paging&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,publicationView,paging&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.PublicationView&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,publicationView&quot;},{&quot;$deletedFields&quot;:[&quot;standardizedSkillUrn&quot;,&quot;standardizedSkill&quot;],&quot;entityUrn&quot;:&quot;urn:li:fs_skill:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,14)&quot;,&quot;name&quot;:&quot;Analysis&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.Skill&quot;},{&quot;$deletedFields&quot;:[&quot;standardizedSkillUrn&quot;,&quot;standardizedSkill&quot;],&quot;entityUrn&quot;:&quot;urn:li:fs_skill:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,13)&quot;,&quot;name&quot;:&quot;Management&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.Skill&quot;},{&quot;$deletedFields&quot;:[&quot;standardizedSkillUrn&quot;,&quot;standardizedSkill&quot;],&quot;entityUrn&quot;:&quot;urn:li:fs_skill:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,12)&quot;,&quot;name&quot;:&quot;Time Management&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.Skill&quot;},{&quot;$deletedFields&quot;:[&quot;standardizedSkillUrn&quot;,&quot;standardizedSkill&quot;],&quot;entityUrn&quot;:&quot;urn:li:fs_skill:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,11)&quot;,&quot;name&quot;:&quot;Teamwork&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.Skill&quot;},{&quot;$deletedFields&quot;:[],&quot;profileId&quot;:&quot;ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8&quot;,&quot;elements&quot;:[&quot;urn:li:fs_skill:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,11)&quot;,&quot;urn:li:fs_skill:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,12)&quot;,&quot;urn:li:fs_skill:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,13)&quot;,&quot;urn:li:fs_skill:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,14)&quot;],&quot;paging&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,skillView,paging&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.SkillView&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,skillView&quot;},{&quot;$deletedFields&quot;:[],&quot;profileId&quot;:&quot;ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8&quot;,&quot;elements&quot;:[],&quot;paging&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,testScoreView,paging&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.TestScoreView&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,testScoreView&quot;},{&quot;$deletedFields&quot;:[],&quot;causeType&quot;:&quot;POLITICS&quot;,&quot;causeName&quot;:&quot;Politics&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.VolunteerCause&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,volunteerCauseView,elements,3746c1d8-d7d4-45bf-9be2-bdf5b0b26db3-3&quot;},{&quot;$deletedFields&quot;:[],&quot;causeType&quot;:&quot;ARTS_AND_CULTURE&quot;,&quot;causeName&quot;:&quot;Arts and Culture&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.VolunteerCause&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,volunteerCauseView,elements,3746c1d8-d7d4-45bf-9be2-bdf5b0b26db3-0&quot;},{&quot;$deletedFields&quot;:[],&quot;causeType&quot;:&quot;EDUCATION&quot;,&quot;causeName&quot;:&quot;Education&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.VolunteerCause&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,volunteerCauseView,elements,3746c1d8-d7d4-45bf-9be2-bdf5b0b26db3-2&quot;},{&quot;$deletedFields&quot;:[],&quot;causeType&quot;:&quot;CHILDREN&quot;,&quot;causeName&quot;:&quot;Children&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.VolunteerCause&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,volunteerCauseView,elements,3746c1d8-d7d4-45bf-9be2-bdf5b0b26db3-1&quot;},{&quot;$deletedFields&quot;:[],&quot;profileId&quot;:&quot;ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8&quot;,&quot;elements&quot;:[&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,volunteerCauseView,elements,3746c1d8-d7d4-45bf-9be2-bdf5b0b26db3-0&quot;,&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,volunteerCauseView,elements,3746c1d8-d7d4-45bf-9be2-bdf5b0b26db3-1&quot;,&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,volunteerCauseView,elements,3746c1d8-d7d4-45bf-9be2-bdf5b0b26db3-2&quot;,&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,volunteerCauseView,elements,3746c1d8-d7d4-45bf-9be2-bdf5b0b26db3-3&quot;],&quot;paging&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,volunteerCauseView,paging&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.VolunteerCauseView&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,volunteerCauseView&quot;},{&quot;$deletedFields&quot;:[&quot;cause&quot;,&quot;company&quot;,&quot;companyUrn&quot;],&quot;role&quot;:&quot;Junior Recruiter&quot;,&quot;entityUrn&quot;:&quot;urn:li:fs_volunteerExperience:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,1955669207)&quot;,&quot;companyName&quot;:&quot;Fkra Group for Human Development&quot;,&quot;timePeriod&quot;:&quot;urn:li:fs_volunteerExperience:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,1955669207),timePeriod&quot;,&quot;description&quot;:&quot; Classifying and Filtering CVs as per job criteria&#92;n Planning, organizing, and building up the work breakdown structure&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.VolunteerExperience&quot;},{&quot;$deletedFields&quot;:[],&quot;profileId&quot;:&quot;ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8&quot;,&quot;elements&quot;:[&quot;urn:li:fs_volunteerExperience:(ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,1955669207)&quot;],&quot;paging&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,volunteerExperienceView,paging&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.profile.VolunteerExperienceView&quot;,&quot;$id&quot;:&quot;urn:li:fs_profileView:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,volunteerExperienceView&quot;},{&quot;firstName&quot;:&quot;Ahmed&quot;,&quot;lastName&quot;:&quot;Riad&quot;,&quot;$deletedFields&quot;:[],&quot;occupation&quot;:&quot;Recruitment  at  confidential &quot;,&quot;objectUrn&quot;:&quot;urn:li:member:388678945&quot;,&quot;entityUrn&quot;:&quot;urn:li:fs_miniProfile:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8&quot;,&quot;backgroundImage&quot;:{&quot;com.linkedin.voyager.common.MediaProcessorImage&quot;:&quot;urn:li:fs_miniProfile:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,backgroundImage,com.linkedin.voyager.common.MediaProcessorImage&quot;},&quot;publicIdentifier&quot;:&quot;ahmedriad1&quot;,&quot;picture&quot;:{&quot;com.linkedin.voyager.common.MediaProcessorImage&quot;:&quot;urn:li:fs_miniProfile:ACoAABcqxSEB3ALCy81wIwCQrBixBH_G3XUdrU8,picture,com.linkedin.voyager.common.MediaProcessorImage&quot;},&quot;trackingId&quot;:&quot;qBlwCg9CSBa7qvrDVKSRhg&#61;&#61;&quot;,&quot;$type&quot;:&quot;com.linkedin.voyager.identity.shared.MiniProfile&quot;}]}
</code>
<code style="display: none" id="datalet-bpr-guid-1605827">
  {"request":"/voyager/api/identity/profiles/ahmedriad1/profileView","status":200,"body":"bpr-guid-1605827"}
</code>
<img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" style="display: none" class="datalet-bpr-guid-1605827"><code style="display: none" id="clientPageInstance">}'];

        $moch = $this->getMockBuilder(CURL::class)
                     ->getMock();
        $moch->method('curl_content')
             ->willReturn($content);
        $url = "https://www.linkedin.com/in/JTechAppleTV";
        $profile = new ProfileImage($url, $moch);
        $actual = $profile->getProfileImage();
        $expected = "https://media.licdn.com/mpr/mpr/shrinknp_400_400/AAEAAQAAAAAAAAxxAAAAJGY5MDlhNGMxLTdlYmUtNDhiMy1iMjQyLTI3Y2NhMmJlYjI4MA.jpg";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_myspace_profile_image()
    {
        $content = ['body' => '{<a id="profileImage" href="/ashleyy.benson"><img src="https://a1-images.myspacecdn.com/images04/10/63998fd3c5224b7fa66a522397638f78/300x300.jpg" alt="">
            <div class="connectButton notReversed tooltips" data-id="6239350" data-entity-key="profile_6239350" data-is-connected="false" data-is-reverse-connected="false" data-show-reverse-status="true" data-image-url="https://a1-images.myspacecdn.com/images04/10/63998fd3c5224b7fa66a522397638f78/300x300.jpg" data-title="Ashley Benson" data-area="profiles" data-type="profile" data-popover-initialized="true">
                    <span></span>
                    <span></span>
                </div></a>}
        '];

        $moch = $this->getMockBuilder(CURL::class)
                     ->getMock();
        $moch->method('curl_content')
             ->willReturn($content);
        $url = "https://myspace.com/ashleyy.benson";
        $profile = new ProfileImage($url, $moch);
        $actual = $profile->getProfileImage();
        $expected = "https://a1-images.myspacecdn.com/images04/10/63998fd3c5224b7fa66a522397638f78/300x300.jpg";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_github_profile_image()
    {
        $content = ['body' => '{<a href="https://avatars2.githubusercontent.com/u/772448?v=4&amp;s=400" aria-hidden="true" class="u-photo d-block position-relative" itemprop="image"><img alt="" class="avatar width-full rounded-2" height="230" src="https://avatars0.githubusercontent.com/u/772448?v=4&amp;s=460" width="230"></a>}
        '];

        $moch = $this->getMockBuilder(CURL::class)
                     ->getMock();
        $moch->method('curl_content')
             ->willReturn($content);
        $url = "https://github.com/jorgeas80";
        $profile = new ProfileImage($url, $moch);
        $actual = $profile->getProfileImage();
        $expected = "https://avatars0.githubusercontent.com/u/772448";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_wikipedia_profile_image()
    {
        $content = ['body' => '{<meta name="generator" content="MediaWiki 1.30.0-wmf.15"/>
<meta name="referrer" content="origin-when-cross-origin"/>
<meta property="og:image" content="https://upload.wikimedia.org/wikipedia/commons/6/66/Tom_Hanks_2014.jpg"/>
<link rel="alternate" href="android-app://org.wikipedia/http/en.m.wikipedia.org/wiki/Tom_Hanks"/>
<link rel="apple-touch-icon" href="/static/apple-touch/wikipedia.png"/>
<link rel="shortcut icon" href="/static/favicon/wikipedia.ico"/>}
        '];

        $moch = $this->getMockBuilder(CURL::class)
                     ->getMock();
        $moch->method('curl_content')
             ->willReturn($content);
        $url = "https://en.wikipedia.org/wiki/Tom_Hanks";
        $profile = new ProfileImage($url, $moch);
        $actual = $profile->getProfileImage();
        $expected = "https://upload.wikimedia.org/wikipedia/commons/6/66/Tom_Hanks_2014.jpg";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_angel_profile_image()
    {
        $content = ['body' => '{<div class="s-grid0-colMd20 s-grid--preMd2 subheader-inner-container">
            <div class="g-lockup-subheader"><div class="js-launchLargePhotoModal photo subheader-avatar">
            <img alt="Mohammed Attya" itemprop="image" class="js-avatar-img" src="https://d1qb2nb5cznatu.cloudfront.net/users/6848157-large?1503837772" />
            </div><div class="js-largePhotoModal mfp-hide u-hidden s-vgPad0_5">
            <div class="g-photo_container gigantic">
            <img alt="Mohammed Attya" itemprop="image" class="js-avatar-img" src="https://d1qb2nb5cznatu.cloudfront.net/users/6848157-large?1503837772" />
            </div>
            </div>}
        '];

        $moch = $this->getMockBuilder(CURL::class)
                     ->getMock();
        $moch->method('curl_content')
             ->willReturn($content);
        $url = "https://angel.co/mohammed/";
        $profile = new ProfileImage($url, $moch);
        $actual = $profile->getProfileImage();
        $expected = "https://d1qb2nb5cznatu.cloudfront.net/users/6848157-large?1503837772";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_foursquare_profile_image()
    {
        $content = ['body' => '{<div class="userPic"><img src="https://igx.4sqi.net/img/user/130x130/444280681_5nvzn010_pVtlU2zoFxQJ7N1GZAbSVpzNm_5Inneqi0VinvZEdNtK_fe_EZpLVOSYFrYX-wRT.jpg" alt="Mohammed Attya" class="avatar " width="130" height="130" title="Mohammed Attya" data-retina-url="https://igx.4sqi.net/img/user/260x260/444280681_5nvzn010_pVtlU2zoFxQJ7N1GZAbSVpzNm_5Inneqi0VinvZEdNtK_fe_EZpLVOSYFrYX-wRT.jpg"></div>}
        '];

        $moch = $this->getMockBuilder(CURL::class)
                     ->getMock();
        $moch->method('curl_content')
             ->willReturn($content);
        $url = "https://foursquare.com/user/444280681";
        $profile = new ProfileImage($url, $moch);
        $actual = $profile->getProfileImage();
        $expected = "https://igx.4sqi.net/img/user/130x130/444280681_5nvzn010_pVtlU2zoFxQJ7N1GZAbSVpzNm_5Inneqi0VinvZEdNtK_fe_EZpLVOSYFrYX-wRT.jpg";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_flickr_profile_image()
    {
        $content = ['body' => '{<div class="coverphoto-content fluid-centered" id="yui_3_16_0_1_1503838311705_3064">

            <div class="avatar no-menu person large" style="background-image: url(//c1.staticflickr.com/1/1/buddyicons/35468159988@N01.jpg?1090161226#35468159988@N01);">
                <div class="loading-overlay">
                    <div class="balls"></div>
                </div>
            </div>}
        '];

        $moch = $this->getMockBuilder(CURL::class)
                     ->getMock();
        $moch->method('curl_content')
             ->willReturn($content);
        $url = "https://www.flickr.com/photos/tom";
        $profile = new ProfileImage($url, $moch);
        $actual = $profile->getProfileImage();
        $expected = "c1.staticflickr.com/1/1/buddyicons/35468159988@N01.jpg?1090161226#35468159988@N01";
        $this->assertEquals($expected, $actual);
    }


    /** @test */
    public function should_return_empty_string_as_flickr_profile_image()
    {
        $content = ['body' => '{<div}'];

        $moch = $this->getMockBuilder(CURL::class)
                     ->getMock();
        $moch->method('curl_content')
             ->willReturn($content);
        $url = "https://www.flickr.com/";
        $profile = new ProfileImage($url, $moch);
        $actual = $profile->getProfileImage();
        $expected = "";
        $this->assertEquals($expected, $actual);
    }


    /** @test */
    public function should_return_producthunt_profile_image()
    {
        $content = ['body' => '{<div class="container_c89a5 lazyLoadContainer_b1038" data-reactid="45"><img height="140" src="https://ph-avatars.imgix.net/419060/original?auto=format&amp;auto=compress&amp;codec=mozjpeg&amp;cs=strip&amp;w=140&amp;h=140&amp;fit=crop" srcset="https://ph-avatars.imgix.net/419060/original?auto=format&amp;auto=compress&amp;codec=mozjpeg&amp;cs=strip&amp;w=140&amp;h=140&amp;fit=crop&amp;dpr=2 2x, https://ph-avatars.imgix.net/419060/original?auto=format&amp;auto=compress&amp;codec=mozjpeg&amp;cs=strip&amp;w=140&amp;h=140&amp;fit=crop&amp;dpr=3 3x" width="140"></div>}
        '];

        $moch = $this->getMockBuilder(CURL::class)
                     ->getMock();
        $moch->method('curl_content')
             ->willReturn($content);
        $url = "https://www.producthunt.com/@tom";
        $profile = new ProfileImage($url, $moch);
        $actual = $profile->getProfileImage();
        $expected = "https://ph-avatars.imgix.net/419060/original?auto=format&amp;auto=compress&amp;codec=mozjpeg&amp;cs=strip&amp;w=140&amp;h=140&amp;fit=crop";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_quora_profile_image()
    {
        $content = ['body' => '{<div class="inner">Ask Question</div></a></div></div></div></div></div><div id="tFbHvV"></div><div id="__w2_HVJDCVJ_body_blur"></div><div class="ContentWrapper"><div id="__w2_evu0YNu_content"><div class="UserPage UserMain"><div id="QcEHMZ"></div><div class="grid_page"><div class="profile_wrapper"><div class="header"><div id="WVjudj"><div class="ProfilePhoto"><div id="FWjUvn"><span class="photo_tooltip" id="__w2_rbglv74_link"><img class="profile_photo_img" src="https://qph.ec.quoracdn.net/main-thumb-24947805-200-VzxobHgXKjGD0VTJOX3AvJKmYLm0ABVY.jpeg" alt="Anas Maassarani" height="200" width="200" /><span id="yrHgUf"></span></span></div></div></div><div class="header_content"><div id="QGkJdL"><div class="ProfileNameAndSig"><h1><span id="CYMHKu"><span id="__w2_oFnlCOn_link"><span class="user">Anas Maassarani</span></span></span></h1><span id="LxdLSU"></span></div></div><div id="EstegH"></div><div id="uByzBR">}
        '];

        $moch = $this->getMockBuilder(CURL::class)
                     ->getMock();
        $moch->method('curl_content')
             ->willReturn($content);
        $url = "https://www.quora.com/profile/Anas-Maassarani";
        $profile = new ProfileImage($url, $moch);
        $actual = $profile->getProfileImage();
        $expected = "https://qph.ec.quoracdn.net/main-thumb-24947805-200-VzxobHgXKjGD0VTJOX3AvJKmYLm0ABVY.jpeg";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_empty_string_quora_profile_image()
    {
        $content = ['body' => '{<div}
        '];

        $moch = $this->getMockBuilder(CURL::class)
                     ->getMock();
        $moch->method('curl_content')
             ->willReturn($content);
        $url = "https://www.quora.com/profile";
        $profile = new ProfileImage($url, $moch);
        $actual = $profile->getProfileImage();
        $expected = "";
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function should_return_empty_string_from_error_quora_profile_image()
    {
        $content = ['body' => '{}', 'error_no' => 404];

        $moch = $this->getMockBuilder(CURL::class)
                     ->getMock();
        $moch->method('curl_content')
             ->willReturn($content);
        $url = "https://www.quora.com/profile/AnaMaaarani";
        $profile = new ProfileImage($url, $moch);
        $actual = $profile->getProfileImage();
        $expected = "";
        $this->assertEquals($expected, $actual);
    }


    /** @test */
    public function should_return_f6s_profile_image()
    {
        $content = ['body' => '{<div class="profile-picture rounded">
                <img src="https://s3.amazonaws.com/f6s-public/profiles/792970_th1.jpg">
    </div>}
        '];

        $moch = $this->getMockBuilder(CURL::class)
                     ->getMock();
        $moch->method('curl_content')
             ->willReturn($content);
        $url = "https://www.f6s.com/rob";
        $profile = new ProfileImage($url, $moch);
        $actual = $profile->getProfileImage();
        $expected = "https://s3.amazonaws.com/f6s-public/profiles/792970_th1.jpg";
        $this->assertEquals($expected, $actual);
    }
}
