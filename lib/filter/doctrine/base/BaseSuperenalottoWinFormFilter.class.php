<?php

/**
 * SuperenalottoWin filter form base class.
 *
 * @package    game_notifier
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseSuperenalottoWinFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'numbers_played' => new sfWidgetFormFilterInput(),
      'contest_id'     => new sfWidgetFormFilterInput(),
      'superstar'      => new sfWidgetFormFilterInput(),
      'game_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Game'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'numbers_played' => new sfValidatorPass(array('required' => false)),
      'contest_id'     => new sfValidatorPass(array('required' => false)),
      'superstar'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'game_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Game'), 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('superenalotto_win_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'SuperenalottoWin';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'numbers_played' => 'Text',
      'contest_id'     => 'Text',
      'superstar'      => 'Number',
      'game_id'        => 'ForeignKey',
    );
  }
}
