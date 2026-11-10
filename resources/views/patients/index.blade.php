
@extends('layouts.app')

@section('title', '患者一覧 | Reha Management')

@section('content')

<div class="max-w-5xl mx-auto">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">患者一覧</h2>
            <p class="text-sm text-slate-500 mt-2">
                登録されている患者情報を管理します。
            </p>
        </div>

        <a href="{{ route('patients.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg font-semibold">
            ＋ 新規患者登録
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">

        <form action="{{ route('patients.index') }}" method="GET"
              class="flex gap-3">

            <input
                type="text"
                name="keyword"
                placeholder="患者名で検索"
                value="{{ request('keyword') }}"
                class="flex-1 border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
            >

            <button type="submit"
                    class="bg-slate-800 hover:bg-slate-700 text-white px-6 rounded-lg">
                検索
            </button>

            <a href="{{ route('patients.index') }}"
               class="border border-slate-300 hover:bg-slate-100 px-5 py-3 rounded-lg">
                リセット
            </a>

        </form>

    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">

        <div class="p-5 border-b border-slate-200">
            <h3 class="font-bold text-lg">登録済み患者</h3>
        </div>

        @if ($patients->isEmpty())

            <p class="p-8 text-center text-slate-500">
                該当する患者はいません。
            </p>

        @else

            <ul class="divide-y divide-slate-100">

                @foreach ($patients as $patient)

                    <li class="p-5 hover:bg-slate-50">

                        <a href="{{ route('patients.show', $patient) }}"
                           class="flex justify-between items-center">

                            <div>
                                <p class="font-semibold text-slate-800">
                                    {{ $patient->name }}
                                </p>

                                <p class="text-sm text-slate-500 mt-1">
                                    生年月日：{{ $patient->birth_date }}
                                </p>
                            </div>

                            <span class="text-blue-600 font-semibold">
                                詳細を見る →
                            </span>

                        </a>

                    </li>

                @endforeach

            </ul>

        @endif

    </div>

</div>

@endsection
