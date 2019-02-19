<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;
use Event;
use App\Events\FileUploadDir;
use App\Events\GetFileInfo;
use App\Events\GetScriptInfo;
use Redirect, Input, Validator;
use App\Models\App\App;
class FileController extends BaseController {

    protected $request;
    protected $model;
    protected $curr_dir;
    public function __construct(Request $request)
    {
        $this->request = $request;
        //$this->model = $model;
    }

    public function upload(){
        if($file = $this->request->file('file')){
            if ($this->request->file('file')->isValid()){
                $ext = $this->request->file('file')->getClientOriginalExtension();
                //$mini_type = $this->request->file('file')->getMimeType();
                if(Input::get('type')){
                    $type = Input::get('type');
                }else{
                    $type = strtolower($ext);
                }
                
                $res = Event::fire(new FileUploadDir($type));
                $curr_dir = $res[0];
                //var_dump($curr_dir);
                $upload_dir = Config()->get('common.upload_path').$curr_dir.'/';
                
                if($type == 'script'){
                    $new_name = $this->request->file('file')->getClientOriginalName();
                }else{
                    $new_name = uniqid().'.'.$ext;
                }
                if($this->request->file('file')->move($upload_dir, $new_name)){
                    if(Input::get('name') == 'icon'){
                        //判断图片大小  >100px的修改尺寸
                        $this->changeImageSize($upload_dir.$new_name);
                    }

                    if($ext == 'apk'){
                        $path = $curr_dir.'/'.$new_name;
                        $app_info = Event::fire(new GetFileInfo($path, $ext));
                        $result = ['code'=>0,'info'=>$app_info[0]];
                        return Response()->json($result);
                    }elseif(Input::get('name') == 'gift'){
                        $code = Event::fire(new GetGiftInfo($upload_dir.$new_name));
                        if($code){
                            $result = ['code'=>0,'info'=>$code[0]];
                        }else{
                            $result = ['code'=>1,'info'=>'礼包内容不能为空'];
                        }
                        return Response()->json($result);
                    }elseif(Input::get('type') == 'script'){
                        $path = $curr_dir.'/'.$new_name;
                        $info = Event::fire(new GetScriptInfo($upload_dir.$new_name));
                        if($info){
                            if($info[0]['package'] != Input::get('package_name')){
                                $result = ['code'=>1,'info'=>'脚本包名错误'];
                            }else{
                                $info[0]['path'] = $path;
                                $result = ['code'=>0,'info'=>$info[0]]; 
                            }
                        }else{
                            $result = ['code'=>1,'info'=>'脚本信息错误'];
                        }
                        return Response()->json($result);
                    }
                    $result = ['code'=>0,'info'=>$curr_dir.'/'.$new_name];
                    return Response()->json($result);
                }
            }
        }
        $result = ['code'=>1,'info'=>$this->request->file('file')->getErrorMessage()];//
        return Response()->json($result);
    } 

    public function apkUpload(){
        $uploads_dir = Config()->get("common.upload_path").'upload_tmp/';
        if($this->request->isMethod('get')){
            if(count($_GET)>0){
                $chunkNumber = $_GET['resumableChunkNumber'];
                $chunkSize = $_GET['resumableChunkSize'];
                $totalSize = $_GET['resumableTotalSize'];
                $identifier = $_GET['resumableIdentifier'];
                $filename = iconv ( 'UTF-8', 'GB2312', $_GET ['resumableFilename'] );
                $valid = $this->validateRequest($chunkNumber, $chunkSize, $totalSize, $identifier, $filename);
                if($valid=='valid'){
                    $chunkFilename = $this->getChunkFilename($uploads_dir,$chunkNumber, $identifier,$filename);
                    
                    if(file_exists($chunkFilename)){
                        return Response('found');
                    } else {
                        //header("HTTP/1.0 404 Not Found");
                        //echo "not_found";
                        return Response('not_found','404');
                    }
                }else{
                    //header("HTTP/1.0 404 Not Found");
                    //echo "not_found";
                    return Response('not_found','404');
                }
            }
        }

        // loop through files and move the chunks to a temporarily created directory
        if($this->request->isMethod('post')){
            if(count($_POST)>0){
                $resumableFilename = iconv ( 'UTF-8', 'GBK//IGNORE', $_POST ['resumableFilename'] );
                $resumableIdentifier=$_POST['resumableIdentifier'];
                $resumableChunkNumber=$_POST['resumableChunkNumber'];
                $resumableTotalSize=$_POST['resumableTotalSize'];
                $resumableChunkSize=$_POST['resumableChunkSize'];
                if (!empty($_FILES)) foreach ($_FILES as $file) {
                    // check the error status
                    if ($file['error'] != 0) {
                        $info = $this->fileError($file['error']);
                        return Response()->json(['status'=>0,'info'=>$info]);
                        //echo json_encode($file);
                        //$this->_log('error '.$file['error'].' in file '.$resumableFilename);
                        //continue;
                    }
                    // init the destination file (format <filename.ext>.part<#chunk>
                    // the file is stored in a temporary directory
                    $temp_dir = $uploads_dir.$resumableIdentifier;
                    $dest_file = $temp_dir.'/'.$resumableFilename.'.part'.$resumableChunkNumber;
                    // create the temporary directory
                    if (!is_dir($temp_dir)) {
                        @mkdir($temp_dir, 0777, true);
                    }
                    // move the temporary file
                    if (!move_uploaded_file($file['tmp_name'], $dest_file)) {
                        return Response()->json(['status'=>0,'info'=>'移动上传文件失败']);
                        //echo 'Error saving';
                        //$this->_log('Error saving (move_uploaded_file) chunk '.$resumableChunkNumber.' for file '.$resumableFilename);
                    } else {
                        // check if all the parts present, and create the final destination file
                        $res = $this->createFileFromChunks($temp_dir, $resumableFilename,$resumableChunkSize, $resumableTotalSize);
                        return Response()->json($res);
                        /*if($res){
                            return Response()->json(['status'=>1,'info'=>$res]);
                        }elseif($res === false){
                            return Response()->json(['status'=>0,'info'=>'cannot create the destination file']);
                        }*/
                    }
                }
            }
        }

    }

    function getChunkFilename ($uploads_dir,$chunkNumber, $identifier,$filename){
        $temp_dir = $uploads_dir.$identifier;
        return  $temp_dir.'/'.$filename.'.part'.$chunkNumber;
    }

    function cleanIdentifier ($identifier){
        return $identifier;
        //return  preg_replace('/^0-9A-Za-z_-/', '', $identifier);
    }

    //$maxFileSize = 2*1024*1024*1024;
    function validateRequest ($chunkNumber, $chunkSize, $totalSize, $identifier, $filename, $fileSize=''){
        // Clean up the identifier
        //$identifier = cleanIdentifier($identifier);
        // Check if the request is sane
        if ($chunkNumber==0 || $chunkSize==0 || $totalSize==0 || $identifier==0 || $filename=="") {
            return 'non_resumable_request';
        }
        $numberOfChunks = max(floor($totalSize/($chunkSize*1.0)), 1);
        if ($chunkNumber>$numberOfChunks) {
            return 'invalid_resumable_request1';
        }
        // Is the file too big?
    //      if($maxFileSize && $totalSize>$maxFileSize) {
    //          return 'invalid_resumable_request2';
    //      }
        if($fileSize!="") {
            if($chunkNumber<$numberOfChunks && $fileSize!=$chunkSize) {
                // The chunk in the POST request isn't the correct size
                return 'invalid_resumable_request3';
            }
            if($numberOfChunks>1 && $chunkNumber==$numberOfChunks && $fileSize!=(($totalSize%$chunkSize)+$chunkSize)) {
                // The chunks in the POST is the last one, and the fil is not the correct size
                return 'invalid_resumable_request4';
            }
            if($numberOfChunks==1 && $fileSize!=$totalSize) {
                // The file is only a single chunk, and the data size does not fit
                return 'invalid_resumable_request5';
            }
        }
        return 'valid';
    }

    /**
     *
     * Logging operation - to a file (upload_log.txt) and to the stdout
     * @param string $str - the logging string
     */
    function _log($str) {
        // log to the output
        $log_str = date('d.m.Y').": {$str}\r\n";
        //echo $log_str;
        // log to file
        if (($fp = fopen('upload_log.txt', 'a+')) !== false) {
            fputs($fp, $log_str);
            fclose($fp);
        }
    }
    /**
     * 
     * Delete a directory RECURSIVELY
     * @param string $dir - directory path
     * @link http://php.net/manual/en/function.rmdir.php
     */
    function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir") {
                        $this->rrmdir($dir . "/" . $object);
                    } else {
                        unlink($dir . "/" . $object);
                    }
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }
    /**
     *
     * Check if all the parts exist, and 
     * gather all the parts of the file together
     * @param string $dir - the temporary directory holding all the parts of the file
     * @param string $fileName - the original file name
     * @param string $chunkSize - each chunk size (in bytes)
     * @param string $totalSize - original file size (in bytes)
     */
    function createFileFromChunks($temp_dir, $fileName, $chunkSize, $totalSize) {
        // count all the parts of this file$fileName
        $total_files = 0;
        foreach(scandir($temp_dir) as $file) {
            if (stripos($file, $fileName) !== false) {
                $total_files++;
            }
        }
        // check that all the parts are present
        // the size of the last part is between chunkSize and 2*$chunkSize
        if ($total_files * $chunkSize >=  ($totalSize - $chunkSize + 1)) {
            // create the final destination file
            $file_info = pathinfo($fileName);
            $type = strtolower($file_info['extension']);

            $res = Event::fire(new FileUploadDir($type));
            $curr_dir = $res[0];
            $upload_dir = Config()->get('common.upload_path').$curr_dir.'/';

            /*if(!file_exists($upload_dir)){
                mkdir($upload_dir);
            }*/
            if($type != 'apk' && $type != 'zip'){
                $new_name = $fileName;
            }else{
                $new_name = uniqid().'.'.$type;
            }
            if(($fp = fopen($upload_dir.$new_name, 'w')) !== false) {
                for ($i=1; $i<=$total_files; $i++) {
                    if(fwrite($fp, file_get_contents($temp_dir.'/'.$fileName.'.part'.$i)) == false){
                        fclose($fp);
                        return ['status'=>0,'info'=>'writing chunk '.$i.'error'];
                    }
                    //$this->_log('writing chunk '.$upload_dir.$fileName.$i);
                }
                fclose($fp);
                // rename the temporary directory (to avoid access from other
                // concurrent chunks uploads) and than delete it
                if (rename($temp_dir, $temp_dir.'_UNUSED')) {
                    $this->rrmdir($temp_dir.'_UNUSED');
                } else {
                    $this->rrmdir($temp_dir);
                }
                if($type != 'apk' && $type != 'zip'){
                    return ['status'=>1,'info'=>$curr_dir.'/'.$new_name,'tk'=>csrf_token()];
                }else{
                    return ['status'=>1,'info'=>$curr_dir.'/'.$new_name];
                }
            }else{
                //$this->_log('cannot create the destination file');
                return ['status'=>0,'info'=>'cannot create the destination file'];
            }
        }
    }

    public function delFile(){
        if(Input::get('url')){
            $file_path = Config()->get('common.upload_path').Input::get('url'); 
            @unlink($file_path);
            $result = ['status'=>1,'info'=>'删除成功'];//.$file_path
        }elseif(Input::get('file')){   //上传一部分取消上传
            $res = $this->deldir(Config()->get('common.upload_path').'upload_tmp/'.Input::get('file'));
            if($res){
                $result = ['status'=>1,'info'=>'删除成功~'];
            }else{
                $result = ['status'=>1,'info'=>'删除失败'];
            }
        }else{
            $result = ['status'=>0,'info'=>'信息获取错误'];  
        }
        return Response()->json($result);
    }

    public function deldir($dir) {
        //先删除目录下的文件：
        if(is_dir($dir)){
            $dh=opendir($dir);
            while ($file=readdir($dh)) {
                if($file!="." && $file!="..") {
                    $fullpath=$dir."/".$file;
                    if(!is_dir($fullpath)) {
                        unlink($fullpath);
                    } else {
                        deldir($fullpath);
                    }
                }
            }
             
            closedir($dh);
              //删除当前文件夹：
            if(rmdir($dir)) {
                return true;
            }
        }
        return false;
    }

    public function apkInfo(){
        if(Input::get('file')){
            $file = explode('.' , Input::get('file')); 
            if(strtolower($file[count($file)-1]) == 'apk'){
                $ext = 'apk';
            }elseif(strtolower($file[count($file)-1]) == 'zip'){
                $ext = 'zip';
            }elseif(strtolower($file[count($file)-1]) == 'xapk'){
                //后缀直接重命名
                $ext = 'zip';
            }else{
                $result = ['code'=>1,'info'=>'文件格式不正确'];
                return Response()->json($result);
            }
            /*$res = Event::fire(new FileUploadDir($ext));
            $curr_dir = $res[0];
            $path = $curr_dir.'/'.Input::get('file');*/
            $app_info = Event::fire(new GetFileInfo(Input::get('file'), $ext));
            if(empty($app_info)){
                return Response()->json(['code'=>1,'msg'=>'读取应用信息失败']);
            }
            $info = $app_info[0];
            //检测包名是否存在 或编辑时与之前的是否相同
            if(is_numeric(Input::get('key'))){  //判断包名是否与之前相同
                $game = App::find(Input::get('key'));
                if($game){
                   if($info['package_name'] != $game->package_name){
                        return Response()->json(['code'=>1,'msg'=>'apk包名与之前的包名不一致~','info'=>$info]);
                    } 
                }
            }else{              //判断apk文件之前是否有人上传
                $is_find = App::where('package_name','=',$info['package_name'])->first();
                if($is_find && !is_numeric(Input::get('key'))){
                    //删除上传文件
                    if($info['is_apk'] == 1){
                        $ext = 'apk';
                    }else{
                        $ext = 'zip';
                    }
                    $file_path = Config()->get('common.upload_path').Input::get('file'); 
                    @unlink($file_path);
                    return Response()->json(['code'=>1,'msg'=>'apk包名已经存在了~','info'=>$info]);
                } 
            }
            return Response()->json(['code'=>0,'info'=>$info,'tk'=>csrf_token()]);
        }
    }

    public function fileError($error){
        switch ($error) {
            case 1:
                $info = '上传的文件超过了服务器限制的值';
                break;
            case 2:
                $info = '上传文件的大小超过了HTML表单限制的值';
                break;
            case 3:
                $info = '文件只有部分被上传';
                break;
            case 4:
                $info = '没有文件被上传';
                break;
            default:
                $info = '上传文件大小为0';
                break;
        }
        return $info;
    }

    public function changeImageSize($source,$width='150',$height='150'){
        //$source = substr($source, 1);
        //取得图片的宽度,高度值 
        $image = getimagesize($source); 
        $ename=explode('/',$image['mime']); 
        $ext=$ename[1];
        if($image[0] > $width || $image[1] > $height){
            $ratew = $width/$image[0];
            $rateh = $height/$image[1];
            $rate = $ratew > $rateh ? $ratew : $rateh;
            $dstw = floor($image[0] * $rate);
            $dsth = floor($image[1] * $rate);

            header("Content-type: image/jpg");
            switch($ext){ 
                case "png": $imgsrc=imagecreatefrompng($source); 
                            break; 
                case "jpeg":$imgsrc=imagecreatefromjpeg($source); 
                            break; 
                case "jpg": $imgsrc=imagecreatefromjpeg($source); 
                            break; 
                case "gif": $imgsrc=imagecreatefromgif($source); 
                            break; 
            }
            //$imgsrc=imagecreatefrompng($source);//PNG
            imagesavealpha($imgsrc,true);//这里很重要 意思是不要丢了$sourePic图像的透明色;
            $img = imagecreatetruecolor($width, $height); //创建一个彩色的底图 
            imagealphablending($img,false);//这里很重要,意思是不合并颜色,直接用$img图像颜色替换,包括透明色;
            imagesavealpha($img,true);//这里很重要,意思是不要丢了$thumb图像的透明色;
            imagecopyresampled($img,$imgsrc,0,0,0,0,$dstw,$dsth,$image[0], $image[1]);
            imagepng($img, $source);
            imagedestroy($img); 
        }
    }

}
