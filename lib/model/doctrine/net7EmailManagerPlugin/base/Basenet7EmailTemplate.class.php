<?php

/**
 * Basenet7EmailTemplate
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $name
 * @property string $email_subject
 * @property clob $email_body
 * @property string $email_from
 * 
 * @method string            getName()          Returns the current record's "name" value
 * @method string            getEmailSubject()  Returns the current record's "email_subject" value
 * @method clob              getEmailBody()     Returns the current record's "email_body" value
 * @method string            getEmailFrom()     Returns the current record's "email_from" value
 * @method net7EmailTemplate setName()          Sets the current record's "name" value
 * @method net7EmailTemplate setEmailSubject()  Sets the current record's "email_subject" value
 * @method net7EmailTemplate setEmailBody()     Sets the current record's "email_body" value
 * @method net7EmailTemplate setEmailFrom()     Sets the current record's "email_from" value
 * 
 * @package    game_notifier
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Basenet7EmailTemplate extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('net7_email_template');
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'unique' => true,
             'length' => 255,
             ));
        $this->hasColumn('email_subject', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('email_body', 'clob', null, array(
             'type' => 'clob',
             ));
        $this->hasColumn('email_from', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $timestampable0 = new Doctrine_Template_Timestampable();
        $i18n0 = new Doctrine_Template_I18n(array(
             'fields' => 
             array(
              0 => 'email_subject',
              1 => 'email_body',
              2 => 'email_from',
             ),
             ));
        $this->actAs($timestampable0);
        $this->actAs($i18n0);
    }
}