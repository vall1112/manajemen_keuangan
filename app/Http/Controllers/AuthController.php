<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Auth,
    Cache,
    Hash,
    Mail,
    Storage,
    Validator
};
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    // ========================== AMBIL DATA USER LOGIN ==========================
    public function me()
    {
        return response()->json([
            'user' => auth()->user()
        ]);
    }

    // ========================== LOGIN ==========================
    public function login(Request $request)
    {
        $validator = Validator::make($request->post(), [
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $user = User::where($loginField, $request->login)->first();

        if (!$user) {
            return response()->json([
                'status'  => false,
                'message' => 'Username / Email atau Password salah!',
            ], 401);
        }

        if ($user->status === 'Pending') {
            return response()->json([
                'status'  => false,
                'message' => 'Akun Anda masih menunggu persetujuan dari admin.',
            ], 403);
        }

        if ($user->status === 'Tidak Aktif') {
            return response()->json([
                'status'  => false,
                'message' => 'Akun Anda tidak aktif, silakan hubungi admin.',
            ], 403);
        }

        if ($user->hasRole('admin')) {
            if (!\Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Username / Email atau Password salah!',
                ], 401);
            }
        } else {
            if ($request->password !== $user->password) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Username / Email atau Password salah!',
                ], 401);
            }
        }

        $token = auth('api')->login($user);

        $user->load('student.classroom');

        return response()->json([
            'status' => true,
            'user' => auth()->user(),
            'token'  => $token,
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
            'nama'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'username' => $request->username,
            'name'     => $request->nama,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = auth()->login($user);

        return response()->json([
            'status'  => true,
            'message' => 'Registrasi berhasil. Silakan login.',
            'user'    => $user,
            'token'   => $token,
        ], 201);
    }

    // ========================== CEK EMAIL ==========================
    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'username' => 'required|string|min:3|max:50|regex:/^[a-zA-Z0-9_]+$/',
        ]);

        if (User::where('email', $request->email)->exists()) {
            return response()->json(['message' => 'Email sudah digunakan'], 409);
        }

        if (User::where('username', $request->username)->exists()) {
            return response()->json(['message' => 'Username sudah digunakan'], 409);
        }

        return response()->json(['message' => 'Email dan Username tersedia']);
    }

    // ========================== KIRIM OTP EMAIL ==========================
    public function sendEmailOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'name'  => 'nullable|string|max:100',
        ]);

        $email = strtolower(trim($request->email));
        $userName = $request->name ?? 'Pengguna';

        if (User::where('email', $email)->exists()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Email sudah terdaftar. Silakan gunakan email lain atau login.',
            ], 400);
        }

        $otp = rand(100000, 999999);
        $otpKey = "otp_{$email}";
        Cache::put($otpKey, $otp, now()->addMinutes(5));

        try {
            Mail::to($email)->send(new OtpMail($otp, $userName, $email));
            return response()->json([
                'status'  => 'success',
                'message' => 'Kode OTP telah dikirim ke email Anda.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal mengirim email OTP. Silakan coba lagi.',
                'error'   => config('app.debug') ? $e->getMessage() : null, // tampilkan error hanya saat debug
            ], 500);
        }
    }

    // ========================== CEK OTP EMAIL ==========================
    public function checkEmailOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|numeric|digits:6',
        ]);

        $email = strtolower(trim($request->email));
        $otpKey = "otp_{$email}";
        $cachedOtp = Cache::get($otpKey);

        if (!$cachedOtp) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Kode OTP telah kadaluarsa. Silakan minta ulang.',
            ], 400);
        }

        if ((string) $cachedOtp !== (string) $request->otp) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Kode OTP salah. Silakan periksa kembali.',
            ], 400);
        }

        Cache::forget($otpKey);

        return response()->json([
            'status'  => 'success',
            'message' => 'Kode OTP berhasil diverifikasi.',
        ]);
    }

    // ========================== LOGOUT ==========================
    public function logout()
    {
        auth()->logout();
        return response()->json(['success' => true]);
    }

    // ========================== PROFILE SISWA ==========================
    public function profile()
    {
        $user = auth()->user()->load('student.classroom');
        $user->makeHidden(['student']); // sembunyikan relasi di user

        return response()->json([
            'user'      => $user,
            'siswa'     => $user->student,
            'classroom' => $user->student?->classroom,
        ]);
    }

    // ========================== UPDATE SISWA ==========================
    public function updateStudent(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        $userData = [
            'username' => $request->input('username', $user->username),
            'email'    => $request->input('email', $user->email),
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        $studentData = $request->only([
            'nama',
            'nis',
            'jenis_kelamin',
            'tempat_lahir',
            'tanggal_lahir',
            'email',
            'telepon',
            'alamat',
            'classroom_id',
            'status'
        ]);

        $student->update(array_filter($studentData));

        return response()->json([
            'success' => true,
            'user'    => $user->fresh('student'),
        ]);
    }

    // ========================== UPDATE USER NON-SISWA ==========================
    public function updateUser(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:6|confirmed',
            'photo'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $validated['photo'] = $request->file('photo')->store('photo', 'public');
        }

        $user->update($validated);

        return response()->json([
            'success' => true,
            'user'    => $user->fresh(),
        ]);
    }
}
