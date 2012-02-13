<?php

/**
 * BaseSfGuardUserProfile
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $facebook_uid
 * @property string $email
 * @property sfGuardUser $sfGuardUser
 * 
 * @method integer            getUserId()       Returns the current record's "user_id" value
 * @method string             getFirstName()    Returns the current record's "first_name" value
 * @method string             getLastName()     Returns the current record's "last_name" value
 * @method string             getFacebookUid()  Returns the current record's "facebook_uid" value
 * @method string             getEmail()        Returns the current record's "email" value
 * @method sfGuardUser        getSfGuardUser()  Returns the current record's "sfGuardUser" value
 * @method SfGuardUserProfile setUserId()       Sets the current record's "user_id" value
 * @method SfGuardUserProfile setFirstName()    Sets the current record's "first_name" value
 * @method SfGuardUserProfile setLastName()     Sets the current record's "last_name" value
 * @method SfGuardUserProfile setFacebookUid()  Sets the current record's "facebook_uid" value
 * @method SfGuardUserProfile setEmail()        Sets the current record's "email" value
 * @method SfGuardUserProfile setSfGuardUser()  Sets the current record's "sfGuardUser" value
 * 
 * @package    game_notifier
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseSfGuardUserProfile extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('sf_guard_user_profile');
        $this->hasColumn('user_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('first_name', 'string', 30, array(
             'type' => 'string',
             'length' => 30,
             ));
        $this->hasColumn('last_name', 'string', 30, array(
             'type' => 'string',
             'length' => 30,
             ));
        $this->hasColumn('facebook_uid', 'string', 20, array(
             'type' => 'string',
             'length' => 20,
             ));
        $this->hasColumn('email', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));


        $this->index('facebook_uid_index', array(
             'fields' => 
             array(
              0 => 'facebook_uid',
             ),
             'unique' => true,
             ));
        $this->index('email_index', array(
             'fields' => 
             array(
              0 => 'email',
             ),
             'unique' => true,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('sfGuardUser', array(
             'local' => 'user_id',
             'foreign' => 'id',
             'onDelete' => 'cascade'));
    }
}