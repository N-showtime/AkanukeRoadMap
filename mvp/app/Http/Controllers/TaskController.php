<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::all();
        return view('task.index', compact('tasks')) ;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return view('task.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:20',
            'budget' => 'nullable|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);
        $task = Task::create([
             ...$validated,
            'description' => $request->description,
            'date' => $request->date,
            'repeat_type' => $request->repeat_type,
            'day_of_week' => is_array($request->day_of_week)? implode(',', $request->day_of_week): $request->day_of_week,
        ]);

         // Ajax (JSON) リクエストなら JSON を返す
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($task, 201, [], JSON_UNESCAPED_UNICODE);
        }

        $request->session()->flash('message', '保存しました');
        return redirect()->route('task.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return view('task.show', compact('task')) ;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        return view('task.edit', compact('task')) ;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'name' => 'required|max:20',
            'budget' => 'nullable|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);
        $task->update([
             ...$validated,
            'description' => $request->description,
            'date' => $request->date,
            'repeat_type' => $request->repeat_type,
            'day_of_week' => is_array($request->day_of_week)? implode(',', $request->day_of_week): $request->day_of_week,

        ]);
         // Ajax (JSON) リクエストなら JSON を返す
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($task, 200, [], JSON_UNESCAPED_UNICODE);
        }
        $request->session()->flash('message', '更新しました');
         return redirect()->route('calendar.index') ;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Task $task)
    {
    try {
        $task->delete();
        $request->session()->flash('message', '削除しました');

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['message' => '削除しました'], 200, [], JSON_UNESCAPED_UNICODE);
        }

        return redirect()->route('calendar.index');
    } catch (\Exception $e) {
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['error' => '削除に失敗しました'], 500, [], JSON_UNESCAPED_UNICODE);
        }

        return back()->with('message', '削除に失敗しました');
    }
}


    // カレンダーへの表示用
    public function getEvents()
    {
        // dateカラムが存在するタスクだけ取得（NULL対策）
        $tasks = Task::whereNotNull('date')->get(['id', 'name', 'date']);

        // FullCalendar用に整形
        $events = $tasks->map(function ($task) {
            return [
                'id' => $task->id,
                'title' => $task->name,  // nameカラムをタイトルに使う
                'start' => $task->date,  // 日付をstartに設定
            ];
        });

        return response()->json($events, 200, [], JSON_UNESCAPED_UNICODE);
    }
}
