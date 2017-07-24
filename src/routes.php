<?php
// Routes

$app->get('/tools','Mrpro\Func\Dins:Index');
$app->get('/test','Mrpro\Func\Dins:Test');
$app->get('/temp','Mrpro\Func\Dins:Temp');
$app->get('/home','Mrpro\Func\Dins:Home');
$app->get('/youku', 'Mrpro\Func\Dins:Youku');
$app->get('/youtube', 'Mrpro\Func\Youtube:Home');

//基本路由
$app->get('/[{name}]', function ($request, $response, $args) {
	// Sample log message
	$this->logger->info("Slim-Skeleton '/' route");
	if(!isset($args['name']) && empty($args['name']))	$args['name'] = 'index';
	$args['mname']	=	$this->menu[$args['name']] ? $this->menu[$args['name']] : '';
	// Render index view
	return $this->view->render($response, '/'.$args['name'].'.phtml', $args);
});