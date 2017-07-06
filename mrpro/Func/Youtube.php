<?php

namespace Mrpro\Func;

class Youtube extends BaseCase
{
	public function Home($request, $response, $args) {
		$mname			=	ltrim($request->getRequestTarget(),'/');

		$args['mname']	=	$c->get('settings')[$mname];

		//var_dump($request->uri->);
		$this->view->render($response,'youtube/home.phtml',$args);
	}
}