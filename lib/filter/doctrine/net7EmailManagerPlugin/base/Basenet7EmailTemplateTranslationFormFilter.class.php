<?php

/**
 * net7EmailTemplateTranslation filter form base class.
 *
 * @package    game_notifier
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class Basenet7EmailTemplateTranslationFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'email_subject' => new sfWidgetFormFilterInput(),
      'email_body'    => new sfWidgetFormFilterInput(),
      'email_from'    => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'email_subject' => new sfValidatorPass(array('required' => false)),
      'email_body'    => new sfValidatorPass(array('required' => false)),
      'email_from'    => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('net7_email_template_translation_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'net7EmailTemplateTranslation';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'email_subject' => 'Text',
      'email_body'    => 'Text',
      'email_from'    => 'Text',
      'lang'          => 'Text',
    );
  }
}
