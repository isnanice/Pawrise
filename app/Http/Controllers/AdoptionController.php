<?php

namespace App\Http\Controllers;

use App\Models\AdoptionApplication;
use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

// Controller untuk mengelola permohonan adopsi dari pengguna
class AdoptionController extends Controller
{
    // Menampilkan daftar permohonan adopsi milik pengguna yang sedang login
    public function index()
    {
        $apps = AdoptionApplication::with(['animal.shelter'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(8);

        return view('user.applications', compact('apps'));
    }

    // Menampilkan form pembuatan permohonan adopsi hewan
    public function create(Animal $animal)
    {
        $animal->load('shelter');

        // Cek apakah ada draft tersimpan untuk hewan ini
        $draft = AdoptionApplication::where('user_id', auth()->id())
            ->where('animal_id', $animal->id)
            ->where('status', 'draft')
            ->latest()
            ->first();

        return view('adoption.create', compact('animal', 'draft'));
    }

    // Menyimpan permohonan sebagai draft (belum diajukan)
    public function saveDraft(Request $request, Animal $animal)
    {
        $data = $request->validate([
            'full_name'  => ['nullable', 'string', 'max:160'],
            'whatsapp'   => ['nullable', 'string', 'max:30'],
            'email'      => ['nullable', 'email'],
            'address'    => ['nullable', 'string', 'max:500'],
            'reason'     => ['nullable', 'string', 'max:2000'],
            'experience' => ['nullable', Rule::in(['belum', 'pernah', 'sedang'])],
        ]);

        // Update draft yang ada, atau buat baru
        AdoptionApplication::updateOrCreate(
            [
                'animal_id' => $animal->id,
                'user_id'   => auth()->id(),
                'status'    => 'draft',
            ],
            [
                'full_name'  => $data['full_name']  ?? '',
                'whatsapp'   => $data['whatsapp']   ?? '',
                'email'      => $data['email']       ?? auth()->user()->email,
                'address'    => $data['address']     ?? '',
                'reason'     => $data['reason']      ?? '',
                'experience' => $data['experience']  ?? 'belum',
                'agreement'  => false,
            ]
        );

        return redirect()->route('user.applications')
            ->with('success', 'Draft berhasil disimpan. Anda dapat melanjutkan kapan saja.');
    }

    // Menyimpan data permohonan adopsi dan mengajukannya
    public function store(Request $request, Animal $animal)
    {
        $data = $request->validate([
            'full_name'  => ['required', 'string', 'max:160'],
            'whatsapp'   => ['required', 'string', 'max:30'],
            'email'      => ['required', 'email'],
            'address'    => ['required', 'string', 'max:500'],
            'reason'     => ['required', 'string', 'max:2000'],
            'experience' => ['required', Rule::in(['belum', 'pernah', 'sedang'])],
            'agreement'  => ['accepted'],
        ]);

        // Jika ada draft untuk hewan ini, update jadi 'menunggu'
        $existing = AdoptionApplication::where('user_id', auth()->id())
            ->where('animal_id', $animal->id)
            ->where('status', 'draft')
            ->first();

        if ($existing) {
            $existing->update([
                'full_name'  => $data['full_name'],
                'whatsapp'   => $data['whatsapp'],
                'email'      => $data['email'],
                'address'    => $data['address'],
                'reason'     => $data['reason'],
                'experience' => $data['experience'],
                'agreement'  => true,
                'status'     => 'menunggu',
            ]);
        } else {
            AdoptionApplication::create([
                'animal_id'  => $animal->id,
                'user_id'    => auth()->id(),
                'full_name'  => $data['full_name'],
                'whatsapp'   => $data['whatsapp'],
                'email'      => $data['email'],
                'address'    => $data['address'],
                'reason'     => $data['reason'],
                'experience' => $data['experience'],
                'agreement'  => true,
                'status'     => 'menunggu',
            ]);
        }

        return redirect()->route('user.applications')
            ->with('success', 'Permohonan adopsi berhasil dikirim.');
    }
    // Menampilkan detail permohonan adopsi untuk adopter
    public function show(AdoptionApplication $application)
    {
        // Pastikan hanya pemilik permohonan yang bisa melihat
        if ($application->user_id !== auth()->id()) {
            abort(403);
        }

        $application->load('animal.shelter');
        return view('user.application_show', compact('application'));
    }
}
