
@extends('layouts.app')

@section('title', '患者詳細')

@section('content')
<div class="max-w-6xl mx-auto">

    {{-- 成功メッセージ --}}
    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    {{-- ページタイトル --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            患者詳細
        </h2>

        <a href="{{ route('patients.index') }}"
           class="text-blue-600 hover:underline">
            患者一覧に戻る
        </a>
    </div>

    {{-- 患者基本情報 --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">

        <h3 class="text-xl font-bold text-slate-800 mb-6">
            {{ $patient->name }} さん
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            <div>
                <p class="text-sm text-slate-500 mb-1">生年月日</p>
                <p class="font-medium">{{ $patient->birth_date }}</p>
            </div>

            <div>
                <p class="text-sm text-slate-500 mb-1">年齢</p>
                <p class="font-medium">
                    {{ \Carbon\Carbon::parse($patient->birth_date)->age }}歳
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500 mb-1">診断名</p>
                <p class="font-medium">{{ $patient->diagnosis }}</p>
            </div>

            <div>
                <p class="text-sm text-slate-500 mb-1">既往歴</p>
                <p class="font-medium">{{ $patient->medical_history }}</p>
            </div>

        </div>
    </div>

    {{-- 機能メニュー --}}
    <h3 class="text-lg font-bold text-slate-800 mb-4">
        管理メニュー
    </h3>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        <a href="{{ route('rehabilitation_records.index', $patient) }}"
           class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:border-blue-400 hover:shadow-md">
            <h3 class="font-bold text-blue-700 mb-2">
                リハビリ実施記録
            </h3>
            <p class="text-sm text-slate-500">
                実施記録の確認・登録・編集
            </p>
        </a>

        <a href="{{ route('rom_evaluations.create', $patient) }}"
           class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:border-blue-400 hover:shadow-md">
            <h3 class="font-bold text-blue-700 mb-2">
                ROM評価
            </h3>
            <p class="text-sm text-slate-500">
                関節可動域の評価・登録
            </p>
        </a>

        <a href="{{ route('mmt_evaluations.create', $patient) }}"
           class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:border-blue-400 hover:shadow-md">
            <h3 class="font-bold text-blue-700 mb-2">
                MMT評価
            </h3>
            <p class="text-sm text-slate-500">
                筋力評価の登録・確認
            </p>
        </a>

        <a href="{{ route('bbs_evaluations.index', $patient) }}"
           class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:border-blue-400 hover:shadow-md">
            <h3 class="font-bold text-blue-700 mb-2">
                BBS評価
            </h3>
            <p class="text-sm text-slate-500">
                評価結果の確認・登録・編集
            </p>
        </a>

    </div>

    {{-- 評価履歴 --}}
    <h3 class="text-lg font-bold text-slate-800 mb-4">
        評価履歴
    </h3>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

        {{-- ROM評価履歴 --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">

            <h3 class="text-lg font-bold text-slate-800 mb-4">
                ROM評価履歴
            </h3>

            <div class="mb-4">
                <a href="{{ route('rom_evaluations.create', $patient) }}"
                   class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    ROM評価を新規登録
                </a>
            </div>

            @forelse ($romEvaluations as $romEvaluation)

                <div class="border-b py-3 flex justify-between items-center gap-4">

                    <div>
                        <a href="{{ route('rom_evaluations.show', [$patient, $romEvaluation->id]) }}"
                           class="font-bold text-blue-600 hover:underline">
                            {{ $romEvaluation->evaluated_at }}
                        </a>

                        <p class="text-sm text-slate-500">
                            評価者：{{ $romEvaluation->evaluator }}
                        </p>
                    </div>

                    <span class="text-sm text-green-600 whitespace-nowrap">
                        保存済み
                    </span>

                </div>

            @empty

                <p class="text-sm text-slate-500">
                    ROM評価の履歴はありません。
                </p>

            @endforelse

        </div>

        {{-- MMT評価履歴 --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">

            <h3 class="text-lg font-bold text-slate-800 mb-4">
                MMT評価履歴
            </h3>

            <div class="mb-4">
                <a href="{{ route('mmt_evaluations.create', $patient) }}"
                   class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    MMT評価を新規登録
                </a>
            </div>

            @forelse ($mmtEvaluations as $mmtEvaluation)

                <div class="border-b py-3 flex justify-between items-center gap-4">

                    <div>
                        <a href="{{ route('mmt_evaluations.show', [$patient, $mmtEvaluation->id]) }}"
                           class="font-bold text-blue-600 hover:underline">
                            {{ $mmtEvaluation->evaluated_at }}
                        </a>

                        <p class="text-sm text-slate-500">
                            評価者：{{ $mmtEvaluation->evaluator }}
                        </p>
                    </div>

                    <span class="text-sm text-green-600 whitespace-nowrap">
                        保存済み
                    </span>

                </div>

            @empty

                <p class="text-sm text-slate-500">
                    MMT評価の履歴はありません。
                </p>

            @endforelse

        </div>

        {{-- BBS評価 --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">

            <h3 class="text-lg font-bold text-slate-800 mb-4">
                BBS評価履歴
            </h3>

            <div class="mb-4">
                <a href="{{ route('bbs_evaluations.create', $patient) }}"
                   class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    BBS評価を新規登録
                </a>
            </div>

            @forelse ($bbsEvaluations as $bbsEvaluation)

                <div class="border-b py-3 flex justify-between items-center gap-4">

                    <div>
                        <a href="{{ route('bbs_evaluations.show', [$patient, $bbsEvaluation]) }}"
                           class="font-bold text-blue-600 hover:underline">
                            {{ $bbsEvaluation->evaluated_at }}
                        </a>

                        <p class="text-sm text-slate-500">
                            評価者：{{ $bbsEvaluation->evaluator }}
                        </p>
                    </div>

                    <span class="text-sm text-green-600 whitespace-nowrap">
                        保存済み
                    </span>

                </div>

            @empty

                <p class="text-sm text-slate-500">
                    BBS評価の履歴はありません。
                </p>

            @endforelse

        </div>

    </div>

    {{-- 患者情報の編集・削除 --}}
    <div class="flex flex-wrap gap-3">

        <a href="{{ route('patients.edit', $patient) }}"
           class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700">
            患者情報を編集
        </a>

        <form action="{{ route('patients.destroy', $patient) }}"
              method="POST"
              onsubmit="return confirm('本当にこの患者情報を削除しますか？');">

            @csrf
            @method('DELETE')

            <button type="submit"
                    class="bg-red-600 text-white px-5 py-2 rounded-lg hover:bg-red-700">
                患者情報を削除
            </button>

        </form>

    </div>

</div>
@endsection
