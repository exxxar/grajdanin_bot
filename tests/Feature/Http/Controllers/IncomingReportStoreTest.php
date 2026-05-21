<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\RoleEnum;
use App\Models\IncomingReport;
use App\Models\Municipality;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class IncomingReportStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_store_incoming_report_with_files(): void
    {
        Storage::fake('local');

        $user = User::factory()->create([
            'role' => RoleEnum::GUEST->value,
            'uuid' => '11111111-2222-4333-8444-555555555555',
        ]);

        $municipality = Municipality::factory()->create();

        $response = $this->actingAs($user)->post('/bot-api/reports/incoming', [
            'type' => 0,
            'received_from' => 'Иванов Иван',
            'phone' => '+79991234567',
            'municipality_id' => $municipality->id,
            'received_at' => '2026-05-20',
            'problem_description' => 'Подробное описание проблемы для теста',
            'help_formats' => json_encode([]),
            'problems' => json_encode(['1' => ['Нет воды']]),
            'documents' => [
                UploadedFile::fake()->create('scan.pdf', 100, 'application/pdf'),
            ],
            'audio_files' => [
                UploadedFile::fake()->create('voice.webm', 100, 'audio/webm'),
            ],
        ]);

        $response->assertCreated();

        $this->assertDatabaseCount('reports', 1);
        $this->assertDatabaseCount('incoming_reports', 1);

        $incoming = IncomingReport::first();
        $report = Report::first();

        $this->assertSame($user->id, $report->from_user_id);
        $this->assertSame('+79991234567', $report->phone);
        $this->assertSame('Иванов Иван', $incoming->received_from);
        $this->assertCount(1, $report->documents);
        $this->assertCount(1, $incoming->audio_files);

        Storage::assertExists($report->documents[0]['path']);
        Storage::assertExists($incoming->audio_files[0]['path']);
    }

    public function test_store_requires_authentication(): void
    {
        $response = $this->postJson('/bot-api/reports/incoming', []);

        $response->assertUnauthorized();
    }
}
