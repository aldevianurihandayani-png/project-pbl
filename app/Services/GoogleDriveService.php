<?php
namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Http\UploadedFile;

class GoogleDriveService
{
    /**
     * Buat Google Client
     */
    public function makeClient(): Client
    {
        $client = new Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT'));
        $client->addScope(Drive::DRIVE);
        $client->setAccessType('offline');
        $client->setPrompt('consent');
        return $client;
    }

    /**
     * Buat service Drive dari token
     */
    public function makeDrive(array|string $accessToken): Drive
    {
        $client = $this->makeClient();
        $client->setAccessToken($accessToken);

        if ($client->isAccessTokenExpired() && isset($accessToken['refresh_token'])) {
            $client->fetchAccessTokenWithRefreshToken($accessToken['refresh_token']);
        }

        return new Drive($client);
    }

    /**
     * Upload file logbook ke Drive, return link publik atau null
     */
    public function uploadLogbookFile($user, UploadedFile $file): ?string
    {
        $accessToken = $user->google_access_token ?? null;
        if (!$accessToken) return null;

        $service = $this->makeDrive($accessToken);

        $driveFile = new DriveFile([
            'name'    => $file->getClientOriginalName(),
            'parents' => [env('GOOGLE_DRIVE_LOGBOOK_FOLDER')],
        ]);

        $created = $service->files->create($driveFile, [
            'data' => file_get_contents($file->getRealPath()),
            'mimeType' => $file->getMimeType(),
            'uploadType' => 'multipart',
            'fields' => 'id,name,webViewLink,webContentLink',
        ]);

        // opsional: share publik
        if (env('GOOGLE_DRIVE_SHARE_WITH_LINK', false)) {
            $perm = new \Google\Service\Drive\Permission();
            if (env('GOOGLE_DRIVE_DOMAIN_RESTRICTED', false)) {
                $perm->setType('domain');
                $perm->setRole('reader');
                $perm->setDomain(env('GOOGLE_DRIVE_DOMAIN'));
            } else {
                $perm->setType('anyone');
                $perm->setRole('reader');
            }
            $service->permissions->create($created->id, $perm);
        }

        return $created->webViewLink;
    }
}
