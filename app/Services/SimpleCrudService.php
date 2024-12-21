<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class SimpleCrudService
{
    public function __construct(
        public SlugService $slugService,
        public ImageService $imageService,
        public FileService $fileService
    ) {
    }

    public function create($model = null, $request = null, $model_type = null)
    {
        DB::transaction(function () use ($model, $request, $model_type) {
            $data = $request->except(array_keys($request->file()));
            if (isset($data['title'])) {
                $data['slug'] = $this->slugService->sluggableArray($data['title']);
            }
            $entity = $model::create($data);
            $this->handleFilesAndImages($request, $entity->id, $model_type);
        });
    }

    public function update($model = null, $request = null, $model_type = null)
    {
        DB::transaction(function () use ($model, $request, $model_type) {
            $exept = array_keys($request->file());
            $exept['page'] = 'page';
            $exept['q'] = 'q';
            $data = $request->except($exept);
            if (isset($data['title'])) {
                $data['slug'] = $this->slugService->sluggableArray($data['title']);
            }
            $model->update($data);
            $this->handleFilesAndImages($request, $model->id, $model_type);
        });
    }

    private function handleFilesAndImages($request, $entityId, $folder)
    {
        foreach ($request->file() as $key => $file) {
            if (is_array($file)) {
                foreach ($file as $f) {
                    $this->processFile($f, $entityId, $folder, $key);
                }
            } else {
                $this->processFile($file, $entityId, $folder, $key);
            }
        }
    }

    private function processFile($file, $entityId, $folder, $field = null)
    {
        $mimeType = explode('/', $file->getClientMimeType())[0];
        if ($mimeType == "image") {
            $this->imageService->handleImages($file, $entityId, $folder, $mimeType, $folder, $field);
        } else {
            $this->fileService->handleFiles($file, $entityId, $folder, $mimeType, $folder, $field);
        }
    }
}
