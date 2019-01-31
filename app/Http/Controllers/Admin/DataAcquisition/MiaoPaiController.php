<?php

namespace App\Http\Controllers\Admin\DataAcquisition;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MiaoPaiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $url = 'http://www.meipai.com/square/13';

        // $arr = $this->doCurlGetRequest($url);
        // dd(json_decode($arr,true));
        $content = file_get_contents($url);
        dd($this->extracturl($content));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function doCurlGetRequest($url,$timeout = 5){
        if($url == "" || $timeout <= 0){
            return false;
        }
        $con = curl_init((string)$url);
        curl_setopt($con, CURLOPT_HEADER, false);
        curl_setopt($con, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($con, CURLOPT_TIMEOUT, (int)$timeout);
        
        return curl_exec($con);
    }

    public function extracturl($page) {
        $matches = array();
        $voide=array();
        $mainurl="";
        $list=array();
        $j=0;
        $pat = "/<li class=\"pr no-select loading  J_media_list_item\".*?>.*?<\/li>/ism";

        preg_match_all($pat, $page, $matches, PREG_PATTERN_ORDER);
        // return $matches;
        for ($i=0; $i <count($matches[0]) ; $i++) { 
            $pat1 ="/data-video=\"(.*?)\"/ism";
            preg_match_all($pat1, $matches[0][$i], $voide, PREG_PATTERN_ORDER);

            $myvoide=$voide[1][0];
            $pat2 ="/src=\"(.*?)\"/ism";
            preg_match_all($pat2, $matches[0][$i], $img, PREG_PATTERN_ORDER);
            $myimg=$img[1][0];
             $pat3 ="/<strong class=\"js-convert-emoji\".*?>(.*?)<\/strong>/ism";
            preg_match_all($pat3, $matches[0][$i], $title, PREG_PATTERN_ORDER);
            $mytitle= $title[1][0];
            $list[$j++]=array(
                'voide'=>$myvoide,
                'title'=>$mytitle,
                'img'=>$myimg);
            
        }
        return $list;
    }


}
