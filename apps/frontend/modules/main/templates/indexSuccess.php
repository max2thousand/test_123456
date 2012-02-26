<?php use_helper('jQuery')?>

<script>
//  window.fbAsyncInit = function() {
//    FB.init({
//      appId      : '<?php echo sfConfig::get("app_facebook_app_id")?>', // App ID
//      channelUrl : '//<?php echo sfContext::getInstance()->getRequest()->getUriPrefix()?>/channel.html', // Channel File
//      status     : true, // check login status
//      cookie     : true, // enable cookies to allow the server to access the session
//      xfbml      : true  // parse XFBML
//    });
//
//    // Additional initialization code here
//  };
//
//  // Load the SDK Asynchronously
//  (function(d){
//     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
//     if (d.getElementById(id)) {return;}
//     js = d.createElement('script'); js.id = id; js.async = true;
//     js.src = "//connect.facebook.net/en_US/all.js";
//     ref.parentNode.insertBefore(js, ref);
//   }(document));
</script>


<div>
    <button id="login">Login</button>
    <button id="disconnect">Disconnect</button>
  </div>
  <div id="user-info" style="display: none;"></div>
<div id="fb-root"></div>
  <script src="http://connect.facebook.net/en_US/all.js"></script>
  <script>
    // initialize the library with the API key
    FB.init({ appId  : '192013477566027' });

    // fetch the status on load
    FB.getLoginStatus(handleSessionResponse);

    $('#login').bind('click', function() {
      FB.login(handleSessionResponse);
    });

    $('#disconnect').bind('click', function() {
      FB.api({ method: 'Auth.revokeAuthorization' }, function(response) {
        clearDisplay();
      });
    });

    // no user, clear display
    function clearDisplay() {
      $('#user-info').hide('fast');
    }

    // handle a session response from any of the auth related calls
    function handleSessionResponse(response) {
      // if we dont have a session, just hide the user info
      if (!response.session) {
        clearDisplay();
        return;
      }

      // if we have a session, query for the user's profile picture and name
      FB.api(
        {
          method: 'fql.query',
          query: 'SELECT name, pic FROM profile WHERE id=' + FB.getSession().uid
        },
        function(response) {
          var user = response[0];
          $('#user-info').html('<img src="' + user.pic + '">' + user.name).show('fast');
        }
      );
    }
  </script>


<?php echo javascript_tag('
//	$(document).ready(function(){
//		$("#facebook_login_button").click(function(){
//				FB.login(function(response) {
//	   			// handle the response
//	 			}, {scope: "email,user_likes"});
//		});
//	});
')?>

<div login>
	<input type="button" id="facebook_login_button" value="facebook login"/>	
</div>