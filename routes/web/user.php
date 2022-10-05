<?php

use App\Http\Controllers\User\Profile\Worker\Certifications\{
    DestroyCertificationsController,
    ShowCertificationsController,
    StoreCertificationsController
};
use App\Http\Controllers\User\Profile\Worker\Specialties\{
    DestroySpecialtiesController,
    ShowSpecialtiesController,
    StoreSpecialtiesController
};
use App\Http\Controllers\User\Worker\{
    DestroyMyWorksController,
    ShowMyWorksController,
    StoreMyWorksController
};
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::prefix('/user')->name('user')->group(function () {
        Route::prefix('/profile')->name('.profile')->group(function () {
            Route::prefix('/worker')->name('.worker')->middleware('worker-profile')->group(function () {
                Route::prefix('/specialties')->name('.specialties')->group(function () {
                    Route::get('', ShowSpecialtiesController::class)->name('.show');
                    Route::post('', StoreSpecialtiesController::class)->name('.store');
                    Route::delete('', DestroySpecialtiesController::class)->name('.destroy');
                });

                Route::prefix('/certifications')->name('.certifications')->group(function () {
                    Route::get('', ShowCertificationsController::class)->name('.show');
                    Route::post('', StoreCertificationsController::class)->name('.store');
                    Route::delete('', DestroyCertificationsController::class)->name('.destroy');
                });
            });
        });

        Route::prefix('/worker')->name('.worker')->middleware('worker-profile')->group(function () {
            Route::prefix('/my-works')->name('.my-works')->group(function () {
                Route::get('', ShowMyWorksController::class);
                Route::post('', StoreMyWorksController::class)->name('.store');
                Route::delete('', DestroyMyWorksController::class)->name('.destroy');
            });
        });
    });
});