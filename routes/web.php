<?php

//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Route;
//
//
//Route::get('/', function (Request $request) {
//    $user = $request->user();
//$user = Auth::user();
//    if ($user) {
//        return response()->json([
//            'id' => $user->id,
//            'name' => $user->name,
//            'email' => $user->email,
//        ]);
//    }
//})->middleware('auth');



use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/', function () {
return \Illuminate\Support\Facades\Auth::user();
//    $users = User::all();
    $users = Auth::user();
    return response()->json($users);
});
//
//
//Route::get('/user/{id}', function ($id) {
//    $user = User::find($id);
//    if ($user) {
//        return response()->json([
//            'id' => $user->id,
//            'name' => $user->name,
//            'email' => $user->email,
//        ]);
//    }
//    return response()->json(['message' => 'User not found'], 404);
//});

//Route::post('/login', function () {
//    $credentials = request(['email', 'password']);
//
//    if (Auth::attempt($credentials)) {
//        $user = Auth::user();
//        return response()->json([
//            'id' => $user->id,
//            'name' => $user->name,
//            'email' => $user->email,
//        ]);
//    }
//
//    return response()->json(['message' => 'Invalid credentials'], 401);
//});
//
//Route::middleware('auth:sanctum')->get('/user', function () {
//    $user = Auth::user();
//
//    if ($user) {
//        return response()->json([
//            'id' => $user->id,
//            'name' => $user->name,
//            'email' => $user->email,
//        ]);
//    }
//
//    return response()->json(['message' => 'Not authenticated'], 401);
//});










