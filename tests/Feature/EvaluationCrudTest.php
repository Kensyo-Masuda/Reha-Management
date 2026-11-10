<?php

namespace Tests\Feature;

use App\Models\BbsEvaluation;
use App\Models\MmtEvaluation;
use App\Models\Patient;
use App\Models\RomEvaluation;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class EvaluationCrudTest extends TestCase
{
    use DatabaseTransactions;

    public function test_mmt_evaluation_can_be_created_updated_and_deleted_with_its_results(): void
    {
        $this->assertSame('mysql', DB::connection()->getDriverName());
        $patient = $this->createPatient('MMT患者');
        $itemId = $this->createAssessmentItem();

        $this->post(route('mmt_evaluations.store', $patient), [
            'evaluated_at' => '2026-11-09',
            'evaluator' => '評価者A',
            'results' => [$itemId => ['right' => 4, 'left' => null]],
        ])->assertRedirect(route('patients.show', $patient));

        $evaluation = MmtEvaluation::query()->where('patient_id', $patient->id)->firstOrFail();
        $this->assertDatabaseHas('mmt_evaluation_results', [
            'mmt_evaluation_id' => $evaluation->id,
            'assessment_item_id' => $itemId,
            'side' => 'right',
            'grade' => 4,
        ]);

        $this->put(route('mmt_evaluations.update', [$patient, $evaluation->id]), [
            'evaluated_at' => '2026-11-10',
            'evaluator' => '評価者B',
            'results' => [$itemId => ['right' => 2, 'left' => 3]],
        ])->assertRedirect(route('mmt_evaluations.show', [$patient, $evaluation->id]));

        $this->assertDatabaseHas('mmt_evaluations', [
            'id' => $evaluation->id,
            'evaluated_at' => '2026-11-10',
            'evaluator' => '評価者B',
        ]);
        $this->assertDatabaseHas('mmt_evaluation_results', [
            'mmt_evaluation_id' => $evaluation->id,
            'assessment_item_id' => $itemId,
            'side' => 'right',
            'grade' => 2,
        ]);
        $this->assertSame(
            2,
            DB::table('mmt_evaluation_results')
                ->where('mmt_evaluation_id', $evaluation->id)
                ->count()
        );

        $this->delete(route('mmt_evaluations.destroy', [$patient, $evaluation->id]))
            ->assertRedirect(route('patients.show', $patient));

        $this->assertDatabaseMissing('mmt_evaluations', ['id' => $evaluation->id]);
        $this->assertDatabaseMissing('mmt_evaluation_results', [
            'mmt_evaluation_id' => $evaluation->id,
        ]);
    }

    public function test_rom_evaluation_can_be_created_updated_and_deleted_with_its_results(): void
    {
        $this->assertSame('mysql', DB::connection()->getDriverName());
        $patient = $this->createPatient('ROM患者');
        $itemId = $this->createAssessmentItem();

        $this->post(route('rom_evaluations.store', $patient), [
            'evaluated_at' => '2026-11-09',
            'evaluator' => '評価者A',
            'results' => [
                $itemId => [
                    'right' => ['active' => 45.5],
                    'left' => ['passive' => 60],
                ],
            ],
        ])->assertRedirect(route('patients.show', $patient));

        $evaluation = RomEvaluation::query()->where('patient_id', $patient->id)->firstOrFail();
        $this->assertDatabaseHas('rom_evaluation_results', [
            'rom_evaluation_id' => $evaluation->id,
            'assessment_item_id' => $itemId,
            'side' => 'right',
            'movement_type' => 'active',
            'angle' => 45.5,
        ]);

        $this->put(route('rom_evaluations.update', [$patient, $evaluation->id]), [
            'evaluated_at' => '2026-11-10',
            'evaluator' => '評価者B',
            'results' => [
                $itemId => [
                    'right' => ['active' => 50.5],
                ],
            ],
        ])->assertRedirect(route('rom_evaluations.show', [$patient, $evaluation->id]));

        $this->assertDatabaseHas('rom_evaluations', [
            'id' => $evaluation->id,
            'evaluated_at' => '2026-11-10',
            'evaluator' => '評価者B',
        ]);
        $this->assertDatabaseHas('rom_evaluation_results', [
            'rom_evaluation_id' => $evaluation->id,
            'assessment_item_id' => $itemId,
            'side' => 'right',
            'movement_type' => 'active',
            'angle' => 50.5,
        ]);
        $this->assertSame(
            1,
            DB::table('rom_evaluation_results')
                ->where('rom_evaluation_id', $evaluation->id)
                ->count()
        );

        $this->delete(route('rom_evaluations.destroy', [$patient, $evaluation->id]))
            ->assertRedirect(route('patients.show', $patient));

        $this->assertDatabaseMissing('rom_evaluations', ['id' => $evaluation->id]);
        $this->assertDatabaseMissing('rom_evaluation_results', [
            'rom_evaluation_id' => $evaluation->id,
        ]);
    }

    public function test_mmt_and_rom_evaluations_cannot_be_updated_or_deleted_through_another_patient(): void
    {
        $patient = $this->createPatient('別患者');
        $owner = $this->createPatient('評価の所有患者');
        $itemId = $this->createAssessmentItem();

        $mmtEvaluation = MmtEvaluation::create([
            'patient_id' => $owner->id,
            'evaluated_at' => '2026-11-09',
            'evaluator' => 'MMT評価者',
        ]);
        $mmtEvaluation->results()->create([
            'assessment_item_id' => $itemId,
            'side' => 'right',
            'grade' => 4,
        ]);

        $romEvaluation = RomEvaluation::create([
            'patient_id' => $owner->id,
            'evaluated_at' => '2026-11-09',
            'evaluator' => 'ROM評価者',
        ]);
        $romEvaluation->results()->create([
            'assessment_item_id' => $itemId,
            'side' => 'right',
            'movement_type' => 'active',
            'angle' => 45,
        ]);

        $this->put(route('mmt_evaluations.update', [$patient, $mmtEvaluation->id]), [
            'evaluated_at' => '2026-11-10',
            'evaluator' => '不正な更新',
            'results' => [$itemId => ['right' => 1]],
        ])->assertNotFound();

        $this->delete(route('mmt_evaluations.destroy', [$patient, $mmtEvaluation->id]))
            ->assertNotFound();

        $this->put(route('rom_evaluations.update', [$patient, $romEvaluation->id]), [
            'evaluated_at' => '2026-11-10',
            'evaluator' => '不正な更新',
            'results' => [$itemId => ['right' => ['active' => 1]]],
        ])->assertNotFound();

        $this->delete(route('rom_evaluations.destroy', [$patient, $romEvaluation->id]))
            ->assertNotFound();

        $this->assertDatabaseHas('mmt_evaluations', [
            'id' => $mmtEvaluation->id,
            'patient_id' => $owner->id,
            'evaluator' => 'MMT評価者',
        ]);
        $this->assertDatabaseHas('rom_evaluations', [
            'id' => $romEvaluation->id,
            'patient_id' => $owner->id,
            'evaluator' => 'ROM評価者',
        ]);
        $this->assertDatabaseHas('mmt_evaluation_results', [
            'mmt_evaluation_id' => $mmtEvaluation->id,
            'grade' => 4,
        ]);
        $this->assertDatabaseHas('rom_evaluation_results', [
            'rom_evaluation_id' => $romEvaluation->id,
            'angle' => 45,
        ]);
    }

    public function test_patient_rehabilitation_and_bbs_pages_and_registration_still_work(): void
    {
        $this->assertSame('mysql', DB::connection()->getDriverName());
        $patient = $this->createPatient('既存機能患者');

        $this->get(route('patients.show', $patient))->assertOk();
        $this->get(route('rehabilitation_records.index', $patient))->assertOk();
        $this->get(route('bbs_evaluations.index', $patient))->assertOk();

        $this->post(route('rehabilitation_records.store', $patient), [
            'performed_at' => '2026-11-09',
            'therapist' => '担当者',
            'content' => '歩行練習',
            'duration' => 20,
        ])->assertRedirect(route('rehabilitation_records.index', $patient));

        $this->assertDatabaseHas('rehabilitation_records', [
            'patient_id' => $patient->id,
            'content' => '歩行練習',
        ]);

        $this->post(route('bbs_evaluations.store', $patient), [
            'evaluated_at' => '2026-11-09',
            'evaluator' => 'BBS評価者',
            'scores' => array_fill(1, 14, 3),
        ])->assertRedirect(route('patients.show', $patient));

        $bbsEvaluation = BbsEvaluation::query()->where('patient_id', $patient->id)->firstOrFail();
        $this->assertSame(
            14,
            DB::table('bbs_evaluation_results')
                ->where('bbs_evaluation_id', $bbsEvaluation->id)
                ->count()
        );
        $this->get(route('patients.show', $patient))
            ->assertOk()
            ->assertSee('BBS評価履歴')
            ->assertSee(route('bbs_evaluations.show', [$patient, $bbsEvaluation]));
        $this->get(route('bbs_evaluations.show', [$patient, $bbsEvaluation]))->assertOk();
    }

    public function test_bbs_evaluation_edit_uses_shared_form_and_update_returns_to_patient_details(): void
    {
        $this->assertSame('mysql', DB::connection()->getDriverName());
        $patient = $this->createPatient('BBS編集患者');
        $bbsEvaluation = BbsEvaluation::create([
            'patient_id' => $patient->id,
            'evaluated_at' => '2026-11-09',
            'evaluator' => '更新前評価者',
        ]);
        $bbsEvaluation->results()->create([
            'item_number' => 1,
            'score' => 2,
        ]);

        $this->get(route('bbs_evaluations.edit', [$patient, $bbsEvaluation]))
            ->assertOk()
            ->assertViewIs('bbs_evaluations.create')
            ->assertSee('BBS評価の編集')
            ->assertSee('患者名：BBS編集患者 さん');

        $this->put(route('bbs_evaluations.update', [$patient, $bbsEvaluation]), [
            'evaluated_at' => '2026-11-10',
            'evaluator' => '更新後評価者',
            'scores' => array_fill(1, 14, 4),
        ])->assertRedirect(route('patients.show', $patient));

        $this->assertDatabaseHas('bbs_evaluations', [
            'id' => $bbsEvaluation->id,
            'evaluated_at' => '2026-11-10',
            'evaluator' => '更新後評価者',
        ]);
        $this->assertDatabaseHas('bbs_evaluation_results', [
            'bbs_evaluation_id' => $bbsEvaluation->id,
            'item_number' => 1,
            'score' => 4,
        ]);
    }

    private function createPatient(string $name): Patient
    {
        return Patient::create([
            'name' => $name,
            'birth_date' => '1980-01-01',
            'diagnosis' => '診断',
            'medical_history' => null,
        ]);
    }

    private function createAssessmentItem(): int
    {
        return DB::table('assessment_items')->insertGetId([
            'body_region' => 'テスト部位',
            'movement' => 'テスト運動',
            'sort_order' => 999,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
