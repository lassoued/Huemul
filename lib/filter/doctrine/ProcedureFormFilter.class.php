<?php

/**
 * Procedure filter form.
 *
 * @package    Huemul
 * @subpackage filter
 * @author     Damian Suarez
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ProcedureFormFilter extends BaseProcedureFormFilter
{
  public function configure()
  {
    $this->disableLocalCSRFProtection();

    $this->setWidget('creator', new sfWidgetFormFilterInput());
    $this->setValidator('creator', new sfValidatorPass(array('required' => false)));

    $this->setWidget('pendientes', new sfWidgetFormInputHidden());
    $this->setValidator('pendientes', new sfValidatorString(array('required' => false)));
    
    $this->setWidget('autorizar', new sfWidgetFormInputHidden());
    $this->setValidator('autorizar', new sfValidatorString(array('required' => false)));


    $this->setWidget('state', new sfWidgetFormDoctrineChoice(array('model' => 'RevisionState', 'add_empty' => true)));
    $this->setValidator('state', new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'RevisionState', 'column' => 'id')));
    
    $this->setWidget('created_at', new sfWidgetFormFilterDate(array(
            'from_date' => new sfWidgetFormDate(array( 'format'=> '%day%/%month%/%year%')),
            'to_date' => new sfWidgetFormDate(array( 'format'=> '%day%/%month%/%year%')),
            'with_empty' => false,
            'template' => 'desde %from_date%<br />hasta %to_date%')));

    $this->setWidget('updated_at', new sfWidgetFormFilterDate(array(
            'from_date' => new sfWidgetFormDate(array( 'format'=> '%day%/%month%/%year%')),
            'to_date' => new sfWidgetFormDate(array( 'format'=> '%day%/%month%/%year%')),
            'with_empty' => false,
            'template' => 'desde %from_date%<br />hasta %to_date%')));
  }

   public function getFields()
  {
    $fields['creator'] = 'custom';
    $fields['state'] = 'custom';
    $fields['pendientes'] = 'custom';
    $fields['autorizar'] = 'custom';
    $fields = parent::getFields();
    return $fields;
  }
 
  public function addCreatorColumnQuery($query, $field, $value)
  {
    $text = $value['text'];
    $sql ="";
    if($text){
      $texts = explode(' ', $text);
      foreach ($texts as $t){
       $sql = "";
      }
      $query->leftJoin($query->getRootAlias().'.Users u')->leftJoin('u.profile p ON u.id = p.sf_guard_user_id')->andWhere('(
         p.first_name LIKE ?
      OR p.last_name LIKE ?
      OR u.username LIKE ?
      OR CONCAT(p.first_name, " ", p.last_name) LIKE ?
      )', array("%$text%", "%$text%", "%$text%", "%$text%"));

    }
    return $query;
  }

  public function addStateColumnQuery($query, $field, $value)
  {
    //SELECT * FROM _procedure p JOIN (SELECT max(number), revision_state_id, procedure_id FROM revision) j ON j.procedure_id = p.id where j.revision_state_id=1
    $text = $value['text'];
    if($text)
       $query->leftJoin($query->getRootAlias().'.Revisions rv')->andWhere('(
         rv.revision_state_id LIKE ?
         AND rv.id = (SELECT MAX(rv2.id) FROM revision rv2 WHERE rv2.procedure_id = '.$query->getRootAlias().'.id )
      )', array("%$text%"));
    return $query;
  }

public function addPendientesColumnQuery($query, $field, $value)
  {
    $single = sfContext::getInstance();
    $user = $single->getUser()->getGroups();
    $sql = "(";
    $sql2 = "(";

    foreach ($user as $u) {
      $sql .= ' i.group_id="'.$u->getId().'" OR ';
      $sql2 .= ' i2.group_id="'.$u->getId().'" OR ';
    }

    $sql = rtrim($sql, ' OR ');
    $sql .= ")";
    $sql2 = rtrim($sql2, ' OR ');
    $sql2 .= ")";


    $text = $value['text'];
    if($text){

    $query->leftJoin($query->getRootAlias().'.Revisions rv')
            ->andWhere('(rv.revision_state_id <> ?
         AND rv.id = (SELECT MAX(rv3.id) FROM revision rv3 WHERE rv3.procedure_id = '.$query->getRootAlias().'.id )
         )', array(4))
            ->andWhere( 'EXISTS (SELECT r1.id FROM revision r1 JOIN r1.RevisionItem ri1 JOIN ri1.Item i
            WHERE i.title="Cierre parcial"
            AND '.$sql.'
            AND r1.procedure_id='.$query->getRootAlias().'.id
            AND r1.id = (SELECT MAX(r2.id) FROM revision r2 INNER JOIN r2.RevisionItem
            WHERE r2.procedure_id = '.$query->getRootAlias().'.id)
            AND ri1.state IN ("error", "sc"))');
           // ->andWhere($sql);
                    /*'rv.id=(SELECT MAX(rv2.id) FROM revision rv2 JOIN rv2.RevisionItem ri2 JOIN ri2.Item i2
              WHERE rv2.procedure_id = '.$query->getRootAlias().'.id
              AND i2.title="Cierre Parcial" AND ('.$sql2.') AND (ri2.state="error" OR ri2.state="sc"))')
            /*->andWhere($sql)
            ->andWhere('ri.state="error" OR ri.state="sc"')
            ->andWhere('i.title="Cierre Parcial"')*/
    }
    return $query;
  }

  public function addAutorizarColumnQuery($query, $field, $value)
  {
    $text = $value['text'];
    if($text){


    $query->leftJoin($query->getRootAlias().'.Revisions rv')
            ->leftJoin('rv.RevisionItem ri')
            ->leftJoin('ri.Item i')
            ->andWhere('(rv.revision_state_id = ?
         AND rv.id = (SELECT MAX(rv3.id) FROM revision rv3 WHERE rv3.procedure_id = '.$query->getRootAlias().'.id )
         )', array(7))
            ->andWhere('i.title="Cierre Parcial"')
            ->andWhere('NOT EXISTS (
              SELECT rv2.id FROM revision rv2 JOIN rv2.RevisionItem ri2 JOIN ri2.Item i2
              WHERE i2.title="Cierre parcial"
              AND ri2.revision_id= rv.id
              AND ri2.state IN ("error", "sc")
                           )');


    }
    return $query;
  }

}