<?php

/**
 * auth actions.
 *
 * @package    game_notifier
 * @subpackage auth
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class authActions extends sfActions
{
	public function executeIndex(sfWebRequest $request) {
		// sicuramente da mettere in un filtro

		$this->logAction('index');

		if (!$this->isFacebookUserLogged() && $this->getUser()->getAttribute('s-from') != 'site'){
			$this->setTemplate("facebookUserNotLogged");
			return;
		}

		if (!$this->getUser()->isAuthenticated()){

			if (!sfConfig::get('app_debug_mode')) {
				if (!$request->hasParameter("signed_request")){
					$this->logError('facebook auth problem');
					$this->redirect("@error");
				}

				$signed_request = $_REQUEST["signed_request"];
				list($encoded_sig, $payload) = explode('.', $signed_request, 2);

				$data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);
			} else {
				$data = array('user_id' => 101010);
			}

			if (!sfConfig::get('app_debug_mode')) {
				$facebookProfile = $this->getFacebookProfileInfo();
			} else {
				$facebookProfile = array('first_name' => 'dsafasdfadsfa', 'last_name' => 'asdfadsfsa', 'email' => 'romeo@romeo.it');
			}

			//$user = Doctrine::getTable('sfGuardUserProfile')->findOneByFacebookUid($data['user_id']);
			$user = Doctrine::getTable('sfGuardUserProfile')->findOneByEmail($facebookProfile['email']);

			if (!$user) {
				$guardUser = new sfGuardUser();
				$guardUser->setUsername($facebookProfile['email']);
				$guardUser->setPassword($data['user_id']);
					
				$guardUser->setEmailAddress($facebookProfile['email']); // $data['user_id'].'@'.$data['user_id'].'.it'
				$guardUser->set('first_name' , $facebookProfile['first_name']);
				$guardUser->set('last_name' , $facebookProfile['last_name']);

				$guardUser->save();
					

				// da settare il nome giusto
				$group = Doctrine::getTable("sfGuardGroup")->findOneBy('name', 'user');
					
				$userGroup = new sfGuardUserGroup();
				$userGroup->set("user_id", $guardUser->id);
				$userGroup->set("group_id", $group->id);
				$userGroup->save();
					
				$user = new sfGuardUserProfile();
				$user->set("user_id", $guardUser->id);
				$user->set("facebook_uid", $data['user_id']);
				$user->set('email' , $facebookProfile['email']);
				$user->set('first_name' , $facebookProfile['first_name']);
				$user->set('last_name' , $facebookProfile['last_name']);

					
				$user->save();
			}

			//if ($this->getUser()->getAttribute('s-from') == 'site')
			//$this->getUser()->setAttribute('s-from', 'app');

			$this->getUser()->signin($user->sfGuardUser);
			$this->getUser()->setAttribute('uriPrefix', $request->getUriPrefix());

			$frontendSettingsFile = sfYaml::load(sfConfig::get('sf_root_dir') . '/apps/frontend/config/settings.yml');
			$culture = $frontendSettingsFile['all']['.settings']['default_culture'];
			$this->getUser()->setCulture($culture);

			$profile = 	$this->getUser()->getGuardUser()->Profile;

			$profile->save();
			//}

				

			$this->redirect("@site_homepage");

		}
	}

	private function getFacebookProfileInfo(){
		$config = array();
		$config['appId'] = sfConfig::get('app_facebook_app_id');
		$config['secret'] = sfConfig::get('app_facebook_app_secret');
		$config['fileUpload'] = false; // optional

		$facebook = new Facebook($config);

		return $facebook->api("/me","GET");

	}

	private function isFacebookUserLogged(){
		try {
			$me = $this->getFacebookProfileInfo();
			if ($me)
			return true;
		} catch (FacebookApiException $e) {
			return false;
		}
		return false;
	}


	private function logAction($action){
		if (sfConfig::get("app_log_enabled")) {
			//$type = $this->getUser()->getAttribute('s-from') != 'site' ? 'facebook' : 'site';
			$log = new ActionLog();
			if ($this->getUser()->getGuardUser()){
				$log->set('user_id', $this->getUser()->getGuardUser()->id);
				$log->set('username', $this->getUser()->getGuardUser()->username);
				$log->set('name', $this->getUser()->getGuardUser()->first_name);
				$log->set('surname', $this->getUser()->getGuardUser()->last_name);
			}
			$log->set('action', $action);
			$log->set('app_type', $type);
			$log->set('browser', $_SERVER['HTTP_USER_AGENT']);
			$log->set('ip', $this->getRealIpAddr());
			$log->set('session_id', session_id());
			$log->save();
		}
	}


	private function logError($error){
		if (sfConfig::get("app_log_enabled")) {
			$type = $this->getUser()->getAttribute('s-from') != 'site' ? 'facebook' : 'site';
			$log = new ActionLog();
				
			if ($this->getUser()->getGuardUser()){
				$log->set('user_id', $this->getUser()->getGuardUser()->id);
				$log->set('username', $this->getUser()->getGuardUser()->username);
				$log->set('name', $this->getUser()->getGuardUser()->first_name);
				$log->set('surname', $this->getUser()->getGuardUser()->last_name);
			}
			$log->set('action', 'error:'.$error);
			$log->set('app_type', $type);
			$log->set('browser', $_SERVER['HTTP_USER_AGENT']);
			$log->set('ip', $this->getRealIpAddr());
			$log->set('session_id', session_id());
			$log->save();
		}
	}

	private function getRealIpAddr(){
		if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
		{
			$ip=$_SERVER['HTTP_CLIENT_IP'];
		}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
		{
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
			$ip=$_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
}
