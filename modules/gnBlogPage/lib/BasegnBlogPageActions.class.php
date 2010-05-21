<?php

/*
 * This file is part of the gumnut package.
 * (c) 2009-2010 Piers Warmers <piers@wranglers.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * BasegnBlogPageActions
 *
 * @package    gnBlogPlugin
 * @subpackage modules
 * @author     Piers Warmers <piers@wranglers.com.au>
 */
class BasegnBlogPageActions extends sfActions
{
  private function setPublicTemplate()
  {
    gnTemplateToolkit::setFrontendTemplateDir();
  }

  /**
   * Executes an application defined process prior to execution of this sfAction object.
   *
   * By default, this method is empty.
   */
  public function preExecute()
  {
    sfConfig::set('app_gn_node_title', 'Blog');
  }

  /**
   * Executes the index page.
   * @param sfWebRequest $request
   * @property Test $_page
   */
  public function executeIndex(sfWebRequest $request)
  {
    $this->setPublicTemplate();
    
    $query = Doctrine::getTable('gnBlogPage')->addNotDeletedQuery();
    $query = Doctrine::getTable('gnBlogPage')->addSiteQuery($query);
    $query->orderBy('page.id DESC');
    
    if(!$this->isAdmin())
    {
      $query = Doctrine::getTable('gnBlogPage')->addPublishedQuery($query);
    }
    
    $this->pager = new sfDoctrinePager(
      'gnBlogPage',
      sfConfig::get('app_gn_blog_max_per_page')
    );
    
    $this->pager->setQuery($query);
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();
  }
  
  public function executeShow(sfWebRequest $request)
  {
    $this->setPublicTemplate();
    
    $this->gn_blog_page = $this->getRoute()->getObject();
    $this->forward404Unless($this->gn_blog_page);

    if(!$this->gn_blog_page->isPublished() && !$this->isAdmin())
    {
      $this->forward('gnGuardAuth','secure');
    }

    $this->updateResponse($this->gn_blog_page);
  }

  private function updateResponse(gnBlogPage $page)
  {
    gnResponseToolkit::setCommonMetasFromPage($page, $this->getUser(), $this->getResponse());
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new gnBlogPageForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    //$request->checkCSRFProtection();
    $this->form = new gnBlogPageForm();
    $this->processForm($request, $this->form);
    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $gn_blog_page = $this->getRoute()->getObject();
    $this->form = new gnBlogPageForm($gn_blog_page);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    //$request->checkCSRFProtection();
    $this->form = new gnBlogPageForm($this->getRoute()->getObject());
    $this->processForm($request, $this->form);
    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    //$request->checkCSRFProtection();
    $this->clearCache();
    $this->getRoute()->getObject()->delete();
    $this->redirect('gnBlogPage/index');
  }

  public function executeUndelete(sfWebRequest $request)
  {
    //$request->checkCSRFProtection();
    $this->clearCache();
    $this->getRoute()->getObject()->undelete();
    $this->redirect('gn_blog_page_show', $this->getRoute()->getObject());
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $was_created = $form->getObject()->isNew();
      $form->save();
      $this->clearCache();
      if($was_created)
      {
        $this->getUser()->setFlash('success', 'Page created successfully.');
        $this->redirect('gn_blog_page_edit', $form->getObject());
      }
      $this->redirect('gn_blog_page_show', $form->getObject());
    }
    $this->getUser()->setFlash('error', 'Some errors were found, see below for details.');
  }

  private function clearCache()
  {
    $cache = $this->getContext()->getViewCacheManager();

    if ($cache)
    {
      $cache->remove('gnBlogPage/index?sf_format=*');
      $cache->remove(sprintf('gnBlogPage/show?id=%s&slug=%s', $this->getRoute()->getObject()->getId(), $this->getRoute()->getObject()->getSlug()));
      $cache->remove('@sf_cache_partial?module=gnBlogPage&action=_blog_page&sf_cache_key='.$this->getRoute()->getObject()->getId());
    }
  }
  private function isAdmin()
  {
    return $this->getUser()->hasCredential(sfConfig::get('app_gn_blog_admin_credential', 'admin_blog'));
  }
}