<x-layouts.app>

       <div class="max-w-7xl mx-auto px-6">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                タスク一覧表示
            </h2>
             @if (session('message'))
                <div class="text-red-600 font-bold">
                    {{session('message')}}
                </div>
            @endif

            <div class="mx-auto px-6">
                @foreach ($tasks as $task)
                    <div class="mt-6 p-6 bg-white rounded-2xl shadow-md border border-gray-200">
                        <p class="p-4 text-lg font-semibold">
                            タスク名：
                            <a href="{{route('task.show', $task)}}" class="text-blue-600">
                                 {{ $task->name }}
                            </a>

                        </p>
                        <hr class="w-full">
                        <p class="mt-4 p-4 text-lg ">
                            備考：
                            {{ $task->description }}
                        </p>
                        <hr class="w-full">
                         <p class="mt-4 p-4 text-lg ">
                            予算：
                            {{ $task->budget }}
                        </p>
                        <hr class="w-full">
                         <p class="mt-4 p-4 text-lg ">
                            タスク実施日：
                            {{ $task->date }}
                        </p>
                        <hr class="w-full">
                         <p class="mt-4 p-4 text-lg ">
                            繰り返しタイプ：
                            {{ $task->repeat_type }}
                        </p>
                        <hr class="w-full">
                         <p class="mt-4 p-4 text-lg ">
                            繰り返す曜日：
                            {{ $task->day_of_week }}
                        </p>
                        <hr class="w-full">
                         <p class="mt-4 p-4 text-lg ">
                            開始日：
                            {{ $task->start_date }}
                        </p>
                        <hr class="w-full">
                          <p class="mt-4 p-4 text-lg ">
                            終了日：
                            {{ $task->end_date }}
                        </p>
                        <div class="flex justify-end p-4 text-sm font-semibold">
                            <p>
                                 {{ $task->created_at }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

       </div>
</x-layouts.app>
