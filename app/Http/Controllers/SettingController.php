<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    // ========================== AMBIL DATA SETTING ==========================
    public function index()
    {
        return response()->json(Setting::first());
    }

    // ========================== UPDATE DATA SETTING ==========================
    public function update(Request $request)
    {
        if (request()->wantsJson()) {
            $request->validate([
                'app' => 'required',
                'school' => 'required',
                'description' => 'required',
                'pemerintah' => 'required',
                'alamat' => 'required',
                'telepon' => 'required',
                'email' => 'required',
                'logo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'bg_auth' => 'required|image|mimes:jpeg,png,jpg|max:8192',
            ]);

            $setting = Setting::first();

            if ($setting->logo != null && $setting->logo != '') {
                $old_photo = str_replace('/storage/', '', $setting->logo);
                Storage::disk('public')->delete($old_photo);
            }

            if ($setting->bg_auth != null && $setting->bg_auth != '') {
                $old_photo = str_replace('/storage/', '', $setting->bg_auth);
                Storage::disk('public')->delete($old_photo);
            }

            if ($setting->logo_sekolah != null && $setting->logo_sekolah != '') {
                $old_photo = str_replace('/storage/', '', $setting->logo_sekolah);
                Storage::disk('public')->delete($old_photo);
            }


            if ($setting->bg_landing_page != null && $setting->bg_landing_page != '') {
                $old_photo = str_replace('/storage/', '', $setting->bg_landing_page);
                Storage::disk('public')->delete($old_photo);
            }

            $data = $request->all();

            if ($request->hasFile('logo')) {
                $data['logo'] = '/storage/' . $request->file('logo')->store('setting', 'public');
            }


            if ($request->hasFile('bg_landing_page')) {
                $data['bg_landing_page'] = '/storage/' . $request->file('bg_landing_page')->store('setting', 'public');
            }

            if ($request->hasFile('bg_auth')) {
                $data['bg_auth'] = '/storage/' . $request->file('bg_auth')->store('setting', 'public');
            }

            if ($request->hasFile('logo_sekolah')) {
                $data['logo_sekolah'] = '/storage/' . $request->file('logo_sekolah')->store('setting', 'public');
            }

            $setting->update($data);

            return response()->json([
                'message' => 'Berhasil memperbarui data Konfigurasi Website',
                'data' => $setting
            ]);
        } else {
            abort(404);
        }
    }
}
