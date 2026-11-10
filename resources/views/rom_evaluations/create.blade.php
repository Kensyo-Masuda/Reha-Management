@extends('layouts.app')

@section('title', isset($romEvaluation) ? 'ROM評価編集' : 'ROM評価登録')

@section('content')
<div class="container mx-auto px-4 py-6">

    <h1 class="text-2xl font-bold mb-4">
        {{ isset($romEvaluation) ? 'ROM評価の編集' : 'ROM評価の登録' }}
    </h1>

    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <p class="text-lg font-bold">
            患者名：{{ $patient->name }} さん
        </p>

        <form
            action="{{ isset($romEvaluation) ? route('rom_evaluations.update', [$patient, $romEvaluation->id]) : route('rom_evaluations.store', $patient) }}"
            method="POST"
        >
            @csrf
            @if (isset($romEvaluation))
                @method('PUT')
            @endif

            <div class="mt-4">
                <label class="block font-bold mb-1">評価日</label>
                <input
                    type="date"
                    name="evaluated_at"
                    value="{{ old('evaluated_at', $romEvaluation->evaluated_at ?? date('Y-m-d')) }}"
                    class="border rounded p-2"
                    required
                >
            </div>

            <div class="mt-4">
                <label class="block font-bold mb-1">評価者</label>
                <input
                    type="text"
                    name="evaluator"
                    value="{{ old('evaluator', $romEvaluation->evaluator ?? '') }}"
                    class="border rounded p-2"
                    required
                >
            </div>

            <h2 class="text-xl font-bold mt-8 mb-4">
                関節可動域評価
            </h2>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse border text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border p-2">部位</th>
                            <th class="border p-2">運動方向</th>
                            <th class="border p-2">右 AROM</th>
                            <th class="border p-2">右 PROM</th>
                            <th class="border p-2">左 AROM</th>
                            <th class="border p-2">左 PROM</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($assessmentItems as $item)
                            <tr>
                                <td class="border p-2">
                                    {{ $item->body_region }}
                                </td>

                                <td class="border p-2">
                                    {{ $item->movement }}
                                </td>

                                @foreach (['right', 'left'] as $side)
                                    @foreach (['active', 'passive'] as $type)
                                        @php
                                            $result = isset($romEvaluation)
                                                ? $romEvaluation->results->first(function ($result) use ($item, $side, $type) {
                                                    return $result->assessment_item_id == $item->id
                                                        && $result->side === $side
                                                        && $result->movement_type === $type;
                                                })
                                                : null;
                                        @endphp
                                        <td class="border p-2">
                                            <input
                                                type="number"
                                                step="0.1"
                                                name="results[{{ $item->id }}][{{ $side }}][{{ $type }}]"
                                                class="border rounded p-2 w-20"
                                                placeholder="°"
                                                min="0"
                                                max="360"
                                                value="{{ old('results.' . $item->id . '.' . $side . '.' . $type, $result?->angle) }}"
                                            >
                                        </td>
                                    @endforeach
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                <button
                    type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded"
                >
                    {{ isset($romEvaluation) ? '変更を保存' : '評価を保存' }}
                </button>
            </div>

            @if (isset($romEvaluation))
                <a
                    href="{{ route('rom_evaluations.show', [$patient, $romEvaluation->id]) }}"
                    class="inline-block border border-slate-300 px-6 py-2 rounded mt-3"
                >
                    キャンセル
                </a>
            @else
                <a
                    href="{{ route('patients.show', $patient) }}"
                    class="inline-block border border-slate-300 px-6 py-2 rounded mt-3"
                >
                    戻る
                </a>
            @endif

        </form>
    </div>
</div>
@endsection
