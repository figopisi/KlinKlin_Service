<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Illuminate\Http\UploadedFile;

class CloudinaryService
{
    protected Cloudinary $cloudinary;

    
    public function __construct()
{
    $this->cloudinary = new Cloudinary([
        'cloud' => [
            'cloud_name' => config('cloudinary.cloud_name'),
            'api_key'    => config('cloudinary.api_key'),
            'api_secret' => config('cloudinary.api_secret'),
        ],
        'url' => [
            'secure' => true,
        ],
    ]);
}

    // -------------------------------------------------------
    // Upload bukti pengambilan baju
    // -------------------------------------------------------
    public function uploadBuktiPengambilan(UploadedFile $file, string $token): array
    {
        return $this->uploadToCloudinary($file, "laundry/orders/{$token}");
    }

    // -------------------------------------------------------
    // Upload bukti nota laundry
    // -------------------------------------------------------
    public function uploadBuktiNota(UploadedFile $file, string $token): array
    {
        return $this->uploadToCloudinary($file, "laundry/orders/{$token}");
    }

    // -------------------------------------------------------
    // Upload bukti pengiriman baju
    // -------------------------------------------------------
    public function uploadBuktiPengiriman(UploadedFile $file, string $token): array
    {
        return $this->uploadToCloudinary($file, "laundry/orders/{$token}");
    }

    // -------------------------------------------------------
    // Hapus gambar berdasarkan public_id
    // -------------------------------------------------------
    public function delete(string $publicId): void
    {
        $this->cloudinary->uploadApi()->destroy($publicId);
    }

    // -------------------------------------------------------
    // Core: compress lalu upload
    // -------------------------------------------------------
    protected function uploadToCloudinary(UploadedFile $file, string $folder): array
    {
        $compressedPath = $this->compress($file);

        $result = $this->cloudinary->uploadApi()->upload($compressedPath, [
            'folder'        => $folder,
            'resource_type' => 'image',
        ]);

        if (file_exists($compressedPath)) {
            unlink($compressedPath);
        }

        return [
            'url'       => $result['secure_url'],
            'public_id' => $result['public_id'],
        ];
    }

    // -------------------------------------------------------
    // Compress gambar menggunakan GD (built-in PHP)
    // -------------------------------------------------------
    protected function compress(UploadedFile $file, int $quality = 75): string
    {
        $mime       = $file->getMimeType();
        $sourcePath = $file->getRealPath();

        $image = match ($mime) {
            'image/jpeg', 'image/jpg' => \imagecreatefromjpeg($sourcePath),
            'image/png'               => \imagecreatefrompng($sourcePath),
            'image/webp'              => \imagecreatefromwebp($sourcePath),
            default                   => throw new \InvalidArgumentException("Format gambar tidak didukung: {$mime}"),
        };

        $tempPath = sys_get_temp_dir() . '/' . uniqid('laundry_', true) . '.jpg';

        \imagejpeg($image, $tempPath, $quality);
        \imagedestroy($image);

        return $tempPath;
    }
}