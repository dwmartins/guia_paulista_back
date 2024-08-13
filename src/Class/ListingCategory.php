<?php

namespace App\Class;

use App\Models\ListingCategoryDAO;

class ListingCategory {
    private int $id = 0;
    private string $name = "";
    private string $photo = "";
    private string $icon = "";
    private string $slugUrl = "";
    private string $status = "Y";
    private string $createdAt = "";
    private string $updatedAt = "";

    public function __construct(array $category = null) {
        if(!empty($category)) {
            foreach ($category as $key => $value) {
                if(property_exists($this, $key)) {
                    if(empty($value)) {
                        continue;
                    }

                    if($key == "photo" || $key == "icon") {
                        continue;
                    }

                    $this->$key = $value;
                }
            }
        }
    }

    public function toArray(): array {
        return [
            "id"        => $this->id,
            "name"      => $this->name,
            "photo"     => $this->photo,
            "icon"      => $this->icon,
            "slugUrl"   => $this->slugUrl,
            "status"    => $this->status,
            "createdAt" => $this->createdAt,
            "updatedAt" => $this->updatedAt,

        ];
    }

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function getPhoto(): string {
        return $this->photo;
    }

    public function setPhoto(string $photo): void {
        $this->photo = $photo;
    }

    public function getIcon(): string {
        return $this->icon;
    }

    public function setIcon(string $icon): void {
        $this->icon = $icon;
    }

    public function getSlugUrl(): string {
        return $this->slugUrl;
    }

    public function setSlugUrl(string $slugUrl): void {
        $this->slugUrl = $slugUrl;
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function setStatus(string $status): void {
        $this->status = $status;
    }

    public function getCreatedAt(): string {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): string {
        return $this->updatedAt;
    }

    public function setUpdatedAt(string $updatedAt): void {
        $this->updatedAt = $updatedAt;
    }

    public function save(): void {
        if(empty($this->getId())) {
            $this->id = ListingCategoryDAO::save($this);
        } else {
            ListingCategoryDAO::update($this);
        }
    }

    public function delete() {
        return ListingCategoryDAO::delete($this);
    }

    public function fetchById(int $id): array {
        $category = ListingCategoryDAO::fetchById($id);

        if(!empty($category)) {
            foreach ($category as $key => $value) {
                if(empty($value)) {
                    continue;
                }

                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
        }

        return $category;
    }
}