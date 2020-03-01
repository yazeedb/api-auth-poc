<?php


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::post('/login', function () {
    $client = new Google_Client([
        'client_id' =>
        '235318923218-s6tms65fam3o6d51shlhmci587s5mi22.apps.googleusercontent.com'
    ]);

    $idToken = Request::get('id_token');

    /**
     * verifyIdToken verifies the following...
     * JWT signature
     * the "aud" claim
     * the "exp" claim
     * the "iss' claim
     */
    $payload = $client->verifyIdToken($idToken);

    // Take $payload['email'] field
    // Check if user exists in table
    // If exists, return 204
    // Else, add to DB and return 204

    if ($payload) {
        $userId = $payload['sub'];
        $userEmail = $payload['email'];

        $existingUser = DB::table('users')
            ->where('id', $userId)
            ->first();

        if (!$existingUser) {
            DB::table('users')->insert([
                'id' => $userId,
                'email' => $userEmail
            ]);
        }

        return response()->json($payload);
    } else {
        return response()->json([
            'error' => 'Invalid Credentials'
        ], 403);
    }
});
