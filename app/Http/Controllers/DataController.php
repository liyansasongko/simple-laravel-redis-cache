<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class DataController extends Controller
{
    public function index()
    {
        $data_all = Redis::get('pp_detail');
        $data = json_decode($data_all, true);
        if(!$data){
            $data = array();
        }
        $listdata = collect($data);

        return view('welcome', compact('listdata'));
    }

    public function store(Request $request)
    {
        try {
            $result = array();
            $ppdata = Redis::get('pp_detail');
            if($ppdata){
                $datappold = json_decode($ppdata, true);
                foreach($datappold as $item){
                    array_push($result, $item);
                }
            }
            
            $data = array(
                'name' => $request->name,
                'address' => $request->address,
                'phone' => $request->phone
            );
            array_push($result, $data);
            
            Redis::set('pp_detail', json_encode($result));

            return redirect()
                    ->back()
                    ->with('success', 'Add Data Successfully');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function update(Request $request)
    {
        try {
            $result = array();
            $ppdata = Redis::get('pp_detail');
            if($ppdata){
                $datappold = json_decode($ppdata, true);
                foreach($datappold as $item){
                    if($item['name'] == $request->name_old){
                        $data = array(
                            'name' => $request->name,
                            'address' => $request->address,
                            'phone' => $request->phone
                        );
                        array_push($result, $data);
                    }else{
                        array_push($result, $item);
                    }
                }
            }
            Redis::set('pp_detail', json_encode($result));
            return redirect()
                    ->back()
                    ->with('success', 'Update Data Successfully');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $result = array();
            $ppdata = Redis::get('pp_detail');
            if($ppdata){
                $datappold = json_decode($ppdata, true);
                foreach($datappold as $item){
                    if($item['name'] != $request->name){
                        array_push($result, $item);
                    }
                }
            }
            Redis::set('pp_detail', json_encode($result));
            return redirect()
                    ->back()
                    ->with('success', 'Delete Data Successfully');
        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
