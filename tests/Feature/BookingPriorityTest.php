<?php

namespace Tests\Feature;

use App\Models\Classmodel;
use App\Models\User;
// use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BookingPriorityTest extends TestCase
{
    public function test_booking_priority_logic()
    {
        Storage::fake('public');

        // Ambil data user real dari database
        $student = User::where('role', 'user')->firstOrFail();
        $adminFakultas = User::where('role', 'admin_fakultas')->firstOrFail();
        $superadmin = User::where('role', 'superadmin')->firstOrFail();

        // Ambil ruangan yg available
        $class = Classmodel::where('is_available', true)->firstOrFail();

        $payload = [
            'classmodel_id' => $class->id,
            'start_datetime' => now()->addDay()->format('Y-m-d H:i'),
            'end_datetime' => now()->addDay()->addHours(2)->format('Y-m-d H:i'),
            'organization' => "Test Org",
            'activity_name' => "Tes Event",
            'full_name' => $student->name,
            'nim' => $student->nim,
            'semester' => $student->semester,
            'prodi' => $student->studyProgram->name ?? 'Test',
            'telp' => '089999999',
            'no_letter' => '123/TEST/IX',
            'date_letter' => now()->format('Y-m-d'),
            'apply_letter' => UploadedFile::fake()->create('apply.pdf', 200),
            'activity_proposal' => UploadedFile::fake()->create('proposal.pdf', 200),
        ];

        // =========== 1) Mahasiswa booking dulu =============
        $this->actingAs($student)
            ->post(route('booking.store'), $payload)
            ->assertRedirect();

        $this->assertDatabaseHas('booking_classes', [
            'user_id' => $student->id,
            'status' => 'pending'
        ]);

        // =========== 2) Superadmin booking sekarang ==========
        $this->actingAs($superadmin)
            ->post(route('booking.store'), $payload)
            ->assertRedirect();

        // After logic → mahasiswa booking harus jadi rejected
        $this->assertDatabaseMissing('booking_classes', [
            'user_id' => $student->id,
            'status' => 'pending'
        ]);

        $this->assertDatabaseHas('booking_classes', [
            'user_id' => $student->id,
            'status' => 'rejected'
        ]);

        // Superadmin harus punya booking pending/approved
        $this->assertDatabaseHas('booking_classes', [
            'user_id' => $superadmin->id
        ]);
    }
}
