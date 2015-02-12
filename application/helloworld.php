<?php
/**
 * плагин для системы, выводящий Hello world
 */

class helloworld {
     function echo_hello(){
         ENGINE::set_option(array(
             'page_tpl'=>'tpl_logo_template',
             'page.title'=>'Привет World!'
         )) ;
         return 'Hello world!';
     }
}