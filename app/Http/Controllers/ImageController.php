<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadFileRequest;
use App\Service\ImageService;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * @param UploadFileRequest $request
     * @return void
     */
    public function store(UploadFileRequest $request)
    {
        $payload = $request->validated();

        $result = $this->imageService->addWatermark($payload);

        if ($result === null) {
            return back()->withErrors(['Something went wrong!']);
        }

        return Storage::disk('local')->download($result['path'], $result['name']);
    }
}
