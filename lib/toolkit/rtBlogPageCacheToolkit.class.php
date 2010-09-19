<?php

/*
 * This file is part of the steercms package.
 * (c) digital Wranglers <steercms@wranglers.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * rtBlogPageCacheToolkit provides cache cleaning logic.
 *
 * @package    reditype
 * @subpackage toolkit
 * @author     Piers Warmers <piers@wranglers.com.au>
 */
class rtBlogPageCacheToolkit
{
  public static function clearCache(rtBlogPage $rt_blog_page = null)
  {
    $cache = sfContext::getInstance()->getViewCacheManager();

    if ($cache)
    {
      rtGlobalCacheToolkit::clearCache();
      
      $cache->remove('rtBlogPage/index'); // index page
      $cache->remove('rtBlogPage/index?page=*'); // index with page
      $cache->remove('rtBlogPage/feed?format=*'); // feed
      $cache->remove('@sf_cache_partial?module=rtBlogPage&action=_latest&sf_cache_key=*'); // latest posts
      $cache->remove('@sf_cache_partial?module=rtBlogPage&action=_archive&sf_cache_key=*'); // archive

      if($rt_blog_page)
      {
        $cache->remove(sprintf('rtBlogPage/show?day=%s&month=%s&slug=%s&year=%s', $rt_blog_page->getDay(), $rt_blog_page->getMonth(), $rt_blog_page->getSlug(), $rt_blog_page->getYear())); // show page
        $cache->remove('@sf_cache_partial?module=rtBlogPage&action=_blog_page&sf_cache_key='.$rt_blog_page->getId());
      }
    }
  }
}