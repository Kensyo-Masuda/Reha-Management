@extends('layouts.app')

@section('title', 'MMT評価詳細')

@section('content')
<div class="max-w-6xl mx-auto">

    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    <h2 class="text-2xl font-bold text-slate-800 mb-6">
        MMT評価詳細
    </h2>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">

        <h3 class="text-xl font-bold mb-4">
            {{ $patient->name }} さん
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-slate-500">評価日</p>
                <p class="font-bold">{{ $mmtEvaluation->evaluated_at }}</p>
            </div>

            <div>
                <p class="text-sm text-slate-500">評価者</p>
                <p class="font-bold">{{ $mmtEvaluation->evaluator }}</p>
            </div>
        </div>

    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">

        <h3 class="text-xl font-bold mb-4">
            徒手筋力検査（MMT）
        </h3>

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

                                @php
                                    $result = $mmtEvaluation->results
                                        ->first(function ($result) use ($item, $side) {
                                            return $result->assessment_item_id == $item->id
                                                && $result->side == $side;
                                        });
                                @endphp

                                <td class="border p-3 text-center">
                                    @if ($result)
                                        {{ $result->grade }}
                                    @else
                                        <span class="text-slate-400">
                                            未評価
                                        </span>
                                    @endif
                                </td>

                            @endforeach

                        </tr>

                    @endforeach
                </tbody>

            </table>

        </div>

    </div>

    <div class="flex flex-wrap justify-between gap-3">
        <a href="{{ route('patients.show', $patient) }}"
           class="inline-block border border-slate-300 px-5 py-2 rounded-lg hover:bg-slate-50">
            患者詳細に戻る
        </a>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('mmt_evaluations.edit', [$patient, $mmtEvaluation->id]) }}"
               class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700">
                編集する
            </a>

            <form action="{{ route('mmt_evaluations.destroy', [$patient, $mmtEvaluation->id]) }}"
                  method="POST"
                  onsubmit="return confirm('このMMT評価を削除してもよろしいですか？');">
                @csrf
                @method('DELETE')

                <button type="submit"
                        class="bg-red-600 text-white px-5 py-2 rounded-lg hover:bg-red-700">
                    この評価を削除
                </button>
            </form>
        </div>
    </div>

</div>
@endsection