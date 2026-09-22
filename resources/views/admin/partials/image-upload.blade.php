{{-- Reusable image field: upload a file OR paste a URL. --}}
@props(['current' => null, 'label' => 'Image'])

<div class="space-y-3 rounded-xl border border-dark-800 bg-dark-900/50 p-4" data-image-field>
    <label class="block text-sm font-medium text-dark-300">{{ $label }}</label>

    @if($current)
        <div class="flex items-center gap-3">
            <img src="{{ $current }}" alt="Current image" class="h-16 w-16 rounded-lg object-cover ring-1 ring-dark-700">
            <label class="flex items-center gap-2 text-xs text-dark-400 cursor-pointer">
                <input type="checkbox" name="remove_image" value="1" class="h-4 w-4 rounded border-dark-700 bg-dark-800 text-red-500 focus:ring-red-500">
                Remove current image
            </label>
        </div>
    @endif

    <div>
        <input type="file" name="image_file" accept="image/*" data-image-input
               class="block w-full text-sm text-dark-400 file:me-3 file:rounded-lg file:border-0 file:bg-primary-600 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-primary-700 file:cursor-pointer file:transition-colors">
        <p class="mt-1 text-xs text-dark-500">Upload JPG, PNG, GIF or WebP (max 2MB).</p>
    </div>

    <img data-image-preview alt="Preview" class="hidden h-20 w-20 rounded-lg object-cover ring-1 ring-primary-500/40">
</div>

<script>
    (function () {
        document.querySelectorAll('[data-image-field]').forEach(function (field) {
            var input = field.querySelector('[data-image-input]');
            var preview = field.querySelector('[data-image-preview]');
            if (!input || !preview) return;
            input.addEventListener('change', function () {
                var file = input.files && input.files[0];
                if (file) {
                    preview.src = URL.createObjectURL(file);
                    preview.classList.remove('hidden');
                } else {
                    preview.classList.add('hidden');
                    preview.removeAttribute('src');
                }
            });
        });
    })();
</script>
