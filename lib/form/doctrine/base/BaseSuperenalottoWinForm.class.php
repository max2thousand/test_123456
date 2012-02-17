<?php

/**
 * SuperenalottoWin form base class.
 *
 * @method SuperenalottoWin getObject() Returns the current form's model object
 *
 * @package    game_notifier
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseSuperenalottoWinForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'numbers_extracted'  => new sfWidgetFormInputText(),
      'contest_id'         => new sfWidgetFormInputText(),
      'superstar'          => new sfWidgetFormInputText(),
      'game_id'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Game'), 'add_empty' => true)),
      'created_at'         => new sfWidgetFormDateTime(),
      'updated_at'         => new sfWidgetFormDateTime(),
      'winning_users_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser')),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'numbers_extracted'  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'contest_id'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'superstar'          => new sfValidatorInteger(array('required' => false)),
      'game_id'            => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Game'), 'required' => false)),
      'created_at'         => new sfValidatorDateTime(),
      'updated_at'         => new sfValidatorDateTime(),
      'winning_users_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('superenalotto_win[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'SuperenalottoWin';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['winning_users_list']))
    {
      $this->setDefault('winning_users_list', $this->object->WinningUsers->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveWinningUsersList($con);

    parent::doSave($con);
  }

  public function saveWinningUsersList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['winning_users_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->WinningUsers->getPrimaryKeys();
    $values = $this->getValue('winning_users_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('WinningUsers', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('WinningUsers', array_values($link));
    }
  }

}
