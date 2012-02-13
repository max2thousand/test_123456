<?php

/**
 * BaseSuperenalottoWin
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $numbers_played
 * @property string $contest_id
 * @property integer $superstar
 * @property integer $game_id
 * @property integer $user_id
 * @property sfGuardUser $User
 * @property Game $Game
 * 
 * @method string           getNumbersPlayed()  Returns the current record's "numbers_played" value
 * @method string           getContestId()      Returns the current record's "contest_id" value
 * @method integer          getSuperstar()      Returns the current record's "superstar" value
 * @method integer          getGameId()         Returns the current record's "game_id" value
 * @method integer          getUserId()         Returns the current record's "user_id" value
 * @method sfGuardUser      getUser()           Returns the current record's "User" value
 * @method Game             getGame()           Returns the current record's "Game" value
 * @method SuperenalottoWin setNumbersPlayed()  Sets the current record's "numbers_played" value
 * @method SuperenalottoWin setContestId()      Sets the current record's "contest_id" value
 * @method SuperenalottoWin setSuperstar()      Sets the current record's "superstar" value
 * @method SuperenalottoWin setGameId()         Sets the current record's "game_id" value
 * @method SuperenalottoWin setUserId()         Sets the current record's "user_id" value
 * @method SuperenalottoWin setUser()           Sets the current record's "User" value
 * @method SuperenalottoWin setGame()           Sets the current record's "Game" value
 * 
 * @package    game_notifier
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseSuperenalottoWin extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('superenalotto_win');
        $this->hasColumn('numbers_played', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('contest_id', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('superstar', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('game_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('user_id', 'integer', null, array(
             'type' => 'integer',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('sfGuardUser as User', array(
             'local' => 'user_id',
             'foreign' => 'id'));

        $this->hasOne('Game', array(
             'local' => 'game_id',
             'foreign' => 'id'));
    }
}