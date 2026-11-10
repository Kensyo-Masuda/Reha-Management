@extends('layouts.app')

@section('title', 'BBS評価詳細')

@section('content')
<div class="max-w-5xl mx-auto">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">
            BBS評価詳細
        </h1>
        <p class="text-slate-500 mt-1">
            {{ $patient->name }} さん
        </p>
    </div>

    {{-- 評価情報 --}}
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">

        <h2 class="text-lg font-bold mb-4">
            評価情報
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            <div>
                <p class="text-sm text-slate-500">評価日</p>
                <p class="font-medium">
                    {{ date('Y/m/d', strtotime($bbsEvaluation->evaluated_at)) }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">評価者</p>
                <p class="font-medium">
                    {{ $bbsEvaluation->evaluator }}
                </p>
            </div>

        </div>

    </div>

    {{-- 評価結果 --}}
    <div class="bg-white rounded-xl shadow-sm p-6">

        <h2 class="text-lg font-bold mb-4">
            BBS評価結果（14項目）
        </h2>

        @php
            $items = [
                '椅子からの立ち上がり',
                '立位保持',
                '座位保持',
                '着座',
                '移乗',
                '閉眼立位保持',
                '閉脚立位保持',
                '上肢前方リーチ',
                '床から物を拾う',
                '左右の肩越しに後ろを振り向く',
                '360度回転',
                '踏み台への足載せ',
                '継ぎ足立位',
                '片脚立位',
            ];
        @endphp

        <div class="space-y-3">

            @foreach ($items as $index => $item)

                @php
                    $result = $bbsEvaluation->results
                        ->firstWhere('item_number', $index + 1);
                @endphp

                <div class="border rounded-lg p-4 flex justify-between items-center gap-3">

                    <span class="font-medium text-slate-700">
                        {{ $index + 1 }}. {{ $item }}
                    </span>

                    <span class="font-bold text-blue-700 whitespace-nowrap">
                        {{ $result?->score !== null ? $result->score . ' 点' : '未評価' }}
                    </span>

                </div>

            @endforeach

        </div>

        {{-- 合計点 --}}
        <div class="mt-6 bg-blue-50 rounded-lg p-5 text-center">

            <p class="text-slate-600 mb-2">
                BBS合計点
            </p>

            <p class="text-3xl font-bold text-blue-700">
                {{ $bbsEvaluation->results->sum('score') }} / 56 点
            </p>

        </div>

    </div>

    {{-- 戻る --}}
    <div class="mt-6 flex justify-between">

    <a
        href="{{ route('bbs_evaluations.index', $patient) }}"
        class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-3 rounded-lg"
    >
        評価履歴に戻る
    </a>

    <a
        href="{{ route('bbs_evaluations.edit', [$patient, $bbsEvaluation]) }}"
        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg"
    >
        編集する
    </a>

    <form
    action="{{ route('bbs_evaluations.destroy', [$patient, $bbsEvaluation]) }}"
    method="POST"
    onsubmit="return confirm('このBBS評価を削除してもよろしいですか？');"
    class="mb-4 text-right"
>
    @csrf
    @method('DELETE')

    <button
        type="submit"
        class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg"
    >
        この評価を削除
    </button>
</form>

</div>

</div>
@endsection