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
        // バリデーションルール
        $rules = [
            'name' => 'required|max:20',
            'budget' => 'nullable|integer',
            'description' => 'nullable|string',
            'repeat_type' => 'nullable|string|in:none,daily,weekly,monthly',
            'date' => 'nullable|date',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'day_of_week' => 'nullable|array',
            'monthly_type' => 'nullable|string|in:date,weekday',
            'monthly_date' => 'nullable|integer|min:1|max:31',
            'monthly_weekday' => 'nullable|string|in:Mon,Tue,Wed,Thu,Fri,Sat,Sun',
            'monthly_week_num' => 'nullable|integer|min:1|max:5',
        ];

        // repeat_type が "none" の場合は date を必須
        if ($request->repeat_type === 'none' || $request->repeat_type === '') {
            $rules['date'] = 'required|date';
        }

        $validated = $request->validate($rules);

        $task = new Task();
        $task->name = $validated['name'];
        $task->budget = $validated['budget'] ?? null;
        $task->description = $validated['description'] ?? null;
        $task->repeat_type = $validated['repeat_type'] ?? '';

        // 単発タスク
        if ($task->repeat_type === 'none' || $task->repeat_type === '') {
            $task->date = $validated['date'];
        }

        // 繰り返しタスク
        if (in_array($task->repeat_type, ['daily', 'weekly', 'monthly'])) {
            $task->start_date = $validated['start_date'] ?? null;
            $task->end_date = $validated['end_date'] ?? null;
        }

        // weekly タスク
        if ($task->repeat_type === 'weekly') {
            $task->day_of_week = isset($validated['day_of_week'])
                ? json_encode($validated['day_of_week'])
                : null;
        }

        // monthly タスク
        if ($task->repeat_type === 'monthly') {
            $task->monthly_type = $validated['monthly_type'] ?? 'date';

            if ($task->monthly_type === 'date') {
                $task->monthly_date = $validated['monthly_date'] ?? null;
            } elseif ($task->monthly_type === 'weekday') {
                $task->monthly_weekday = $validated['monthly_weekday'] ?? null;
                $task->monthly_week_num = $validated['monthly_week_num'] ?? null;
            }
        }

        $task->save();

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
        // バリデーションルールF
        $rules = [
            'name' => 'required|max:20',
            'budget' => 'nullable|integer',
            'description' => 'nullable|string',
            'repeat_type' => 'nullable|string|in:none,daily,weekly,monthly',
            'date' => 'nullable|date',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'day_of_week' => 'nullable|array',
            'monthly_type' => 'nullable|string|in:date,weekday',
            'monthly_date' => 'nullable|integer|min:1|max:31',
            'monthly_weekday' => 'nullable|string|in:Mon,Tue,Wed,Thu,Fri,Sat,Sun',
            'monthly_week_num' => 'nullable|integer|min:1|max:5',
        ];

        // repeat_type が 'none' または空文字の場合は date を必須
        if ($request->repeat_type === 'none' || $request->repeat_type === '') {
            $rules['date'] = 'required|date';
        }

        $validated = $request->validate($rules);

        // 基本情報を更新
        $task->name = $validated['name'];
        $task->budget = $validated['budget'] ?? null;
        $task->description = $validated['description'] ?? null;
        $task->repeat_type = $validated['repeat_type'] ?? '';

        // 単発タスク
        if ($task->repeat_type === 'none' || $task->repeat_type === '') {
            $task->date = $validated['date'];
        } else {
            $task->date = null; // 単発日付はリセット
        }

        // 繰り返しタスク
        if (in_array($task->repeat_type, ['daily', 'weekly', 'monthly'])) {
            $task->start_date = $validated['start_date'] ?? null;
            $task->end_date = $validated['end_date'] ?? null;
        } else {
            $task->start_date = null;
            $task->end_date = null;
        }

        // weekly タスク
        if ($task->repeat_type === 'weekly') {
            $task->day_of_week = isset($validated['day_of_week'])
                ? json_encode($validated['day_of_week'])
                : null;
        } else {
            $task->day_of_week = null;
        }

        // monthly タスク
        if ($task->repeat_type === 'monthly') {
            $task->monthly_type = $validated['monthly_type'] ?? 'date';

            if ($task->monthly_type === 'date') {
                $task->monthly_date = $validated['monthly_date'] ?? null;
                $task->monthly_weekday = null;
                $task->monthly_week_num = null;
            } elseif ($task->monthly_type === 'weekday') {
                $task->monthly_weekday = $validated['monthly_weekday'] ?? null;
                $task->monthly_week_num = $validated['monthly_week_num'] ?? null;
                $task->monthly_date = null;
            }
        } else {
            $task->monthly_type = null;
            $task->monthly_date = null;
            $task->monthly_weekday = null;
            $task->monthly_week_num = null;
        }

        $task->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($task, 200, [], JSON_UNESCAPED_UNICODE);
        }

        $request->session()->flash('message', '更新しました');
        return redirect()->route('calendar.index');
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
    public function getEvents(Request $request)
    {
        $start = $request->start ? \Carbon\Carbon::parse($request->start) : now()->startOfMonth();
        $end   = $request->end   ? \Carbon\Carbon::parse($request->end)   : now()->endOfMonth();

        $tasks = Task::all();

        $events = collect();

        foreach ($tasks as $task) {
            switch ($task->repeat_type) {
                case '':
                case 'none':
                    if ($task->date && \Carbon\Carbon::parse($task->date)->between($start, $end)) {
                        $events->push([
                            'id' => $task->id,
                            'title' => $task->name,
                            'start' => $task->date,
                        ]);
                    }
                    break;

                case 'daily':
                    if ($task->start_date && $task->end_date) {
                        $period = new \DatePeriod(
                            new \DateTime($task->start_date),
                            new \DateInterval('P1D'),
                            (new \DateTime($task->end_date))->modify('+1 day')
                        );
                        foreach ($period as $date) {
                            if ($date >= $start && $date <= $end) {
                                $events->push([
                                    'id' => $task->id,
                                    'title' => $task->name,
                                    'start' => $date->format('Y-m-d'),
                                ]);
                            }
                        }
                    }
                    break;

                case 'weekly':
                    if ($task->start_date && $task->end_date && $task->day_of_week) {
                        $days = json_decode($task->day_of_week);
                        $period = new \DatePeriod(
                            new \DateTime($task->start_date),
                            new \DateInterval('P1D'),
                            (new \DateTime($task->end_date))->modify('+1 day')
                        );
                        foreach ($period as $date) {
                            if (in_array($date->format('D'), $days) && $date >= $start && $date <= $end) {
                                $events->push([
                                    'id' => $task->id,
                                    'title' => $task->name,
                                    'start' => $date->format('Y-m-d'),
                                ]);
                            }
                        }
                    }
                    break;

                case 'monthly':
                    if ($task->start_date && $task->end_date) {
                        $period = new \DatePeriod(
                            new \DateTime($task->start_date),
                            new \DateInterval('P1D'),
                            (new \DateTime($task->end_date))->modify('+1 day')
                        );

                        foreach ($period as $date) {

                            // まず DateTime → Carbon へ統一
                            $carbon = \Carbon\Carbon::instance($date);

                            // 範囲外なら次へ
                            if ($carbon->lt($start) || $carbon->gt($end)) {
                                continue;
                            }

                            // ---- 日付指定（月の何日） ----
                            if ($task->monthly_type === 'date') {

                                if ($task->monthly_date == $carbon->format('j')) {
                                    $events->push([
                                        'id'    => $task->id,
                                        'title' => $task->name,
                                        'start' => $carbon->format('Y-m-d'),
                                    ]);
                                }

                                continue;
                            }

                            // ---- 曜日指定（第〇曜日） ----
                            if ($task->monthly_type === 'weekday' && $task->monthly_weekday && $task->monthly_week_num) {

                                // まず「指定の曜日か？」
                                if ($carbon->format('D') !== $task->monthly_weekday) {
                                    continue;
                                }

                                // 月の指定曜日一覧作成
                                $monthStart = $carbon->copy()->startOfMonth();
                                $monthEnd   = $carbon->copy()->endOfMonth();
                                $weekDay    = $task->monthly_weekday;

                                $weekdayDates = [];
                                $d = $monthStart->copy();

                                while ($d <= $monthEnd) {
                                    if ($d->format('D') === $weekDay) {
                                        $weekdayDates[] = $d->copy();
                                    }
                                    $d->addDay();
                                }

                                // 第 N 曜日に該当するか？
                                $targetIndex = $task->monthly_week_num - 1;

                                if (isset($weekdayDates[$targetIndex]) && $weekdayDates[$targetIndex]->isSameDay($carbon)) {
                                    $events->push([
                                        'id'    => $task->id,
                                        'title' => $task->name,
                                        'start' => $carbon->format('Y-m-d'),
                                    ]);
                                }
                            }
                        }
                    }
    break;

            }
        }

        return response()->json($events->values(), 200, [], JSON_UNESCAPED_UNICODE);
    }



}
