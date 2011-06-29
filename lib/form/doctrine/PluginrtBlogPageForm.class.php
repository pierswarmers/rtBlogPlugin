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
    
    // Categories
    $query = Doctrine_Query::create()->from('rtCategory c');
    
    if(!$this->isNew())
    {
      $query->select('c.id,c.title,cto.position,cto.model_id,cto.model');
      $query->leftJoin('c.rtCategoryToObject cto');
      $query->orderBy('ISNULL(cto.position), cto.position ASC');
    }
    else
    {
      $query->orderBy('c.title');
    }    
    
    $result = $query->execute();
    
    if($result)
    {
      $categories_choice   = array();
      $categories_selected = array();
      
      foreach($result as $category)
      {
        if($category->rtCategoryToObject[0]->getPosition() !== NULL && $category->rtCategoryToObject[0]->getModelId() == $this->getObject()->getId())
        {
          $categories_selected[$category->getId()] = $category->getId();
        }
        $categories_choice[$category->getId()] = $category->getTitle(); 
      }
      
      $this->setWidget('rt_categories_list', new sfWidgetFormChoice(array('choices' => $categories_choice, 'expanded' => true ,'multiple' => true)));
      
      // Set checkboxes to checked if categories are set
      if(!$this->isNew())
      {
        $this->setDefault('rt_categories_list',$categories_selected);
      }
      
      $this->widgetSchema['rt_categories_list']->setLabel('Categories');
      $this->widgetSchema->setHelp('rt_categories_list', 'Optional features this blog post is defined by. Dragging up or down changes the display order.');
      $this->validatorSchema['rt_categories_list'] = new sfValidatorPass();
    }
  }
  
  /**
   * Proxt method
   * 
   * @param Doctrine_Connection $con
   */
  protected function doSave($con = null)
  {
    parent::doSave();
    
    $this->savertCategoriesList($con);
  }
  
  /**
   * Save categories
   * 
   * @param Doctrine_Connection $con
   */
  public function savertCategoriesList($con = null)
  {
    if(!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if(!isset($this->widgetSchema['rt_categories_list']))
    {
      // Somebody has unset this widget
      return;
    }

    if(null === $con)
    {
      $con = $this->getConnection();
    }
    
    // Remove current connections
    Doctrine_Query::create()->from('rtCategoryToObject cto')
      ->andWhere('cto.model_id = ?', $this->object->id)
      ->andWhere('cto.model = ?','rtBlogPage')
      ->delete()
      ->execute();
    
    $values = $this->getValue('rt_categories_list');
      
    if(!is_array($values))
    {
      $values = array();
    }

    if(count($values))
    {
      $i = 0;
      foreach($values as $v)
      {
        $rt_category_to_object = new rtCategoryToObject();
        $rt_category_to_object->setModelId($this->object->id);
        $rt_category_to_object->setModel('rtBlogPage');
        $rt_category_to_object->setCategoryId($v);
        $rt_category_to_object->setPosition($i);
        $rt_category_to_object->save();
        $i++;
      }
    }    
  }
}
