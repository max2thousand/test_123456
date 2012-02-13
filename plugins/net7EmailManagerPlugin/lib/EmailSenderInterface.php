<?php 

interface EmailSenderInterface{

	public function send($name, $dests, $contents, $lang = 'en', $attachments = array(), $from = '');
}