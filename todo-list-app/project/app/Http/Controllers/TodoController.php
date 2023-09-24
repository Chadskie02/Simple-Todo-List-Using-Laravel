<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view('todo.index');
    }

    public function fetchtodo()
    {
        $todos = Todo::all();
        return response()->json([
            'todos'=>$todos,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'=> 'required|max:191',
            'title'=>'required|max:191',
            'body'=>'required|max:191',
            'category'=>'required|max:191',

            // 'email'=>'required|email|max:191',
            // 'phone'=>'required|max:12|min:12',
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status'=>400,
                'errors'=>$validator->messages()
            ]);
        }
        else
        {
            $todo = new Todo;
            $todo->user_id = $request->input('user_id');
            $todo->title = $request->input('title');
            $todo->body = $request->input('body');
            $todo->category = $request->input('category');
            $todo->save();
            return response()->json([
                'status'=>200,
                'message'=>'Todo Added Successfully.'
            ]);
        }

    }

    public function edit($id)
    {
        $todo = Todo::find($id);
        if($todo)
        {
            return response()->json([
                'status'=>200,
                'todo'=> $todo,
            ]);
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'No Todo Found.'
            ]);
        }

    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id'=> 'required|max:191',
            'title'=>'required|max:191',
            'body'=>'required|max:191',
            'category'=>'required|max:191',
            // 'category'=>'required|max:12|min:12',
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status'=>400,
                'errors'=>$validator->messages()
            ]);
        }
        else
        {
            $todo = Todo::find($id);
            if($todo)
            {
                $todo->user_id = $request->input('user_id');
                $todo->title = $request->input('title');
                $todo->body = $request->input('body');
                $todo->category = $request->input('category');
                $todo->update();
                return response()->json([
                    'status'=>200,
                    'message'=>'Todo Updated Successfully.'
                ]);
            }
            else
            {
                return response()->json([
                    'status'=>404,
                    'message'=>'No Todo Found.'
                ]);
            }

        }
    }

    public function destroy($id)
    {
        $todo = Todo::find($id);
        if($todo)
        {
            $todo->delete();
            return response()->json([
                'status'=>200,
                'message'=>'Todo Deleted Successfully.'
            ]);
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'No Todo Found.'
            ]);
        }
    }
}
