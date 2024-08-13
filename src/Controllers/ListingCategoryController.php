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
            $iconData = "";
            $photoData = "";

            $category = new ListingCategory($data);

            if(!CategoryValidator::create($data)) {
                return;
            }

            if(!empty($files['icon']) || !empty($files['photo'])) {
                $errors = "";

                if(!empty($files['icon'])) {
                    $iconData = FileValidators::validIcon($files['icon']);

                    if(isset($iconData['invalid'])) {
                        $errors = $iconData['invalid'];
                    }
                }

                if(!empty($files['photo'])) {
                    $photoData = FileValidators::validImage($files['photo']);

                    if(isset($photoData['invalid'])) {
                        $errors = $photoData['invalid'];
                    }
                }

                if(!empty($errors)) {
                    return $response->json([
                        'message' => $errors
                    ], 400);
                }
            }

            $category->save();

            if(!empty($iconData) || !empty($photoData)) {
                if(!empty($iconData)) {
                    $iconName = $category->getId() . "_icon." . $iconData['mimeType'];
                    UploadFile::uploadFile($files['icon'], $this->categoryImagesFolder, $iconName);
                    $category->setIcon($iconName);
                }

                if(!empty($photoData)) {
                    $photoName = $category->getId() . "_photo." . $photoData['mimeType'];
                    UploadFile::uploadFile($files['photo'], $this->categoryImagesFolder, $photoName);
                    $category->setPhoto($photoName);
                }

                $category->save();
            }

            return $response->json([
                "message" => CATEGORY_CREATED,
                "categoryData" => $category->toArray()
            ], 201);

        } catch (Exception $e) {
            logError($e->getMessage());
            return $response->json([
                'message' => FATAL_ERROR
            ], 500);
        }
    }

    public function update(Request $request, Response $response) {
        try {
            $data = $request->body();
            $files = $request->files();
            $iconData = "";
            $photoData = "";

            $category = new ListingCategory($data);

            if(!CategoryValidator::create($data)) {
                return;
            }

            if(!empty($files['icon']) || !empty($files['photo'])) {
                $errors = "";

                if(!empty($files['icon'])) {
                    $iconData = FileValidators::validIcon($files['icon']);

                    if(isset($iconData['invalid'])) {
                        $errors = $iconData['invalid'];
                    }
                }

                if(!empty($files['photo'])) {
                    $photoData = FileValidators::validImage($files['photo']);

                    if(isset($photoData['invalid'])) {
                        $errors = $photoData['invalid'];
                    }
                }

                if(!empty($errors)) {
                    return $response->json([
                        'message' => $errors
                    ], 400);
                }
            }

            if(!empty($iconData) || !empty($photoData)) {
                if(!empty($iconData)) {
                    $iconName = $category->getId() . "_icon." . $iconData['mimeType'];
                    UploadFile::uploadFile($files['icon'], $this->categoryImagesFolder, $iconName);
                    $category->setIcon($iconName);
                }

                if(!empty($photoData)) {
                    $photoName = $category->getId() . "_photo." . $photoData['mimeType'];
                    UploadFile::uploadFile($files['photo'], $this->categoryImagesFolder, $photoName);
                    $category->setPhoto($photoName);
                }

                $category->save();
            }

            $category->save();

            return $response->json([
                "message" => CATEGORY_UPDATED,
                "categoryData" => $category->toArray()
            ], 201);

        } catch (Exception $e) {
            logError($e->getMessage());
            return $response->json([
                'message' => FATAL_ERROR
            ], 500);
        }
    }

    public function updatePhotoAndIcon(Request $request, Response $response, $params) {
        try {
            $files = $request->files();
            $iconData = "";
            $photoData = "";

            if(!empty($files['icon']) || !empty($files['photo'])) {
                $errors = "";

                if(!empty($files['icon'])) {
                    $iconData = FileValidators::validIcon($files['icon']);

                    if(isset($iconData['invalid'])) {
                        $errors = $iconData['invalid'];
                    }
                }

                if(!empty($files['photo'])) {
                    $photoData = FileValidators::validImage($files['photo']);

                    if(isset($photoData['invalid'])) {
                        $errors = $photoData['invalid'];
                    }
                }

                if(!empty($errors)) {
                    return $response->json([
                        'message' => $errors
                    ], 400);
                }

                $category = new ListingCategory();
                $category->fetchById($params[0]);

                if(!empty($iconData)) {
                    $iconName = $category->getId() . "_icon." . $iconData['mimeType'];
                    
                    if(!empty($category->getIcon())) {
                        UploadFile::removeFile($category->getIcon(), $this->categoryImagesFolder);
                    }

                    UploadFile::uploadFile($files['icon'], $this->categoryImagesFolder, $iconName);
                    $category->setIcon($iconName);
                }

                if(!empty($photoData)) {
                    if(!empty($category->getPhoto())) {
                        UploadFile::removeFile($category->getPhoto(), $this->categoryImagesFolder);
                    }

                    $photoName = $category->getId() . "_photo." . $photoData['mimeType'];
                    UploadFile::uploadFile($files['photo'], $this->categoryImagesFolder, $photoName);
                    $category->setPhoto($photoName);
                }

                $category->save();

                return $response->json([
                    "message" => CATEGORY_UPDATED,
                ], 201);
            } else {
                return $response->json([
                    "message" => NO_FILES,
                ], 400);
            }
            
        } catch (Exception $e) {
            logError($e->getMessage());
            return $response->json([
                'message' => FATAL_ERROR
            ], 500);
        }
    }
}
