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

    $route_token = sfConfig::get('app_rt_blog_route_prefix', 'posts');

    // Page
    $routing->prependRoute(
      'rt_blog_page_index',
      new sfRoute(sprintf('/%s', $route_token),array('module' => 'rtBlogPage', 'action' => 'index'))
    );

    $routing->prependRoute(
      'rt_blog_page_pagination',
      new sfRoute(sprintf('/%s/page/:page', $route_token),array('module' => 'rtBlogPage', 'action' => 'index'))
    );

    // Year only
    $routing->prependRoute(
      'rt_blog_page_y',
      new sfRoute(sprintf('/%s/:year', $route_token),array('module' => 'rtBlogPage', 'action' => 'index'))
    );

    $routing->prependRoute(
      'rt_blog_page_y_pagination',
      new sfRoute(sprintf('/%s/:year/page/:page', $route_token),array('module' => 'rtBlogPage', 'action' => 'index'))
    );

    // Year, month only
    $routing->prependRoute(
      'rt_blog_page_ym',
      new sfRoute(sprintf('/%s/:year/:month', $route_token),array('module' => 'rtBlogPage', 'action' => 'index'))
    );

    $routing->prependRoute(
      'rt_blog_page_ym_pagination',
      new sfRoute(sprintf('/%s/:year/:month/page/:page', $route_token),array('module' => 'rtBlogPage', 'action' => 'index'))
    );

    // Year, month and day
    $routing->prependRoute(
      'rt_blog_page_ymd',
      new sfRoute(sprintf('/%s/:year/:month/:day', $route_token),array('module' => 'rtBlogPage', 'action' => 'index'))
    );

    $routing->prependRoute(
      'rt_blog_page_ymd_pagination',
      new sfRoute(sprintf('/%s/:year/:month/:day/page/:page', $route_token),array('module' => 'rtBlogPage', 'action' => 'index'))
    );

    // Feeds
    $routing->prependRoute(
      'rt_blog_page_feed',
      new sfRoute(sprintf('/%s/feed.:format', $route_token),
        array('module' => 'rtBlogPage', 'action' => 'feed', 'format' => 'atom1'),
        array('format' => 'atom1|rss10|rss091|rss201|rss|atom')
      )
    );

    // Show
    $routing->prependRoute(
      'rt_blog_page_show',
      new sfDoctrineRoute(
        sprintf('/%s/:year/:month/:day/:slug', $route_token),
        array('module' => 'rtBlogPage', 'action' => 'show'),
        array('id' => '\d+', 'sf_method' => array('get')),
        array('model' => 'rtBlogPage', 'type' => 'object')
      )
    );
  }
}