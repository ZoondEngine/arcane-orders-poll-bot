<?php

use App\Services\Telegram\Concerns\InteractionWithUserSubscriptions;
use App\Services\Telegram\Scenes\SubscriptionScene;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/test', function() {
    ///** @var \App\Repositories\Poll\PollRepository $repository */
    //$repository = app(\App\Repositories\Poll\PollRepository::class);
    //$repository->query()->get()->each->delete();

    return SubscriptionScene::aggregateAvailableSubscriptions(\App\Models\TelegramUser::query()->find(1));
});
