<?php

namespace App\Http\Controllers\Admin\Nba;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Nba\Calendar;
use App\Http\Controllers\Base\CommonController;

class CalendarController extends CommonController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $where = [];
        $search = [];
        if (strlen($time = $request->input('time')) > 0) {
           $search['time'] = $time;
           $time = strtotime($time);
           $where = [['time',$time]];
        }
        $calendar = Calendar::where($where)
                            ->orderBy('time','desc')
                            ->orderBy('created_at','desc')
                            ->paginate(10);

        return view('admin.nba.calendar.list',compact('calendar','search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $calendar = new Calendar();
        return view('admin.nba.calendar.edit',compact('calendar'));
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
            'good_content' => 'required',
            'bad_content' => 'required',
            'person_name' => 'required',
            'person_word' => 'required',
            'time' => 'required|date',

        ]);

        $calendar = new Calendar();

        $calendar->good_content = $request->good_content;
        $calendar->bad_content = $request->bad_content;
        $calendar->person_name = $request->person_name;
        $calendar->person_word = $request->person_word;
        $calendar->tip = $request->tip?$request->tip:'';
        $calendar->time = strtotime($request->time);
        $calendar->admin_id = $request->admin_id;

        if ($request->file('avatar')) {
           try {
                if (!$path = $this->uploadImageData('avatar',['png','jpeg','jpg'],'uploads/nba/calendar')) {
                   return $this->error('图片保存失败');
                }
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
            $calendar->avatar = $path;
        }

        if ($request->file('img')) {
           try {
                if (!$path = $this->uploadImageData('img',['png'],'uploads/nba/calendar')) {
                   return $this->error('图片保存失败');
                }
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
            $calendar->img = $path;
        }


        if (!$calendar->save()) {
            return $this->error('添加老黄历失败');
        }

        return $this->success('添加老黄历成功');


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $calendar = Calendar::find($id);

        if (is_null($calendar)) {
            return $this->error('获取数据失败..');
        }

        return view('admin.nba.calendar.edit',compact('calendar'));
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
        if (!intval($id)) {
            return $this->error('非法参数');
        }

        $this->validate($request,[
            'good_content' => 'required',
            'bad_content' => 'required',
            'person_name' => 'required',
            'person_word' => 'required',
            'time' => 'required',

        ]);

        $data = $request->all();
        unset($data['_method']);
        unset($data['_token']);
        unset($data['s']);
        unset($data['r']);
        if (is_null($data['tip'])) {
            $data['tip'] = '';
        }

        if ($request->file('avatar')) {
           try {
                if (!$path = $this->uploadImageData('avatar',['png','jpeg','jpg'],'uploads/nba/calendar')) {
                   return $this->error('图片保存失败');
                }
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
            $data['avatar'] = $path;
        }

        if ($request->file('img')) {
           try {
                if (!$path = $this->uploadImageData('img',['png'],'uploads/nba/calendar')) {
                   return $this->error('图片保存失败');
                }
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
            $data['img'] = $path;
        }

        $data['time'] = strtotime($request->time);

        if (!Calendar::where('id',$id)->update($data)) {
            return $this->error('修改老黄历失败');
        }

        return $this->success('修改老黄历成功');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $calendar  = new Calendar();

        return $this->doDelete($id,$calendar);
    }
}
