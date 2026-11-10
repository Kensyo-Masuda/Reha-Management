
@extends('layouts.app')

@section('title', 'リハビリ実施記録 | Reha Management')

@section('content')

<div class="max-w-5xl mx-auto">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">
                リハビリ実施記録
            </h2>
            <p class="text-sm text-slate-500 mt-2">
                {{ $patient->name }} さんの実施記録
            </p>
        </div>

        <a href="{{ route('rehabilitation_records.create', $patient) }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg font-semibold">
            ＋ 新規登録
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if ($records->isEmpty())

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-10 text-center">
            <p class="text-slate-500">
                リハビリ実施記録はまだありません。
            </p>
        </div>

    @else

        <div class="space-y-5">

            @foreach ($records as $record)

                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">

                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-lg font-bold text-slate-800">
                            {{ $record->performed_at }}
                        </h3>

                        <span class="text-sm text-slate-500">
                            {{ $record->duration }}分
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">

                        <div>
                            <p class="text-sm text-slate-500 mb-1">担当者</p>
                            <p class="font-semibold text-slate-800">
                                {{ $record->therapist }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-slate-500 mb-1">実施時間</p>
                            <p class="font-semibold text-slate-800">
                                {{ $record->duration }}分
                            </p>
                        </div>

                        <div class="md:col-span-2">
                            <p class="text-sm text-slate-500 mb-1">実施内容</p>
                            <p class="text-slate-800 whitespace-pre-line">
                                {{ $record->content }}
                            </p>
                        </div>

                    </div>

                    <div class="flex justify-end gap-3 border-t border-slate-100 pt-4">

                        <a href="{{ route('rehabilitation_records.edit', [$patient, $record]) }}"
                           class="border border-slate-300 hover:bg-slate-100 px-5 py-2 rounded-lg">
                            編集
                        </a>

                        <form action="{{ route('rehabilitation_records.destroy', [$patient, $record]) }}"
                              method="POST"
                              onsubmit="return confirm('このリハビリ実施記録を削除しますか？');">
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
