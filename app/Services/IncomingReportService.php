<?php

namespace App\Services;

use App\Enums\ChatTypeEnum;
use App\Enums\ReportTypeEnum;
use App\Enums\RoleEnum;
use App\Models\Chat;
use App\Models\IncomingReport;
use App\Models\Message;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncomingReportService
{
    public function __construct(
        private readonly ReportFileStorage $fileStorage,
        private readonly CrmNotifier $crmNotifier,
    ) {
    }

    /**
     * @return array{incoming: IncomingReport, chat_id: int, report_url: string}
     */
    public function createFromRequest(Request $request, User $user): array
    {
        $validated = $request->validated();

        return DB::transaction(function () use ($request, $user, $validated) {
            $helpFormats = array_values(array_filter(
                $validated['help_formats'] ?? [],
                fn ($id) => $id !== null && $id !== ''
            ));

            $toUserId = $validated['to_user_id']
                ?? User::query()->where('role', '>=', RoleEnum::OFFICIAL->value)->value('id')
                ?? $user->id;

            $report = Report::create([
                'type' => ReportTypeEnum::INCOMING->value,
                'priority' => 0,
                'from_user_id' => $user->id,
                'to_user_id' => $toUserId,
                'municipality_id' => $validated['municipality_id'],
                'received_at' => $validated['received_at'],
                'phone' => $validated['phone'] ?? null,
                'documents' => [],
            ]);

            $documentMeta = $this->fileStorage->storeMany(
                $user,
                $report->id,
                'documents',
                $request->file('documents', [])
            );

            $audioMeta = $this->fileStorage->storeMany(
                $user,
                $report->id,
                'audio',
                $request->file('audio_files', [])
            );

            if ($documentMeta !== []) {
                $report->update(['documents' => $documentMeta]);
            }

            $incoming = IncomingReport::create([
                'report_id' => $report->id,
                'received_from' => $validated['received_from'] ?? null,
                'problem_description' => $validated['problem_description'],
                'help_formats' => $helpFormats,
                'comment' => $validated['comment'] ?? null,
                'problems' => $validated['problems'] ?? null,
                'solutions' => $validated['solutions'] ?? null,
                'difficulties' => $validated['difficulties'] ?? null,
                'audio_files' => $audioMeta,
            ])->load('report');

            $reportUrl = ReportUrlBuilder::forReport($report->id);
            $chat = $this->createReportChat($user, $report, $incoming, $reportUrl);
            $this->crmNotifier->notifyIncomingReport($report, $incoming, $reportUrl);

            return [
                'incoming' => $incoming,
                'chat_id' => $chat->id,
                'report_url' => $reportUrl,
            ];
        });
    }

    private function createReportChat(User $user, Report $report, IncomingReport $incoming, string $reportUrl): Chat
    {
        $chat = Chat::create([
            'title' => 'Заявка №' . $report->id,
            'report_id' => $report->id,
            'type' => ChatTypeEnum::REPORT->value,
        ]);

        Message::create([
            'user_id' => $user->id,
            'report_id' => $report->id,
            'chat_id' => $chat->id,
            'type' => 'system',
            'text' => sprintf(
                'Заявка №%d принята. %s от %s. Открыть: %s',
                $report->id,
                mb_substr($incoming->problem_description, 0, 120),
                $incoming->received_from ?? 'заявитель',
                $reportUrl
            ),
            'attachments' => [$reportUrl],
        ]);

        return $chat;
    }
}
