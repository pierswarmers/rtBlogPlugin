<?php

/*
 * This file is part of the gumnut package.
 * (c) 2009-2010 Piers Warmers <piers@wranglers.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * BasertBlogPageActions
 *
 * @package    rtBlogPlugin
 * @subpackage modules
 * @author     Piers Warmers <piers@wranglers.com.au>
 */
class BasertBlogPageActions extends sfActions
{
  /**
   * Executes an application defined process prior to execution of this sfAction object.
   *
   * By default, this method is empty.
   */
  public function preExecute()
  {
    sfConfig::set('app_rt_node_title', 'Blog');
    rtTemplateToolkit::setFrontendTemplateDir();
  }

  /**
   * Executes the index page.
   * @param sfWebRequest $request
   * @property Test $_page
   */
  public function executeIndex(sfWebRequest $request)
  {    
    $query = Doctrine::getTable('rtBlogPage')->addSiteQuery();
    $query->orderBy('page.id DESC');
    
    if(!$this->isAdmin())
    {
      $query = Doctrine::getTable('rtBlogPage')->addPublishedQuery($query);
    }
    
    $this->pager = new sfDoctrinePager(
      'rtBlogPage',
      $this->getCountPerPage($request)
    );
    
    $this->pager->setQuery($query);
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();
  }

  private function getCountPerPage(sfWebRequest $request)
  {
    $count = sfConfig::get('app_rt_blog_max_per_page', 20);
    if($request->hasParameter('show_more'))
    {
      $count = sfConfig::get('app_rt_blog_per_page_multiple', 2) * $count;
    }

    return $count;
  }

  public function executeFeed(sfWebRequest $request)
  {
    $format = $request->getParameter('format');

    if($format === 'atom')
    {
      $format = 'atom1';
    }
    elseif($format === 'rss')
    {
      $format = 'rss201';
    }

    $feed = sfFeedPeer::newInstance($format);

    $feed->setTitle(sfConfig::get('app_rt_blog_title', 'Latest News'));
    $feed->setLink('http://'.rtSiteToolkit::getCurrentDomain());
    $feed->setAuthorEmail(sfConfig::get('app_rt_blog_author_email'));
    $feed->setAuthorName(sfConfig::get('app_rt_blog_author_name', 'News Editor'));

//    $feedImage = new sfFeedImage();
//    $feedImage->setFavicon('http://'.rtSiteToolkit::getCurrentDomain().'/favicon.ico');
//    $feed->setImage($feedImage);

    $query = Doctrine::getTable('rtBlogPage')->addPublishedQuery();
    $query->limit(20)
          ->orderBy('page.id DESC');
    $posts = $query->execute();
    
    foreach ($posts as $post)
    {
      $item = new sfFeedItem();
      $item->setTitle($post->getTitle());
      $item->setLink('@rt_blog_page_show?id='.$post->getId().'&slug='.$post->getSlug());
      $item->setAuthorName($post->getAuthorName());
      $item->setAuthorEmail($post->getAuthorEmail());
      $item->setPubdate(strtotime($post->getCreatedAt()));
      $item->setUniqueId($post->getSlug());
      $item->setDescription($post->getDescription());

      $feed->addItem($item);
    }

    $this->feed = $feed;
  }
  
  public function executeShow(sfWebRequest $request)
  {    
    $this->rt_blog_page = $this->getRoute()->getObject();
    $this->forward404Unless($this->rt_blog_page);

    rtSiteToolkit::checkSiteReference($this->rt_blog_page);

    if(!$this->rt_blog_page->isPublished() && !$this->isAdmin())
    {
      $this->forward('rtGuardAuth','secure');
    }

    $this->updateResponse($this->rt_blog_page);
  }

  private function updateResponse(rtBlogPage $page)
  {
    rtResponseToolkit::setCommonMetasFromPage($page, $this->getUser(), $this->getResponse());
  }

  private function isAdmin()
  {
    return $this->getUser()->hasCredential(sfConfig::get('app_rt_blog_admin_credential', 'admin_blog'));
  }
}