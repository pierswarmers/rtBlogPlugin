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
    $query = Doctrine::getTable('rtBlogPage')->addSiteQuery()
             ->orderBy('page.id DESC')
             ->limit(10);

    $query = Doctrine::getTable('rtBlogPage')->addPublishedQuery($query);
             
    $this->rt_blog_posts = $query->execute();
  }
}