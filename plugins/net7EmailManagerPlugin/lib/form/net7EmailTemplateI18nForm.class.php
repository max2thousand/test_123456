<?php

/**
 * net7EmailTemplate form.
 *
 * @package    net7SymfonyPlugins
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrinePluginFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class net7EmailTemplateI18nForm extends Pluginnet7EmailTemplateForm
{
public function configure() {
  	unset(
  		$this['created_at'],
  		$this['updated_at']
  	);
  	
  	$this->embedI18n(array('en', 'it'));
  	$this->widgetSchema->setLabel('it', 'Italiano');
  	$this->widgetSchema->setLabel('en', 'English');
  	
  }
}
