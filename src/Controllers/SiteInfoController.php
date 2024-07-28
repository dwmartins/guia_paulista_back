<?php

namespace App\Controllers;

use App\Class\SiteInfo;
use App\Http\Request;
use App\Http\Response;
use App\Utils\UploadFile;
use App\Validators\FileValidators;
use App\Validators\SiteInfoValidator;
use Exception;

class SiteInfoController {

    private string $siteInfoImagesFolder = "systemImages";

    public function create(Request $request, Response $response) {
        try {
            $requestData = $request->body();

            $siteInfo = new SiteInfo($requestData);

            if(!SiteInfoValidator::create($siteInfo)) {
                return;
            }

            $siteInfo->fetch();

            if(!empty($siteInfo->getId())) {
                $siteInfo->update($requestData);
            }

            $siteInfo->save();

            $response->json([
                "message" => SAVED_WEBSITE_INFORMATION,
                "siteInfoData" => $siteInfo->toArray()
            ], 201);
            
        } catch (Exception $e) {
            logError($e->getMessage());
            return $response->json([
                "message" => FATAL_ERROR
            ], 500);
        }
    }

    public function setImages(Request $request, Response $response) {
        try {
            $requestFiles = $request->files();

            $files = [
                "logoImage" => !empty($requestFiles["logoImage"]) ? $requestFiles["logoImage"] : null,
                "coverImage" => !empty($requestFiles["coverImage"]) ? $requestFiles["coverImage"] : null,
                "ico" => !empty($requestFiles["ico"]) ? $requestFiles["ico"] : null,
                "defaultImage" => !empty($requestFiles["defaultImage"]) ? $requestFiles["defaultImage"] : null
            ];

            $siteInfo = new SiteInfo();
            $siteInfo->fetch();

            foreach ($files as $key => $file) {
                if(empty($file)) {
                    continue;
                }

                if($key == "ico") {
                    $fileData = FileValidators::validIcon($file);
                } else {
                    $fileData = FileValidators::validImage($file);
                }

                if(isset($fileData["invalid"])) {
                    return $response->json([
                        "error"   => true,
                        "message" => $fileData["invalid"]
                    ], 400);
                }

                $fileName = $key . "." . $fileData["mimeType"];
                UploadFile::uploadFile($file, $this->siteInfoImagesFolder, $fileName);

                switch ($key) {
                    case "logoImage":
                        $siteInfo->setLogoImage($fileName);
                        break;
                    case "coverImage":
                        $siteInfo->setCoverImage($fileName);
                        break;
                    case "defaultImage":
                        $siteInfo->setDefaultImage($fileName);
                        break;
                    case "ico":
                        $siteInfo->setIco($fileName);
                        break;
                    default:
                        break;
                }
            }

            $siteInfo->save();

            return $response->json([
                "message" => UPDATED_IMAGES,
                "siteInfoData" => $siteInfo->toArray()
            ]);

        } catch (Exception $e) {
            logError($e->getMessage());
            return $response->json([
                "message" => FATAL_ERROR
            ], 500);
        }
    }

    public function fetch(Request $request, Response $response) {
        try {
            $siteInfo = new SiteInfo();
            
            return $response->json([
                "siteInfo" => $siteInfo->fetch(),
                "settings" => getAllSettings()
            ]);

        } catch (Exception $e) {
            logError($e->getMessage());
            return $response->json([
                "message" => FATAL_ERROR
            ], 500);
        }
    }
}