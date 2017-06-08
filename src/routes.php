<?php
// Routes

$app->get('/[{name}]', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");
    if(!isset($args['name']) && empty($args['name']))	$args['name'] = 'index';
    // Render index view
    return $this->view->render($response, '/'.$args['name'].'.phtml', $args);
});