<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of gnBlogPluginConfigurationclass
 *
 * @author pierswarmers
 */
class gnBlogPluginConfiguration extends sfPluginConfiguration
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
      'gn_blog_page_index',
      new sfRoute('/blog',array('module' => 'gnBlogPage', 'action' => 'index'))
    );

    $routing->prependRoute(
      'gn_blog_page_index_admin',
      new sfRoute('/blog_admin',array('module' => 'gnBlogPage', 'action' => 'index_admin'))
    );

    $routing->prependRoute(
      'gn_blog_page_show',
      new sfDoctrineRoute(
        '/blog/:slug/:id',
          array('module' => 'gnBlogPage', 'action' => 'show'),
          array('id' => '\d+', 'sf_method' => array('get')),
          array('model' => 'gnBlogPage', 'type' => 'object')
      )
    );

    $routing->prependRoute('gn_page', new sfDoctrineRouteCollection(array(
      'name'                => 'gn_blog_page',
      'model'               => 'gnBlogPage',
      'module'              => 'gnBlogPage',
      'prefix_path'         => 'blog',
      'with_wildcard_routes' => true,
      'collection_actions'  => array('filter' => 'post', 'batch' => 'post', 'page' => 'get'),
      'requirements'        => array(),
    )));

    $routing->prependRoute(
      'gn_blog_page_pagination',
      new sfRoute('/blog/page/:page',array('module' => 'gnBlogPage', 'action' => 'index'))
    );

    $routing->prependRoute(
            'gn_blog_page_undelete',
            new sfDoctrineRoute(
            '/blog/undelete/:id',
            array('module' => 'gnBlogPage', 'action' => 'undelete'),
            array('id' => '\d+', 'sf_method' => array('get')),
            array('model' => 'gnBlogPage', 'type' => 'object')
            )
    );
  }
}