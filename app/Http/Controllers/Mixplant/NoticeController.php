<?php

namespace App\Http\Controllers\Mixplant;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Bhz\MessageUser;
Use App\Models\User\User;

/**
 * 上传超时通知
 */
class NoticeController extends Controller
{
    /**
     * 通知列表
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = MessageUser::with('user.roled', 'user.posi', 'user.section', 'user.company')->paginate();

        return view('mixplant.notice.index', compact('users'));
    }

    /**
     * 添加通知人员
     * 
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::with('roled', 'posi', 'section', 'mixplantMessageUser')->get();

        return view('mixplant.notice.create', compact('users'));
    }

    /**
     * 保存通知人员
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, MessageUser $user)
    {
        if( $user->sync($request->get('notice', []) ) )
                return [ 'status' => 1, 'info' => '设置成功'];

        return ['status' => 0, 'info' => '设置失败'];
    }

    /**
     * 删除通知人员
     * @param MessageUser $messageUser
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(MessageUser $messageUser,$id)
    {
        try{
            $messageUser->where('id',$id)->delete();
            $result['status']=1;
            $result['id']=$id;
            $result['info']='删除成功';
            return Response()->json($result);
        }catch (\Exception $e){
            $result['status']=0;
            $result['info']='删除失败';
            return Response()->json($result);
        }
    }


    /**
     * 消息记录
     * 
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('mixplant.notice.detail');
    }
}
