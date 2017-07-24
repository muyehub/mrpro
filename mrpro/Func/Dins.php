<?php

namespace Mrpro\Func;

use Curl\Curl;
use Slim\Container;

class Dins extends BaseCase
{
	/**
	 * ����Instagram��Ƶ����Ƭ
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
		$a = $this->has_limit_word('����freestyleô');
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
		//signature�����ɹ���
		$source		=	'miaopai';						//��Դ
		$token		=	'fibv_nf?^&^2398*(*&#^89';		//�����
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
				'desc'			=>	'��Ƶ����1',				//��Ƶ����
				'vurl'			=>	'http://vod.vcloud.360.cn/vod_jiluyitest/vod-car-beijing/video2016061517044214659814825918.mp4',//��Ƶurl
				'from_type'		=>	1,						//����	1����Ƶ 2 ����
				'source_type'	=>	1,						//��Դ 	1΢��
				'put_time'		=>	time(),					//�ύʱ��
				'cover_img'		=>	'',						//��Ƶ����ͼ
				'duration'		=>	0,						//��Ƶʱ��
				'is_video_watermark'	=>		0			//�Ƿ���ˮӡ0��1��
			),
			1=>array(
				'desc'			=>	'��Ƶ����2',				//��Ƶ����
				'vurl'			=>	'http://vod.vcloud.360.cn/vod_jiluyitest/vod-car-beijing/video2016061517044214659814825918.mp4',//��Ƶurl
				'from_type'		=>	1,						//����	1����Ƶ 2 ����
				'source_type'	=>	1,						//��Դ 	1΢��
				'put_time'		=>	time(),					//�ύʱ��
				'cover_img'		=>	'',						//��Ƶ����ͼ
				'duration'		=>	0,						//��Ƶʱ��
				'is_video_watermark'	=>		0			//�Ƿ���ˮӡ0��1��
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
				error_log("\n[".date("Y-m-d H:i:s")."] limit_word��".$string." -{$v}",3,date('Y-m-d')."_tryError.log");
				return true;
			}
		}
	}

	//�¼�����̳�������д�
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

	//��һ������
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

	//Ϊ����ͳ�ƶ���������Щ���ó��ֵĴ�
	if(count($badWord) >= 2){
		error_log("\n[".date("Y-m-d H:i:s")."] ��һ������".$string.var_export($badWord,true),3,date('Y-m-d')."_tryError.log");
		return true;
	}
	$forbid_together = include_once("/cache/forbid_arr_group.php");
	//����һ����ֵ�
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
				error_log("\n[".date("Y-m-d H:i:s")."] ��ϴʣ�".$string.var_export($match,true),3,date('Y-m-d')."_tryError.log");
				return true;
			}
		}
	}


	//���������
	$forbid_preg = include_once("/cache/forbid_arr_regular.php");
	if (!empty($forbid_preg)) {
		foreach($forbid_preg as $preg) {
			$ret = preg_match_all($preg,$clean_data,$match);
			if($ret){
				error_log("\n[".date("Y-m-d H:i:s")."] ��������飺".$string.var_export($match,true),3,COMMENT_LOG_PATH.date('Y-m-d')."_tryError.log");
				return true;
			}
		}
	}
	return false;
	}
}