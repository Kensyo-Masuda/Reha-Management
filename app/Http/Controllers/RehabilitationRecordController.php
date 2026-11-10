<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\RehabilitationRecord;
use Illuminate\Http\Request;

class RehabilitationRecordController extends Controller
{
    // 一覧表示
    public function index(Patient $patient)
    {
        $records = $patient->rehabilitationRecords;

        return view('rehabilitation_records.index', compact('patient', 'records'));
    }

    // 登録画面
    public function create(Patient $patient)
    {
        return view('rehabilitation_records.create', compact('patient'));
    }

    // 登録処理
    public function store(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'performed_at' => 'required|date',
            'therapist' => 'required|string|max:255',
            'content' => 'required|string',
            'duration' => 'required|integer|min:1',
        ]);

        $patient->rehabilitationRecords()->create($validated);

        return redirect()->route('rehabilitation_records.index', $patient)
            ->with('success', 'リハビリ実施記録を登録しました。');
    }

    // 編集画面
    public function edit(Patient $patient, RehabilitationRecord $record)
    {
        return view('rehabilitation_records.edit', compact('patient', 'record'));
    }

    // 更新処理
    public function update(Request $request, Patient $patient, RehabilitationRecord $record)
    {
        $validated = $request->validate([
            'performed_at' => 'required|date',
            'therapist' => 'required|string|max:255',
            'content' => 'required|string',
            'duration' => 'required|integer|min:1',
        ]);

        $record->update($validated);

        return redirect()->route('rehabilitation_records.index', $patient)
            ->with('success', 'リハビリ実施記録を更新しました。');
    }

    // 削除処理
    public function destroy(Patient $patient, RehabilitationRecord $record)
    {
        $record->delete();

        return redirect()->route('rehabilitation_records.index', $patient)
            ->with('success', 'リハビリ実施記録を削除しました。');
    }
}