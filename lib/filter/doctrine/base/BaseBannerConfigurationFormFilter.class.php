<?php

/**
 * BannerConfiguration filter form base class.
 *
 * @package    game_notifier
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseBannerConfigurationFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'page_routing' => new sfWidgetFormFilterInput(),
      'banner_text'  => new sfWidgetFormFilterInput(),
      'is_default'   => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'position'     => new sfWidgetFormChoice(array('choices' => array('' => '', 'top' => 'top', 'bottom' => 'bottom', 'left' => 'left', 'right' => 'right'))),
    ));

    $this->setValidators(array(
      'page_routing' => new sfValidatorPass(array('required' => false)),
      'banner_text'  => new sfValidatorPass(array('required' => false)),
      'is_default'   => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'position'     => new sfValidatorChoice(array('required' => false, 'choices' => array('top' => 'top', 'bottom' => 'bottom', 'left' => 'left', 'right' => 'right'))),
    ));

    $this->widgetSchema->setNameFormat('banner_configuration_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'BannerConfiguration';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'page_routing' => 'Text',
      'banner_text'  => 'Text',
      'is_default'   => 'Boolean',
      'position'     => 'Enum',
    );
  }
}
