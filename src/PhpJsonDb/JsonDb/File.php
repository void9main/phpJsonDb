<?php
namespace PhpJsonDb\JsonDb;

class File{

    /**
     * search file
     */
    public static function searchFile($path){
        if(!is_dir($path)){
            return false;
        }
        $con = dirname($path);
        $filename = scandir($con);
        $conname = array();
        foreach($filename as $k=>$v){
            if($v=="." || $v==".."){continue;}
            $conname[] = substr($v,0,strpos($v,"."));
        }
        return $conname;
    }
    /**
     * create file dir
     */
    public static function createFileDir($dir,$permissions){
        $dir = iconv("UTF-8", "GBK", $dir);
        if (!file_exists($dir)){
            mkdir ($dir,$permissions,true);            
            return true;
        } else {
            return true;
        }
    }
    /**
     * create file
     */
    public static function createFile($file){
        if(file_exists($file)){
            return true;
        }else{
            file_put_contents($file,json_encode(array(),true));
            return true;
        }
    }
    /**
     * get json
     */
    public static function getFile($file){
        $arr = array();
        if(!file_exists($file)){
            throw new \Exception("file not exist");
        }
        $data = file_get_contents($file);
        // if(self::isJson($data) == false){
        //     throw new \Exception("file notis json");
        // }
        $arr = json_decode($data,true);
        return $arr;
    }
     /**
     * is json
     * @param string $data
     * @param bool $assoc
     * @return array|bool|mixed|string
     */
    public static function isJson($data = '', $assoc = false) {
        $data = json_decode($data, $assoc);
        if ($data && (is_object($data)) || (is_array($data) && !empty(current($data)))) {
            return true;
        }
        return false;
    }
    /**
     * set File
     */
    public static function setFile($file,$data){
        $arr = array();
        if(file_exists($file)){
           unlink($file);
        }
        if(!is_array($data)){
            $data = $arr;
        }
        file_put_contents($file,json_encode($data,true));
    }
}