@extends('layouts.app')

@section('title', 'BBS評価履歴')

@section('content')
<div class="max-w-5xl mx-auto">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                BBS評価履歴
            </h1>
            <p class="text-slate-500 mt-1">
                {{ $patient->name }} さん
            </p>
        </div>

        <a
            href="{{ route('bbs_evaluations.create', $patient) }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"
        >
            新しい評価を登録
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">

        @if ($bbsEvaluations->isEmpty())

            <p class="text-slate-500 text-center py-8">
                BBS評価の履歴はありません。
            </p>

        @else

            <div class="overflow-x-auto">
                <table class="w-full text-left">

                    <thead>
                        <tr class="border-b text-slate-600">
                            <th class="p-3">評価日</th>
                            <th class="p-3">評価者</th>
                            <th class="p-3">合計点</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($bbsEvaluations as $evaluation)

                            <tr class="border-b hover:bg-slate-50">

                                <td class="p-3">
    <a
        href="{{ route('bbs_evaluations.show', [$patient, $evaluation]) }}"
        class="text-blue-600 hover:underline font-medium"
    >
        {{ date('Y/m/d', strtotime($evaluation->evaluated_at)) }}
    </a>
</td>

                                <td class="p-3">
                                    {{ $evaluation->evaluator }}
                                </td>

                                <td class="p-3 font-bold text-blue-700">
                                    {{ $evaluation->results->sum('score') }} / 56 点
                                </td>

                            </tr>

                        @endforeach
                    </tbody>

                </table>
            </div>

        @endif

    </div>

</div>
@endsection