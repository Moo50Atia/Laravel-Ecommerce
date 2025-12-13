<?php

if (! function_exists('vite_asset')) {
    function vite_asset($path)
    {
        $manifestPath = public_path('build/manifest.json');

        if (! file_exists($manifestPath)) {
            return '/'.$path; // fallback لو مفيش manifest
        }

        $manifest = json_decode(file_get_contents($manifestPath), true);

        if (! array_key_exists($path, $manifest)) {
            return '/'.$path; // fallback لو المفتاح مش موجود
        }

        // نرجّع لينك relative عشان يشتغل عالـ localhost أو ngrok أو أي دومين
        return '/build/' . $manifest[$path]['file'];
    }
}
 