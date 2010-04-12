<?php

require_once dirname(__FILE__).'/../lib/revisionsGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/revisionsGeneratorHelper.class.php';

/**
 * revisions actions.
 *
 * @package    Huemul
 * @subpackage revisions
 * @author     Damian Suarez
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class revisionsActions extends autoRevisionsActions {
  public function executeListBackToProcedures(sfWebRequest $request) {
    return $this->redirect('procedures/index');
  }


  public function executeCreateControlRevision(sfWebRequest $request) {

    $revision = Doctrine::getTable('Revision')->find($request->getParameter('revision_id'));
    $procedure = $revision->getProcedure();

    
    $new_revision = $procedure->addControlRevision($revision->get('id'));

    return $this->redirect('revisions/control?id='.$new_revision->get('id'));
  }

  /**
   * action Control
   *
   * @author Damian Suarez
   */
  public function executeControl(sfWebRequest $request) {
    $this->revision = Doctrine::getTable('Revision')->find($request->getParameter('id'));
    $this->rev_items = $this->revision->getRevisionItem();

    if($request->getMethod() == 'POST') {
      $params = $request->getParameter('items');

      if(!empty($params)) {
        foreach ($params as $key => $value) {

          if($value != 'nc') {
            $rev_item = RevisionItemTable::retrieveByRevisionAndItem($this->revision->get('id'), $key);
            $rev_item->setState($value);
            $rev_item->save();
          }
        }
      }
    }

    $this->rev_itemsGroup = array ();
    foreach ($this->rev_items as $rev_item) {
      if(!array_key_exists($rev_item->getItem()->getGroup()->getId(), $this->rev_itemsGroup)) $this->rev_itemsGroup[$rev_item->getItem()->getGroup()->getId()] = array();
      array_push($this->rev_itemsGroup[$rev_item->getItem()->getGroup()->getId()], $rev_item);
    }

  }

  /**
   * action Close
   *
   * @author Damian Suarez
   */
  public function executeClose(sfWebRequest $request) {
    $this->revision = Doctrine::getTable('Revision')->find($request->getParameter('id'));
    $this->revision->setRevisionStateId(7);
    $this->revision->setBlock(true);
    $this->revision->save();

    return $this->redirect('revisions/control?id='.$this->revision->get('id'));
  }

/**
   * action Item
   *
   * @author Damian Suarez
   */
  public function executeItem(sfWebRequest $request) {
    $this->revItem = Doctrine::getTable('RevisionItem')->find($request->getParameter('id'));

    $msg = new ComunicationItem();
    $msg->setRevisionItemId($this->revItem->get('id'));

    $this->form = new ComunicationItemForm($msg);

  }

  public function executeCommentCreate(sfWebRequest $request) {
    $params = $request->getParameter('comunication_item');

    $this->forward404Unless($request->isMethod(sfRequest::POST));
    $this->revItem = Doctrine::getTable('RevisionItem')->find($params['revision_item_id']);

    $this->form = new ComunicationItemForm();

    $this->processCommentForm($request, $this->form);

    $this->setTemplate('item');
  }

  protected function processCommentForm(sfWebRequest $request, sfForm $form) {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid()) {
      $msg = $form->save();

      $this->redirect('revisions/item?id='.$msg->getRevisionItemId());
    }
  }

  /**
   * action Observe
   *
   * @author Damian Suarez
   */
  public function executeObserve(sfWebRequest $request) {

  }
}