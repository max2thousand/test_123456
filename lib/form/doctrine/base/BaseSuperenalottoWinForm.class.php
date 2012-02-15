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
      'id'             => new sfWidgetFormInputHidden(),
      'numbers_played' => new sfWidgetFormInputText(),
      'contest_id'     => new sfWidgetFormInputText(),
      'superstar'      => new sfWidgetFormInputText(),
      'game_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Game'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'numbers_played' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'contest_id'     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'superstar'      => new sfValidatorInteger(array('required' => false)),
      'game_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Game'), 'required' => false)),
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

}
