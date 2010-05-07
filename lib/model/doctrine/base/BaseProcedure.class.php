<?php

/**
 * BaseProcedure
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $cadastral_data_id
 * @property integer $formu_id
 * @property string $dossier
 * @property boolean $is_finished
 * @property integer $revisions_count
 * @property Formu $Formu
 * @property CadastralData $CadastralData
 * @property Doctrine_Collection $Users
 * @property Doctrine_Collection $UserProcedure
 * @property Doctrine_Collection $Revisions
 * 
 * @method integer             getId()                Returns the current record's "id" value
 * @method integer             getCadastralDataId()   Returns the current record's "cadastral_data_id" value
 * @method integer             getFormuId()           Returns the current record's "formu_id" value
 * @method string              getDossier()           Returns the current record's "dossier" value
 * @method boolean             getIsFinished()        Returns the current record's "is_finished" value
 * @method integer             getRevisionsCount()    Returns the current record's "revisions_count" value
 * @method Formu               getFormu()             Returns the current record's "Formu" value
 * @method CadastralData       getCadastralData()     Returns the current record's "CadastralData" value
 * @method Doctrine_Collection getUsers()             Returns the current record's "Users" collection
 * @method Doctrine_Collection getUserProcedure()     Returns the current record's "UserProcedure" collection
 * @method Doctrine_Collection getRevisions()         Returns the current record's "Revisions" collection
 * @method Procedure           setId()                Sets the current record's "id" value
 * @method Procedure           setCadastralDataId()   Sets the current record's "cadastral_data_id" value
 * @method Procedure           setFormuId()           Sets the current record's "formu_id" value
 * @method Procedure           setDossier()           Sets the current record's "dossier" value
 * @method Procedure           setIsFinished()        Sets the current record's "is_finished" value
 * @method Procedure           setRevisionsCount()    Sets the current record's "revisions_count" value
 * @method Procedure           setFormu()             Sets the current record's "Formu" value
 * @method Procedure           setCadastralData()     Sets the current record's "CadastralData" value
 * @method Procedure           setUsers()             Sets the current record's "Users" collection
 * @method Procedure           setUserProcedure()     Sets the current record's "UserProcedure" collection
 * @method Procedure           setRevisions()         Sets the current record's "Revisions" collection
 * 
 * @package    Huemul
 * @subpackage model
 * @author     Damian Suarez
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseProcedure extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('_procedure');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('cadastral_data_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('formu_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('dossier', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('is_finished', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));
        $this->hasColumn('revisions_count', 'integer', null, array(
             'type' => 'integer',
             ));


        $this->setAttribute(Doctrine_Core::ATTR_EXPORT, Doctrine_Core::EXPORT_ALL);

        $this->option('collate', 'utf8_unicode_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Formu', array(
             'local' => 'formu_id',
             'foreign' => 'id'));

        $this->hasOne('CadastralData', array(
             'local' => 'cadastral_data_id',
             'foreign' => 'id'));

        $this->hasMany('sfGuardUser as Users', array(
             'refClass' => 'UserProcedure',
             'local' => 'procedure_id',
             'foreign' => 'user_id'));

        $this->hasMany('UserProcedure', array(
             'local' => 'id',
             'foreign' => 'procedure_id'));

        $this->hasMany('Revision as Revisions', array(
             'local' => 'id',
             'foreign' => 'procedure_id'));

        $timestampable0 = new Doctrine_Template_Timestampable(array(
             ));
        $this->actAs($timestampable0);
    }
}