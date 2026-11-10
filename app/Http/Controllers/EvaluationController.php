<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Evaluation;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    // 機能評価の一覧表示
    public function index(Patient $patient)
    {
        $evaluations = $patient->evaluations;

        return view('evaluations.index', compact('patient', 'evaluations'));
    }

    // 機能評価の登録画面
    public function create(Patient $patient)
   {
    return view('evaluations.create', compact('patient'));
   }

   // 機能評価の登録処理
public function store(Request $request, Patient $patient)
{
    $validated = $request->validate([
        'evaluated_at' => 'required|date',
        'evaluation_type' => 'required|string|max:255',
        'result' => 'required|string',
        'remarks' => 'nullable|string',
    ]);

    $patient->evaluations()->create($validated);

    return redirect()->route('evaluations.index', $patient)
        ->with('success', '機能評価を登録しました。');
}

// 機能評価の編集画面
public function edit(Patient $patient, Evaluation $evaluation)
{
    return view('evaluations.edit', compact('patient', 'evaluation'));
}

// 機能評価の更新処理
public function update(Request $request, Patient $patient, Evaluation $evaluation)
{
    $validated = $request->validate([
        'evaluated_at' => 'required|date',
        'evaluation_type' => 'required|string|max:255',
        'result' => 'required|string',
        'remarks' => 'nullable|string',
    ]);

    $evaluation->update($validated);

    return redirect()->route('evaluations.index', $patient)
        ->with('success', '機能評価を更新しました。');
}

// 機能評価の削除処理
public function destroy(Patient $patient, Evaluation $evaluation)
{
    $evaluation->delete();

    return redirect()->route('evaluations.index', $patient)
        ->with('success', '機能評価を削除しました。');
}
}