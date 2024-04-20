<?php

namespace App\Repositories;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Interfaces\UserAuthRepositoryInterface;
use Illuminate\Http\Request;
use Twilio\Rest\Client;

class UserAuthRepository implements UserAuthRepositoryInterface
{

    public function login(array $data): array
    {
        if (!$token = auth('api')->attempt($data)) {
            return failedResponse(404, ['error' => 'These credentials do not match our records.']);
        }

        $result = new UserResource(auth()->user());
        $result['token'] = createNewToken($token);
        return successResponse(200, $result);
    }

    public function register(array $data): array
    {
        sendVerificationSMS($data['phone_number']);

        $user = User::create([
            'name' => $data['name'],
            'user_name' => $data['user_name'],
            'phone_number' => $data['phone_number'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'image' => $data['image'],
            'address' => $data['address'],
        ]);

        if ($user->save()) {
            $token = auth('api')->attempt($data);
            $result = new UserResource($user);
            $result['token'] = createNewToken($token);

            return successResponse(201, $result);
        }
        return failedResponse(400, ['error' => 'Bad Request']);
    }

    public function verify(Request $request)
    {
        $data = $request->validate([
            'verification_code' => ['required', 'numeric'],
            'phone_number' => ['required', 'string'],
        ]);
        /* Get credentials from .env */
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_sid = getenv("TWILIO_SID");
        $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");

        $twilio = new Client($twilio_sid, $token);

        try {
            $verification = $twilio->verify->v2->services($twilio_verify_sid)
                ->verificationChecks
                ->create(['code' => $data['verification_code'], 'to' => $data['phone_number']]);
        } catch (\Exception $e) {
            return failedResponse(500, ['error' => 'no Verification']);
        }

        if ($verification->status === 'approved') {
            $user = User::where('phone', $data['phone'])->first();
            $user->isVerified = true;

            if ($user->save()) {

                $token = auth()->attempt($data);
                $result = new UserResource($user);
                $result['token'] = createNewToken($token);

                return successResponse(201, $result);
            }

        }

        return failedResponse(400, ['error' => 'Bad Request']);
    }

    public function user_profile(): array
    {
        $user = auth('api')->user();
        return successResponse(200, new UserResource($user));
    }

    public function logout(): array
    {
        auth('api')->logout();
        return successResponse(200, ['message' => 'User logged out successfully']);
    }


}
