<?php

namespace App\Http\Controllers;

use App\Models\BatchObat;
use App\Models\Obat;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
class pagescontroler extends Controller
{
    // ===== Halaman biasa =====

    public function dasboard()
    {
            $batasSegera = today()->addDays(BatchObat::AMBANG_SEGERA_HARI);

    // Ambil semua batch yang masih ada sisa stok, termasuk yang sudah kedaluwarsa
    $obats = Obat::with(['batches' => fn (HasMany $q) => $q->where('sisa_stok', '>', 0)->fefo()])
        ->orderBy('nama')
        ->get()
        ->map(function (Obat $obat) use ($batasSegera) {
            $aktif = $obat->batches->filter(fn (BatchObat $b) => $b->tanggal_kedaluwarsa->gte(today()));
            $lewat = $obat->batches->filter(fn (BatchObat $b) => $b->tanggal_kedaluwarsa->lt(today()));

            $stok = (int) $aktif->sum('sisa_stok');

            $punyaExpired = $lewat->isNotEmpty();
            $menipis      = $stok > 0 && $stok <= $obat->batas_minimum;
            $segera       = $aktif->contains(fn (BatchObat $b) => $b->tanggal_kedaluwarsa->lte($batasSegera));
            $habis        = $stok <= 0;

            // Satu status utama per baris tabel, urut dari yang paling mendesak
            [$status, $prioritas] = match (true) {
                $punyaExpired => ['expired', 1],
                $habis        => ['habis', 2],
                $menipis      => ['menipis', 3],
                $segera       => ['segera', 4],
                default       => ['aman', 5],
            };

            return [
                'nama'         => $obat->nama,
                'stok'         => $stok,
                'satuan'       => $obat->satuan,
                'expired'      => ($aktif->first() ?? $lewat->first())?->tanggal_kedaluwarsa,
                'status'       => $status,
                'prioritas'    => $prioritas,
                'punyaExpired' => $punyaExpired,
                'menipis'      => $menipis,
                'segera'       => $segera,
            ];
        });

    $totalObat         = $obats->count();
    $stokMenipis       = $obats->where('menipis', true)->count();
    $mendekatiExpired  = $obats->where('segera', true)->count();
    $sudahExpired      = $obats->where('punyaExpired', true)->count();

    $ringkasan = $obats->sortBy([['prioritas', 'asc'], ['nama', 'asc']])->take(10);

    return view('dasboard', compact(
        'totalObat', 'stokMenipis', 'mendekatiExpired', 'sudahExpired', 'ringkasan'
    ));
    }

    public function login()
    {
        return view('login');
    }

    public function mengelola(): View
    {
        $obats = Obat::query()
            ->withSum(['batches as stok_tersedia' => fn ($q) => $q->tersedia()], 'sisa_stok')
            ->withMin(['batches as expired_terdekat' => fn ($q) => $q->tersedia()], 'tanggal_kedaluwarsa')
            ->orderBy('nama')
            ->get();

        $kategoris = Obat::select('kategori')->distinct()->orderBy('kategori')->pluck('kategori');

        return view('mengelola', compact('obats', 'kategoris'));
    }
    public function detailObat(Obat $obat): View
    {
        $obat->load(['batches' => fn (HasMany $query) => $query->fefo()]);

        return view('obat.detail', compact('obat'));
    }
    public function riwayat()
    {
        return view('riwayat');
    }

    public function semuaObat(): View
    {
        $obats = Obat::with(['batches' => fn (HasMany $query) => $query->tersedia()])
            ->orderBy('nama')
            ->get();

        return view('obat.menu', ['halaman' => 'semua-obat', 'obats' => $obats]);
    }

    public function daftarObat(): View
    {
        $kategoris = Obat::select('kategori')->distinct()->orderBy('kategori')->pluck('kategori');

        return view('obat.menu', ['halaman' => 'daftar-obat', 'kategoris' => $kategoris]);
    }

    public function simpanObat(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:30|unique:obats,kode',
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:80',
            'deskripsi' => 'nullable|string|max:500',   // tambahan
            'satuan' => 'required|string|max:30',
            // ...sisanya tetap
        ]);

        $validated['kategori'] = $this->tentukanKategori($validated);   // BARU
        unset($validated['kategori_lainnya']);
        Obat::create($validated);

        return redirect()->route('semua-obat')->with('success', 'Obat berhasil didaftarkan.');
    }
    private function tentukanKategori(array $validated): string
    {
        $kategori = $validated['kategori'] === '__lainnya__' ? trim($validated['kategori_lainnya']) : $validated['kategori'];

        return Obat::whereRaw('LOWER(kategori) = ?', [mb_strtolower($kategori)])->value('kategori')?? $kategori;
    }
    public function simpanObatModal(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'kode'                => 'required|string|max:30|unique:obats,kode',
        'nama'                => 'required|string|max:255',
        'kategori'            => 'required|string|max:80',
        'kategori_lainnya'    => 'required_if:kategori,__lainnya__|nullable|string|max:80',
        'deskripsi'           => 'nullable|string|max:500',
        'satuan'              => 'required|string|max:30',
        'harga_beli'          => 'required|numeric|min:0',
        'harga_jual'          => 'required|numeric|min:0',
        'batas_minimum'       => 'required|integer|min:0',
        'stok_awal'           => 'required|integer|min:1',
        'tanggal_kedaluwarsa' => 'required|date|after:today',
    ]);
    $kategori = $this->tentukanKategori($validated);

    DB::transaction(function () use ($kategori, $validated) {
        $obat = Obat::create([
            'kode'          => $validated['kode'],
            'nama'          => $validated['nama'],
            'kategori' => $kategori,
            'deskripsi'     => $validated['deskripsi'] ?? null,
            'satuan'        => $validated['satuan'],
            'harga_beli'    => $validated['harga_beli'],
            'harga_jual'    => $validated['harga_jual'],
            'batas_minimum' => $validated['batas_minimum'],
        ]);

        $obat->batches()->create([
            'nomor_batch'         => 'AWAL-' . now()->format('Ymd'),
            'pemasok'             => null,
            'tanggal_masuk'       => today(),
            'tanggal_kedaluwarsa' => $validated['tanggal_kedaluwarsa'],
            'jumlah_awal'         => $validated['stok_awal'],
            'sisa_stok'           => $validated['stok_awal'],
        ]);
    });

    return redirect()->route('mengelola')->with('success', 'Obat berhasil ditambahkan.');
}

    public function updateObat(Request $request, Obat $obat): RedirectResponse
{
    $validated = $request->validate([
        'kode'             => ['required', 'string', 'max:30', Rule::unique('obats', 'kode')->ignore($obat->id)],
        'nama'             => 'required|string|max:255',
        'kategori'         => 'required|string|max:80',
        'kategori_lainnya' => 'required_if:kategori,__lainnya__|nullable|string|max:80',
        'deskripsi'        => 'nullable|string|max:500',
        'satuan'           => 'required|string|max:30',
        'harga_beli'       => 'required|numeric|min:0',
        'harga_jual'       => 'required|numeric|min:0',
        'batas_minimum'    => 'required|integer|min:0',
    ]);

    $obat->update([
        'kode'          => $validated['kode'],
        'nama'          => $validated['nama'],
        'kategori'      => $this->tentukanKategori($validated),
        'deskripsi'     => $validated['deskripsi'] ?? null,
        'satuan'        => $validated['satuan'],
        'harga_beli'    => $validated['harga_beli'],
        'harga_jual'    => $validated['harga_jual'],
        'batas_minimum' => $validated['batas_minimum'],
    ]);

    return redirect()->route('mengelola')->with('success', 'Data obat berhasil diperbarui.');
}
    public function tambahStok(): View
    {
        $obats = Obat::orderBy('nama')->get();

        return view('obat.menu', ['halaman' => 'tambah-stok', 'obats' => $obats]);
    }

    public function simpanStok(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'obat_id' => 'required|exists:obats,id',
            'nomor_batch' => [
                'required',
                'string',
                'max:50',
                Rule::unique('batch_obats', 'nomor_batch')
                    ->where('obat_id', $request->input('obat_id')),
            ],
            'pemasok' => 'nullable|string|max:255',
            'tanggal_masuk' => 'required|date',
            'tanggal_kedaluwarsa' => 'required|date|after_or_equal:tanggal_masuk',
            'jumlah' => 'required|integer|min:1',
        ]);

        $obat = Obat::findOrFail($validated['obat_id']);
        $obat->batches()->create([
            'nomor_batch' => $validated['nomor_batch'],
            'pemasok' => $validated['pemasok'] ?? null,
            'tanggal_masuk' => $validated['tanggal_masuk'],
            'tanggal_kedaluwarsa' => $validated['tanggal_kedaluwarsa'],
            'jumlah_awal' => $validated['jumlah'],
            'sisa_stok' => $validated['jumlah'],
        ]);

        return redirect()->route('semua-obat')->with('success', 'Stok obat berhasil ditambahkan.');
    }

    public function hapusObat(): View
    {
        $obats = Obat::with(['batches' => fn (HasMany $query) => $query->tersedia()])
            ->orderBy('nama')
            ->get();

        return view('obat.menu', ['halaman' => 'hapus-obat', 'obats' => $obats]);
    }

    public function hapusObatDariDaftar(Obat $obat): RedirectResponse
    {
        $obat->delete();

        return redirect()->route('hapus-obat')->with('success', 'Obat berhasil dihapus.');
    }

    public function prioritasObat(): View
    {
        $obats = Obat::with(['batches' => fn (HasMany $q) => $q->where('sisa_stok', '>', 0)->fefo()])
        ->orderBy('nama')
        ->get()
        ->filter(function (Obat $obat): bool {
            $aktif = $obat->batches->filter(fn (BatchObat $b) => $b->tanggal_kedaluwarsa->gte(today()));
            $lewat = $obat->batches->filter(fn (BatchObat $b) => $b->tanggal_kedaluwarsa->lt(today()));

            return $aktif->sum('sisa_stok') <= $obat->batas_minimum
                || $lewat->isNotEmpty()
                || $aktif->contains(fn (BatchObat $b) => $b->tanggal_kedaluwarsa->lte(today()->addDays(BatchObat::AMBANG_SEGERA_HARI)));
        })
        ->values();

    return view('obat.menu', ['halaman' => 'prioritas-obat', 'obats' => $obats]);
    }

    public function kasir(Request $request): View
    {
        $obats = Obat::query()
            ->with(['batches' => fn ($query) => $query->tersedia()])
            ->cari($request->string('q')->toString())
            ->orderBy('nama')
            ->get();

        // Pastikan file kasir.blade.php ada di resources/views/layout/kasir.blade.php
        return view('layout.kasir', compact('obats'));
    }

    public function profil()
    {
        return view('profil');
    }

    public function showRegister()
    {
        return view('register');
    }

    // ===== Simpan data pendaftaran ke database =====

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create($validated);

        return redirect('/')->with('success', 'Pendaftaran berhasil, silakan login.');
    }

    // ===== Login =====

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect('/dashboard');
        }

        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Email atau password salah.');
    }

    // ===== Logout =====

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logout berhasil! Kamu sudah keluar dari sistem ApotekCare.');
    }
}
