<?php

use App\Http\Controllers\BbsEvaluationController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\MmtEvaluationController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\RehabilitationRecordController;
use App\Http\Controllers\RomEvaluationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('patients.index');
});

Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');

Route::get('/patients/create', [PatientController::class, 'create'])->name('patients.create');

Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');

Route::get('/patients/{patient}', [PatientController::class, 'show'])
    ->name('patients.show');

Route::get('/patients/{patient}/edit', [PatientController::class, 'edit'])
    ->name('patients.edit');

Route::put('/patients/{patient}', [PatientController::class, 'update'])
    ->name('patients.update');

Route::delete('/patients/{patient}', [PatientController::class, 'destroy'])
    ->name('patients.destroy');

Route::get('/patients/{patient}/rehabilitation-records', [RehabilitationRecordController::class, 'index'])
    ->name('rehabilitation_records.index');

Route::get('/patients/{patient}/rehabilitation-records/create', [RehabilitationRecordController::class, 'create'])
    ->name('rehabilitation_records.create');

Route::post('/patients/{patient}/rehabilitation-records', [RehabilitationRecordController::class, 'store'])
    ->name('rehabilitation_records.store');

Route::get('/patients/{patient}/rehabilitation-records/{record}/edit', [RehabilitationRecordController::class, 'edit'])
    ->name('rehabilitation_records.edit');

Route::put('/patients/{patient}/rehabilitation-records/{record}', [RehabilitationRecordController::class, 'update'])
    ->name('rehabilitation_records.update');

Route::delete('/patients/{patient}/rehabilitation-records/{record}', [RehabilitationRecordController::class, 'destroy'])
    ->name('rehabilitation_records.destroy');

// 機能評価一覧
Route::get('/patients/{patient}/evaluations', [EvaluationController::class, 'index'])
    ->name('evaluations.index');

// 機能評価の登録画面
Route::get('/patients/{patient}/evaluations/create', [EvaluationController::class, 'create'])
    ->name('evaluations.create');

// 機能評価の登録処理
Route::post('/patients/{patient}/evaluations', [EvaluationController::class, 'store'])
    ->name('evaluations.store');

// 機能評価の編集画面
Route::get('/patients/{patient}/evaluations/{evaluation}/edit', [EvaluationController::class, 'edit'])
    ->name('evaluations.edit');

// 機能評価の更新処理
Route::put('/patients/{patient}/evaluations/{evaluation}', [EvaluationController::class, 'update'])
    ->name('evaluations.update');

// 機能評価の削除処理
Route::delete('/patients/{patient}/evaluations/{evaluation}', [EvaluationController::class, 'destroy'])
    ->name('evaluations.destroy');

// ROM評価の登録画面
Route::get('/patients/{patient}/rom-evaluations/create', [RomEvaluationController::class, 'create'])
    ->name('rom_evaluations.create');

// ROM評価の保存処理
Route::post('/patients/{patient}/rom-evaluations', [RomEvaluationController::class, 'store'])
    ->name('rom_evaluations.store');

// ROM評価の詳細画面
Route::get('/patients/{patient}/rom-evaluations/{romEvaluationId}', [RomEvaluationController::class, 'show'])
    ->name('rom_evaluations.show');

Route::get('/patients/{patient}/rom-evaluations/{romEvaluationId}/edit', [RomEvaluationController::class, 'edit'])
    ->name('rom_evaluations.edit');

Route::put('/patients/{patient}/rom-evaluations/{romEvaluationId}', [RomEvaluationController::class, 'update'])
    ->name('rom_evaluations.update');

Route::delete('/patients/{patient}/rom-evaluations/{romEvaluationId}', [RomEvaluationController::class, 'destroy'])
    ->name('rom_evaluations.destroy');

// MMT評価の新規登録画面
Route::get('/patients/{patient}/mmt-evaluations/create', [MmtEvaluationController::class, 'create'])
    ->name('mmt_evaluations.create');

// MMT評価の保存
Route::post('/patients/{patient}/mmt-evaluations', [MmtEvaluationController::class, 'store'])
    ->name('mmt_evaluations.store');

// MMT評価の詳細表示
Route::get('/patients/{patient}/mmt-evaluations/{mmtEvaluationId}', [MmtEvaluationController::class, 'show'])
    ->name('mmt_evaluations.show');

Route::get('/patients/{patient}/mmt-evaluations/{mmtEvaluationId}/edit', [MmtEvaluationController::class, 'edit'])
    ->name('mmt_evaluations.edit');

Route::put('/patients/{patient}/mmt-evaluations/{mmtEvaluationId}', [MmtEvaluationController::class, 'update'])
    ->name('mmt_evaluations.update');

Route::delete('/patients/{patient}/mmt-evaluations/{mmtEvaluationId}', [MmtEvaluationController::class, 'destroy'])
    ->name('mmt_evaluations.destroy');

// BBS評価
Route::get('/patients/{patient}/bbs-evaluations/create', [BbsEvaluationController::class, 'create'])
    ->name('bbs_evaluations.create');

Route::post('/patients/{patient}/bbs-evaluations', [BbsEvaluationController::class, 'store'])
    ->name('bbs_evaluations.store');

// BBS評価履歴一覧
Route::get('/patients/{patient}/bbs-evaluations', [BbsEvaluationController::class, 'index'])
    ->name('bbs_evaluations.index');

// BBS評価詳細
Route::get('/patients/{patient}/bbs-evaluations/{bbsEvaluation}', [BbsEvaluationController::class, 'show'])
    ->name('bbs_evaluations.show');

Route::get('/patients/{patient}/bbs-evaluations/{bbsEvaluation}/edit', [BbsEvaluationController::class, 'edit'])
    ->name('bbs_evaluations.edit');

Route::put('/patients/{patient}/bbs-evaluations/{bbsEvaluation}', [BbsEvaluationController::class, 'update'])
    ->name('bbs_evaluations.update');

Route::delete('/patients/{patient}/bbs-evaluations/{bbsEvaluation}', [BbsEvaluationController::class, 'destroy'])
    ->name('bbs_evaluations.destroy');
