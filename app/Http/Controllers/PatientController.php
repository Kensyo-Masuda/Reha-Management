<?php

namespace App\Http\Controllers;

use App\Models\MmtEvaluation;
use App\Models\Patient;
use App\Models\RomEvaluation;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    
    public function index(Request $request)
{
    $query = Patient::query();

    if ($request->filled('keyword')) {
        $query->where('name', 'like', '%' . $request->keyword . '%');
    }

    $patients = $query->get();

    return view('patients.index', compact('patients'));
}

    public function create()
{
    return view('patients.create');
}

public function show(Patient $patient)
{
    // ROM評価履歴
    $romEvaluations = RomEvaluation::where('patient_id', $patient->id)
        ->orderBy('evaluated_at', 'desc')
        ->get();

    // MMT評価履歴
    $mmtEvaluations = MmtEvaluation::where('patient_id', $patient->id)
        ->orderBy('evaluated_at', 'desc')
        ->get();

    // BBS評価履歴
    $bbsEvaluations = $patient->bbsEvaluations()
        ->orderBy('evaluated_at', 'desc')
        ->get();

    return view('patients.show', compact(
        'patient',
        'romEvaluations',
        'mmtEvaluations',
        'bbsEvaluations'
    ));
}

public function edit(Patient $patient)
{
    return view('patients.edit', compact('patient'));
}

public function update(Request $request, Patient $patient)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'birth_date' => 'required|date',
        'diagnosis' => 'required|string|max:255',
        'medical_history' => 'nullable|string',
    ]);

    $patient->update($validated);

    return redirect()->route('patients.show', $patient)
        ->with('success', '患者情報を更新しました。');
}

public function destroy(Patient $patient)
{
    $patient->delete();

    return redirect()->route('patients.index')
        ->with('success', '患者情報を削除しました。');
}

public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'birth_date' => 'required|date',
        'diagnosis' => 'required|string|max:255',
        'medical_history' => 'nullable|string',
    ]);

    Patient::create($validated);

    return redirect()->route('patients.index')
        ->with('success', '患者情報を登録しました。');
}
}