<?php

namespace App\Class;

use App\Models\SettingsDAO;

class Settings {
    private string $name = "";
    private string $setting = "";

    public function __construct(array $setting = null) {
        if(!empty($setting)) {
            $this->name = $setting['name'];
            $this->setting = $setting['setting'];
        }
    }

    public function toArray(): array {
        return [
            "name"    => $this->name,
            "setting" => $this->setting 
        ];
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName($name): void {
        $this->name = $name;
    }

    public function getSetting(): string {
        return $this->setting;
    }

    public function setSetting($setting): void {
        $this->setting = $setting;
    }   

    public function save() {
        SettingsDAO::save($this);
    }

    public function update(array $values) {
        $this->name = $values["name"];
        $this->setting = $values["setting"];

        SettingsDAO::update($this);
    }
}
