<?php

namespace App\Http\Controllers;

use App\Models\AssessmentItem;
use App\Models\Patient;
use App\Models\RomEvaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RomEvaluationController extends Controller
{
    // ROM評価の新規登録画面
    public function create(Patient $patient)
    {
        $assessmentItems = AssessmentItem::orderBy('sort_order')->get();

        return view('rom_evaluations.create', compact(
            'patient',
            'assessmentItems'
        ));
    }

    // ROM評価の保存処理
    public function store(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'evaluated_at' => ['required', 'date'],
            'evaluator' => ['required', 'string', 'max:255'],

            'results' => ['nullable', 'array'],
            'results.*' => ['array'],

            'results.*.right.active' => ['nullable', 'numeric', 'min:0', 'max:360'],
            'results.*.right.passive' => ['nullable', 'numeric', 'min:0', 'max:360'],
            'results.*.left.active' => ['nullable', 'numeric', 'min:0', 'max:360'],
            'results.*.left.passive' => ['nullable', 'numeric', 'min:0', 'max:360'],
        ]);

        $this->ensureValidAssessmentItems($validated['results'] ?? []);

        DB::transaction(function () use ($validated, $patient) {

            $romEvaluation = RomEvaluation::create([
                'patient_id' => $patient->id,
                'evaluated_at' => $validated['evaluated_at'],
                'evaluator' => $validated['evaluator'],
            ]);

            foreach ($validated['results'] ?? [] as $itemId => $values) {
                foreach (['right', 'left'] as $side) {
                    foreach (['active', 'passive'] as $type) {

                        $angle = $values[$side][$type] ?? null;

                        if ($angle === null || $angle === '') {
                            continue;
                        }

                        $romEvaluation->results()->create([
                            'assessment_item_id' => $itemId,
                            'side' => $side,
                            'movement_type' => $type,
                            'angle' => $angle,
                        ]);
                    }
                }
            }
        });

        return redirect()
            ->route('patients.show', $patient)
            ->with('success', 'ROM評価を保存しました。');
    }

    // ROM評価の詳細画面
    public function show(Patient $patient, int $romEvaluationId)
    {
        $assessmentItems = AssessmentItem::orderBy('sort_order')->get();
        $romEvaluation = $patient->romEvaluations()
            ->with('results.assessmentItem')
            ->findOrFail($romEvaluationId);

        return view('rom_evaluations.show', compact(
            'patient',
            'romEvaluation',
            'assessmentItems'
        ));
    }

    // ROM評価の編集画面
    public function edit(Patient $patient, int $romEvaluationId)
    {
        $romEvaluation = $patient->romEvaluations()
            ->with('results')
            ->findOrFail($romEvaluationId);
        $assessmentItems = AssessmentItem::orderBy('sort_order')->get();

        return view('rom_evaluations.create', compact(
            'patient',
            'romEvaluation',
            'assessmentItems'
        ));
    }

    // ROM評価の更新
    public function update(Request $request, Patient $patient, int $romEvaluationId)
    {
        $romEvaluation = $patient->romEvaluations()->findOrFail($romEvaluationId);

        $validated = $request->validate([
            'evaluated_at' => ['required', 'date'],
            'evaluator' => ['required', 'string', 'max:255'],
            'results' => ['nullable', 'array'],
            'results.*' => ['array'],
            'results.*.right.active' => ['nullable', 'numeric', 'min:0', 'max:360'],
            'results.*.right.passive' => ['nullable', 'numeric', 'min:0', 'max:360'],
            'results.*.left.active' => ['nullable', 'numeric', 'min:0', 'max:360'],
            'results.*.left.passive' => ['nullable', 'numeric', 'min:0', 'max:360'],
        ]);

        $this->ensureValidAssessmentItems($validated['results'] ?? []);

        DB::transaction(function () use ($validated, $romEvaluation) {
            $romEvaluation->update([
                'evaluated_at' => $validated['evaluated_at'],
                'evaluator' => $validated['evaluator'],
            ]);

            $romEvaluation->results()->delete();

            foreach ($validated['results'] ?? [] as $itemId => $values) {
                foreach (['right', 'left'] as $side) {
                    foreach (['active', 'passive'] as $type) {
                        $angle = $values[$side][$type] ?? null;

                        if ($angle === null || $angle === '') {
                            continue;
                        }

                        $romEvaluation->results()->create([
                            'assessment_item_id' => $itemId,
                            'side' => $side,
                            'movement_type' => $type,
                            'angle' => $angle,
                        ]);
                    }
                }
            }
        });

        return redirect()
            ->route('rom_evaluations.show', [$patient, $romEvaluation->id])
            ->with('success', 'ROM評価を更新しました。');
    }

    // ROM評価の削除
    public function destroy(Patient $patient, int $romEvaluationId)
    {
        $romEvaluation = $patient->romEvaluations()->findOrFail($romEvaluationId);
        $romEvaluation->delete();

        return redirect()
            ->route('patients.show', $patient)
            ->with('success', 'ROM評価を削除しました。');
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
