<?php

namespace App\Http\Controllers;

use App\Models\Target;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TargetController extends Controller
{
    public function flatten(array $array) {
        $return = array();
        array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });
        return $return;
    }

    public function prepareArrayToSave($arr)
    {
        $arr = array_map('array_values',$arr);
        $maxLength = 0;
        foreach($arr as $key=>$value){
            if(count($value)>$maxLength){
                $maxLength=count($value);
            }
        }
        
        $res =[];
        for($i = 0 ; $i < $maxLength ; $i++){
            $block = [];
            foreach($arr as $key=>$value){
                $block[Str::singular($key)]=$value[$i];
            }
            array_push($res,$block);
        }
        return $res;
    }

    public function store(Request $request){
        
        $targetsData = $request->only('targets' , 'months' , 'user_ids');
        $dataChanged = $this->prepareArrayToSave($targetsData);

        for($i = 0 ; $i < count($dataChanged) ;$i++){
            $userChanged = Target::where('user_id' , $dataChanged[$i]['user_id'])
            ->where('month' , $dataChanged[$i]['month'])
            ->first();
            
            if(!$userChanged){
                User::find($dataChanged[$i]['user_id'])->targets()->create($dataChanged[$i]);
            }else if($userChanged){
                Target::where('user_id' , $dataChanged[$i]['user_id'])
                ->where('month' , $dataChanged[$i]['month'])
                ->update(['target' => $dataChanged[$i]['target']]);
            }else{
                return response()->json('Not In This Month');
            }
        }
        // foreach($request->user_id as $user){
        //     User::find($user)->targets()->createMany($dataChanged);
        // }
        // $targets = [];
        // $months = [];
        // foreach($targetsData['targets'] as $tar){
        //     array_push($targets , json_decode($tar));
        // }
        // // dd($targets);

        // foreach($targetsData['months'] as $mon){
        //     array_push($months , json_decode($mon));
        // }
        // // dd($months);
        
        // $finalTarget = $this->flatten($targets);
        // $finalMonth = $this->flatten($months);
        
        
        // $collectiveData = array_combine($finalMonth , $finalTarget);
        
        // foreach($request->user_ids as $user){
        //     foreach($collectiveData as $key => $data){
        //         $target = new Target();
        //         $target->month = $key;
        //         $target->target = $data;
        //         $target->user_id = $user;
        //         $target->save();
        //     }    
        // }
    }

    
}