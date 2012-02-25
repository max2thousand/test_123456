<?php

require_once dirname(__FILE__).'/../lib/net7_email_templateGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/net7_email_templateGeneratorHelper.class.php';

/**
 * net7_email_template actions.
 *
 * @package    net7SymfonyPlugins
 * @subpackage net7_email_template
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class net7_email_templateActions extends autoNet7_email_templateActions
{
	public function executeSendTestEmail(sfWebRequest $request ){
		$email = $request->getParameter('personal_email');
		$emailTemplate = Doctrine::getTable('net7EmailTemplate')->find($request->getParameter('id'));

		$placeholders = $this->getPlaceHolders($emailTemplate);
		$contents = array();
		
		for ($i = 0 ; $i < count($placeholders) ; $i++) $contents[str_replace('%', '',$placeholders[$i])] = 'TEST_MAIL_CONTENT_'.$i;
		$mailSender = new EmailSender();
		$mailSender->send($emailTemplate->name, $email, $contents, 'it');
		
//		$mailerConf = sfConfig::get('app_mail_manager_mailer');
//		
//		$mailer = EmailSender::getSwiftMailer($mailerConf);
//
//		$message = Swift_Message::newInstance()
//		->setSubject($mailerConf['registration_mail']['subject'])
//		->setFrom(array($mailerConf['from_address'] => $mailerConf['from_name']))
//		->setTo($email)
//		->setReturnPath($mailerConf['from_address'])
//		->setBody("klròemtlkermlkermtylkretmylkrtmelykertmylkertmyklem");
//		
//		$mailer->send($message);
		
		
		$this->getUser()->setFlash("notice", "Mail sent correctly");
		$this->redirect($request->getReferer());
	}
	
	private function getPlaceholders($email, $lang = 'it'){
		$subject = $email->Translation[$lang]->email_subject;
		$body = $email->Translation[$lang]->email_body;
		
		preg_match_all('/%%.+%%/U', $body, $matches);
		preg_match_all('/%%.+%%/U', $subject, $matches2);
		
		return array_merge($matches[0], $matches2[0]);
	}
	
	
}
