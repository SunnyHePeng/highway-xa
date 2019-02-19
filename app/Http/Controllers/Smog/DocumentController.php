<?php

namespace App\Http\Controllers\Smog;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Smog\Document;
use App\Traits\UploadFile;

/**
 * 治污减霾施工相关文档
 */
class DocumentController extends Controller
{
    use UploadFile;

    /**
     * 上传文件类型
     * 
     * @var array
     */
    private $_extensions = ['pdf'];

    /**
     * 上传文件大小byte
     * 
     * @var int
     */
    private $_max_size = 1024 * 1024 * 5;

    /**
     * 文档列表
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $documents = Document::latest()->paginate();

        return view('smog.document.index', compact('documents'));
    }

    /**
     * 创建文档
     * 
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('smog.document.add');
    }

    /**
     * 文件上传
     * 
     * @return \Illuminate\Http\Response
     */
    public function upload()
    {
        if($path = $this->_upload('file', 'documents'))
            return ['code'=>0,'info'=> $path];

        return ['code'=>1,'info'=> ''];
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

        Document::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'path' => $request->input('file')
            ]);

        return ['status' => 0, 'info' => '添加成功']; 
    }

    /**
     * 删除
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $count = Document::destroy($id);

        if(!$count) return response('未删除任何文档', 404);

        return ['status' => 0, 'info' => '删除成功'];
    }
}
