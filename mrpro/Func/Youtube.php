<?php

namespace Mrpro\Func;

class Youtube extends BaseCase
{
	public function Home($request, $response, $args) {
		$mname			=	ltrim($request->getRequestTarget(),'/');
		$args['mname']	=	$this->getMenu()[$mname];

		$this->getView()->render($response,'youtube/home.phtml',$args);
	}
}