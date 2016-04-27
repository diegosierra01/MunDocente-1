<?php

namespace MunDocente\Http\Controllers;

use Illuminate\Http\Request;

use MunDocente\Http\Requests;
use MunDocente\Http\Controllers\Controller;
use MunDocente\Publication;
use MunDocente\Area;

use DB;

class IndexController extends Controller
{
   public function index(){
        $publications = Publication::paginate(2);
        $areas = Area::all();
        return view('app', compact('publications', 'areas'));
    }

    public function teacher_call(){
    	$publications = DB::table('publications')
                                    ->where('type', '=', 1)
                                    ->paginate(5);
    	$areas = Area::all();
    	return view('teacher_call', compact('publications', 'areas'));
    }

    public function academic_event(){
    	$publications = DB::table('publications')
                                    ->where('type', '=', 3)
                                    ->paginate(5);
    	$areas = Area::all();
    	return view('academic_event', compact('publications', 'areas'));
    }

    public function scientific_magazine(){
    	$publications = DB::table('publications')
                                    ->where('type', '=', 2)
                                    ->paginate(5);
    	$areas = Area::all();
    	return view('scientific_magazine', compact('publications', 'areas'));
    }
     public function search(){
        
        return view('search');
    }
    public function record(){
        
        return view('record');
    }
}