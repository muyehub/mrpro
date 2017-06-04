<?php
// Routes

$app->get('/[{name}]', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");
    //$args['stylePath'] = $this->renderer->getTemplatePath().$args['name'];
    // Render index view
    return $this->view->render($response, $args['name'].'/index.phtml', $args);
});