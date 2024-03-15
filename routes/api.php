<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Collection;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/user', function (){
    global $users;
    return ($users);
});


Route::get('/user/{userName}', function ($userName){
    global $users;
    foreach ($users as $user) {
        if ($user['name'] === $userName) {
            return $user;
        }
    }

    return ['error' => 'Cannot find user with name ' . $userName];
})->whereAlpha('userName')->name('user.byName');

Route::get('/user/{userIndex}', function ($userIndex){
    global $users;
    $userIndex = (int) $userIndex;
    $totalUsers = count($users);

    if ($userIndex >= 0 && $userIndex < $totalUsers) {
        return $users[$userIndex];
    } else {
        return ['error' => 'Cannot find user with index ' . $userIndex];
    }
})->whereNumber('userIndex')->name('user.byIndex');


Route::prefix('/user')->group(function (){
    Route::get('/{userIndex}', function ($userIndex){
        global $users;
        $userIndex = (int) $userIndex;
        $totalUsers = count($users);

        if ($userIndex >= 0 && $userIndex < $totalUsers) {
            return $users[$userIndex];
        }

        return ['error' => 'Cannot find user with index ' . $userIndex];
    })->whereNumber('userIndex')->name('user.byIndex');

    Route::get('/{userName}', function ($userName) {
        global $users;
        foreach ($users as $user) {
            if ($user['name'] === $userName) {
                return $user;
            }
        }

        return ['error' => 'Cannot find user with name ' . $userName];
    })->whereAlpha('userName')->name('user.byName');

    Route::fallback(function () {
        return ['error' => 'You can not get user like this'];
    });
});


Route::prefix('/user')->group(function (){
    // API route to get a post by userIndex and postIndex
    Route::get('/{userIndex}/post/{postIndex}', function ($userIndex, $postIndex) {
        global $users;
        $userIndex = (int) $userIndex;
        $postIndex = (int) $postIndex;

        $totalUsers = count($users);

        if ($userIndex >= 0 && $userIndex < $totalUsers) {
            $user = $users[$userIndex];

            $userPosts = $user['posts'];

            if ($postIndex >= 0 && $postIndex < count($userPosts)) {
                return $userPosts[$postIndex];
            } else {
                return ['error' => 'Cannot find post with id ' . $postIndex . ' for user ' . $userIndex];
            }
        }

        return ['error' => 'Cannot find user with index ' . $userIndex];
    })->whereNumber(['userIndex', 'postIndex'])->name('user.post');
});