
@extends('layouts.app')

@section('title', 'ROM評価詳細')

@section('content')
<div class="max-w-6xl mx-auto">

    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            ROM評価詳細
        </h2>

        <a href="{{ route('patients.show', $patient) }}"
           class="text-blue-600 hover:underline">
            患者詳細に戻る
        </a>
    </div>

    {{-- 評価基本情報 --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">

        <h3 class="text-xl font-bold mb-4">
            {{ $patient->name }} さん
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-slate-500">評価日</p>
                <p class="font-bold">
                    {{ $romEvaluation->evaluated_at }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">評価者</p>
                <p class="font-bold">
                    {{ $romEvaluation->evaluator }}
                </p>
            </div>
        </div>

    </div>

    {{-- ROM測定結果 --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">

        <h3 class="text-lg font-bold mb-4">
            関節可動域評価結果
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse border text-sm">

                <thead class="bg-slate-100">
                    <tr>
                        <th class="border p-3">部位</th>
                        <th class="border p-3">運動方向</th>
                        <th class="border p-3">右 AROM</th>
                        <th class="border p-3">右 PROM</th>
                        <th class="border p-3">左 AROM</th>
                        <th class="border p-3">左 PROM</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($assessmentItems ?? [] as $item)
                        <tr>
                            <td class="border p-3">
                                {{ $item->body_region }}
                            </td>

                            <td class="border p-3">
                                {{ $item->movement }}
                            </td>

                            @foreach (['right', 'left'] as $side)
                                @foreach (['active', 'passive'] as $type)

                                    @php
                                        $result = $romEvaluation->results
                                            ->first(function ($result) use ($item, $side, $type) {
                                                return $result->assessment_item_id == $item->id
                                                    && $result->side === $side
                                                    && $result->movement_type === $type;
                                            });
                                    @endphp

                                    <td class="border p-3 text-center">
                                        @if ($result)
                                            {{ $result->angle }}°
                                        @else
                                            <span class="text-slate-400">
                                                未評価
                                            </span>
                                        @endif
                                    </td>

                                @endforeach
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

    </div>

    <div class="mt-6 flex flex-wrap justify-between gap-3">
        <a href="{{ route('patients.show', $patient) }}"
           class="inline-block border border-slate-300 px-5 py-2 rounded-lg hover:bg-slate-50">
            患者詳細に戻る
        </a>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('rom_evaluations.edit', [$patient, $romEvaluation->id]) }}"
               class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700">
                編集する
            </a>

            <form action="{{ route('rom_evaluations.destroy', [$patient, $romEvaluation->id]) }}"
                  method="POST"
                  onsubmit="return confirm('このROM評価を削除してもよろしいですか？');">
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
