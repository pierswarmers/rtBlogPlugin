<?php

/**
 * gnBlogPageAdmin actions.
 *
 * @package    symfony
 * @subpackage gnBlogPageAdmin
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class gnBlogPageAdminActions extends sfActions
{
  private $_gn_blog_page;

  public function getGnBlogPage(sfWebRequest $request)
  {
    $this->forward404Unless($gn_blog_page = Doctrine::getTable('gnBlogPage')->find(array($request->getParameter('id'))), sprintf('Object gn_blog_page does not exist (%s).', $request->getParameter('id')));
    return $gn_blog_page;
  }

  public function preExecute()
  {
    gnTemplateToolkit::setTemplateForMode('backend');
  }

  public function executeIndex(sfWebRequest $request)
  {
    $query = Doctrine::getTable('gnBlogPage')->addNotDeletedQuery();
    $query = Doctrine::getTable('gnBlogPage')->addSiteQuery($query);
    $query->orderBy('page.id DESC');

    $this->gn_blog_pages = $query->execute();
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new gnBlogPageForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new gnBlogPageForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $gn_blog_page = $this->getGnBlogPage($request);
    $this->form = new gnBlogPageForm($gn_blog_page);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $gn_blog_page = $this->getGnBlogPage($request);
    $this->form = new gnBlogPageForm($gn_blog_page);

    $this->processForm($request, $this->form);
    $this->clearCache($request);
    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $gn_blog_page = $this->getGnBlogPage($request);
    $gn_blog_page->delete();
    $this->clearCache($request);
    
    $this->redirect('gnBlogPageAdmin/index');
  }

  public function executeUndelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $gn_blog_page = $this->getGnBlogPage($request);
    $gn_blog_page->undelete();
    $this->clearCache($request);

    $this->redirect('gnBlogPageAdmin/index');
  }

  public function executeVersions(sfWebRequest $request)
  {
    $this->gn_blog_page = $this->getGnBlogPage($request);
    $this->gn_blog_page_versions = Doctrine::getTable('gnBlogPageVersion')->findById($this->gn_blog_page->getId());
  }

  public function executeCompare(sfWebRequest $request)
  {
    $this->gn_blog_page = $this->getGnBlogPage($request);
    $this->current_version = $this->gn_blog_page->version;

    if(!$request->hasParameter('version1') || !$request->hasParameter('version2'))
    {
      $this->getUser()->setFlash('error', 'Please select two versions to compare.', false);
      $this->redirect('gnBlogPage/versions?id='.$this->gn_blog_page->getId());
    }

    $this->version_1 = $request->getParameter('version1');
    $this->version_2 = $request->getParameter('version2');
    $this->versions = array();

    $this->versions[1] = array(
      'title' => $this->gn_blog_page->revert($this->version_1)->title,
      'content' => $this->gn_blog_page->revert($this->version_1)->content,
      'description' => $this->gn_blog_page->revert($this->version_1)->description,
      'updated_at' => $this->gn_blog_page->revert($this->version_1)->updated_at
    );
    $this->versions[2] = array(
      'title' => $this->gn_blog_page->revert($this->version_2)->title,
      'content' => $this->gn_blog_page->revert($this->version_2)->content,
      'description' => $this->gn_blog_page->revert($this->version_1)->description,
      'updated_at' => $this->gn_blog_page->revert($this->version_1)->updated_at
    );
  }
  
  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $gn_blog_page = $form->save();
      $this->redirect('gnBlogPageAdmin/index');
    }
  }

  public function executeRevert(sfWebRequest $request)
  {
    $this->gn_blog_page = $this->getGnBlogPage($request);
    $this->gn_blog_page->revert($request->getParameter('revert_to'));
    $this->gn_blog_page->save();
    $this->getUser()->setFlash('notice', 'Reverted to version ' . $request->getParameter('revert_to'), false);
    $this->clearCache($request);
    $this->redirect('gnBlogPageAdmin/edit?id='.$this->gn_blog_page->getId());
  }

  private function clearCache($request)
  {
    $cache = $this->getContext()->getViewCacheManager();
    $gn_blog_page = $this->getGnBlogPage($request);
    if ($cache)
    {
      $cache->remove('gnBlogPage/index?sf_format=*');
      $cache->remove(sprintf('gnBlogPage/show?id=%s&slug=%s', $gn_blog_page->getId(), $gn_blog_page->getSlug()));
      $cache->remove('@sf_cache_partial?module=gnBlogPage&action=_blog_page&sf_cache_key='.$gn_blog_page->getId());
    }
  }
}
