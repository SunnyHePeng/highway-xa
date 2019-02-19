<?php

namespace App\Http\Controllers\Smog;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Smog\Video;
use App\Traits\UploadFile;

/**
 * 治污减霾施工相关视频
 */
class VideoController extends Controller
{
    use UploadFile;

    /**
     * 上传文件类型
     * 
     * @var array
     */
    private $_extensions = ['flv', 'mp4'];

    /**
     * 上传文件大小byte
     * 
     * @var int
     */
    private $_max_size = 1024 * 1024 * 500;

    /**
     * 视频列表
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $videos = Video::latest()->paginate();

        return view('smog.video.index', compact('videos'));
    }

    /**
     * 创建视频
     * 
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('smog.video.add');
    }

    /**
     * 文件上传
     * 
     * @return \Illuminate\Http\Response
     */
    public function upload()
    {
        if($path = $this->_upload('file', 'videos'))
            return ['code'=>0,'info'=> $path];

        return ['code'=>1,'info'=> '', 'message' => request()->file('file')-> getError(), 'mime' => request()->file('file')-> guessExtension()];
    }
    
    /**
     * 保存
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$request->has(['title', 'file']))
            return ['status' => 1, 'info' => '数据填写不完整！'];

        Video::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'path' => $request->input('file')
            ]);

        return ['status' => 0, 'info' => '添加成功']; 
    }
    
    /**
     * 详情
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $video = Video::findOrFail($id);

        return view('smog.video.detail', compact('video'));
    }

    /**
     * 删除
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $count = Video::destroy($id);

        if(!$count) return response('未删除任何视频', 404);

        return ['status' => 0, 'info' => '删除成功'];
    }    

}
