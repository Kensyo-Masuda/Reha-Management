
@extends('layouts.app')

@section('title', '機能評価一覧 | Reha Management')

@section('content')

<div class="max-w-5xl mx-auto">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">
                機能評価一覧
            </h2>
            <p class="text-sm text-slate-500 mt-2">
                {{ $patient->name }} さんの機能評価
            </p>
        </div>

        <a href="{{ route('evaluations.create', $patient) }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg font-semibold">
            ＋ 新規登録
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if ($evaluations->isEmpty())

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-10 text-center">
            <p class="text-slate-500">
                機能評価はまだ登録されていません。
            </p>
        </div>

    @else

        <div class="space-y-5">

            @foreach ($evaluations as $evaluation)

                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">

                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-lg font-bold text-slate-800">
                            {{ $evaluation->evaluated_at }}
                        </h3>

                        <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $evaluation->evaluation_type }}
                        </span>
                    </div>

                    <div class="space-y-5">

                        <div>
                            <p class="text-sm text-slate-500 mb-1">評価結果</p>
                            <p class="text-slate-800 whitespace-pre-line">
                                {{ $evaluation->result }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-slate-500 mb-1">備考</p>
                            <p class="text-slate-800 whitespace-pre-line">
                                {{ $evaluation->remarks ?: 'なし' }}
                            </p>
                        </div>

                    </div>

                    <div class="flex justify-end gap-3 border-t border-slate-100 pt-4 mt-6">

                        <a href="{{ route('evaluations.edit', [$patient, $evaluation]) }}"
                           class="border border-slate-300 hover:bg-slate-100 px-5 py-2 rounded-lg">
                            編集
                        </a>

                        <form action="{{ route('evaluations.destroy', [$patient, $evaluation]) }}"
                              method="POST"
                              onsubmit="return confirm('この機能評価を削除しますか？');">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="text-red-600 hover:bg-red-50 border border-red-200 px-5 py-2 rounded-lg">
                                削除
                            </button>
                        </form>

                    </div>

                </div>

            @endforeach

        </div>

    @endif

    <div class="mt-8">
        <a href="{{ route('patients.show', $patient) }}"
           class="text-blue-600 hover:text-blue-800 font-semibold">
            ← 患者詳細に戻る
        </a>
    </div>

</div>

@endsection
