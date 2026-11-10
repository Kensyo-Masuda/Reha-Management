
@extends('layouts.app')

@section('title', '機能評価の編集 | Reha Management')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-800">
            機能評価の編集
        </h2>
        <p class="text-sm text-slate-500 mt-2">
            {{ $patient->name }} さんの機能評価を編集します。
        </p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8">

        <form action="{{ route('evaluations.update', [$patient, $evaluation]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">

                <div>
                    <label class="block font-semibold text-slate-700 mb-2">
                        評価日
                    </label>
                    <input
                        type="date"
                        name="evaluated_at"
                        value="{{ old('evaluated_at', $evaluation->evaluated_at) }}"
                        class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                    >
                    @error('evaluated_at')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-2">
                        評価種別
                    </label>
                    <select
                        name="evaluation_type"
                        class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                    >
                        <option value="">選択してください</option>

                        @foreach (['ROM', 'MMT', 'BBS', 'その他'] as $type)
                            <option value="{{ $type }}"
                                @selected(old('evaluation_type', $evaluation->evaluation_type) == $type)>
                                {{ $type }}
                            </option>
                        @endforeach

                    </select>
                    @error('evaluation_type')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-2">
                        評価結果
                    </label>
                    <textarea
                        name="result"
                        rows="5"
                        class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                    >{{ old('result', $evaluation->result) }}</textarea>
                    @error('result')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-2">
                        備考
                    </label>
                    <textarea
                        name="remarks"
                        rows="4"
                        class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                    >{{ old('remarks', $evaluation->remarks) }}</textarea>
                    @error('remarks')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="flex justify-end gap-3 mt-8">

                <a href="{{ route('evaluations.index', $patient) }}"
                   class="border border-slate-300 hover:bg-slate-100 px-5 py-3 rounded-lg">
                    戻る
                </a>

                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold"
                >
                    更新する
                </button>

            </div>

        </form>

    </div>

</div>

@endsection
