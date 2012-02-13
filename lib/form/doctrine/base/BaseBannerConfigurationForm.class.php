<?php

/**
 * BannerConfiguration form base class.
 *
 * @method BannerConfiguration getObject() Returns the current form's model object
 *
 * @package    game_notifier
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseBannerConfigurationForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'page_routing' => new sfWidgetFormInputText(),
      'banner_text'  => new sfWidgetFormTextarea(),
      'is_default'   => new sfWidgetFormInputCheckbox(),
      'position'     => new sfWidgetFormChoice(array('choices' => array('top' => 'top', 'bottom' => 'bottom', 'left' => 'left', 'right' => 'right'))),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'page_routing' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'banner_text'  => new sfValidatorString(array('required' => false)),
      'is_default'   => new sfValidatorBoolean(array('required' => false)),
      'position'     => new sfValidatorChoice(array('choices' => array(0 => 'top', 1 => 'bottom', 2 => 'left', 3 => 'right'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('banner_configuration[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'BannerConfiguration';
  }

}
