<?php

namespace App\Controllers;

use App\Class\FileCache;
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

            $fieldsValid = SiteInfoValidator::create($requestData);

            if(!$fieldsValid['isValid']) {
                return $response->json([
                    "message" => $fieldsValid['message']
                ], 400);
            }
                
            $siteInfo = new SiteInfo();
            $siteInfo->fetch();

            if(!empty($siteInfo->getId())) {
                $siteInfo->update($requestData);
            }

            $siteInfo->save();

            $this->updateCache($siteInfo);

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
                "logo" => !empty($requestFiles["logo"]) ? $requestFiles["logo"] : null,
                "cover" => !empty($requestFiles["cover"]) ? $requestFiles["cover"] : null,
                "icon" => !empty($requestFiles["icon"]) ? $requestFiles["icon"] : null,
                "default" => !empty($requestFiles["default"]) ? $requestFiles["default"] : null
            ];

            $siteInfo = new SiteInfo();
            $siteInfo->fetch();

            foreach ($files as $key => $file) {
                if(empty($file)) {
                    continue;
                }

                if($key == "icon") {
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

                switch ($key) {
                    case "logo":
                        if(!empty($siteInfo->getLogoImage())) {
                            UploadFile::removeFile($siteInfo->getLogoImage(), $this->siteInfoImagesFolder);
                        }

                        $siteInfo->setLogoImage($fileName);
                        break;
                    case "cover":
                        if(!empty($siteInfo->getCoverImage())) {
                            UploadFile::removeFile($siteInfo->getCoverImage(), $this->siteInfoImagesFolder);
                        }

                        $siteInfo->setCoverImage($fileName);
                        break;
                    case "default":
                        if(!empty($siteInfo->getDefaultImage())) {
                            UploadFile::removeFile($siteInfo->getDefaultImage(), $this->siteInfoImagesFolder);
                        }

                        $siteInfo->setDefaultImage($fileName);
                        break;
                    case "icon":
                        if(!empty($siteInfo->getIco())) {
                            UploadFile::removeFile($siteInfo->getIco(), $this->siteInfoImagesFolder);
                        }

                        $siteInfo->setIco($fileName);
                        break;
                    default:
                        break;
                }

                UploadFile::uploadFile($file, $this->siteInfoImagesFolder, $fileName);
            }

            $siteInfo->save();

            $this->updateCache($siteInfo);

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
            $cache = new FileCache();
            $cacheData = $cache->get('site_info');
            $data = [];

            if($cacheData) {
                $data = $cacheData;
            } else {
                $data = $siteInfo->fetch();
                $cache->set('site_info', $data);
            }
            
            return $response->json([
                "siteInfo" => $data,
                "settings" => getAllSettings()
            ]);

        } catch (Exception $e) {
            logError($e->getMessage());
            return $response->json([
                "message" => FATAL_ERROR
            ], 500);
        }
    }

    private function updateCache(SiteInfo $siteInfo) {
        $cache = new FileCache();
        $cache->set('site_info', $siteInfo->toArray());
    }
}