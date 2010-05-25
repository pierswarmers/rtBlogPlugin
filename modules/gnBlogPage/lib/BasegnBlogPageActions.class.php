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
  /**
   * Executes an application defined process prior to execution of this sfAction object.
   *
   * By default, this method is empty.
   */
  public function preExecute()
  {
    sfConfig::set('app_gn_node_title', 'Blog');
    gnTemplateToolkit::setFrontendTemplateDir();
  }

  /**
   * Executes the index page.
   * @param sfWebRequest $request
   * @property Test $_page
   */
  public function executeIndex(sfWebRequest $request)
  {    
    $query = Doctrine::getTable('gnBlogPage')->addSiteQuery();
    $query->orderBy('page.id DESC');
    
    if(!$this->isAdmin())
    {
      $query = Doctrine::getTable('gnBlogPage')->addPublishedQuery($query);
    }
    
    $this->pager = new sfDoctrinePager(
      'gnBlogPage',
      sfConfig::get('app_gn_blog_max_per_page', 10)
    );
    
    $this->pager->setQuery($query);
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();
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

    $feed->setTitle(sfConfig::get('app_gn_blog_title', 'Latest News'));
    $feed->setLink('http://'.gnSiteToolkit::getCurrentDomain());
    $feed->setAuthorEmail(sfConfig::get('app_gn_blog_author_email'));
    $feed->setAuthorName(sfConfig::get('app_gn_blog_author_name', 'News Editor'));

//    $feedImage = new sfFeedImage();
//    $feedImage->setFavicon('http://'.gnSiteToolkit::getCurrentDomain().'/favicon.ico');
//    $feed->setImage($feedImage);

    $query = Doctrine::getTable('gnBlogPage')->addPublishedQuery();
    $query->limit(20)
          ->orderBy('page.id DESC');
    $posts = $query->execute();
    
    foreach ($posts as $post)
    {
      $item = new sfFeedItem();
      $item->setTitle($post->getTitle());
      $item->setLink('@gn_blog_page_show?id='.$post->getId().'&slug='.$post->getSlug());
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

  private function isAdmin()
  {
    return $this->getUser()->hasCredential(sfConfig::get('app_gn_blog_admin_credential', 'admin_blog'));
  }
}