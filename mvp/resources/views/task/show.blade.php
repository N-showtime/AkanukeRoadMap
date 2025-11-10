<x-layouts.app>

       <div class="max-w-7xl mx-auto px-6">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                タスク個別表示
            </h2>
            @if (session('message'))
                <div class="text-red-600 font-bold">
                     {{ session('message') }}
                </div>
            @endif

        <div class="mt-6 p-6 bg-white rounded-2xl shadow-md border border-gray-200">
                <div class="mt-4 p-4">
                    <p class="text-lg font-semibold">
                        {{ $task->name }}
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
         </div>

        <div class="flex justify-end space-x-2 mt-5">
            <a href=" {{ route('task.edit', $task) }}">
                <flux:button variant="primary" class="cursor-pointer">
                    編集
                </flux:button>
            </a>
            <form method="post" action="{{ route('task.destroy', $task)}}">
                @csrf
                @method('delete')
                    <flux:button variant="danger" type="submit" class="cursor-pointer">
                    削除
                </flux:button>
            </form>
        </div>
    </div>
</x-layouts.app>
