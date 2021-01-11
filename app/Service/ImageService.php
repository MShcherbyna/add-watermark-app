<?php

namespace App\Service;

use App\Service\ImageServiceContract;
use Illuminate\Support\Facades\Storage;
use ColorThief\ColorThief;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Log;

Class ImageService implements ImageServiceContract
{
    protected $watermarks = [
        'red' => 'red.jpg',
        'green' => 'green.jpg',
        'blue' => 'blue.jpg'
    ];

    /**
     * @param array $payload
     * @return string
     */
    public function saveImage(array $payload): string
    {
        $file = $payload['image'];
        $fileName = time().'_'.$file->getClientOriginalName();
        $filePath = $file->storeAs('images', $fileName, 'local');

        return $filePath;
    }

    /**
     * @param string $path
     * @return void
     */
    public function removeImage(string $path): void
    {
        if (Storage::disk('local')->exists($path)) {
            Storage::disk('local')->delete($path);
        }
    }

    /**
     * @param array $payload
     * @return array|null
     */
    public function addWatermark(array $payload): ?array
    {
        $filePath = $this->saveImage($payload);
        $sourceImage = Storage::disk('local')->get($filePath);
        $mainColor = $this->getMainColor($sourceImage);
        $stamp = Storage::disk('local')->get('/wotermarks/' . $this->watermarks[$mainColor]);

        try {
            $image = Image::make($sourceImage);

            $height = $image->height();
            $width = $image->width();

            $stamp = Image::make($stamp);
            $stamp->resize(($height/5), ($width/5));

            $this->removeImage($filePath);

            $image->insert($stamp, 'bottom-right', 5, 5);
            $image->save(public_path('images/outload.jpg'));

        } catch (\Throwable $e) {
            Log::debug("Image Error: " . $e->getMessage());

            return null;
        }

        return [
            'path' => 'images/outload.jpg',
            'name' => 'outload.jpg'
        ];

    }

    /**
     * @param string $sourceImage
     * @return string
     */
    protected function getMainColor(string $sourceImage): string
    {
        $color = '';
        //This function returns an array of three integer values, (Red, Green & Blue)
        $dominantColor = ColorThief::getColor($sourceImage);
        $mainColor = array_keys($dominantColor, max($dominantColor));

        switch ($mainColor[0]) {
            case 0:
                $color = 'red';
                break;
            case 1:
                $color = 'green';
                break;
            case 2:
                $color = 'blue';
                break;
        }

        return $color;
    }

}
