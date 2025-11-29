<x-layouts.app>
    <div class="max-w-7xl mx-auto px-6">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">垢抜けタスク</h2>

        @if (session('message'))
            <div class="text-red-600 font-bold">{{ session('message') }}</div>
        @endif

        <form method="post" action="{{ route('task.store') }}" id="save-form" x-data="taskForm()">
            @csrf

            {{-- タスク名 --}}
            <div class="mt-8">
                <label for="name" class="font-semibold">タスク名</label>
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                <input type="text" name="name" class="w-full p-2 border rounded-md" id="name">
            </div>

            {{-- 備考 --}}
            <div class="mt-4">
                <label for="description" class="font-semibold">備考</label>
                <textarea name="description" class="w-full p-2 border rounded-md" id="description" rows="4"></textarea>
            </div>

            {{-- 予算 --}}
            <div class="mt-4">
                <label for="budget" class="font-semibold">予算</label>
                <input type="number" name="budget" class="w-full p-2 border rounded-md" id="budget">
            </div>

            {{-- 繰り返しタイプ --}}
            <div class="mt-6">
                <label for="repeat_type" class="font-semibold block mb-2">繰り返しタイプ</label>
                <select name="repeat_type" id="repeat_type" x-model="repeatType" class="w-full p-2 border rounded-md">
                    <option value="">繰り返しなし</option>
                    <option value="daily">毎日</option>
                    <option value="weekly">毎週</option>
                    <option value="monthly">毎月</option>
                </select>
            </div>

            {{-- タスク実行日（繰り返しなし） --}}
            <div class="mt-4" x-show="repeatType === ''">
                <label for="date" class="font-semibold">タスク実行日</label>
                <input type="text" x-ref="date" name="date" class="w-full p-2 border rounded-md" id="date">
            </div>

            {{-- 繰り返す曜日 --}}
            <div class="mt-4" x-show="repeatType === 'weekly'">
                <label class="font-semibold block mb-3">繰り返す曜日</label>
                <div class="flex flex-wrap gap-x-6 gap-y-3">
                    @foreach (['Mon'=>'月','Tue'=>'火','Wed'=>'水','Thu'=>'木','Fri'=>'金','Sat'=>'土','Sun'=>'日'] as $key=>$label)
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="day_of_week[]" value="{{ $key }}" class="rounded border-gray-300">
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- 開始日・終了日（繰り返しありの場合のみ表示） --}}
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6" x-show="repeatType !== ''">
                <div>
                    <label for="start_date" class="font-semibold">開始日</label>
                    <input type="text" x-ref="startDate" name="start_date" class="w-full p-2 border rounded-md" id="start_date">
                </div>
                <div>
                    <label for="end_date" class="font-semibold">終了日</label>
                    <input type="text" x-ref="endDate" name="end_date" class="w-full p-2 border rounded-md" id="end_date">
                </div>
            </div>

            {{-- 毎月用オプション --}}
            <div class="mt-4" x-show="repeatType === 'monthly'">
                <label class="font-semibold block mb-2">毎月の繰り返し方法</label>
                <select name="monthly_type" x-model="monthlyType" class="w-full p-2 border rounded-md">
                    <option value="date">日付指定</option>
                    <option value="weekday">曜日指定</option>
                </select>
            </div>

            {{-- 日付指定（月の何日か） --}}
            <div class="mt-2" x-show="repeatType === 'monthly' && monthlyType === 'date'">
                <label class="font-semibold">日付</label>
                <input type="number" name="monthly_date" min="1" max="31" class="w-full p-2 border rounded-md">
            </div>

            {{-- 曜日指定（第〇曜日） --}}
            <div class="mt-2" x-show="repeatType === 'monthly' && monthlyType === 'weekday'">
                <label class="font-semibold">曜日</label>
                <select name="monthly_weekday" class="w-full p-2 border rounded-md">
                    <option value="Mon">月</option>
                    <option value="Tue">火</option>
                    <option value="Wed">水</option>
                    <option value="Thu">木</option>
                    <option value="Fri">金</option>
                    <option value="Sat">土</option>
                    <option value="Sun">日</option>
                </select>

                <label class="font-semibold mt-2">第何週</label>
                <select name="monthly_week_num" class="w-full p-2 border rounded-md">
                    <option value="1">第1週</option>
                    <option value="2">第2週</option>
                    <option value="3">第3週</option>
                    <option value="4">第4週</option>
                    <option value="5">第5週</option>
                </select>
            </div>


            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded mt-6">作成する</button>
        </form>
    </div>
</x-layouts.app>

@vite(['resources/css/app.css', 'resources/js/app.js'])
{{-- <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script> --}}

<script>
    function taskForm() {
        return {
            repeatType: '',      // 繰り返しタイプ
            monthlyType: 'date', // 毎月の繰り返し方法（初期値：日付指定）
            init() {
                // 単発用 date
                this.datePicker = flatpickr(this.$refs.date, {
                    dateFormat: "Y-m-d",
                });

                // 繰り返し用 start/end
                this.startPicker = flatpickr(this.$refs.startDate, {
                    dateFormat: "Y-m-d",
                    onChange: (selectedDates, dateStr) => {
                        if (this.endPicker) {
                            this.endPicker.set('minDate', dateStr);
                        }
                    }
                });
                this.endPicker = flatpickr(this.$refs.endDate, {
                    dateFormat: "Y-m-d",
                });
            }
        }
    }
</script>

