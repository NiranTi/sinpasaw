<?php
// ── app/Http/Controllers/Tenant/PengaturanController.php ────────────────────
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class PengaturanController extends Controller
{
    /* ── Halaman Pengaturan ─────────────────────────────────────────── */
    public function index()
    {
        $tenant = Auth::user()->tenant;
        $user   = Auth::user();
        return view('tenant.pengaturan', compact('tenant', 'user'));
    }

    /* ── Update Profil Toko ─────────────────────────────────────────── */
    public function updateProfil(Request $request)
    {
        $user   = Auth::user();
        $tenant = $user->tenant;

        $request->validate([
            'nama_tenant' => 'required|string|max:255',
            'kategori'    => 'nullable|string|max:100',
            'blok'        => 'nullable|string|max:50',
            'nama_pemilik'=> 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'no_hp'       => 'nullable|string|max:20',
            'foto'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        /* ── Update tenant data ── */
        $tenantData = [
            'nama_tenant' => $request->nama_tenant,
            'kategori'    => $request->kategori,
            'blok'        => $request->blok,
        ];

        /* ── Handle foto upload ── */
        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($tenant->foto && Storage::disk('public')->exists($tenant->foto)) {
                Storage::disk('public')->delete($tenant->foto);
            }
            $path = $request->file('foto')->store('tenant-photos', 'public');
            $tenantData['foto'] = 'storage/' . $path;
        }

        $tenant->update($tenantData);

        /* ── Update user data ── */
        $user->update([
            'name'  => $request->nama_pemilik,
            'email' => $request->email,
            'phone' => $request->no_hp, // Tambahkan kolom 'phone' di tabel users via migration
        ]);

        return redirect()->route('tenant.pengaturan')
                         ->with('alert', 'Profil berhasil disimpan!');
    }

    /* ── Update Kata Sandi ──────────────────────────────────────────── */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'kata_sandi_lama' => 'required|string',
            'kata_sandi_baru' => ['required', 'confirmed', Password::min(8)],
        ], [
            'kata_sandi_baru.confirmed' => 'Kata sandi baru tidak cocok.',
            'kata_sandi_baru.min'       => 'Kata sandi minimal 8 karakter.',
        ]);

        $user = Auth::user();

        /* Verifikasi kata sandi lama */
        if (!Hash::check($request->kata_sandi_lama, $user->password)) {
            return back()->withErrors(['kata_sandi_lama' => 'Kata sandi lama tidak sesuai.'])
                         ->withInput();
        }

        $user->update(['password' => Hash::make($request->kata_sandi_baru)]);

        return redirect()->route('tenant.pengaturan')
                         ->with('alert', 'Kata sandi berhasil diubah!');
    }
}
