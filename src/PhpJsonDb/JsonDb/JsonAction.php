<?php
namespace PhpJsonDb\JsonDb;

use PhpJsonDb\JsonDb\File;

class JsonAction{

    private $jsonName = "";

    private $jsonPath = "";

    /**
     * @param dir json文件位置，默认当前引用目录的__DIR__."/jsondb/"
     * @param name json 文件名称 默认当前时间戳的md5值
     * @param permissions 权限，默认0777
     */
    public function __construct($name = Null,$dir = __DIR__."/jsondb/",$permissions = 0777){
        $time = time();
        $name = (is_null($name))?md5($time):$name;
        $fileArr = File::searchFile($dir);
        if($fileArr == false){
            $resExist = File::createFileDir($dir,$permissions);
            if($resExist != true){
                throw new \Exception("Unable to create folder");
            }
            File::createFile($dir.$name.".json");
            $this->jsonName = $name.".json";
            $this->jsonPath = $dir.$name.".json";
        }else{
            if(!empty($fileArr)){
                if(!in_array($name,$fileArr)){  
                    File::createFile($dir.$name.".json");
                }
            }else{
                File::createFile($dir.$name.".json");
            }
            $this->jsonName = $name.".json";
            $this->jsonPath = $dir.$name.".json";
        }
    }
    /**
     * get
     */
    public function get(){
        $data = File::getFile($this->jsonPath);
        return $data;
    }
    /**
     * del
     * @param field 字段
     * @param key_type id|field
     */
    public function del($field_content,$key_type = "id"){
        $set = array();
        $data = File::getFile($this->jsonPath);
        if(!empty($data)){
            foreach($data as $key=>$val){
                if($key_type == "id"){
                    if($val['id'] == $field_content){
                        unset($data[$key]);
                    }
                }else{
                    if($val[$key_type] == $field_content){
                        unset($data[$key]);
                    }
                }
            }
        }
        if(!isset($data)){
            $data = array();
        }
        if(!empty($data)){
            foreach($data as $key=>$val){
                $set[] = $val;
            }
        }
        File::setFile($this->jsonPath,$set);
    }
    /**
     * update
     * @param field_content 需要更新的数据
     * @param field id|field
     * @param key_name 字段名
     */
    public function update($field_content,$field,$key_name){
        $ret = array();
        if(empty($field_content)){
            throw new \Exception("update content is empty");
        }
        if(!is_array($field_content)){
            throw new \Exception("add content is error");
        }
        $data = self::get();
        if(!empty($data)){
            $change = "false";
            foreach($data as $key=>$val){
                if($val[$key_name] == $field){
                    $change = "true";
                }
                if($change == "true"){
                    foreach($val as $k=>$v){
                        foreach($field_content as $s=>$g){
                            if($s == $k){
                                $data[$key][$k] = $g;
                            }
                        }
                    }
                }
                $change = "false";
            }

        }else{
            $ret = array();
        }
        if(!empty($data)){
            foreach($data as $key=>$val){
                $ret[] = $val;
            }
        }
        File::setFile($this->jsonPath,$ret);
    }
    /**
     * add
     */
    public function add($field_content){
        $data = self::get();
        if(!is_array($field_content)){
            throw new \Exception("add content is error");
        }
        $i = count($data);
        foreach($field_content as $key=>$val){
            $i++;
            $field_content[$key]['id'] = $i;
        }
        foreach($field_content as $key=>$val){
            $set[] = $val;
        }
        $ret = array_merge($data,$set);
        File::setFile($this->jsonPath,$ret);
    }
}
?>