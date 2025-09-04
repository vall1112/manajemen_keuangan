<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    // ========================== AMBIL DATA USER YANG SEDANG LOGIN ==========================
    public function me()
    {
        $user = auth()->user()->load('student.classroom');

        return response()->json([
            'user' => $user
        ]);
    }

    // ========================== LOGIN DENGAN EMAIL ==========================
    public function loginEmail(Request $request)
    {
        $validator = Validator::make($request->post(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json([
                'status' => false,
                'message' => 'Email / Password salah!'
            ], 401);
        }

        return response()->json([
            'status' => true,
            'user' => auth()->user(),
            'token' => $token
        ]);
    }

    // ========================== LOGIN DENGAN USERNAME ==========================
    public function loginUsername(Request $request)
    {
        $validator = Validator::make($request->post(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        $credentials = $validator->validated();

        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                'status' => false,
                'message' => 'Username / Password salah!'
            ], 401);
        }

        return response()->json([
            'status' => true,
            'user' => auth()->user(),
            'token' => $token
        ]);
    }

    // ========================== REGISTRASI USER ==========================
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'regex:/^[a-zA-Z][a-zA-Z0-9_]*$/',
                'unique:users,username',
            ],
            'nama' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'username' => $request->username,
            'name' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = auth()->login($user);

        return response()->json([
            'status' => true,
            'message' => 'Registrasi berhasil. Silahkan login.',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    // ========================== KIRIM KODE OTP KE EMAIL ==========================
    public function sendEmailOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->email;

        if (User::where('email', $email)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email sudah terdaftar. Silakan gunakan email lain atau login.'
            ], 400);
        }

        $otp = rand(100000, 999999);
        $otpKey = "otp_{$email}";
        Cache::put($otpKey, $otp, now()->addMinutes(5));

        Mail::to($email)->send(new OtpMail($otp));

        return response()->json([
            'status' => 'success',
            'message' => 'Kode OTP telah dikirim ke email Anda.'
        ]);
    }

    // ========================== CEK KODE OTP EMAIL ==========================
    public function checkEmailOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric|digits:6'
        ]);

        $otpKey = "otp_{$request->email}";
        $cachedOtp = Cache::get($otpKey);

        if (!$cachedOtp || (string)$cachedOtp !== (string)$request->otp) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kode OTP salah atau telah kadaluarsa.'
            ], 400);
        }

        Cache::forget($otpKey);

        return response()->json([
            'status' => 'success',
            'message' => 'Kode OTP berhasil diverifikasi.'
        ]);
    }

    // ========================== LOGOUT ==========================
    public function logout()
    {
        auth()->logout();
        return response()->json(['success' => true]);
    }

    // ========================== MENGAMBIL DATA USER SISWA ==========================
    public function profile()
    {
        $user = auth()->user()->load([
            'student.classroom'
        ]);

        return response()->json([
            'user'      => $user,
            'siswa'     => $user->student,
            'classroom' => $user->student?->classroom,
        ]);
    }

    // ========================== MENGUPDATE DATA USER SISWA ==========================
    public function updateStudent(Request $request)
    {
        $user = Auth::user();

        // --- Update User ---
        $userData = [
            'username' => $request->input('username', $user->username),
            'email'    => $request->input('email', $user->email), // <-- sinkronkan email
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        // --- Update Student ---
        $studentData = [
            'nama'          => $request->input('nama', $user->student->nama),
            'nis'           => $request->input('nis', $user->student->nis),
            'jenis_kelamin' => $request->input('jenis_kelamin', $user->student->jenis_kelamin),
            'tempat_lahir'  => $request->input('tempat_lahir', $user->student->tempat_lahir),
            'tanggal_lahir' => $request->input('tanggal_lahir', $user->student->tanggal_lahir),
            'email'         => $request->input('email', $user->student->email),
            'telepon'       => $request->input('telepon', $user->student->telepon),
            'alamat'        => $request->input('alamat', $user->student->alamat),
            'classroom_id'  => $request->input('classroom_id', $user->student->classroom_id),
            'status'        => $request->input('status', $user->student->status),
        ];

        $user->student()->update($studentData);

        return response()->json([
            'success' => true,
            'user'    => $user->fresh('student'),
        ]);
    }

    // ========================== MENGUPDATE DATA USER SELAIN SISWA ==========================
    public function updateUser(Request $request)
    {
        $user = auth()->user(); // ambil user login

        $validatedData = $request->validate([
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'name'  => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:6|confirmed',
            'photo'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $validatedData['photo'] = $request->file('photo')->store('photo', 'public');
        } else {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
                $validatedData['photo'] = null;
            }

        $user->update($validatedData);

        return response()->json([
            'success' => true,
            'user'    => $user->fresh(),
        ]);
    }
}
}