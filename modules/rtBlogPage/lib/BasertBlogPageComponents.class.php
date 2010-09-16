<?php

/*
 * This file is part of the gumnut package.
 * (c) 2009-2010 Piers Warmers <piers@wranglers.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * BasertBlogPageComponents
 *
 * @package    rtBlogPlugin
 * @subpackage modules
 * @author     Piers Warmers <piers@wranglers.com.au>
 */
class BasertBlogPageComponents extends sfComponents
{
  public function executeLatest(sfWebRequest $request)
  {
    $this->rt_blog_posts = $this->getQuery()->execute();
  }

  public function executeLatestSummary(sfWebRequest $request)
  {
    $this->rt_blog_posts = $this->getQuery()->execute();
  }

  public function executeArchive(sfWebRequest $request)
  {
    $months = 12;
    if($this->getVar('options'))
    {
      $options = $this->getVar('options');
      $months = $options['months'];
    }

    $previous_year  = date('Y',strtotime(sprintf("-%s months",$months)));
    $previous_month = date('m',strtotime(sprintf("-%s months",$months)));
    $current_year  = date('Y');
    $current_month = date('m');
    
    $q = Doctrine::getTable('rtBlogPage')->addSiteQuery()
          ->select('YEAR(page.published_from) as year, MONTH(page.published_from) as month, count(page.published_from) as count')
          ->andWhere('page.published_from >= ?',sprintf('%s-%s-%s 00:00:00', $previous_year, $previous_month, 1))
          ->andWhere('page.published_from <= ?',sprintf('%s-%s-%s 00:00:00', $current_year, $current_month, 1))
          ->groupBy('MONTH(page.published_from)')
          ->orderBy('YEAR(page.published_from) DESC, MONTH(page.published_from) DESC');
    $posts = $q->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
    
    $this->rt_blog_posts = $posts;
  }

  protected function getQuery()
  {
    $query = Doctrine::getTable('rtBlogPage')->addSiteQuery()
             ->orderBy('page.id DESC')
             ->limit(10);

    $query = Doctrine::getTable('rtBlogPage')->addPublishedQuery($query);

    return $query;
  }
}