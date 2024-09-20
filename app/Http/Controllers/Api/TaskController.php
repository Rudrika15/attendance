<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Version;
use Carbon\Carbon;
use COM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function addTask(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'task' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 200);
        }
        $userId =  Auth::user()->id;
        $task = $request->task;


        $findTask = Task::where('userId', $userId)->whereDate('created_at', date('Y-m-d'))->orderBy('id', 'desc')->first();
        if (!$findTask) {

            $Task = new Task();
            $Task->userId = $userId;
            $Task->task = $task;
            $Task->save();

            return response()->json([
                'status' => true,
                'message' => 'Task Added successfully',
                'data' => $Task
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Task Already Added',
            ]);
        }
    }
    public function myTask()
    {
        $userId =  Auth::user()->id;
        $task = Task::where('userId', $userId)->orderBy('id', 'desc')->get();
        return response()->json([
            'status' => true,
            'message' => 'Get Task successfully',
            'data' => $task
        ], 200);
    }
    public function deleteTask(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 200);
        }
        $task = Task::find($request->id);
        
        if ($task->created_at->format('Y-m-d') == date('Y-m-d')) {


            $task->delete();

            return response()->json([
                'status' => true,
                'message' => 'Task Deleted successfully',
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'you can not delete previous task',
            ]);
        }
    }
    public function showTask(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 200);
        }
        $task = Task::find($request->id);
        return response()->json([
            'status' => true,
            'message' => 'Task List successfully',
            'data' => $task
        ], 200);
    }
    public function updateTask(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'task' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 200);
        }

        $task = Task::find($request->id);
       
        if ($task->created_at->format('Y-m-d') == date('Y-m-d')) {

            $task->task = $request->task;
            $task->save();
            return response()->json([
                'status' => true,
                'message' => 'Task Updated successfully',
                'data' => $task
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'you can only update today task',
            ]);
        }
    }
    public function allTask(Request $request)
    {
        $date = $request->date;
        $name = $request->name;

        $query = Task::orderBy('id', 'desc')->with('taskWithUser');

        if ($name) {
            $query->whereHas('taskWithUser', function ($q) use ($name) {
                $q->where('name', 'like', '%' . $name . '%');
            });
        }

        if ($date) {
            $query->whereDate('created_at', $date);
        }

        $task = $query->get();

        return response()->json([
            'status' => true,
            'message' => 'Task List successfully',
            'data' => $task
        ], 200);
    }
    public function version()
    {
        $version = Version::orderBy('id', 'desc')->first();

        return response()->json([
            'status' => true,
            'message' => 'Version List successfully',
            'data' => $version
        ], 200);
    }
}
