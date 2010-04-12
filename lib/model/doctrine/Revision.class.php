<?php

/**
 * Revision
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    Huemul
 * @subpackage model
 * @author     Damian Suarez
 * @version    SVN: $Id: Builder.php 7200 2010-02-21 09:37:37Z beberlei $
 */
class Revision extends BaseRevision
{
  public function setFilename() {
    if (!is_null($this->getFile())) {
      $ext = explode('.', $this->getFile());
      $ext = $ext[1];
      $this->setFile($this->getNumber().'.'.$this->getProcedureId().'.'.$ext);
    }
  }

  public function getFilename() {
    if (!is_null($this->getFile())) {

    }
  }

  public function getPrevious() {
    $q = Doctrine_Query::create()
      ->from('Revision r')
      ->where('r.procedure_id = ?', $this->get('procedure_id'))
      ->orderBy('r.number Desc');

    return $q->fetchOne();
  }

  /*
   * El numero de revision siempre se incrementa automaticamente.
   */
  public function save(Doctrine_Connection $conn = null) {
    if ($this->isNew()) {
      $singleton = sfContext::getInstance();
      $this->setCreatorId($singleton->getUser()->getGuardUser()->getId());

      $previous_rev = $this->getPrevious();
      if($previous_rev) {

        $this->setNumber($previous_rev->getNumber() + 1);
        
        $state = $previous_rev->getRevisionStateId();

        // seteamos el estado de la revision actual en funcion del estado de la revision anterior
        // if($state == 1) $this->setRevisionStateId(5);
        // elseif($state == 7) $this->setRevisionStateId(5);

        // block previous revision
        $previous_rev->setBlock(true);
        $previous_rev->save();
      }

      // revisions count
      $procedure = $this->getProcedure();
      $procedure->setRevisionsCount($procedure->getRevisions()->count() + 1);
      $procedure->save();
    }

    parent::save($conn);
  }

  public function delete(Doctrine_Connection $conn = null) {
    parent::delete($conn);
    $procedure = $this->getProcedure();
    $procedure->setRevisionsCount($procedure->getRevisions()->count());
    $procedure->save();
  }
}
