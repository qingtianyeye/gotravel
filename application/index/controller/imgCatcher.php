<?php 
    
    namespace app\index\controller;
    use \think\View;

    class imgCatcher{
        const FLODER_DIR = '/static/img/';
        // const FLODER_DIRS = '/static/img/';
        const IMG_TYPE = 'jpg|bmp|png|gif';
        var $floder;
        var $url;
        var $type;
        var $domain;
        var $pic_arr;

        public function __construct($url,$floder='',$type=''){
            $this->url = $url;
             
            $url_arr = parse_url($url);
            $this->domain = array_shift($url_arr);
            
            if(trim($floder) == ""){
                //echo "111111";die();
                $this->folder = FLODER_DIR;
                // echo "HHHH";die();
                // echo $this->folder;die();
            }else{
                // echo "2";die();
                $this->folder = $floder.'/';
            }
 
            if(trim($type)==""){
                $this->type = IMG_TYPE;
            }else{
                $this->type = $type; 
            }
             
            $this->pic_arr = array();

            if(!is_dir(CSS_PATH.'/'.$this->folder)){
                mkdir(CSS_PATH.'/'.$this->folder);
            }
        }
 
        public function get_pic(){
            set_time_limit(0);//抓取不受时间限制  
            //获取图片二进制流  
            $data = self::CurlGet($this->url);  
            //利用正则表达式得到图片链接  
            $pattern_src = '/<img.*?src\=\"(.*\.('.$this->type.')).*?>/';// 
            $num = preg_match_all($pattern_src, $data, $match_src); 
            $filename = PICCACHE_PATH.'pic_arr.php';
            if(file_exists($filename)){
                require PICCACHE_PATH.'pic_arr.php';
            }
            $this->pic_arr=$match_src[1];//获得图片数组
            $this->get_name();   
            return 0;  
        } 
 
        public function get_name(){  
            $pic_arr = $this->pic_arr;
            //图片编号和类型  
            $pattern_type = '/.*\/(.*?)$/';
            //$sjstr = '<?php';
            foreach($pic_arr as $pic_item){//循环取出每幅图的地址
                //判断图片的地址如果不含有http则添加domain地址
                if(!strstr($pic_item,'http://')){
                    $newurl = explode('/',$this->url);
                    array_pop($newurl);
                    $zpurl = implode('/',$newurl);
                    $pic_item = $zpurl.'/'.$pic_item;  
                    // echo $pic_item ;die();
                }
                $num = preg_match_all($pattern_type,$pic_item,$match_type);
                //以流的形式保存图片  
                // echo CSS_PATH.$this->folder.$match_type[1][0];die();
                // echo $match_type[1][0];die();

                $write_fd = fopen(CSS_PATH.$this->folder.$match_type[1][0],"wb"); 
                // fwrite($write_fd, self::CurlGet($pic_item,$this->url));
                
                if(fwrite($write_fd, self::CurlGet($pic_item,$this->url))){
                   $sjstr_arr[] = 'img/'.$match_type[1][0];
                   //$sjstr.=' $sjstr_arr='.var_export($match_type[1][0], true).';';
                }
                fclose($write_fd);  
            }
            $result = array_merge($pic_arr,$sjstr_arr);
            print_r($result);die();
            
            $output='<?php
                $pic_arr='.var_export($result, true).';
            ?>';
            file_put_contents(PICCACHE_PATH.'pic_arr.php',$output);
            return 0;  
        } 
 
 
         //抓取网页内容  
        static function CurlGet($url,$domain=""){
            if(substr($url, 0, 1) == "/"){
                $url =  $domain.$url;
            }
 
            if(substr($url, 0, 1) == "."){
                $url =  $domain.substr($url, 1);
            }
            if(substr($url, 0, 2) == ".."){
                $url =  $domain.substr($url, 2);
            }
            $url=str_replace('&','&',$url);  
            $curl = curl_init();  
            curl_setopt($curl, CURLOPT_URL, $url);  
            curl_setopt($curl, CURLOPT_HEADER, false);  
            //curl_setopt($curl, CURLOPT_REFERER,$url);  
            curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; SeaPort/1.2; Windows NT 5.1; SV1; InfoPath.2)");  
            curl_setopt($curl, CURLOPT_COOKIEJAR, 'cookie.txt');  
            curl_setopt($curl, CURLOPT_COOKIEFILE, 'cookie.txt');  
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);  
            $values = curl_exec($curl);  
            curl_close($curl);
            // var_dump($values);die();
            return $values;  
        }     
 
    }
 ?>