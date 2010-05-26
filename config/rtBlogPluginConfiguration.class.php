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

    $routing->prependRoute(
      'rt_blog_page_index',
      new sfRoute('/blog',array('module' => 'rtBlogPage', 'action' => 'index'))
    );

    $routing->prependRoute(
      'rt_blog_page_feed',
      new sfRoute('/blog/feed.:format',
              array('module' => 'rtBlogPage', 'action' => 'feed', 'format' => 'atom1'),
              array('format' => 'atom1|rss10|rss091|rss201|rss|atom')));

    $routing->prependRoute(
      'rt_blog_page_show',
      new sfDoctrineRoute(
        '/blog/:id/:slug',
          array('module' => 'rtBlogPage', 'action' => 'show'),
          array('id' => '\d+', 'sf_method' => array('get')),
          array('model' => 'rtBlogPage', 'type' => 'object')
      )
    );

    $routing->prependRoute(
      'rt_blog_page_pagination',
      new sfRoute('/blog/page/:page',array('module' => 'rtBlogPage', 'action' => 'index'))
    );

    $routing->prependRoute(
            'rt_blog_page_undelete',
            new sfDoctrineRoute(
            '/blog/undelete/:id',
            array('module' => 'rtBlogPage', 'action' => 'undelete'),
            array('id' => '\d+', 'sf_method' => array('get')),
            array('model' => 'rtBlogPage', 'type' => 'object')
            )
    );
  }
}