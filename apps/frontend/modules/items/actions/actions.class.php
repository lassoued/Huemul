<?php

/**
 * items actions.
 *
 * @package    Huemul
 * @subpackage items
 * @author     Damian Suarez
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class itemsActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->items = Doctrine::getTable('Item')
      ->createQuery('a')
      ->execute();
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new ItemForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new ItemForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($item = Doctrine::getTable('Item')->find(array($request->getParameter('id'))), sprintf('Object item does not exist (%s).', $request->getParameter('id')));
    $this->form = new ItemForm($item);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($item = Doctrine::getTable('Item')->find(array($request->getParameter('id'))), sprintf('Object item does not exist (%s).', $request->getParameter('id')));
    $this->form = new ItemForm($item);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($item = Doctrine::getTable('Item')->find(array($request->getParameter('id'))), sprintf('Object item does not exist (%s).', $request->getParameter('id')));
    $item->delete();

    $this->redirect('items/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $item = $form->save();

      $this->redirect('items/edit?id='.$item->getId());
    }
  }
}
