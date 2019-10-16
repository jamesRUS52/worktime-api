<?php
/* 
 * priority by routes from begin
 * certain route should by in begin list of routes then general routes
 */

use jamesRUS52\phpfrm\Router;

# default routes


Router::add('^$', ['controller'=>'Main', 'action'=>'index']);
Router::add('^(?P<controller>[a-z-]+)/?(?P<action>[a-z0-9-_]+)?$');
Router::add('^api/(?P<prefix>v[0-9\.]+)/(?P<controller>[a-z-]+)?$', ['action'=>'index']);
Router::add('^api/(?P<prefix>v[0-9\.]+)/(?P<controller>[a-z-]+)/?(?P<action>[a-z0-9-_]+)?$');



