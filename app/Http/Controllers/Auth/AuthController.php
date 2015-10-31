<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
use Facebook\Exceptions\FacebookSDKException;
use App\Models\Access\User;
use Validator;
use Illuminate\Http\Request;

class AuthController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. By default, this controller uses
	| a simple trait to add these behaviors. Why don't you explore it?
	|
	*/

	use AuthenticatesAndRegistersUsers;

	/**
	 * Override default handling of login request by adding captcha check
	 * Handle a login request to the application.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function postLogin(Request $request)
	{
		$this->validate($request, [
			'email' => 'required|email', 'password' => 'required', 'g-recaptcha-response' => 'required|captcha'
		]);

		$credentials = $request->only('email', 'password');

		if ($this->auth->attempt($credentials, $request->has('remember')))
		{
			return redirect()->intended($this->redirectPath());
		}

		return redirect($this->loginPath())
					->withInput($request->only('email', 'remember'))
					->withErrors([
						'email' => $this->getFailedLoginMessage(),
					]);
	}

	/**
	 * Create a new authentication controller instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Guard  $auth
	 * @param  \Illuminate\Contracts\Auth\Registrar  $registrar
	 * @return void
	 */
	public function __construct(Guard $auth, Registrar $registrar)
	{
		$this->auth = $auth;
		$this->registrar = $registrar;

		$this->middleware('guest', ['except' => 'getLogout']);
	}

/*
	public function getTwitter() {
	    // your SIGN IN WITH TWITTER  button should point to this route
	    $sign_in_twitter = TRUE;
	    $force_login = FALSE;
	    $callback_url = 'https://' . $_SERVER['HTTP_HOST'] . '/auth/twittercallback';
	    // Make sure we make this request w/o tokens, overwrite the default values in case of login.
	    Twitter::set_new_config(['token' => '', 'secret' => '']);
	    $token = Twitter::getRequestToken($callback_url);
	    if( isset( $token['oauth_token_secret'] ) ) {
	        $url = Twitter::getAuthorizeURL($token, $sign_in_twitter, $force_login);

	        Session::put('oauth_state', 'start');
	        Session::put('oauth_request_token', $token['oauth_token']);
	        Session::put('oauth_request_token_secret', $token['oauth_token_secret']);

	        return redirect($url);
	    }
	    return redirect('/auth/login');
	}

	public function getTwittercallback() {
	    // You should set this route on your Twitter Application settings as the callback
	    // https://apps.twitter.com/app/YOUR-APP-ID/settings
	    if(Session::has('oauth_request_token')) {
	        $request_token = [
	            'token' => Session::get('oauth_request_token'),
	            'secret' => Session::get('oauth_request_token_secret'),
	        ];

	        Twitter::set_new_config($request_token);

	        $oauth_verifier = FALSE;
	        if(Input::has('oauth_verifier')) {
	            $oauth_verifier = Input::get('oauth_verifier');
	        }

	        // getAccessToken() will reset the token for you
	        $token = Twitter::getAccessToken( $oauth_verifier );
	        if( !isset( $token['oauth_token_secret'] ) ) {
	            return Redirect::to('/')->with('flash_error', 'We could not log you in on Twitter.');
	        }

	        $credentials = Twitter::query('account/verify_credentials');
	        if( is_object( $credentials ) && !isset( $credentials->error ) ) {
			    //$user = User::createOrUpdateGraphNode($credentials, 'twitter');
			    // Log the user into Laravel
			    //\Auth::login($user);

	            return redirect('/')->with('flash_notice', "Congrats! You've successfully signed in!");
	        }
	        return redirect('/')->with('flash_error', 'Crab! Something went wrong while signing you up!');
	    }
	}

*/
	
	public function getFacebook(LaravelFacebookSdk $fb) {
		$login_link = $fb
    	->getRedirectLoginHelper()
    	->getLoginUrl('https://'.$_SERVER["HTTP_HOST"].'/auth/facebookcallback', ['email', 'public_profile', 'publish_actions']);
    	//$ret = array('fb_login_url' => $login_link);
    	//return view('auth.facebook', $ret);
    	return redirect($login_link);
	}

	public function getFacebookcallback(LaravelFacebookSdk $fb) {
		// Obtain an access token.
	    try {
	        $token = $fb->getAccessTokenFromRedirect();
	    } catch (FacebookSDKException $e) {
	        dd($e->getMessage());
	    }

	    // Access token will be null if the user denied the request
	    // or if someone just hit this URL outside of the OAuth flow.
	    if (! $token) {
	        // Get the redirect helper
	        $helper = $fb->getRedirectLoginHelper();

	        if (! $helper->getError()) {
	            abort(403, 'Unauthorized action.');
	        }

	        // User denied the request
	        dd(
	            $helper->getError(),
	            $helper->getErrorCode(),
	            $helper->getErrorReason(),
	            $helper->getErrorDescription()
	        );
	    }

	    if (! $token->isLongLived()) {
	        // OAuth 2.0 client handler
	        $oauth_client = $fb->getOAuth2Client();

	        // Extend the access token.
	        try {
	            $token = $oauth_client->getLongLivedAccessToken($token);
	        } catch (FacebookSDKException $e) {
	            dd($e->getMessage());
	        }
	    }

	    $fb->setDefaultAccessToken($token);

	    // Save for later
	    \Session::put('fb_user_access_token', (string) $token);

	    // Get basic info on the user from Facebook.
	    try {
	        $response = $fb->get('/me?fields=id,name,email');
	    } catch (FacebookSDKException $e) {
	        dd($e->getMessage());
	    }

	    // Convert the response to a `Facebook/GraphNodes/GraphUser` collection
	    $facebook_user = $response->getGraphUser();
	    // Create the user if it does not exist or update the existing entry.
	    // This will only work if you've added the SyncableGraphNodeTrait to your User model.
	    $user = User::createOrUpdateGraphNode($facebook_user);

	    // Log the user into Laravel
	    \Auth::login($user);

	    return redirect('/')->with('message', 'Successfully logged in with Facebook');

	}

}
