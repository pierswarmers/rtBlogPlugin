<?php

/*
 * This file is part of the gumnut package.
 * (c) 2009-2010 Piers Warmers <piers@wranglers.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * BasertBlogPageAdminActions
 *
 * @package    rtBlogPlugin
 * @subpackage modules
 * @author     Piers Warmers <piers@wranglers.com.au>
 */
class BasertBlogPageAdminActions extends sfActions
{
  public function preExecute()
  {
    rtTemplateToolkit::setTemplateForMode('backend');
  }
  
  public function getrtBlogPage(sfWebRequest $request)
  {
    $this->forward404Unless($rt_blog_page = Doctrine::getTable('rtBlogPage')->find(array($request->getParameter('id'))), sprintf('Object rt_blog_page does not exist (%s).', $request->getParameter('id')));
    return $rt_blog_page;
  }

  public function executeIndex(sfWebRequest $request)
  {
    $query = Doctrine::getTable('rtBlogPage')->getQuery();
    $query->orderBy('page.created_at DESC');
    
    $this->pager = new sfDoctrinePager(
      'rtBlogPage',
      sfConfig::get('app_rt_admin_pagination_limit', 50)
    );

    $this->pager->setQuery($query);
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();
  }

  public function executeShow(sfWebRequest $request)
  {
    rtSiteToolkit::siteRedirect($this->getrtBlogPage($request));
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new rtBlogPageForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    $this->form = new rtBlogPageForm();
    $this->processForm($request, $this->form);
    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $rt_blog_page = $this->getrtBlogPage($request);
    $this->form = new rtBlogPageForm($rt_blog_page);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $rt_blog_page = $this->getrtBlogPage($request);
    $this->form = new rtBlogPageForm($rt_blog_page);
    $this->processForm($request, $this->form);
    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    $rt_blog_page = $this->getrtBlogPage($request);
    $rt_blog_page->delete();
    $this->clearCache($rt_blog_page);
    $this->redirect('rtBlogPageAdmin/index');
  }

  public function executeVersions(sfWebRequest $request)
  {
    $this->rt_blog_page = $this->getrtBlogPage($request);
    $this->rt_blog_page_versions = Doctrine::getTable('rtBlogPageVersion')->findById($this->rt_blog_page->getId());
  }

  public function executeCompare(sfWebRequest $request)
  {
    $this->rt_blog_page = $this->getrtBlogPage($request);
    $this->current_version = $this->rt_blog_page->version;

    if(!$request->hasParameter('version1') || !$request->hasParameter('version2'))
    {
      $this->getUser()->setFlash('error', 'Please select two versions to compare.', false);
      $this->redirect('rtBlogPageAdmin/versions?id='.$this->rt_blog_page->getId());
    }

    $this->version_1 = $request->getParameter('version1');
    $this->version_2 = $request->getParameter('version2');
    $this->versions = array();

    $this->versions[1] = array(
      'title' => $this->rt_blog_page->revert($this->version_1)->title,
      'content' => $this->rt_blog_page->revert($this->version_1)->content,
      'description' => $this->rt_blog_page->revert($this->version_1)->description,
      'updated_at' => $this->rt_blog_page->revert($this->version_1)->updated_at
    );
    $this->versions[2] = array(
      'title' => $this->rt_blog_page->revert($this->version_2)->title,
      'content' => $this->rt_blog_page->revert($this->version_2)->content,
      'description' => $this->rt_blog_page->revert($this->version_1)->description,
      'updated_at' => $this->rt_blog_page->revert($this->version_1)->updated_at
    );
  }

  public function executeRevert(sfWebRequest $request)
  {
    $this->rt_blog_page = $this->getrtBlogPage($request);
    $this->rt_blog_page->revert($request->getParameter('revert_to'));
    $this->rt_blog_page->save();
    $this->getUser()->setFlash('notice', 'Reverted to version ' . $request->getParameter('revert_to'), false);
    $this->clearCache($this->rt_blog_page);
    $this->redirect('rtBlogPageAdmin/edit?id='.$this->rt_blog_page->getId());
  }
  
  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $rt_blog_page = $form->save();
      $this->clearCache($rt_blog_page);

      $action = $request->getParameter('rt_post_save_action', 'index');

      if($action == 'edit')
      {
        $this->redirect('rtBlogPageAdmin/edit?id='.$rt_blog_page->getId());
      }elseif($action == 'show')
      {
        rtSiteToolkit::siteRedirect($rt_blog_page);
      }

      $this->redirect('rtBlogPageAdmin/index');
    }

    $this->getUser()->setFlash('default_error', true, false);
  }

  private function clearCache($rt_blog_page = null)
  {
    $cache = $this->getContext()->getViewCacheManager();
    
    if ($cache)
    {
      $cache->remove('rtBlogPage/index'); // index page
      $cache->remove('rtBlogPage/index?page=*'); // index with page
      $cache->remove('rtBlogPage/feed?format=*'); // feed
      $cache->remove('@sf_cache_partial?module=rtBlogPage&action=_latest&sf_cache_key=*');
      
      if($rt_blog_page)
      {
        $cache->remove(sprintf('rtBlogPage/show?id=%s&slug=%s', $rt_blog_page->getId(), $rt_blog_page->getSlug())); // show page
        $cache->remove('@sf_cache_partial?module=rtBlogPage&action=_blog_page&sf_cache_key='.$rt_blog_page->getId()); // show page partial.
      }
    }
  }
}