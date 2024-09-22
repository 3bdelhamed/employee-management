<?php

require_once __DIR__ . '/../helpers/functions.php';

class FileManager {

    private string $upload_path;

    function __construct() {
        $this->upload_path = config('app', 'upload_path');
    }

    function store($file): bool|string {
        if (!is_array($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }
    
        $from = $file['tmp_name'];
        $new_file_name = time() . '.' . $this->getFileExtension($file);
        $target = $this->upload_path . '/' . $new_file_name;
        if (move_uploaded_file($from, $target)) {
            return $new_file_name;
        } else {
            return false;
        }
    }
    

    function delete($filename): bool {
        $target = $this->upload_path . '/' . $filename;
        if (file_exists($target)) {
            unlink($target);
            return true;
        } else {
            return false;
        }
    }

    private function getFileExtension($file): string {
        return pathinfo($file['name'], PATHINFO_EXTENSION);
    }

}
