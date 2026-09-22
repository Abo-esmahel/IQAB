<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HandlesImageUploads
{
    /**
     * Resolve the final image value: uploaded file wins, then pasted URL,
     * then the current value (or null when explicitly removed).
     */
    protected function resolveImage(Request $request, ?string $current, string $directory): ?string
    {
        if ($request->hasFile('image_file') && $request->file('image_file')->isValid()) {
            $this->deleteStoredImage($current);

            return Storage::url($request->file('image_file')->store($directory, 'public'));
        }

        $url = trim((string) $request->input('image', ''));

        if ($url !== '') {
            if ($current && $current !== $url) {
                $this->deleteStoredImage($current);
            }

            return $url;
        }

        if ($request->boolean('remove_image')) {
            $this->deleteStoredImage($current);

            return null;
        }

        return $current;
    }

    /**
     * Delete a previously uploaded file (never touches external URLs).
     */
    protected function deleteStoredImage(?string $path): void
    {
        if ($path && str_starts_with($path, '/storage/')) {
            Storage::disk('public')->delete(Str::after($path, '/storage/'));
        }
    }
}
