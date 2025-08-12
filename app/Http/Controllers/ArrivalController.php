<?php

namespace App\Http\Controllers;

use App\Models\Arrival;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ArrivalController extends Controller
{
    public function store(Request $request)
    {
        // 1) Validasi data
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'passport_number' => 'required|string|max:50',
            'nationality' => 'required|string|max:100',
            'gender' => 'required|in:male,female',
            'birth_date' => 'required|date',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email:rfc,dns|max:255',
            'stay_address' => 'required|string|max:255',
            'flight_number' => 'required|string|max:50',
            'arrival_date' => 'required|date',
            'origin_city' => 'required|string|max:100',
            'destination_city' => 'required|string|max:100',
            'health_history' => 'nullable|string|max:5000',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',

            // Validasi file
            'photo' => 'required|file|image|mimes:jpg,jpeg,png|max:2048',
            'vaccine_certificate' => 'required|file|mimetypes:application/pdf,image/jpeg,image/png|max:4096',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $disk = Storage::disk('protected');
        $photoPath = null;
        $vaccinePath = null;

        try {
            DB::beginTransaction();

            // Simpan photo
            $allowedExt = ['jpg', 'jpeg', 'png'];
            $photoExt = strtolower($request->file('photo')->getClientOriginalExtension());
            if (!in_array($photoExt, $allowedExt, true)) {
                return response()->json(['message' => 'Ekstensi file foto tidak valid.'], 422);
            }
            $photoName = Str::ulid() . '.' . $photoExt;
            $photoPath = "uploads/photo/{$photoName}";
            $disk->putFileAs('uploads/photo', $request->file('photo'), $photoName, ['visibility' => 'private']);

            // Simpan sertifikat vaksin
            $vaxExt = strtolower($request->file('vaccine_certificate')->getClientOriginalExtension());
            if (!in_array($vaxExt, $allowedExt, true)) {
                return response()->json(['message' => 'Ekstensi file sertifikat vaksin tidak valid.'], 422);
            }
            $vaxName = Str::ulid() . '.' . $vaxExt;
            $vaccinePath = "uploads/vaccine/{$vaxName}";
            $disk->putFileAs('uploads/vaccine', $request->file('vaccine_certificate'), $vaxName, ['visibility' => 'private']);

            if (!$disk->exists($photoPath) || !$disk->exists($vaccinePath)) {
                throw new \RuntimeException('Gagal menyimpan file.');
            }

            // Simpan data ke DB
            $data = $validator->validated();
            $arrival = Arrival::create([
                'id' => (string) Str::uuid(),
                'full_name' => $data['full_name'],
                'passport_number' => $data['passport_number'],
                'nationality' => $data['nationality'],
                'gender' => $data['gender'],
                'birth_date' => $data['birth_date'],
                'photo_path' => $photoPath,
                'phone_number' => $data['phone_number'],
                'email' => $data['email'],
                'stay_address' => $data['stay_address'],
                'flight_number' => $data['flight_number'],
                'arrival_date' => $data['arrival_date'],
                'origin_city' => $data['origin_city'],
                'destination_city' => $data['destination_city'],
                'health_history' => $data['health_history'] ?? null,
                'emergency_contact_name' => $data['emergency_contact_name'],
                'emergency_contact_phone' => $data['emergency_contact_phone'],
                'vaccine_certificate_path' => $vaccinePath,
            ]);

            DB::commit();

            return response()->json([
                'id' => $arrival->id,
                'message' => 'Pendaftaran berhasil',
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();

            // Hapus file jika sudah ada
            if ($photoPath && $disk->exists($photoPath)) {
                $disk->delete($photoPath);
            }
            if ($vaccinePath && $disk->exists($vaccinePath)) {
                $disk->delete($vaccinePath);
            }

            return response()->json([
                'message' => 'Gagal memproses pendaftaran',
                'error' => app()->hasDebugModeEnabled() ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function show(Arrival $arrival)
    {
        return $arrival;
    }

    public function index()
    {
        $arrivals = Arrival::select([
                'id',
                'full_name',
                'nationality',
                'gender',
                'arrival_date',
                'origin_city',
                'destination_city',
                'status'
            ])
            ->latest('arrival_date')
            ->simplePaginate(15);

        return response()->json([
            'data' => $arrivals->items(),
            'meta' => [
                'current_page' => $arrivals->currentPage(),
                'next_page_url' => $arrivals->nextPageUrl(),
                'per_page' => $arrivals->perPage(),
            ]
        ]);
    }

   public function approve(Request $request, Arrival $arrival)
    {
        $arrival->status = 'approved';
        $arrival->approved_by_user_id = $request->user()->id;
        $arrival->save();

        return response()->json([
            'message' => 'Status verified successfully',
            'data' => $arrival
        ]);
    }

    public function reject(Request $request, Arrival $arrival)
    {
        $request->validate([
            'reject_reason' => 'required|string|max:500'
        ]);

        $arrival->status = 'rejected';
        $arrival->rejected_by_user_id = $request->user()->id;
        $arrival->reject_reason = $request->input('reject_reason');
        $arrival->save();

        return response()->json([
            'message' => 'Status rejected successfully',
            'data' => $arrival
        ]);
    }

}
