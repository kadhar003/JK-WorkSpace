<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TeamController;
Route::get('/', function () {
    return view('welcome');
});

Route::get('/invitations/{invitation}/accept', [App\Http\Controllers\TeamController::class, 'accept'])->name('invitations.accept');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/workspaces', [WorkspaceController::class, 'index']);
    Route::post('/workspaces', [WorkspaceController::class, 'store']);

});

Route::middleware(['auth'])->group(function () {

    Route::get('/chat/{workspace}', [ChatController::class, 'index']);

    Route::post('/chat/send', [ChatController::class, 'send']);

});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {

        $user = auth()->user();

        return view('dashboard', [
            'workspaceCount' => $user->workspaces()->count(),
            'usageCount' => $user->usage_count,
            'plan' => $user->plan,
        ]);

    })->name('dashboard');

    Route::middleware(['auth'])->group(function () {

        Route::get('/tasks/{workspace}', [TaskController::class, 'index']);

        Route::post('/tasks/create', [TaskController::class, 'store']);

        Route::post('/tasks/update-status', [TaskController::class, 'updateStatus']);

    });

    Route::post('/tasks/update-status', [TaskController::class, 'updateStatus']);

    

Route::middleware(['auth'])->group(function () {

    Route::get('/notes/{workspace}', [NoteController::class, 'index']);

    Route::post('/notes/update', [NoteController::class, 'update']);

});

Route::middleware(['auth'])->group(function () {

    Route::get(
        '/projects/{project}/files',
        [FileController::class, 'index']
    );

    Route::get(
        '/files/{file}',
        [FileController::class, 'show']
    );

    Route::post(
        '/files/save',
        [FileController::class, 'save']
    );

});

    Route::get('/team', [TeamController::class, 'index'])->name('team.index');
    Route::post('/team/invite', [TeamController::class, 'invite'])->name('team.invite');
    Route::delete('/team/invitations/{invitation}/withdraw', [TeamController::class, 'withdraw'])->name('team.withdraw');
    
    Route::get('/billing', [PaymentController::class, 'index']);
    Route::post('/billing/create-order', [PaymentController::class, 'createOrder']);
    Route::post('/billing/verify', [PaymentController::class, 'verifyPayment']);

});

require __DIR__.'/auth.php';
