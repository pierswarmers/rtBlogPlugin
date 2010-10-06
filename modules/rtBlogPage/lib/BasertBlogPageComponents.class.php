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
    $table = Doctrine::getTable('rtBlogPage');

    $q = $table->addPublishedQuery();
    $q = $table->addSiteQuery($q);
    $q->select('YEAR(page.published_from) as year, MONTH(page.published_from) as month, count(page.published_from) as count, page.site_id')
      ->groupBy('year, month')
      ->orderBy('year DESC, month DESC');

    $this->rt_blog_posts = $q->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
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