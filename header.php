<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Worthio</title>
   <?php wp_head(); ?>
  </head>
  <body <?php body_class('') ?> >
    
  <?php
      if(function_exists('wp_body_open') ) {
         wp_body_open();
      }
  ?>

  <div id="page" class="site">
        <header id="mainHead" class="site-header" role="worthio header">
            <?php get_template_part('/parts/header/main-menu'); ?>   
        </header>    
        <div id="content" class="site-content">        
           
   