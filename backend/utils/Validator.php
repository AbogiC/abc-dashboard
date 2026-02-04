<?php
class Validator
{
    public static function validateRequired($data, $fields)
    {
        $errors = [];
        foreach ($fields as $field) {
            if (!isset($data[$field]) || empty(trim($data[$field]))) {
                $errors[$field] = "The $field field is required";
            }
        }
        return $errors;
    }

    public static function validateEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email format";
        }
        return null;
    }

    public static function validatePassword($password)
    {
        if (strlen($password) < 8) {
            return "Password must be at least 8 characters long";
        }
        return null;
    }

    public static function validateDate($date, $format = DATE_FORMAT)
    {
        $d = DateTime::createFromFormat($format, $date);
        if (!$d || $d->format($format) !== $date) {
            return "Invalid date format. Expected $format";
        }
        return null;
    }

    public static function sanitize($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::sanitize($value);
            }
            return $data;
        }

        // Remove whitespace
        $data = trim($data);
        // Remove slashes
        $data = stripslashes($data);
        // Convert special characters to HTML entities
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

        return $data;
    }
}
?>