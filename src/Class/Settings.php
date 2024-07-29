<?php

namespace App\Class;

use App\Models\SettingsDAO;

class Settings {
    private string $name = "";
    private string $value = "";

    public function __construct(array $value = null) {
        if(!empty($value)) {
            $this->name = $value['name'];
            $this->value = $value['value'];
        }
    }

    public function toArray(): array {
        return [
            "name"    => $this->name,
            "value" => $this->value 
        ];
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName($name): void {
        $this->name = $name;
    }

    public function getValue(): string {
        return $this->value;
    }

    public function setValue($value): void {
        $this->value = $value;
    }   

    public function save() {
        SettingsDAO::save($this);
    }

    public function update(array $values) {
        $this->name = $values["name"];
        $this->value = $values["value"];

        SettingsDAO::update($this);
    }
}
