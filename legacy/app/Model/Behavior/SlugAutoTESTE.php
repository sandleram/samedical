<?php

#http://www.cakecoded.com/article/cakephp-generate-slug-automatically

//Generate a Slug from a Title Automatically in CakePHP
//
//JUL 18TH 2014, 22:40
//These days, it is becoming more and more important to have search friendly URL's.  It tends to be easier in CakePHP to use the 'id' field as the parameter in URL's, but given the importance of SEO, it is better to use a unique slug for the front end view pages.  A slug is a nice name such as: /article/this-is-the-title.
//
//This tutorial will give some simple code to generate a slug automatically in a Cake friendly way.  This example is for an "articles" table, but this could easily be adapted for other models.
//
//In your app/Model/AppModel.php file (or if you prefer, your specific model file), enter in the following method:
//
///**
//* This method generates a slug from a title
//*
//* @param  string $title The title or name
//* @param  string $id The ID of the model
//* @return string Slug
//*/
//    public function generateSlug($title = null, $id = null) {
//        if (!$title) {
//            throw new NotFoundException(__('Invalid Title'));
//        }
//
//        $title = strtolower($title);
//        $slug  = Inflector::slug($title, '-');
//
//        $conditions = array();
//        $conditions[$this->alias . '.slug'] = $slug;
//
//        if ($id) {
//            $conditions[$this->primaryKey. ' NOT'] = $id;
//        }
//
//        $total = $this->find('count', array('conditions' => $conditions, 'recursive' => -1));
//        if ($total > 0) {
//            for ($number = 2; $number > 0; $number ++) {
//                $conditions[$this->alias . '.slug'] = $slug . '-' . $number;
//
//                $total = $this->find('count', array('conditions' => $conditions, 'recursive' => -1));
//                if ($total == 0) {
//                    return $slug . '-' . $number;
//                }
//            }
//        }
//
//        return $slug;
//    }
//Then in your model file, add the following method, e.g. app/Model/Article.php
//
///**
// * Overrides parent before save for slug generation
// * Also handles ordering of the page
// *
// * @return boolean Always true
// */
//    public function beforeSave($options = array()) {
//        if (!empty($this->data[$this->alias]['title']) && empty($this->data[$this->alias]['slug'])) {
//            if (!empty($this->data[$this->alias][$this->primaryKey])) {
//                $this->data[$this->alias]['slug'] = $this->generateSlug($this->data[$this->alias]['title'], $this->data['Article'][$this->primaryKey]);
//            } else {
//                $this->data[$this->alias]['slug'] = $this->generateSlug($this->data[$this->alias]['title']);
//            }
//        }
//
//        return true;
//    }
//And just like that, we will have a slug that will be made automatically from the title!