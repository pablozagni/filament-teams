<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\AcceptInvitation;
use App\Livewire\RejectInvitation;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/invitation/{token}/accept', AcceptInvitation::class)->name('invitation.accept');
Route::get('/invitation/{token}/reject', RejectInvitation::class)->name('invitation.reject');
