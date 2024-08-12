<?php

namespace App\Controllers;

use App\Class\ListingCategory;
use App\Http\Request;
use App\Http\Response;
use App\Utils\UploadFile;
use App\Validators\CategoryValidator;
use App\Validators\FileValidators;
use Exception;

class ListingCategoryController {
    private string $categoryImagesFolder = "categories";

    public function create(Request $request, Response $response) {
        try {
            $data = $request->body();
            $files = $request->files();

            $category = new ListingCategory($data);

            if(!CategoryValidator::create($data)) {
                return;
            }

            $category->save();

            if(!empty($files['icon']) || !empty($files['photo'])) {
                $errors = "";

                if(!empty($files['icon'])) {
                    $iconData = FileValidators::validIcon($files['icon']);

                    if(isset($iconData['invalid'])) {
                        $errors = $iconData['invalid'];
                    } else {
                        $iconName = $category->getId() . "_icon." . $iconData['mimeType'];
                        UploadFile::uploadFile($files['icon'], $this->categoryImagesFolder, $iconName);
                        $category->setIcon($iconName);
                    }
                }

                if(!empty($files['photo'])) {
                    $photoData = FileValidators::validIcon($files['photo']);

                    if(isset($photoData['invalid'])) {
                        $errors = isset($photoData['invalid']);
                    } else {
                        $photoName = $category->getId() . "_photo." . $photoData['mimeType'];
                        UploadFile::uploadFile($files['photo'], $this->categoryImagesFolder, $photoName);
                        $category->setPhoto($photoName);
                    }
                }

                if(!empty($errors)) {
                    return $response->json([
                        'message' => $errors
                    ], 400);
                }

                $category->save();
            }

            $categoryData = $category->toArray();

            return $response->json([
                "message" => CATEGORY_CREATED,
                "categoryData" => $categoryData
            ], 201);

        } catch (Exception $e) {
            logError($e->getMessage());
            return $response->json([
                'message' => FATAL_ERROR
            ], 500);
        }
    }
}
