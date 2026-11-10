<?php

namespace App\Http\Controllers;

use App\Models\BbsEvaluation;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BbsEvaluationController extends Controller
{
    // BBS評価入力画面
    public function create(Patient $patient)
    {
        return view('bbs_evaluations.create', compact('patient'));
    }

    // BBS評価履歴一覧
    public function index(Patient $patient)
    {
        $bbsEvaluations = $patient->bbsEvaluations()
            ->with('results')
            ->orderBy('evaluated_at', 'desc')
            ->get();

        return view('bbs_evaluations.index', compact(
            'patient',
            'bbsEvaluations'
        ));
    }

    // BBS評価詳細
    public function show(Patient $patient, BbsEvaluation $bbsEvaluation)
    {
        $bbsEvaluation = $patient->bbsEvaluations()
            ->with('results')
            ->findOrFail($bbsEvaluation->id);

        return view('bbs_evaluations.show', compact(
            'patient',
            'bbsEvaluation'
        ));
    }

    // BBS評価編集画面
    public function edit(Patient $patient, BbsEvaluation $bbsEvaluation)
    {
        $bbsEvaluation = $patient->bbsEvaluations()
            ->with('results')
            ->findOrFail($bbsEvaluation->id);

        return view('bbs_evaluations.create', compact(
            'patient',
            'bbsEvaluation'
        ));
    }

    // BBS評価保存
    public function store(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'evaluated_at' => ['required', 'date'],
            'evaluator' => ['required', 'string', 'max:255'],
            'scores' => ['required', 'array', 'size:14'],
            'scores.*' => ['nullable', 'integer', 'between:0,4'],
        ]);

        DB::transaction(function () use ($validated, $patient) {

            $evaluation = BbsEvaluation::create([
                'patient_id' => $patient->id,
                'evaluated_at' => $validated['evaluated_at'],
                'evaluator' => $validated['evaluator'],
            ]);

            for ($i = 1; $i <= 14; $i++) {
                $evaluation->results()->create([
                    'item_number' => $i,
                    'score' => $validated['scores'][$i] ?? null,
                ]);
            }
        });

        return redirect()
            ->route('patients.show', $patient)
            ->with('success', 'BBS評価を保存しました。');
    }

    // BBS評価更新
    public function update(
        Request $request,
        Patient $patient,
        BbsEvaluation $bbsEvaluation
    ) {
        $bbsEvaluation = $patient->bbsEvaluations()
            ->findOrFail($bbsEvaluation->id);

        $validated = $request->validate([
            'evaluated_at' => ['required', 'date'],
            'evaluator' => ['required', 'string', 'max:255'],
            'scores' => ['required', 'array', 'size:14'],
            'scores.*' => ['nullable', 'integer', 'between:0,4'],
        ]);

        DB::transaction(function () use ($validated, $bbsEvaluation) {

            $bbsEvaluation->update([
                'evaluated_at' => $validated['evaluated_at'],
                'evaluator' => $validated['evaluator'],
            ]);

            for ($i = 1; $i <= 14; $i++) {
                $bbsEvaluation->results()->updateOrCreate(
                    ['item_number' => $i],
                    ['score' => $validated['scores'][$i] ?? null]
                );
            }
        });

        return redirect()
            ->route('patients.show', $patient)
            ->with('success', 'BBS評価を更新しました。');
    }

    // BBS評価削除
    public function destroy(
        Patient $patient,
        BbsEvaluation $bbsEvaluation
    ) {
        $bbsEvaluation = $patient->bbsEvaluations()
            ->findOrFail($bbsEvaluation->id);

        $bbsEvaluation->delete();

        return redirect()
            ->route('bbs_evaluations.index', $patient)
            ->with('success', 'BBS評価を削除しました。');
    }
}
