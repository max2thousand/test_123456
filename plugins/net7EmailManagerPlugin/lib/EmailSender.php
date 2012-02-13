<?php

class EmailSender implements EmailSenderInterface{

	var $webPrefix = '';

	public function sendFromTask($name, $dests, $contents, $lang = 'en', $attachments = array(), $from = '',$cc=null,$bcc=null){
		$this->send($name, $dests, $contents, $lang = 'en', $attachments = array(), $from = '',$cc=null,$bcc=null);
	}



	/*public function send($name, $dests, $contents, $lang = 'en', $attachments = array(), $from = '', $cc = null, $bcc = null){
		$mailerConf = sfConfig::get('app_mail_manager_mailer');
		if ($mailerConf['use_exim_transport']){
			
		} else	$this->sendSmtpMail($name, $dests, $contents, $attachments, $from, $cc, $bcc);
		
	}*/

	
	public function send($name, $dests, $contents, $lang = 'en', $attachments = array(), $from = '', $cc = null, $bcc = null) {
		require_once sfConfig::get('sf_root_dir').'/lib/vendor/symfony/lib/vendor/swiftmailer/swift_required.php';
	
			$email = Pluginnet7EmailTemplateTable::retrieveByNameAndLang($name, $lang);
	
			$dest_array = array();
			$destList = '';
	
			if (is_array($dests)){
				foreach ($dests as $dest){
					$dest_array[$dest] = '';
					$destList .= $dest.' ';
				}
			}else $dest_array = $dests;
			try {
				$mailerConf = sfConfig::get('app_mail_manager_mailer');
				
				
				$subject = $email->Translation[$lang]['email_subject'];
				$body = $email->Translation[$lang]['email_body'];
				
				if ($from == ''){
					$from = $email->Translation[$lang]['email_from'];
				}
	
				foreach ($contents as $placeHolder => $value){
					$subject = str_replace('%%'.$placeHolder.'%%', $value, $subject);
					$body = str_replace('%%'.$placeHolder.'%%', $value, $body);
				}
				
				if ($mailerConf['use_exim_transport'])
					$mailer = Swift_Mailer::newInstance(Swift_MailTransport::newInstance());
				else $mailer = self::getSwiftMailer($mailerConf);
					
				$message = Swift_Message::newInstance()
				->setSubject($subject)
				->setFrom(array($from => $mailerConf['from_name']))
				->setTo($dest_array)
				->setReturnPath($from)
				->setBody($body, 'text/html');
				
				if (isset($cc) && is_array($cc))
					$message->setCc($cc);
	
				foreach ( $attachments as $filepath ){
					$attachment = Swift_Attachment::fromPath($filepath, mime_content_type($filepath));
					$message->attach($attachment);
				}
	
				// adding from header
				$message->getHeaders()->addTextHeader('From', $from);
				
				$mailer->send($message);
	
	
			} catch (Exception $e) {
				print_r($e);
				die;
				//TODO
				// scrivo nel file di log ... impossibile inviare la mail
			}	
	}
	
	
	public static function getSwiftMailer($mailerConf){
		$transport = Swift_SmtpTransport::newInstance($mailerConf['host'],$mailerConf['port'] , $mailerConf['encryption'])
		->setUsername($mailerConf['username'])
		->setPassword($mailerConf['password']);

		$mailer = Swift_Mailer::newInstance($transport);

		return $mailer;
	}

}
