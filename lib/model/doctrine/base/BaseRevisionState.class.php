<?php

/**
 * BaseRevisionState
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property Doctrine_Collection $Revision
 * 
 * @method integer             getId()          Returns the current record's "id" value
 * @method string              getTitle()       Returns the current record's "title" value
 * @method string              getDescription() Returns the current record's "description" value
 * @method Doctrine_Collection getRevision()    Returns the current record's "Revision" collection
 * @method RevisionState       setId()          Sets the current record's "id" value
 * @method RevisionState       setTitle()       Sets the current record's "title" value
 * @method RevisionState       setDescription() Sets the current record's "description" value
 * @method RevisionState       setRevision()    Sets the current record's "Revision" collection
 * 
 * @package    Huemul
 * @subpackage model
 * @author     Damian Suarez
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseRevisionState extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('revision_state');
        $this->hasColumn('id', 'integer', 2, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => 2,
             ));
        $this->hasColumn('title', 'string', 50, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 50,
             ));
        $this->hasColumn('description', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));


        $this->setAttribute(Doctrine_Core::ATTR_EXPORT, Doctrine_Core::EXPORT_ALL);

        $this->option('collate', 'utf8_unicode_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Revision', array(
             'local' => 'id',
             'foreign' => 'revision_state_id'));

        $timestampable0 = new Doctrine_Template_Timestampable(array(
             ));
        $this->actAs($timestampable0);
    }
}