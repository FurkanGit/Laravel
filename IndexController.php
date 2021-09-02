<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Record;

class IndexController extends Controller
{
    public function index(){

        $data = Record::paginate(5);
        return view('index',['collection'=>$data])->with('i', (request()->input('page', 1) - 1) * 5);
          
    }


    public function create(){

        return view('create');
    }

    public function store(Request $request){
        $users=Record::where('title',  $request->title)->first();
        if ($users == null) {
            $data=new Record;
            $data->title=$request->title;
            $data->description=$request->description;
            $data->save();
            $request->session()->flash('msg','Record Inserted Successfully');
            session()->flash('alert-class', 'success');
            return redirect('/index');
        }else{
            session()->flash('msg',"This Record  $request->title Already Exist.");
            return redirect('/index');
        }
    }

    public function show($id)
    {
        $data= Record::where('id',$id)->first();
        return view('show',compact('data'));
    }

    public function edit($id)
    {
        $data= Record::where('id',$id)->first();
        return view('edit',compact('data'));
    }

    public function update(Request $request, $id){

        $data=Record::where('id', $id)->first();
        $data->title=$request->title;
        $data->description=$request->description;
        $data->save();
        $request->session()->flash('msg','Record Updated Successfully');
        session()->flash('alert-class', 'success');
        return redirect('/index');
    }

    public function delete($id){

        $data=Record::find($id);
        $data->delete();
        session()->flash('msg','Record Deleted Successfully');
        return redirect('/index');
 
 
 
    }
    public function search(Request $request){

        $search = $request->get('search');
        $data=Record::where('description', 'like', '%'.$search.'%')->paginate(5);
        return view('index',['collection'=>$data])->with('i', (request()->input('page', 1) - 1) * 5);

    }
}