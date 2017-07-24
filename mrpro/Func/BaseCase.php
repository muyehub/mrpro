<?php

namespace Mrpro\Func;

use Slim\App;
use Slim\Container as Con;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Environment;

/**
 * This is an example class that shows how you could set up a method that
 * runs the application. Note that it doesn't cover all use-cases and is
 * tuned to the specifics of this skeleton app, so if your needs are
 * different, you'll need to change it.
 */
class BaseCase extends \PHPUnit_Framework_TestCase
{
	/**
	 * Use middleware when running application?
	 *
	 * @var bool
	 */
	protected $withMiddleware = true;

	protected $container;

	// constructor receives container instance
	public function __construct(\Slim\Container $container) {
		parent::__construct();
		$this->container 	= 	$container;
	}

	//获取模板容器
	public function getView() {
		return $this->container->get('view');
	}

	//获取菜单英汉对照配置
	public function getMenu() {
		return $this->container->get('menu');
	}

	/**
	 * Process the application given a request method and URI
	 * 处理应用程序提交的请求方法和URI
	 *
	 * @param string $requestMethod the request method (e.g. GET, POST, etc.)
	 * @param string $requestUri the request URI
	 * @param array|object|null $requestData the request data
	 * @return \Slim\Http\Response
	 */
	public function runApp($requestMethod, $requestUri, $requestData = null)
	{
		// Create a mock environment for testing with
		$environment = Environment::mock(
			[
				'REQUEST_METHOD' => $requestMethod,
				'REQUEST_URI' => $requestUri
			]
		);

		// Set up a request object based on the environment
		$request = Request::createFromEnvironment($environment);

		// Add request data, if it exists
		if (isset($requestData)) {
			$request = $request->withParsedBody($requestData);
		}

		// Set up a response object
		$response = new Response();

		// Use the application settings
		$settings = require __DIR__ . '/../../src/settings.php';

		// Instantiate the application
		$app = new App($settings);

		// Set up dependencies
		require __DIR__ . '/../../src/dependencies.php';

		// Register middleware
		if ($this->withMiddleware) {
			require __DIR__ . '/../../src/middleware.php';
		}

		// Register routes
		require __DIR__ . '/../../src/routes.php';

		// Process the application
		$response = $app->process($request, $response);

		// Return the response
		return $response;
	}
}
