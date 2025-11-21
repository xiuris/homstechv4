<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class EnforceUploadLimits
{
    public function handle(Request $request, Closure $next): Response
    {
        $limitMb = (int) config('installer.upload_max_mb', 10);
        $limitBytes = $limitMb * 1024 * 1024;

        foreach ($request->allFiles() as $files) {
            foreach ((array) $files as $file) {
                if ($file instanceof UploadedFile && $file->getSize() > $limitBytes) {
                    abort(413, 'Upload excede o limite configurado.');
                }
            }
        }

        return $next($request);
    }
}
