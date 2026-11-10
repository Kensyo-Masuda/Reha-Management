<?php

namespace App\Http\Controllers;

use App\Models\AssessmentItem;
use App\Models\MmtEvaluation;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MmtEvaluationController extends Controller
{
    // MMT評価の新規登録画面
    public function create(Patient $patient)
    {
        $assessmentItems = AssessmentItem::orderBy('sort_order')->get();

        return view('mmt_evaluations.create', compact(
            'patient',
            'assessmentItems'
        ));
    }

    // MMT評価の保存
    public function store(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'evaluated_at' => ['required', 'date'],
            'evaluator' => ['required', 'string', 'max:255'],
            'results' => ['nullable', 'array'],
            'results.*' => ['array'],
            'results.*.right' => ['nullable', 'integer', 'between:0,5'],
            'results.*.left' => ['nullable', 'integer', 'between:0,5'],
        ]);

        $this->ensureValidAssessmentItems($validated['results'] ?? []);

        DB::transaction(function () use ($validated, $patient) {

            $mmtEvaluation = MmtEvaluation::create([
                'patient_id' => $patient->id,
                'evaluated_at' => $validated['evaluated_at'],
                'evaluator' => $validated['evaluator'],
            ]);

            foreach ($validated['results'] ?? [] as $itemId => $sides) {

                foreach (['right', 'left'] as $side) {

                    $grade = $sides[$side] ?? null;

                    // 未評価は保存しない
                    if ($grade === null || $grade === '') {
                        continue;
                    }

                    $mmtEvaluation->results()->create([
                        'assessment_item_id' => $itemId,
                        'side' => $side,
                        'grade' => $grade,
                    ]);
                }
            }
        });

        return redirect()
            ->route('patients.show', $patient)
            ->with('success', 'MMT評価を保存しました。');
    }

    // MMT評価の詳細表示
    public function show(Patient $patient, int $mmtEvaluationId)
    {
        $mmtEvaluation = $patient->mmtEvaluations()
            ->with('results.assessmentItem')
            ->findOrFail($mmtEvaluationId);

        $assessmentItems = AssessmentItem::orderBy('sort_order')->get();

        return view('mmt_evaluations.show', compact(
            'patient',
            'mmtEvaluation',
            'assessmentItems'
        ));
    }

    // MMT評価の編集画面
    public function edit(Patient $patient, int $mmtEvaluationId)
    {
        $mmtEvaluation = $patient->mmtEvaluations()
            ->with('results')
            ->findOrFail($mmtEvaluationId);
        $assessmentItems = AssessmentItem::orderBy('sort_order')->get();

        return view('mmt_evaluations.create', compact(
            'patient',
            'mmtEvaluation',
            'assessmentItems'
        ));
    }

    // MMT評価の更新
    public function update(Request $request, Patient $patient, int $mmtEvaluationId)
    {
        $mmtEvaluation = $patient->mmtEvaluations()->findOrFail($mmtEvaluationId);

        $validated = $request->validate([
            'evaluated_at' => ['required', 'date'],
            'evaluator' => ['required', 'string', 'max:255'],
            'results' => ['nullable', 'array'],
            'results.*' => ['array'],
            'results.*.right' => ['nullable', 'integer', 'between:0,5'],
            'results.*.left' => ['nullable', 'integer', 'between:0,5'],
        ]);

        $this->ensureValidAssessmentItems($validated['results'] ?? []);

        DB::transaction(function () use ($validated, $mmtEvaluation) {
            $mmtEvaluation->update([
                'evaluated_at' => $validated['evaluated_at'],
                'evaluator' => $validated['evaluator'],
            ]);

            $mmtEvaluation->results()->delete();

            foreach ($validated['results'] ?? [] as $itemId => $sides) {
                foreach (['right', 'left'] as $side) {
                    $grade = $sides[$side] ?? null;

                    if ($grade === null || $grade === '') {
                        continue;
                    }

                    $mmtEvaluation->results()->create([
                        'assessment_item_id' => $itemId,
                        'side' => $side,
                        'grade' => $grade,
                    ]);
                }
            }
        });

        return redirect()
            ->route('mmt_evaluations.show', [$patient, $mmtEvaluation->id])
            ->with('success', 'MMT評価を更新しました。');
    }

    // MMT評価の削除
    public function destroy(Patient $patient, int $mmtEvaluationId)
    {
        $mmtEvaluation = $patient->mmtEvaluations()->findOrFail($mmtEvaluationId);
        $mmtEvaluation->delete();

        return redirect()
            ->route('patients.show', $patient)
            ->with('success', 'MMT評価を削除しました。');
    }

    private function ensureValidAssessmentItems(array $results): void
    {
        $itemIds = array_keys($results);

        if (AssessmentItem::whereIn('id', $itemIds)->count() !== count($itemIds)) {
            throw ValidationException::withMessages([
                'results' => '評価項目が正しくありません。',
            ]);
        }
    }
}
