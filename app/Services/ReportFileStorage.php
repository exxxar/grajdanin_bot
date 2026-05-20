<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ReportFileStorage
{
    /**
     * @param  UploadedFile[]  $files
     * @return array<int, array{path: string, original_name: string, mime: string|null, size: int}>
     */
    public function storeMany(User $user, int $reportId, string $subdir, array $files): array
    {
        $stored = [];

        foreach ($files as $file) {
            if (!$file instanceof UploadedFile) {
                continue;
            }

            $extension = $file->getClientOriginalExtension() ?: $file->extension() ?: 'bin';
            $filename = Str::uuid() . '.' . $extension;
            $directory = "users/{$user->uuid}/reports/{$reportId}/{$subdir}";

            $path = $file->storeAs($directory, $filename);

            $stored[] = [
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime' => $file->getClientMimeType(),
                'size' => $file->getSize(),
            ];
        }

        return $stored;
    }
}
