<?php

namespace App\Controllers;

use App\Class\ListingCategory;
use App\Http\Request;
use App\Http\Response;
use App\Models\ListingCategoryDAO;
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

            $category = new ListingCategory($data);

            if(ListingCategoryDAO::fetchBySlugUrl($category->getSlugUrl())) {
                return $response->json([
                    'message'   => SLUG_URL_IS_ALREADY
                ], 409);
            }

            if(!CategoryValidator::create($data)) {
                return;
            }

            if(!empty($files['icon'])) {
                $errors = "";

                if(!empty($files['icon'])) {
                    $iconData = FileValidators::validIcon($files['icon']);

                    if(isset($iconData['invalid'])) {
                        $errors = $iconData['invalid'];
                    }
                }

                if(!empty($errors)) {
                    return $response->json([
                        'message' => $errors
                    ], 400);
                }
            }

            $category->save();

            if(!empty($iconData)) {
                if(!empty($iconData)) {
                    $iconName = $category->getId() . "_icon." . $iconData['mimeType'];
                    UploadFile::uploadFile($files['icon'], $this->categoryImagesFolder, $iconName);
                    $category->setIcon($iconName);
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

    public function fetch(Request $request, Response $response) {
        try {
            return $response->json(ListingCategoryDAO::fetch());
        } catch (\Exception $e) {
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

            $category = new ListingCategory();
            $category->fetchById($data['id']);
            $category->update($data);

            if(!CategoryValidator::create($data)) {
                return;
            }

            if(isset($files['icon']) && !empty($files['icon'])) {
                $errors = "";

                $iconData = FileValidators::validIcon($files['icon']);

                if(isset($iconData['invalid'])) {
                    $errors = $iconData['invalid'];
                }

                if(!empty($errors)) {
                    return $response->json([
                        'message' => $errors
                    ], 400);
                }
            }

            if(!empty($iconData)) {
                $iconName = $category->getId() . "_icon." . $iconData['mimeType'];

                if($category->getIcon()) {
                    UploadFile::removeFile($category->getIcon(), $this->categoryImagesFolder);
                }

                UploadFile::uploadFile($files['icon'], $this->categoryImagesFolder, $iconName);
                $category->setIcon($iconName);
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

    public function delete(Request $request, Response $response, $params) {
        try {
            $category = new ListingCategory();
            $category->fetchById($params[0]);

            if(!empty($category->getIcon())) {
                UploadFile::removeFile($category->getIcon(), $this->categoryImagesFolder);
            }

            $category->delete();

            return $response->json([
                "message" => CATEGORY_DELETE,
            ]);

        } catch (Exception $e) {
            logError($e->getMessage());
            return $response->json([
                'message' => FATAL_ERROR
            ], 500);
        }
    }

    public function deleteMultiples(Request $request, Response $response) {
        try {
            $data = $request->body();

            foreach ($data as $value) {
                $category = new ListingCategory();
                $category->fetchById($value);

                if(!empty($category->getIcon())) {
                    UploadFile::removeFile($category->getIcon(), $this->categoryImagesFolder);
                }

                $category->delete();
            }

            return $response->json([
                "message" => CATEGORIES_DELETED,
            ]);

        } catch (Exception $e) {
            logError($e->getMessage());
            return $response->json([
                'message' => FATAL_ERROR
            ], 500);
        }
    }
}
