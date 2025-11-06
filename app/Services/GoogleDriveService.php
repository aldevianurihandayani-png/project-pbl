<?php
namespace App\Services;

use Google\Client;
use Google\Service\Drive;

class GoogleDriveService {
    public function client(): Client {
        $c = new Client();
        $c->setAuthConfig(base_path(env('GOOGLE_DRIVE_SERVICE_ACCOUNT_JSON')));
        $c->addScope(Drive::DRIVE);
        return $c;
    }
    public function service(): Drive {
        return new Drive($this->client());
    }
}
