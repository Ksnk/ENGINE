<?php
return array (
  'engine.aliaces' => 
  array (
    'Database' => 'xDatabaseXilen',
    'Rights' => 'xRights',
    'User' => 'ENGINE_user',
    'Main' => 'xWebApplication',
    'Page' => 'xPage',
    'Sitemap' => 'xSitemap',
  ),
  'engine.interfaces' => 
  array (
    'link' => 
    array (
      0 => 'ENGINE_router',
      1 => 'link',
    ),
    'log' => 
    array (
      0 => 'ENGINE_logger',
      1 => 'log',
    ),
    'db' => 
    array (
      0 => 'Database',
      1 => 'getInstance',
    ),
    'action' => 
    array (
      0 => 'ENGINE_action',
      1 => 'action',
    ),
    'user_find' => 
    array (
      0 => 'User',
      1 => 'user_find',
    ),
    'has_rights' => 
    array (
      0 => 'Rights',
      1 => 'has_rights',
    ),
    'template' => 
    array (
      0 => 'Main',
      1 => 'template',
    ),
    'run' => 
    array (
      0 => 'Main',
      1 => 'run',
    ),
  ),
  'engine.include_files' => 
  array (
    0 => 'D:\\projects\\cms\\build\\system/func.php',
  ),
  'engine.class_vocabular' => 
  array (
    'template_compiler' => 'D:\\projects\\cms\\build\\system/plugins/template_compiler.php',
  ),
  'engine.event_handler' => 
  array (
    'INITIALIZE' => 
    array (
      'init_tpl' => 
      array (
        0 => 'Main',
        1 => 'init_tpl',
      ),
      'auth_check' => 
      array (
        0 => 'User',
        1 => 'auth_check',
      ),
      0 => 
      array (
        0 => 'Main',
        1 => 'init_tpl',
      ),
    ),
    'INITIALIZE/pre' => 
    array (
      0 => 'ENGINE::startSessionIfExists',
    ),
  ),
  'external.options' => 
  array (
    'login' => 'cookie|2592000',
    'USER' => 'session',
    'past_browser_agent' => 'session',
    'session.page.error' => 'session',
  ),
);
