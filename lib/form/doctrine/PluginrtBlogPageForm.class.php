<?php

/**
 * PluginrtBlogPage form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginrtBlogPageForm extends BasertBlogPageForm
{
  public function setup()
  {
    parent::setup();
    
    $user = sfContext::getInstance()->getUser()->getGuardUser();

    $options = array();

    if($user)
    {
      $options['default'] = $user->getName();
    }
    
    $this->widgetSchema   ['author_name'] = new sfWidgetFormInputText($options);
    $this->validatorSchema['author_name'] = new sfValidatorString(array('max_length' => 255, 'required' => false));

    $options = array();

    if($user)
    {
      $options['default'] = $user->getEmailAddress();
    }
    
    $this->widgetSchema   ['author_email'] = new sfWidgetFormInputText($options);
    $this->validatorSchema['author_email'] = new sfValidatorString(array('max_length' => 255, 'required' => false));
  }
}
