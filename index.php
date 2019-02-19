<?php
require 'core.php';

$path = $_SERVER['REQUEST_URI'];
$route = str_replace($prefix, '', $path);
$params = isset(explode('?', $route)[1]) ? explode('?', $route)[1] : '';

$connector = [
	'\/' 													=> 'home',
	'\/discuss\/?' 								=> 'discuss',
	'\/discuss\/[0-9]+\/?' 				=> 'discuss-show',
	'\/discuss\/[0-9]+\/?' 				=> 'discuss-show',
	'\/comment-[a-z]+\/[0-9]+\/?'	=> 'comment-show',
	'\/buynsell\/?' 							=> 'buynsell',
	'\/buynsell\/[a-z0-9-]+\/?'		=> 'buynsell-show',
	'\/article\/?' 								=> 'article',
	'\/article\/[a-z0-9-]+\/?' 		=> 'article-show',
	'\/event\/?'									=> 'event',
	'\/event\/[a-z0-9-]+\/?' 			=> 'event-show',
	'\/member\/?'									=> 'member',
	'\/member\/[a-z0-9-]+\/?' 		=> 'member-show',
	'\/file\/?'									=> 'file',
];

$found = false;
foreach ($connector as $key => $value) {
	if( preg_match('/^'.$key.'$/', $route) ) {
		include 'views/page-'.$value.'.php';
		$found = true;
	}
	if( preg_match('/^'.$key.'\?[A-Za-z0-9-=&]+$/', $route) ) {
		include 'views/page-'.$value.'.php';
		$found = true;
	}
}

?>