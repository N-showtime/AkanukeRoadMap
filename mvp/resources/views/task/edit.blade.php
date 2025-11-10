<x-layouts.app>

    <div class="max-w-7xl mx-auto px-6">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">垢抜けタスク</h2>
        {{-- <x-message :message="session('message')" /> --}}

        @if (session('message'))
            <div class="text-red-600 font-bold">
                {{session('message')}}
            </div>
        @endif
        {{--  保存フォーム（task.store） --}}
        <form method="post" action="{{ route('task.update', $task) }}" id="save-form">
            @csrf
            @method('patch')
            <div class="mt-8">
                <div class="w-full flex flex-col">
                    <label for="name" class="font-semibold mt-4">タスク名</label>
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    <input type="text" name="name" class="w-auto p-2 border border-gray-300 rounded-md" id="name" value="{{ old('name', $task->name )}}">
                </div>

                <div class="w-full flex flex-col">
                    <label for="description" class="font-semibold mt-4">備考</label>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    <textarea name="description" class="w-auto p-2 border border-gray-300 rounded-md" id="description" cols="30" rows="8">{{ old('description', $task->description )}}</textarea>
                </div>

                <div class="w-full flex flex-col">
                    <label for="budget" class="font-semibold mt-4">予算</label>
                    <x-input-error :messages="$errors->get('number')" class="mt-2" />
                    <input type="number" name="budget" class="w-auto p-2 border border-gray-300 rounded-md" id="budget" value="{{ old('budget', $task->budget )}}">
                </div>

                <div class="w-full flex flex-col">
                    <label for="date" class="font-semibold mt-4">タスク実施日</label>
                    <x-input-error :messages="$errors->get('date')" class="mt-2" />
                    <input type="date" name="date" class="w-auto p-2 border border-gray-300 rounded-md" id="date" value="{{ old('date', $task->date )}}">
                </div>

                {{-- 繰り返しタイプ --}}
                <div class="w-full flex flex-col mt-6">
                    <label for="repeat_type" class="font-semibold block mb-2">繰り返しタイプ</label>
                    <select name="repeat_type" id="repeat_type" class="w-full p-2 border border-gray-300 rounded-md">
                        <option value="" {{ old('repeat_type', $task->repeat_type) == '' ? 'selected' : '' }}>繰り返しなし</option>
                        <option value="daily" {{ old('repeat_type', $task->repeat_type) == 'daily' ? 'selected' : '' }}>毎日</option>
                        <option value="weekly" {{ old('repeat_type', $task->repeat_type) == 'weekly' ? 'selected' : '' }}>毎週</option>
                        <option value="monthly" {{ old('repeat_type', $task->repeat_type) == 'monthly' ? 'selected' : '' }}>毎月</option>
                    </select>
                </div>


                {{-- 繰り返す曜日 --}}
                <div class="w-full flex flex-col mt-6">
                    <label class="font-semibold block mb-3">繰り返す曜日</label>
                    <div class="flex flex-wrap gap-x-6 gap-y-3">
                        @php
                            $selectedDays = old('day_of_week', explode(',', $task->day_of_week ?? ''));
                        @endphp
                        @foreach (['Mon' => '月', 'Tue' => '火', 'Wed' => '水', 'Thu' => '木', 'Fri' => '金', 'Sat' => '土', 'Sun' => '日'] as $key => $label)
                            <label class="flex items-center space-x-2 text-gray-700">
                                <input type="checkbox" name="day_of_week[]" value="{{ $key }}" class="rounded border-gray-300" {{ in_array($key, $selectedDays) ? 'checked' : '' }}>
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>


                <div class="w-full flex flex-col mt-6">
                    <label for="start_date" class="font-semibold mt-4">開始日</label>
                    {{-- <x-input-error :messages="$errors->get('title')" class="mt-2" /> --}}
                    <input type="date" name="start_date" class="w-auto p-2 border border-gray-300 rounded-md" id="start_date" value="{{ old('start_date', $task->start_date )}}">
                </div>

                <div class="w-full flex flex-col">
                    <label for="end_date" class="font-semibold mt-4">終了日</label>
                    {{-- <x-input-error :messages="$errors->get('title')" class="mt-2" /> --}}
                    <input type="date" name="end_date" class="w-auto p-2 border border-gray-300 rounded-md" id="end_date" value="{{ old('end_date', $task->end_date )}}">
                </div>

            </div>
        </form>



        {{--  送信ボタンは保存フォームと紐付け --}}
        <button type="submit" form="save-form" class="w-full bg-blue-500 text-white py-2 rounded mt-4">
            更新する
        </button>
    </div>
</x-layouts.app>
