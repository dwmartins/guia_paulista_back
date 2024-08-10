<?php 

namespace App\Class;

use App\Models\UserDAO;

class User {
    private int $id = 0;
    private string $name = "";
    private string $lastName = "";
    private string $email = "";
    private string $password = "";
    private string $token = "";
    private string $active = "Y";
    private string $role = "visitor"; // ['support', 'admin', 'mod', 'sponsor', visitor, 'test']
    private string $description = "";
    private string $phone = "";
    private ?string $dateOfBirth = null;
    private string $address = "";
    private string $city = "";
    private string $zipCode = "";
    private string $state = "";
    private string $photo = "";
    private string $acceptsEmails = "Y";
    private string $publishContactInfo = "N";
    private string $createdAt = "";
    private string $updatedAt = "";

    public function __construct(array $user = null) {
        if (!empty($user)) {
            foreach ($user as $key => $value) {
                if (property_exists($this, $key)) {
                    if(empty($value)) {
                        continue;
                    }
                    
                    if($key === "password") {
                        $this->password = $this->isPasswordHashed($value) ? $value : password_hash($value, PASSWORD_DEFAULT);
                        continue;
                    }

                    $this->$key = $value;
                }
            }
        }
    }

    public function toArray(): array {
        return [
            "id"                    => $this->id,
            "name"                  => $this->name,
            "lastName"              => $this->lastName,
            "email"                 => $this->email,
            "password"              => $this->password,
            "token"                 => $this->token,
            "active"                => $this->active,
            "role"                  => $this->role,
            "description"           => $this->description,
            "phone"                 => $this->phone,
            "photo"                 => $this->photo,
            "dateOfBirth"           => $this->dateOfBirth,
            "address"               => $this->address,
            "city"                  => $this->city,
            "zipCode"               => $this->zipCode,
            "state"                 => $this->state,
            "acceptsEmails"         => $this->acceptsEmails,
            "publishContactInfo"    => $this->publishContactInfo,
            "createdAt"             => $this->createdAt,
            "updatedAt"             => $this->updatedAt,
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
        $this->name = ucfirst($name);
    }

    public function getLastName(): string {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void {
        $this->lastName = ucfirst($lastName);
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function setPassword(string $password): void {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function getToken(): string {
        return $this->token;
    }

    public function setToken(string $token): void {
        $this->token = $token;
    }

    public function getActive(): string {
        return $this->active;
    }

    public function setActive(string $active): void {
        $this->active = $active;
    }

    public function getRole(): string {
        return $this->role;
    }

    public function setRole(string $role): void {
        $this->role = $role;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function setDescription(string $description): void {
        $this->description = $description;
    }

    public function getPhone(): string {
        return $this->phone;
    }

    public function setPhone(string $phone): void {
        $this->phone = $phone;
    }

    public function getPhoto(): string {
        return $this->photo;
    }

    public function setPhoto(string $photo): void {
        $this->photo = $photo;
    }

    // Getter e Setter para dateOfBirth
    public function getDateOfBirth(): string {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(string $dateOfBirth): void {
        $this->dateOfBirth = $dateOfBirth;
    }

    public function getAddress(): string {
        return $this->address;
    }

    public function setAddress(string $address): void {
        $this->address = $address;
    }

    public function getCity(): string {
        return $this->city;
    }

    public function setCity(string $city): void {
        $this->city = $city;
    }

    public function getZipCode(): string {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): void {
        $this->zipCode = $zipCode;
    }

    public function getState(): string {
        return $this->state;
    }

    public function setState(string $state): void {
        $this->state = $state;
    }

    public function getAcceptsEmails(): string {
        return $this->acceptsEmails;
    }

    public function setAcceptsEmails(string $acceptsEmails): void {
        $this->photo = $acceptsEmails;
    }

    public function getPublishContactInfo(): string {
        return $this->publishContactInfo;
    }

    public function setPublishContactInfo(string $info): void {
        $this->publishContactInfo = $info;
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

    public function setUpdatedAt($updatedAt): void {
        $this->updatedAt = $updatedAt;
    }

    private function isPasswordHashed($password) {
        $info = password_get_info($password);
        return $info['algo'];
    }

    public function update(array $user): void {
        foreach ($user as $key => $value) {
            if(empty($value)) {
                continue;
            }

            if (property_exists($this, $key)) {
                if($key === "password") {
                    $this->password = $this->isPasswordHashed($value) ? $value : password_hash($value, PASSWORD_DEFAULT);
                    continue;
                }

                $this->$key = $value;
            }
        }
    }

    public function save(): void {
        if(empty($this->getId())) {
            $this->id = UserDAO::save($this);
        } else {
            UserDAO::update($this);
        }
    }

    public function updatePassword(): int {
        return UserDAO::updatePassword($this);
    }

    public function updatePhoto(): int {
        return UserDAO::updatePhoto($this);
    }

    public function delete(): int {
        return UserDAO::delete($this);
    }

    public function fetchById(int $user_id): array {
        $user = UserDAO::fetchById($user_id);

        if(!empty($user)) {
            foreach ($user as $key => $value) {
                if(empty($value)) {
                    continue;
                }

                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
        }

        return $user;
    }

    public function fetchByEmail(string $email): array {
        $user = UserDAO::fetchByEmail($email);

        if(!empty($user)) {
            foreach ($user as $key => $value) {
                if(empty($value)) {
                    continue;
                }

                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
        }

        return $user;
    }

    public function updateRole(): int {
        return UserDAO::updateRole($this);
    }
 }