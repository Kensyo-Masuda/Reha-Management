
@extends('layouts.app')

@section('title', 'リハビリ実施記録の登録 | Reha Management')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-800">
            リハビリ実施記録の登録
        </h2>
        <p class="text-sm text-slate-500 mt-2">
            {{ $patient->name }} さんの実施記録を登録します。
        </p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8">

        <form action="{{ route('rehabilitation_records.store', $patient) }}" method="POST">
            @csrf

            <div class="space-y-6">

                <div>
                    <label class="block font-semibold text-slate-700 mb-2">
                        実施日
                    </label>
                    <input
                        type="date"
                        name="performed_at"
                        value="{{ old('performed_at') }}"
                        class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                    >
                    @error('performed_at')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-2">
                        担当者
                    </label>
                    <input
                        type="text"
                        name="therapist"
                        value="{{ old('therapist') }}"
                        placeholder="担当者名を入力"
                        class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                    >
                    @error('therapist')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-2">
                        実施内容
                    </label>
                    <textarea
                        name="content"
                        rows="5"
                        placeholder="リハビリの実施内容を入力"
                        class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                    >{{ old('content') }}</textarea>
                    @error('content')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-2">
                        実施時間（分）
                    </label>
                    <input
                        type="number"
                        name="duration"
                        value="{{ old('duration') }}"
                        placeholder="例：20"
                        class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                    >
                    @error('duration')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="flex justify-end gap-3 mt-8">

                <a href="{{ route('rehabilitation_records.index', $patient) }}"
                   class="border border-slate-300 hover:bg-slate-100 px-5 py-3 rounded-lg">
                    戻る
                </a>

                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold"
                >
                    登録する
                </button>

            </div>

        </form>

    </div>

</div>

@endsection
