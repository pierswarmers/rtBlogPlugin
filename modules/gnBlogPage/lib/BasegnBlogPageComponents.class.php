<?php

/*
 * This file is part of the gumnut package.
 * (c) 2009-2010 Piers Warmers <piers@wranglers.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * BasegnBlogPageComponents
 *
 * @package    gnBlogPlugin
 * @subpackage modules
 * @author     Piers Warmers <piers@wranglers.com.au>
 */
class BasegnBlogPageComponents extends sfComponents
{
  public function executeLatest(sfWebRequest $request)
  {
    $query = Doctrine::getTable('gnBlogPage')->addSiteQuery()
             ->orderBy('page.id DESC')
             ->limit(10);

    $query = Doctrine::getTable('gnBlogPage')->addPublishedQuery($query);
             
    $this->gn_blog_posts = $query->execute();
  }
}