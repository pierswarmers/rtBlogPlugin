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

    if(!is_null($this->gn_blog_page->getDeletedAt()) && !$this->isAdmin())
    {
      $this->forward404();
    }

    $this->updateResponse($this->gn_blog_page);
  }

  private function updateResponse(gnBlogPage $page)
  {
    gnResponseToolkit::setCommonMetasFromPage($page, $this->getUser(), $this->getResponse());
  }

  public function executeRevert(sfWebRequest $request)
  {
    $this->gn_blog_page = $this->getRoute()->getObject();
    $this->gn_blog_page->Translation[$this->getUser()->getCulture()]->revert($request->getParameter('revert_to'));
    $this->gn_blog_page->save();
    $this->getUser()->setFlash('notice', 'Reverted to version ' . $request->getParameter('revert_to'), false);
    $this->clearCache();
    $this->redirect('gn_blog_page_show',$this->gn_blog_page);
  }

  public function executeVersions(sfWebRequest $request)
  {
    $this->gn_blog_page = $this->getRoute()->getObject();
  }

  public function executeCompare(sfWebRequest $request)
  {
    $this->gn_blog_page = $this->getRoute()->getObject();
    $this->current_version = $this->gn_blog_page->Translation[$this->getUser()->getCulture()]->version;

    if(!$request->hasParameter('version1') || !$request->hasParameter('version2'))
    {
      $this->getUser()->setFlash('error', 'Please select two versions to compare.', false);
      $this->redirect('gnBlogPage/versions?id='.$this->gn_blog_page->getId());
    }

    $this->version_1 = $request->getParameter('version1');
    $this->version_2 = $request->getParameter('version2');
    $t = $this->gn_blog_page->Translation[$this->getUser()->getCulture()];
    $this->versions = array();

    $this->versions[1] = array(
      'title' => $t->revert($this->version_1)->title,
      'content' => $t->revert($this->version_1)->content
    );
    $this->versions[2] = array(
      'title' => $t->revert($this->version_2)->title,
      'content' => $t->revert($this->version_2)->content
    );
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