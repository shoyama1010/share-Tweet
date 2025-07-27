<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Auth as FirebaseAuth;
use App\Models\User;
use Kreait\Firebase\Auth;

class AuthController extends Controller
{
    protected $firebaseAuth;

    public function __construct(FirebaseAuth $firebaseAuth)
    {
        $this->firebaseAuth = $firebaseAuth;
    }

    public function firebaseLogin(Request $request)
    {
        Log::info('🔥 firebaseLoginが呼ばれた');
        Log::info('Authorizationヘッダー: ' . $request->header('Authorization'));

        $idToken = $request->bearerToken();

        try { // フロントから送られてきた Firebase ID トークン
            $idToken = $request->bearerToken();

            if (!$idToken) {
                return response()->json(['error' => 'No Firebase token provided'], 401);
            }

            // 🔹 Firebase でトークン検証
            $verifiedIdToken = $this->firebaseAuth->verifyIdToken($idToken);
            if (!$verifiedIdToken) {
                return response()->json(['error' => 'Invalid Firebase token'], 401);
            }

            // 🔹 Firebase UID を取得
            $uid = $verifiedIdToken->claims()->get('sub');
            $firebaseUser = $this->firebaseAuth->getUser($uid);

            // 🔹 Laravel の DB にユーザーを同期（なければ作成、あれば更新）
            $user = User::updateOrCreate(
                ['firebase_uid' => $uid],
                [
                    'email' => $firebaseUser->email,
                    'name' => $firebaseUser->displayName ?? 'No Name',
                    'email_verified_at' => now(),
                ]
            );

            return response()->json([
                'message' => 'Firebase user synced successfully',
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Authentication failed: ' . $e->getMessage()], 401);
        }
    }
}
