<?php

namespace Mrpro\Func;

use Curl\Curl;
use Slim\Container;

class Dins extends BaseCase
{
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
		$a = 'https://r1.ykimg.com/054201015865CA9F6A0A400455FD1B3D';
		$b = get_headers($a,1);
		$a = $this->has_limit_word('你有freestyle么');
		var_dump($a);
		//var_dump($res['data']);
	}

	public function Youku()
	{
		$curl	=	new Curl();
		$res 	= 	$curl->get('https://openapi.youku.com/v2/videos/show.json?client_id=39fa61a8c2b6ddd9&video_id=XMTg3MjAzMTc1Ng==');
		$res	=	json_decode($res->response,true);
		if($res['bigThumbnail']){
			$pic = $curl->get($res['bigThumbnail']);
			var_dump($pic->response_headers);
			//$pic = json_decode($pic->response_headers,true);
		}
		//var_dump($pic);
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
				'cover_img'		=>	'',						//视频封面图
				'duration'		=>	0,						//视频时长
				'is_video_watermark'	=>		0			//是否有水印0无1有
			),
			1=>array(
				'desc'			=>	'视频描述2',				//视频描述
				'vurl'			=>	'http://vod.vcloud.360.cn/vod_jiluyitest/vod-car-beijing/video2016061517044214659814825918.mp4',//视频url
				'from_type'		=>	1,						//类型	1短视频 2 长文
				'source_type'	=>	1,						//来源 	1微博
				'put_time'		=>	time(),					//提交时间
				'cover_img'		=>	'',						//视频封面图
				'duration'		=>	0,						//视频时长
				'is_video_watermark'	=>		0			//是否有水印0无1有
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
		$this->getView()->render($response,'index.phtml',$args);
	}

	public function has_limit_word($string){
		$cacheFile="/limit_word.cache.php";
		if(file_exists($cacheFile) && time() - filemtime($cacheFile) < 7*24*3600){
			$conts=include($cacheFile);
		}else{
			$conts = file_get_contents("http://hezuo.xcar.com.cn/cms/forbid_key/censor_key.php");
			$conts = @unserialize($conts);
			$str="<?php \t return \t ".var_export($conts,true)."; \n?>";
			file_put_contents($cacheFile,$str);
		}

	if (!empty($conts)) {
		foreach($conts as $v){
			if(preg_match($v,$string)){
				var_dump($v);exit;
				error_log("\n[".date("Y-m-d H:i:s")."] limit_word：".$string." -{$v}",3,date('Y-m-d')."_tryError.log");
				return true;
			}
		}
	}

	//新加入论坛来的敏感词
	$bbs_censor = include_once "/cache/forbid_arr_censor.php";
	$bbs_censor = $bbs_censor['mod'];
	if(!empty($bbs_censor)){
		foreach($bbs_censor as $v){
			preg_match($v, $string,$match);
			if($match[0]){
				return true;
			}
		}
	}
	//include_once(COMMENT_ROOT."forbid_arr.php");
	$forbid_word2 = include_once("/cache/forbid_arr_lexicon.php");

	//进一步处理
	$clean_data=formatText($string);
	//$clean_data=$string;
	$badWord=array();
	if (!empty($forbid_word2)) {
		foreach($forbid_word2 as $v){
			if(strstr($clean_data,$v)){
				$badWord[]=$v;
			}
		}
	}

	//为的是统计都出现了哪些不该出现的词
	if(count($badWord) >= 2){
		error_log("\n[".date("Y-m-d H:i:s")."] 进一步处理：".$string.var_export($badWord,true),3,date('Y-m-d')."_tryError.log");
		return true;
	}
	$forbid_together = include_once("/cache/forbid_arr_group.php");
	//不能一起出现的
	if (!empty($forbid_together)) {
		foreach($forbid_together as $row) {
			$flag = true;
			foreach($row as $v){
				$preg = '/'.$v.'/i';
				$ret = preg_match_all($preg,$string,$match);
				if($ret>=1){
				}else{
					$flag = false;
				}
			}
			if($flag){
				error_log("\n[".date("Y-m-d H:i:s")."] 组合词：".$string.var_export($match,true),3,date('Y-m-d')."_tryError.log");
				return true;
			}
		}
	}


	//正则的数组
	$forbid_preg = include_once("/cache/forbid_arr_regular.php");
	if (!empty($forbid_preg)) {
		foreach($forbid_preg as $preg) {
			$ret = preg_match_all($preg,$clean_data,$match);
			if($ret){
				error_log("\n[".date("Y-m-d H:i:s")."] 正则的数组：".$string.var_export($match,true),3,COMMENT_LOG_PATH.date('Y-m-d')."_tryError.log");
				return true;
			}
		}
	}
	return false;
	}
}