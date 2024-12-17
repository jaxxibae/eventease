<?php
class Encryption {
    public static function hashPassword($password, $salt): string {
        return hash('sha256', $password . $salt);
    }

    public static function generateSalt(): string {
        return bin2hex(random_bytes(32));
    }
}