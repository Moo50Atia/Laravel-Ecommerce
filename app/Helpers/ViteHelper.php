<?php

if (!function_exists('vite_asset')) {
    /**
     * Get the path to a versioned Vite asset from manifest.json.
     *
     * @param  string  $path
     * @return string
     */
    function vite_asset(string $path): string
    {
        static $manifest = null;

        if ($manifest === null) {
            $manifestPath = public_path('build/manifest.json');

            if (!file_exists($manifestPath)) {
                throw new Exception('Vite manifest not found. Run "npm run build".');
            }

            $manifest = json_decode(file_get_contents($manifestPath), true);
        }

        if (!isset($manifest[$path])) {
            throw new Exception("Vite asset '{$path}' not found in manifest.");
        }

        return asset('build/' . $manifest[$path]['file']);
    }
}
