<?php
require_once __DIR__ . '/../../config/config.php';

class DashboardController {
    public function index() {
        requerirLogin();
        require_once __DIR__ . '/../views/dashboard.php';
    }
}