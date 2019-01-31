<?php

namespace App\Http\Controllers\Admin\Nba;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Base\CommonController;
use App\Models\Nba\MangaTag;
use App\Models\Nba\VideoTag;
use App\Models\Nba\VideoList;

class ColumnDataController extends CommonController
{

    public function getData(Request $request)
    {
    	if ($request->isMethod('post')) {

    		   //1.代表视频集合
    		   //2:代表视频标签
    		   //3:代表漫画标签
    		   $types = ['1','2','3'];

			   if (!intval($type=$request->type)) {
	    			return $this->ajaxResponse('500','非法参数');
	    	   }

	    	   if (in_array($type,$types)) {
	    	   		if ($type == 1) {
	    	   			return  $this->ajaxResponse('200',$this->getVideoList());
	    	   		}else if($type == 2){
	    	   			return  $this->ajaxResponse('200',$this->getVideoTag());
	    	   		}else if($type == 3){
	    	   			return  $this->ajaxResponse('200',$this->getMangaTag());
	    	   		}
	    	   }

	    	 return $this->ajaxResponse('500','获取数据失败，没有该类型');
	    	
		}else{
			return $this->ajaxResponse('500','The method not allow');
		}
    	

    }

     /**
     * @Author    linyiyuan
     * @DateTime  2018-04-12
     * 获取漫画标签
     */
    protected function getMangaTag()
    {
        $mangaTag = MangaTag::select('id','name')
                    ->get();
        $mangaTag = $this->toArray($mangaTag);

        $mangaTag = array_column($mangaTag, 'name','id');

        $mangaTag = array_map(function($val){ return base64_decode($val);},$mangaTag);

        return $mangaTag;
    }

      /**
     * @Author    linyiyuan
     * @DateTime  2018-04-09
     * @copyright 获取视频标签
     */
    protected function getVideoTag()
    {
        $videoTag = VideoTag::select('id','name')->get();

        $videoTag = $this->toArray($videoTag);

        $videoTag = array_column($videoTag, 'name','id');

        $videoTag = array_map(function($val){ return base64_decode($val);},$videoTag );

        return $videoTag;
    }

    /**
     * @Author    linyiyuan
     * @DateTime  2018-04-09
     * @copyright 获取视频集合
     */
    protected function getVideoList()
    {
        $videoList = VideoList::select('id','list_name')->get();

        $videoList = $this->toArray($videoList);

        $videoList = array_column($videoList, 'list_name','id');


        return $videoList;
    }

    /**
     * @Author    linyiyuan
     * @DateTime  2018-04-18
     * @将栏目三种类型和其所属标签合成一个数组
     */
    public function getDataName()
    {
        $dataName['1'] = $this->getVideoList();
        $dataName['2'] = $this->getVideoTag();
        $dataName['3'] = $this->getMangaTag();

        return $dataName;
    }


}
