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
   * 
   * @param sfWebRequest $request
   * @property Test $_page
   */
  public function executeIndex(sfWebRequest $request)
  {
    $table = Doctrine::getTable('rtBlogPage');
    $query = $table->addSiteQuery();
    $query = $table->addPublishedQuery($query);
    $query->orderBy('page.published_from DESC, page.created_at DESC');

    $year  = $request->hasParameter('year') ? $request->getParameter('year') : null;
    $month = $request->hasParameter('month') ? $request->getParameter('month') : null;
    $day   = $request->hasParameter('day') ? $request->getParameter('day') : null;

    if($request->hasParameter('year') && $request->hasParameter('month') && $request->hasParameter('day'))
    {
      $query->andWhere('page.published_from >= ?',sprintf('%s-%s-%s 00:00:00', $year, $month, $day));
      $query->andWhere('page.published_from < ?',sprintf('%s-%s-%s 00:00:00', $year, $month, $day+1));
    }
    elseif($request->hasParameter('year') && $request->hasParameter('month'))
    {
      $query->andWhere('page.published_from >= ?',sprintf('%s-%s-%s 00:00:00', $year, $month, 1));
      $query->andWhere('page.published_from < ?',sprintf('%s-%s-%s 00:00:00', ($month < 12) ? $year : $year+1, ($month < 12) ? $month+1 : 1, 1));
    }
    elseif($request->hasParameter('year'))
    {
      $query->andWhere('page.published_from >= ?',sprintf('%s-%s-%s 00:00:00', $year, 1, 1));
      $query->andWhere('page.published_from < ?',sprintf('%s-%s-%s 00:00:00', $year+1, 1, 1));
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
    
    foreach ($posts as $rt_blog_page)
    {
      $item = new sfFeedItem();
      $item->setTitle($rt_blog_page->getTitle());
      $item->setLink($this->generateUrl('rt_blog_page_show', $rt_blog_page, array('absolute' => true)));
      $item->setAuthorName($rt_blog_page->getAuthorName());
      $item->setAuthorEmail($rt_blog_page->getAuthorEmail());
      $item->setPubdate(strtotime($rt_blog_page->getCreatedAt()));
      $item->setUniqueId($rt_blog_page->getSlug());
      $item->setDescription($rt_blog_page->getDescription());

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