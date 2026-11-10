@extends('layouts.app')

@section('title', isset($mmtEvaluation) ? 'MMT評価編集' : 'MMT評価登録')

@section('content')
<div class="max-w-6xl mx-auto">

    <h2 class="text-2xl font-bold text-slate-800 mb-6">
        {{ isset($mmtEvaluation) ? 'MMT評価の編集' : 'MMT評価の登録' }}
    </h2>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">

        <p class="text-lg font-bold mb-4">
            患者名：{{ $patient->name }} さん
        </p>

        <form
            action="{{ isset($mmtEvaluation) ? route('mmt_evaluations.update', [$patient, $mmtEvaluation->id]) : route('mmt_evaluations.store', $patient) }}"
            method="POST"
        >
            @csrf
            @if (isset($mmtEvaluation))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">

                <div>
                    <label class="block font-bold mb-1">評価日</label>
                    <input
                        type="date"
                        name="evaluated_at"
                        value="{{ old('evaluated_at', $mmtEvaluation->evaluated_at ?? date('Y-m-d')) }}"
                        class="border rounded-lg p-2 w-full"
                        required
                    >
                </div>

                <div>
                    <label class="block font-bold mb-1">評価者</label>
                    <input
                        type="text"
                        name="evaluator"
                        value="{{ old('evaluator', $mmtEvaluation->evaluator ?? '') }}"
                        class="border rounded-lg p-2 w-full"
                        required
                    >
                </div>

            </div>

            <h3 class="text-xl font-bold mb-4">
                徒手筋力検査（MMT）
            </h3>

            <p class="text-sm text-slate-500 mb-4">
                右・左それぞれの筋力を0～5で入力してください。
            </p>

            <div class="overflow-x-auto">

                <table class="w-full border-collapse border text-sm">

                    <thead class="bg-slate-100">
                        <tr>
                            <th class="border p-3">部位</th>
                            <th class="border p-3">運動方向</th>
                            <th class="border p-3">右 MMT</th>
                            <th class="border p-3">左 MMT</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($assessmentItems as $item)

                            <tr>
                                <td class="border p-3">
                                    {{ $item->body_region }}
                                </td>

                                <td class="border p-3">
                                    {{ $item->movement }}
                                </td>

                                @foreach (['right', 'left'] as $side)

                                    <td class="border p-3">
                                        @php
                                            $result = isset($mmtEvaluation)
                                                ? $mmtEvaluation->results->first(function ($result) use ($item, $side) {
                                                    return $result->assessment_item_id == $item->id
                                                        && $result->side === $side;
                                                })
                                                : null;
                                        @endphp

                                        <select
                                            name="results[{{ $item->id }}][{{ $side }}]"
                                            class="border rounded-lg p-2 w-full"
                                        >
                                            <option value="">未評価</option>

                                            @for ($score = 0; $score <= 5; $score++)
                                                <option
                                                    value="{{ $score }}"
                                                    {{ (string) old('results.' . $item->id . '.' . $side, $result?->grade) === (string) $score ? 'selected' : '' }}
                                                >
                                                    {{ $score }}
                                                </option>
                                            @endfor

                                        </select>

                                    </td>

                                @endforeach

                            </tr>

                        @endforeach
                    </tbody>

                </table>

            </div>

            <div class="mt-6 flex gap-3">

                <button
                    type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700"
                >
                    {{ isset($mmtEvaluation) ? '変更を保存' : '評価を保存' }}
                </button>

                <a
                    href="{{ isset($mmtEvaluation) ? route('mmt_evaluations.show', [$patient, $mmtEvaluation->id]) : route('patients.show', $patient) }}"
                    class="border border-slate-300 px-6 py-2 rounded-lg hover:bg-slate-50"
                >
                    {{ isset($mmtEvaluation) ? 'キャンセル' : '戻る' }}
                </a>

            </div>

        </form>

    </div>

</div>
@endsection
