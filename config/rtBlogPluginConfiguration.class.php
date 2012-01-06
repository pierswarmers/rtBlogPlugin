<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of rtBlogPluginConfigurationclass
 *
 * @author pierswarmers
 */
class rtBlogPluginConfiguration extends sfPluginConfiguration
{
  public function initialize()
  {
    $this->dispatcher->connect('routing.load_configuration', array($this, 'listenToRoutingLoadConfiguration'));
  }

  /**
   * Enable the required routes, carefully checking that no customisation are present.
   * 
   * @param sfEvent $event
   */
  public function listenToRoutingLoadConfiguration(sfEvent $event)
  {
    $routing = $event->getSubject();

    // Page
    $routing->prependRoute(
      'rt_blog_page_index',
      new sfRoute('/blog',array('module' => 'rtBlogPage', 'action' => 'index'))
    );

    $routing->prependRoute(
      'rt_blog_page_pagination',
      new sfRoute('/blog/page/:page',array('module' => 'rtBlogPage', 'action' => 'index'))
    );

    // Year only
    $routing->prependRoute(
      'rt_blog_page_y',
      new sfRoute('/blog/:year',array('module' => 'rtBlogPage', 'action' => 'index'))
    );

    $routing->prependRoute(
      'rt_blog_page_y_pagination',
      new sfRoute('/blog/:year/page/:page',array('module' => 'rtBlogPage', 'action' => 'index'))
    );

    // Year, month only
    $routing->prependRoute(
      'rt_blog_page_ym',
      new sfRoute('/blog/:year/:month',array('module' => 'rtBlogPage', 'action' => 'index'))
    );

    $routing->prependRoute(
      'rt_blog_page_ym_pagination',
      new sfRoute('/blog/:year/:month/page/:page',array('module' => 'rtBlogPage', 'action' => 'index'))
    );

    // Year, month and day
    $routing->prependRoute(
      'rt_blog_page_ymd',
      new sfRoute('/blog/:year/:month/:day',array('module' => 'rtBlogPage', 'action' => 'index'))
    );

    $routing->prependRoute(
      'rt_blog_page_ymd_pagination',
      new sfRoute('/blog/:year/:month/:day/page/:page',array('module' => 'rtBlogPage', 'action' => 'index'))
    );

    // Feeds
    $routing->prependRoute(
      'rt_blog_page_feed',
      new sfRoute('/blog/feed.:format',
              array('module' => 'rtBlogPage', 'action' => 'feed', 'format' => 'atom1'),
              array('format' => 'atom1|rss10|rss091|rss201|rss|atom')
      )
    );

    // Show
    $routing->prependRoute(
      'rt_blog_page_show',
      new sfDoctrineRoute(
        '/blog/:year/:month/:day/:slug',
          array('module' => 'rtBlogPage', 'action' => 'show'),
          array('id' => '\d+', 'sf_method' => array('get')),
          array('model' => 'rtBlogPage', 'type' => 'object')
      )
    );
  }
}