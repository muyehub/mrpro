<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
/*$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};*/

// Register Twig View helper
$container['view'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    $view = new \Slim\Views\Twig($settings['template_path'], [
        //'cache' => $settings['cache_path']
		'cache'	=>	false
    ]);

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));

    return $view;
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// menu
$container['menu'] = function ($c) {
	return $c->get('settings')['menu'];
};
