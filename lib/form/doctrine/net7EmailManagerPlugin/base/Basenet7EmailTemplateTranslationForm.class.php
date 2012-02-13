<?php

/**
 * net7EmailTemplateTranslation form base class.
 *
 * @method net7EmailTemplateTranslation getObject() Returns the current form's model object
 *
 * @package    game_notifier
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class Basenet7EmailTemplateTranslationForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'email_subject' => new sfWidgetFormInputText(),
      'email_body'    => new sfWidgetFormTextarea(),
      'email_from'    => new sfWidgetFormInputText(),
      'lang'          => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'email_subject' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'email_body'    => new sfValidatorString(array('required' => false)),
      'email_from'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'lang'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('lang')), 'empty_value' => $this->getObject()->get('lang'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('net7_email_template_translation[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'net7EmailTemplateTranslation';
  }

}
