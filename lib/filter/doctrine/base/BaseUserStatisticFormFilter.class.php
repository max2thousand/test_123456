<?php

/**
 * UserStatistic filter form base class.
 *
 * @package    game_notifier
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseUserStatisticFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'num_bets'     => new sfWidgetFormFilterInput(),
      'num_accesses' => new sfWidgetFormFilterInput(),
      'num_wins'     => new sfWidgetFormFilterInput(),
      'user_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'num_bets'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'num_accesses' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'num_wins'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('User'), 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('user_statistic_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'UserStatistic';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'num_bets'     => 'Number',
      'num_accesses' => 'Number',
      'num_wins'     => 'Number',
      'user_id'      => 'ForeignKey',
    );
  }
}
