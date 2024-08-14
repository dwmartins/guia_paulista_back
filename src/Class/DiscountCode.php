<?php

namespace App\Class;

use App\Models\DiscountCodeDAO;

class DiscountCode {
    private int $id = 0;
    private string $code = "";
    private int $discount = 0;
    private int $sponsor = 0;
    private string $startDate = "";
    private string $endDate = "";
    private string $active = "N";
    private string $module = "";
    private int $maxUses = 0;
    private int $currentUses = 0;
    private string $createdAt = "";
    private string $updatedAt = "";

    public function __construct(array $code = null) {
        if(!empty($code)) {
            foreach ($code as $key => $value) {
                if(empty($value)) {
                    continue;
                }

                if(property_exists($this, $key)) {
                    if($key === "module") {
                        $this->module = strtolower($value);
                        continue;
                    }

                    $this->$key = $value;
                }
            }
        }
    }

    public function toArray(): array {
        return [
            "id"            => $this->id,
            "code"          => $this->code,
            "discount"      => $this->discount,
            "sponsor"       => $this->sponsor,
            "startDate"     => $this->startDate,
            "endDate"       => $this->endDate,
            "active"        => $this->active,
            "module"        => $this->module,
            "maxUses"       => $this->maxUses,
            "currentUses"   => $this->currentUses,
            "createdAt"     => $this->createdAt,
            "updatedAt"     => $this->updatedAt
        ];
    }

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getCode(): string {
        return $this->code;
    }

    public function setCode(string $code): void {
        $this->code = $code;
    }

    public function getDiscount(): int {
        return $this->discount;
    }

    public function setDiscount(int $discount): void {
        $this->discount = $discount;
    }

    public function getSponsor(): int {
        return $this->sponsor;
    }

    public function setSponsor(int $sponsor): void {
        $this->sponsor = $sponsor;
    }

    public function getStartDate(): string {
        return $this->startDate;
    }

    public function setStartDate(string $startDate): void {
        $this->startDate = $startDate;
    }

    public function getEndDate(): string {
        return $this->endDate;
    }

    public function setEndDate(string $endDate): void {
        $this->endDate = $endDate;
    }

    public function getActive(): string {
        return $this->active;
    }

    public function setActive(string $active): void {
        $this->active = $active;
    }

    public function getModule(): string {
        return $this->module;
    }

    public function setModule(string $module): void {
        $this->module = strtolower($module);
    }

    public function getMaxUses(): int {
        return $this->maxUses;
    }

    public function setMaxUses(int $maxUses): void {
        $this->maxUses = $maxUses;
    }

    public function getCurrentUses(): int {
        return $this->currentUses;
    }

    public function setCurrentUses(int $currentUses): void {
        $this->currentUses = $currentUses;
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

    public function useDiscountCode(): void {
        $this->currentUses++;

        if($this->currentUses === $this->maxUses) {
            $this->setActive("N");
        }

        $this->save();
    }

    public function save(): int {
        if(empty($this->getId())) {
            return DiscountCodeDAO::save($this);
        } else {
            return DiscountCodeDAO::update($this);
        }
    }

    public function fetchById(int $id): array {
        $discountCode = DiscountCodeDAO::fetchById($id);

        if(!empty($discountCode)) {
            foreach ($discountCode as $key => $value) {
                if(empty($value)) {
                    continue;
                }

                if(property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
        }

        return $discountCode;
    }  

    public function fetchByCode(string $code): array {
        $discountCode = DiscountCodeDAO::fetchByCode($code);

        if(!empty($discountCode)) {
            foreach ($discountCode as $key => $value) {
                if(empty($value)) {
                    continue;
                }

                if(property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
        }

        return $discountCode;
    } 
}