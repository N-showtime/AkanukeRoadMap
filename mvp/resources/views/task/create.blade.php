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
        <form method="post" action="{{ route('task.store') }}" id="save-form">
            @csrf
            <div class="mt-8">
                <div class="w-full flex flex-col">
                    <label for="name" class="font-semibold mt-4">タスク名</label>
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    <input type="text" name="name" class="w-auto p-2 border border-gray-300 rounded-md" id="name">
                </div>

                <div class="w-full flex flex-col">
                    <label for="description" class="font-semibold mt-4">備考</label>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    <textarea name="description" class="w-auto p-2 border border-gray-300 rounded-md" id="description" cols="30" rows="8"></textarea>
                </div>

                <div class="w-full flex flex-col">
                    <label for="budget" class="font-semibold mt-4">予算</label>
                    <x-input-error :messages="$errors->get('number')" class="mt-2" />
                    <input type="number" name="budget" class="w-auto p-2 border border-gray-300 rounded-md" id="budget">
                </div>

                <div class="w-full flex flex-col">
                    <label for="date" class="font-semibold mt-4">タスク実施日</label>
                    <x-input-error :messages="$errors->get('date')" class="mt-2" />
                    <input type="date" name="date" class="w-auto p-2 border border-gray-300 rounded-md" id="date">
                </div>

                {{-- 繰り返しタイプ --}}
                <div class="w-full flex flex-col mt-6">
                    <label for="repeat_type" class="font-semibold block mb-2">繰り返しタイプ</label>
                    <select name="repeat_type" id="repeat_type" class="w-full p-2 border border-gray-300 rounded-md">
                        <option value="">繰り返しなし</option>
                        <option value="daily">毎日</option>
                        <option value="weekly">毎週</option>
                        <option value="monthly">毎月</option>
                    </select>
                </div>

                {{-- 繰り返す曜日 --}}
                <div class="w-full flex flex-col mt-6">
                    <label class="font-semibold block mb-3">繰り返す曜日</label>
                    <div class="flex flex-wrap gap-x-6 gap-y-3">
                        @foreach (['Mon' => '月', 'Tue' => '火', 'Wed' => '水', 'Thu' => '木', 'Fri' => '金', 'Sat' => '土', 'Sun' => '日'] as $key => $label)
                            <label class="flex items-center space-x-2 text-gray-700">
                                <input type="checkbox" name="day_of_week[]" value="{{ $key }}" class="rounded border-gray-300">
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>


                <div class="w-full flex flex-col mt-6">
                    <label for="start_date" class="font-semibold mt-4">開始日</label>
                    {{-- <x-input-error :messages="$errors->get('title')" class="mt-2" /> --}}
                    <input type="date" name="start_date" class="w-auto p-2 border border-gray-300 rounded-md" id="start_date">
                </div>

                <div class="w-full flex flex-col">
                    <label for="end_date" class="font-semibold mt-4">終了日</label>
                    {{-- <x-input-error :messages="$errors->get('title')" class="mt-2" /> --}}
                    <input type="date" name="end_date" class="w-auto p-2 border border-gray-300 rounded-md" id="end_date">
                </div>

                @error('end_date')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror

            </div>
        </form>



        {{--  送信ボタンは保存フォームと紐付け --}}
        <button type="submit" form="save-form" class="w-full bg-blue-500 text-white py-2 rounded mt-4">
            作成する
        </button>
    </div>
</x-layouts.app>

@vite(['resources/js/app.js'])

<script>
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("save-form");

    form.addEventListener("submit", async (e) => {
        e.preventDefault(); // ページリロード防止

        const formData = new FormData(form);

        try {
            const response = await fetch("{{ route('task.store') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json",
                },
                body: formData,
            });

            if (!response.ok) {
                const err = await response.json();
                console.error(err);
                alert("保存に失敗しました。");
                return;
            }

            const data = await response.json();
            alert(`「${data.name}」を登録しました！`);

            // // フォームをリセット
            // form.reset();

            // // カレンダーを再読み込み
            // if (window.calendar) {
            //     window.calendar.refetchEvents();
            // }

            // ✅ カレンダー画面（/calendar）に移動
            window.location.href = "{{ route('calendar.index') }}";

        } catch (error) {
            console.error(error);
            alert("通信エラーが発生しました。");
        }
    });
});
</script>

