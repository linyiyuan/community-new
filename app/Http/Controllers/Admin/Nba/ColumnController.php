<?php

namespace App\Http\Controllers\Admin\Nba;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Base\CommonController;
use App\Http\Controllers\Admin\Nba\ColumnDataController;
use App\Models\Nba\Column;
use Route;

class ColumnController extends CommonController
{
    /**
     *  首页栏目列表页
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $column = Column::orderBy('sort','asc')->paginate(10);

        $columnData = new ColumnDataController();
        
        $dataName = $columnData->getDataName();//拿到栏目类型所对应的标签/集合

        return view('admin.nba.column.list',compact('column','dataName'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $column = new Column();

        return view('admin.nba.column.edit',compact('column'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'type' => 'required|integer',
            'data_id' => 'required|integer',
            'column_name' => 'required',
        ]);

        $column = new Column();

        $column->column_name = base64_encode($request->column_name);
        $column->sort = $request->sort?$request->sort:0;
        $column->data_id = $request->data_id;
        $column->type = $request->type;

        if(!$column->save()){
            return $this->error('添加栏目失败');
        }
        //清除缓存
        $redis_key = 'nba_column_list';
        $redis = Redis::connection('ddz_web_m');
        $redis->del($redis_key);
        return $this->success('添加栏目成功');

       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        $model = new Column();

        $is_show = $request->is_show;

        //清除缓存
        $redis_key = 'nba_column_list';
        $redis = Redis::connection('ddz_web_m');
        $redis->del($redis_key);
        return $this->changeIsShow($model,$id,$is_show);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!intval($id)) {
            return $this->error('非法参数');
        }

        if (is_null($column = Column::find($id))) {
            return $this->error('获取对象数据失败');
        }
        
        return view('admin.nba.column.edit',compact('column'));


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!intval($id) && is_null(Column::find($id)) ) {
            return $this->error('非法参数/获取对象数据失败');
        }

        $this->validate($request,[
            'type' => 'required|integer',
            'data_id' => 'required|integer',
            'column_name' => 'required',
        ]);

        $column = Column::find($id);

        // dd($request->data_id);
        $column->column_name = base64_encode($request->column_name);
        $column->sort = $request->sort?$request->sort:0;
        $column->data_id = $request->data_id;
        $column->type = $request->type;

        if(!$column->save()){
            return $this->error('修改栏目失败');
        }

        //清除缓存
        $redis_key = 'nba_column_list';
        $redis = Redis::connection('ddz_web_m');
        $redis->del($redis_key);
        return $this->success('修改栏目成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = new Column();

        //清除缓存
        $redis_key = 'nba_column_list';
        $redis = Redis::connection('ddz_web_m');
        $redis->del($redis_key);
        return $this->doDelete($id,$model);
    }

    public function getData()
    {
        
    }

   
}
