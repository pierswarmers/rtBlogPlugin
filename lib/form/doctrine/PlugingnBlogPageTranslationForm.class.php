<?php

/**
 * PlugingnBlogPageTranslation form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PlugingnBlogPageTranslationForm extends BasegnBlogPageTranslationForm
{
  public function setup()
  {
    parent::setup();
    unset($this['version'], $this['created_at'], $this['updated_at']);

    // set the widgets
    $this->setWidget('title',       new sfWidgetFormInputText(array(), array('class' => 'title')));
    $this->setWidget('content',     new gnWidgetFormTextareaMarkdown(array(), array()));
    $this->setWidget('tags',        new sfWidgetFormInput(array(), array('class' => 'tag-input')));
    $this->setWidget('description', new sfWidgetFormInputText(array(), array()));

    // inject the tags into the default value
    $this->setDefault('tags', implode(', ', $this->getObject()->getTags()));

    // set the validators
    $this->setValidator('tags',     new sfValidatorString(array('required' => false)));
    $this->setValidator('title',    new sfValidatorString(array('max_length' => 255, 'required' => true), array('required' => 'please enter a descriptive title.')));
    $this->setValidator('content',  new sfValidatorString(array('required' => true), array('required' => 'please enter some content.')));

    $this->widgetSchema->setHelp('description', 'As short description describing this page.');
  }
}
