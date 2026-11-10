@extends('layouts.app')

@section('title', isset($bbsEvaluation) ? 'BBS評価編集' : 'BBS評価登録')

@section('content')
<div class="max-w-6xl mx-auto">

    <h2 class="text-2xl font-bold text-slate-800 mb-6">
        {{ isset($bbsEvaluation) ? 'BBS評価の編集' : 'BBS評価の登録' }}
    </h2>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">

        <p class="text-lg font-bold mb-4">
            患者名：{{ $patient->name }} さん
        </p>

        <form
            action="{{ isset($bbsEvaluation) ? route('bbs_evaluations.update', [$patient, $bbsEvaluation]) : route('bbs_evaluations.store', $patient) }}"
            method="POST"
        >
            @csrf
            @if (isset($bbsEvaluation))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">

                <div>
                    <label class="block font-bold mb-1">評価日</label>
                    <input
                        type="date"
                        name="evaluated_at"
                        value="{{ old('evaluated_at', $bbsEvaluation->evaluated_at ?? date('Y-m-d')) }}"
                        class="border rounded-lg p-2 w-full"
                        required
                    >
                </div>

                <div>
                    <label class="block font-bold mb-1">評価者</label>
                    <input
                        type="text"
                        name="evaluator"
                        value="{{ old('evaluator', $bbsEvaluation->evaluator ?? '') }}"
                        class="border rounded-lg p-2 w-full"
                        required
                    >
                </div>

            </div>

            <h3 class="text-xl font-bold mb-4">
                バランス評価（BBS）
            </h3>

            <p class="text-sm text-slate-500 mb-4">
                各項目を0～4点で評価してください。
            </p>

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

            <div class="space-y-4">

                @foreach ($items as $index => $item)
                    @php
                        $result = isset($bbsEvaluation)
                            ? $bbsEvaluation->results->firstWhere('item_number', $index + 1)
                            : null;
                    @endphp

                    <div class="border rounded-lg p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <label class="font-medium">
                            {{ $index + 1 }}. {{ $item }}
                        </label>

                        <select
                            name="scores[{{ $index + 1 }}]"
                            class="bbs-score border rounded-lg p-2 w-full sm:w-32"
                        >
                            <option value="">未評価</option>
                            @for ($score = 0; $score <= 4; $score++)
                                <option
                                    value="{{ $score }}"
                                    {{ (string) old('scores.' . ($index + 1), $result?->score) === (string) $score ? 'selected' : '' }}
                                >
                                    {{ $score }} 点
                                </option>
                            @endfor
                        </select>
                    </div>
                @endforeach

            </div>

            <div class="mt-6 bg-blue-50 rounded-lg p-5 text-center">
                <p class="text-slate-600 mb-2">BBS合計点</p>
                <p class="text-3xl font-bold text-blue-700">
                    <span id="bbs-total">0</span> / 56 点
                </p>
            </div>

            <div class="mt-6 flex gap-3">
                <button
                    type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700"
                >
                    {{ isset($bbsEvaluation) ? '変更を保存' : '評価を保存' }}
                </button>

                <a
                    href="{{ isset($bbsEvaluation) ? route('bbs_evaluations.show', [$patient, $bbsEvaluation]) : route('patients.show', $patient) }}"
                    class="border border-slate-300 px-6 py-2 rounded-lg hover:bg-slate-50"
                >
                    {{ isset($bbsEvaluation) ? 'キャンセル' : '戻る' }}
                </a>
            </div>

        </form>

    </div>

</div>

<script>
    function calculateBbsTotal() {
        let total = 0;

        document.querySelectorAll('.bbs-score').forEach(select => {
            if (select.value !== '') {
                total += Number(select.value);
            }
        });

        document.getElementById('bbs-total').textContent = total;
    }

    document.querySelectorAll('.bbs-score').forEach(select => {
        select.addEventListener('change', calculateBbsTotal);
    });

    calculateBbsTotal();
</script>
@endsection