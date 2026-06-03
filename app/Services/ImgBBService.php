<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class ImgBBService
{
    protected string $apiKey = 'cd4cbd15d854cce8d541bc9b8ddc56ad';
    protected string $apiUrl = 'https://api.imgbb.com/1/upload';

    /**
     * Upload an image/GIF file to ImgBB.
     *
     * @param UploadedFile $file
     * @return string|null The uploaded image URL or null on failure
     */
    public function upload(UploadedFile $file): ?string
    {
        try {
            // Convert to WebP first!
            $webpData = $this->convertToWebP($file);
            $base64Image = base64_encode($webpData);

            $response = Http::asForm()->post($this->apiUrl, [
                'key' => $this->apiKey,
                'image' => $base64Image,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['data']['url'] ?? null;
            }

            Log::error('ImgBB Upload Error: ' . $response->body());
            return null;
        } catch (Exception $e) {
            Log::error('ImgBB Service Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Converts image to WebP format.
     * Preserves GIF animations if Imagick is available.
     */
    private function convertToWebP(UploadedFile $file): string
    {
        $mime = $file->getMimeType();
        $path = $file->getPathname();

        try {
            // If it's a GIF and Imagick is available, convert animated GIF to WebP
            if ($mime === 'image/gif' && class_exists('Imagick')) {
                $imagick = new \Imagick($path);
                $imagick = $imagick->coalesceImages();
                foreach ($imagick as $frame) {
                    $frame->setImageFormat('webp');
                }
                $imagick = $imagick->deconstructImages();
                $tempPath = tempnam(sys_get_temp_dir(), 'webp_');
                $imagick->writeImages($tempPath, true);
                $webpData = file_get_contents($tempPath);
                @unlink($tempPath);
                return $webpData;
            }

            // Standard conversion using GD library
            $image = null;
            if ($mime === 'image/jpeg' || $mime === 'image/jpg') {
                $image = imagecreatefromjpeg($path);
            } elseif ($mime === 'image/png') {
                $image = imagecreatefrompng($path);
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
            } elseif ($mime === 'image/gif') {
                // If Imagick is not available, we return the original GIF to keep animations intact
                return file_get_contents($path);
            }

            if ($image) {
                ob_start();
                imagewebp($image, null, 85);
                $webpData = ob_get_clean();
                imagedestroy($image);
                return $webpData;
            }
        } catch (Exception $e) {
            Log::warning('WebP Conversion failed, uploading original: ' . $e->getMessage());
        }

        return file_get_contents($path);
    }

    /**
     * Upload a resized square icon to ImgBB.
     */
    public function uploadResizedIcon(UploadedFile $file, int $size = 128): ?string
    {
        try {
            $webpData = $this->convertToResizedWebP($file, $size);
            $base64Image = base64_encode($webpData);

            $response = Http::asForm()->post($this->apiUrl, [
                'key' => $this->apiKey,
                'image' => $base64Image,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['data']['url'] ?? null;
            }

            Log::error('ImgBB Icon Upload Error: ' . $response->body());
            return null;
        } catch (Exception $e) {
            Log::error('ImgBB Icon Service Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Converts image to resized WebP format.
     */
    private function convertToResizedWebP(UploadedFile $file, int $targetSize): string
    {
        $mime = $file->getMimeType();
        $path = $file->getPathname();

        try {
            $image = null;
            if ($mime === 'image/jpeg' || $mime === 'image/jpg') {
                $image = imagecreatefromjpeg($path);
            } elseif ($mime === 'image/png') {
                $image = imagecreatefrompng($path);
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
            } elseif ($mime === 'image/webp') {
                $image = imagecreatefromwebp($path);
            }

            if ($image) {
                $origWidth = imagesx($image);
                $origHeight = imagesy($image);

                $resized = imagecreatetruecolor($targetSize, $targetSize);

                // Keep transparent background
                imagealphablending($resized, false);
                imagesavealpha($resized, true);

                $minSize = min($origWidth, $origHeight);
                $srcX = (int)(($origWidth - $minSize) / 2);
                $srcY = (int)(($origHeight - $minSize) / 2);

                imagecopyresampled($resized, $image, 0, 0, $srcX, $srcY, $targetSize, $targetSize, $minSize, $minSize);

                ob_start();
                imagewebp($resized, null, 85);
                $webpData = ob_get_clean();
                imagedestroy($image);
                imagedestroy($resized);
                return $webpData;
            }
        } catch (Exception $e) {
            Log::warning('Resized WebP Conversion failed, uploading original: ' . $e->getMessage());
        }

        return file_get_contents($path);
    }
}
