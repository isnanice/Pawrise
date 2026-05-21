<?php

namespace App\Http\Controllers\Shelter;

use App\Http\Controllers\Controller;
use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

// Controller untuk mengelola data hewan oleh shelter
class AnimalController extends Controller
{
    // Menampilkan daftar hewan peliharaan di shelter
    public function index(Request $request)
    {
        $shelter = auth()->user()->shelter;
        abort_unless($shelter, 403, 'Shelter belum terdaftar.');

        $query = Animal::where('shelter_id', $shelter->id);

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('breed', 'like', "%{$search}%");
            });
        }
        if ($species = $request->input('species')) {
            $query->where('species', $species);
        }
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $animals = $query->latest()->paginate(10)->withQueryString();
        return view('shelter.animals.index', compact('animals'));
    }

    // Menampilkan form tambah data hewan baru
    public function create()
    {
        return view('shelter.animals.create');
    }

    // Menyimpan data hewan baru ke database
    public function store(Request $request)
    {
        $shelter = auth()->user()->shelter;
        abort_unless($shelter, 403);

        $data = $this->validated($request);
        $data['shelter_id'] = $shelter->id;
        $data['code'] = $data['code'] ?? 'SHL-' . str_pad(Animal::where('shelter_id', $shelter->id)->count() + 1, 3, '0', STR_PAD_LEFT);

        if ($request->hasFile('main_photo')) {
            $data['main_photo'] = $request->file('main_photo')->store('animals', 'public');
        }

        Animal::create($data);

        return redirect()->route('shelter.animals.index')->with('success', 'Hewan baru berhasil ditambahkan.');
    }

    // Menampilkan form edit data hewan
    public function edit(Animal $animal)
    {
        $this->authorizeShelter($animal);
        return view('shelter.animals.edit', compact('animal'));
    }

    // Memperbarui data hewan di database
    public function update(Request $request, Animal $animal)
    {
        $this->authorizeShelter($animal);

        $data = $this->validated($request, $animal);

        if ($request->hasFile('main_photo')) {
            if ($animal->main_photo) {
                Storage::disk('public')->delete($animal->main_photo);
            }
            $data['main_photo'] = $request->file('main_photo')->store('animals', 'public');
        }

        $animal->update($data);
        return redirect()->route('shelter.animals.index')->with('success', 'Data hewan berhasil diperbarui.');
    }

    // Mengarsipkan data hewan (soft delete)
    public function destroy(Animal $animal)
    {
        $this->authorizeShelter($animal);
        $animal->delete();
        return back()->with('success', 'Data hewan berhasil dipindahkan ke arsip.');
    }

    // Menampilkan daftar hewan yang diarsipkan
    public function trash()
    {
        $shelter = auth()->user()->shelter;
        abort_unless($shelter, 403);

        $animals = Animal::onlyTrashed()->where('shelter_id', $shelter->id)->latest()->paginate(10);
        return view('shelter.animals.trash', compact('animals'));
    }

    // Memulihkan data hewan dari arsip
    public function restore($id)
    {
        $shelter = auth()->user()->shelter;
        $animal = Animal::withTrashed()->where('shelter_id', $shelter->id)->findOrFail($id);
        $animal->restore();
        return redirect()->route('shelter.animals.trash')->with('success', 'Data hewan berhasil dipulihkan.');
    }

    // Menghapus data hewan secara permanen
    public function forceDelete($id)
    {
        $shelter = auth()->user()->shelter;
        $animal = Animal::withTrashed()->where('shelter_id', $shelter->id)->findOrFail($id);

        if ($animal->main_photo) {
            Storage::disk('public')->delete($animal->main_photo);
        }

        $animal->forceDelete();
        return redirect()->route('shelter.animals.trash')->with('success', 'Data hewan berhasil dihapus permanen.');
    }

    // Memvalidasi apakah hewan dimiliki oleh shelter yang bersangkutan
    private function authorizeShelter(Animal $animal): void
    {
        $shelter = auth()->user()->shelter;
        abort_unless($shelter && $animal->shelter_id === $shelter->id, 403);
    }

    private function validated(Request $request, ?Animal $animal = null): array
    {
        $data = $request->validate([
            'code'        => ['nullable', 'string', 'max:20'],
            'name'        => ['required', 'string', 'max:120'],
            'species'     => ['required', Rule::in(['anjing', 'kucing', 'lainnya'])],
            'breed'       => ['required', 'string', 'max:120'],
            'age_months'  => ['required', 'integer', 'min:0', 'max:360'],
            'weight_kg'   => ['nullable', 'numeric', 'min:0', 'max:200'],
            'gender'      => ['required', Rule::in(['jantan', 'betina'])],
            'size'        => ['required', Rule::in(['kecil', 'sedang', 'besar'])],
            'status'      => ['required', Rule::in(['tersedia', 'diproses', 'diadopsi'])],
            'description' => ['nullable', 'string', 'max:5000'],
            'characteristics' => ['nullable', 'string', 'max:500'],
            'medical_history' => ['nullable', 'string', 'max:5000'],
            'main_photo'  => ['nullable', 'image', 'max:4096'],
            'vaccinated_rabies_date' => ['nullable', 'date'],
            'vaccinated_distemper_date' => ['nullable', 'date'],
        ]);

        // Secara eksplisit cast ke boolean agar false tersimpan saat di uncheck
        $data['vaccinated'] = $request->boolean('vaccinated');
        $data['vaccinated_distemper'] = $request->boolean('vaccinated_distemper');
        $data['sterilized'] = $request->boolean('sterilized');
        $data['dewormed'] = $request->boolean('dewormed');
        $data['flea_free'] = $request->boolean('flea_free');
        $data['special_needs'] = $request->boolean('special_needs');

        return $data;
    }
}
