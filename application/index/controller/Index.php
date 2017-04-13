<?php
namespace app\index\controller;
use \think\View;

class Index
{
    public function index()
    {
    	//电话归属地查询接口(前后双引号变成json)
  		// $res = file_get_contents('https://tcc.taobao.com/cc/json/mobile_tel_segment.htm?tel=15022785343');
		// $res = trim(explode('=',$res)[1]);
		// $res = $this->gbktoutf8($res);
		// $res = str_replace("'",'"', $res);
		// echo $res;die();
		// { mts:"1502278", province:"天津", catName:"中国移动", telString:"15022785343", areaVid:"29428", ispVid:"3236139", carrier:"天津移动" } 
		// $res = preg_replace('/(\w+):/is', '"$1":', $res);
		// echo $res;die(); 
		// $mobileinfo = json_decode($res,1);
		// print_r($mobileinfo);die();

		// var_dump($res);die();
		//print_r(__GetZoneResult_ );die();
		//eval("\$res = \"$res\";");

		// print_r($res);die();

    	/*瀑布流抓取图片start*/
    	require 'imgCatcher.php';
    	header('content-type:text/html;charset=utf-8');
    	$filename = PICCACHE_PATH.'pic_arr.php';
    	$pic_arr = '';
    	if(file_exists($filename)){
    		require PICCACHE_PATH.'pic_arr.php';
		}
    	// http://demo.htmleaf.com/1601/201601091701/index.html
    	// http://demo.htmleaf.com/1506/201506021516/index.html
    	if(!$pic_arr){
			$catcher = New imgCatcher("http://demo.htmleaf.com/1506/201506021516/index.html","img","jpg");
			$newpic = $catcher->get_pic();
	    	/*over*/
    	}

		$view = new View();
    	//$this->assign("list",$pic_arr);
    	if(file_exists($filename)){
    		return $view->fetch('index',['list'=> $pic_arr]);
    	} else {
    		return $view->fetch('index');
    	}
    	//$this->display('index');
    }

  	function gbktoutf8($string) {
	    if (is_array ( $string )) {
	        foreach ( $string as $key => $value ) {
	            $string [$key] = gbktoutf8($value);
	            //$string [$key]['name'] = iconv ( 'GBK', "utf-8", $value['name'] );
	        }
	    } else {
	        $string = iconv ( 'GBK', "UTF-8//IGNORE", $string );
	    }
	    return $string;
	}


}
