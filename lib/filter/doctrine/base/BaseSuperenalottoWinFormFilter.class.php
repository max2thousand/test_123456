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
      'numbers_extracted'  => new sfWidgetFormFilterInput(),
      'contest_id'         => new sfWidgetFormFilterInput(),
      'superstar'          => new sfWidgetFormFilterInput(),
      'game_id'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Game'), 'add_empty' => true)),
      'created_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'winning_users_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser')),
    ));

    $this->setValidators(array(
      'numbers_extracted'  => new sfValidatorPass(array('required' => false)),
      'contest_id'         => new sfValidatorPass(array('required' => false)),
      'superstar'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'game_id'            => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Game'), 'column' => 'id')),
      'created_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'winning_users_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('superenalotto_win_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addWinningUsersListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query
      ->leftJoin($query->getRootAlias().'.SuperenalottoWinUser SuperenalottoWinUser')
      ->andWhereIn('SuperenalottoWinUser.user_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'SuperenalottoWin';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'numbers_extracted'  => 'Text',
      'contest_id'         => 'Text',
      'superstar'          => 'Number',
      'game_id'            => 'ForeignKey',
      'created_at'         => 'Date',
      'updated_at'         => 'Date',
      'winning_users_list' => 'ManyKey',
    );
  }
}
