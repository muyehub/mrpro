<?php

namespace Mrpro\Func;

use Curl\Curl;

class Dins extends BaseCase
{
	protected $container;

	// constructor receives container instance
	public function __construct(\Slim\Container $container) {
		parent::__construct();
		$this->container = $container;
	}

	/**
	 * 下载Instagram视频和照片
	 */
	public function Index()
	{
		$curl 		= 	new Curl();
		$page_no	=	1;
		$page_size	=	10;
		$data		=	array(
			'partner_id'	=>	'test',
			'timestamp'		=>	time(),
			'start_time'	=>	'20160501',
			'end_time'		=>	'20170501',
			'page_no'		=>	$page_no,
			'page_size'		=>	$page_size,
			//$sign			=>	''
		);
		$res	=	$curl->get("http://api-test.che.360.cn/videopublish/supply", $data);
		$res	=	json_decode($res->response,true);
		//var_dump($res['data']);
	}

	public function Test()
	{
		header("Content-type: text/html; charset=GBK");
		$curl 		= 	new Curl();
		//signature串生成过程
		$source		=	'miaopai';						//来源
		$token		=	'fibv_nf?^&^2398*(*&#^89';		//随机串
		$timestamp	=	time();
		$tmpArr		=	array($source, $token, $timestamp);
		sort($tmpArr, SORT_STRING);
		$tmpStr 	= implode( $tmpArr );
		$signature 	= substr(md5(sha1($tmpStr)), 5,20);

		$data		=	array(
			'source'		=>	$source,
			'token'			=>	$token,
			'timestamp'		=>	$timestamp,
			'signature'		=>	$signature,
		);
		$data['list'] = array(
			0=>array(
				'desc'			=>	'视频描述1',				//视频描述
				'vurl'			=>	'http://vod.vcloud.360.cn/vod_jiluyitest/vod-car-beijing/video2016061517044214659814825918.mp4',//视频url
				'from_type'		=>	1,						//类型	1短视频 2 长文
				'source_type'	=>	1,						//来源 	1微博
				'put_time'		=>	time(),					//提交时间
			),
			1=>array(
				'desc'			=>	'视频描述2',				//视频描述
				'vurl'			=>	'http://vod.vcloud.360.cn/vod_jiluyitest/vod-car-beijing/video2016061517044214659814825918.mp4',//视频url
				'from_type'		=>	1,						//类型	1短视频 2 长文
				'source_type'	=>	1,						//来源 	1微博
				'put_time'		=>	time(),					//提交时间
			),
		);
		$res	=	$curl->post("http://hezuo.xcar.com.cn/xtv/importXtv.php", $data);
		var_dump($res->response);
	}

	public function Temp(){
		$response = $this->runApp('GET', '/');

		$this->assertEquals(200, $response->getStatusCode());
		$this->assertContains('Home', (string)$response->getBody());
	}

	public function Home($request, $response, $args) {
		$view = $this->container->get('view');
		$view->render($response,'index.phtml',$args);
	}
}