
@extends('layouts.app')

@section('title', '患者情報編集 | Reha Management')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-800">患者情報編集</h2>
        <p class="text-sm text-slate-500 mt-2">
            登録されている患者情報を変更します。
        </p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8">

        <form action="{{ route('patients.update', $patient) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">

                <div>
                    <label class="block font-semibold text-slate-700 mb-2">氏名</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $patient->name) }}"
                        class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                    >
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-2">生年月日</label>
                    <input
                        type="date"
                        name="birth_date"
                        value="{{ old('birth_date', $patient->birth_date) }}"
                        class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                    >
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-2">診断名</label>
                    <input
                        type="text"
                        name="diagnosis"
                        value="{{ old('diagnosis', $patient->diagnosis) }}"
                        class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                    >
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-2">既往歴</label>
                    <textarea
                        name="medical_history"
                        rows="4"
                        class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                    >{{ old('medical_history', $patient->medical_history) }}</textarea>
                </div>

            </div>

            <div class="flex justify-end gap-3 mt-8">

                <a href="{{ route('patients.show', $patient) }}"
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
