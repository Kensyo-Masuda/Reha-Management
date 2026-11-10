
@extends('layouts.app')

@section('title', '患者登録 | Reha Management')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-800">患者登録</h2>
        <p class="text-sm text-slate-500 mt-2">
            新しい患者情報を登録します。
        </p>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">
            <p class="font-semibold mb-2">入力内容に問題があります。</p>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8">

        <form action="{{ route('patients.store') }}" method="POST">
            @csrf

            <div class="space-y-6">

                <div>
                    <label class="block font-semibold text-slate-700 mb-2">氏名</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                    >
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-2">生年月日</label>
                    <input
                        type="date"
                        name="birth_date"
                        value="{{ old('birth_date') }}"
                        class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                    >
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-2">診断名</label>
                    <input
                        type="text"
                        name="diagnosis"
                        value="{{ old('diagnosis') }}"
                        class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                    >
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-2">既往歴</label>
                    <textarea
                        name="medical_history"
                        rows="4"
                        class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                    >{{ old('medical_history') }}</textarea>
                </div>

            </div>

            <div class="flex justify-end gap-3 mt-8">

                <a href="{{ route('patients.index') }}"
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
