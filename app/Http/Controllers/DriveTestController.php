<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleDriveService;
use Google\Service\Drive\DriveFile;

class DriveTestController extends Controller
{
    public function form()
    {
        return view('drive-test');
    }

    public function upload(Request $request, GoogleDriveService $gdrive)
    {
        $request->validate([
            'file' => 'required|file|max:5120',
        ]);

        // Ambil access token user, misal dari session atau database
        $accessToken = $request->user()->google_access_token ?? null;

        if (!$accessToken) {
            return back()->withErrors(['file' => 'User belum terautentikasi ke Google Drive']);
        }

        // Buat service Drive
        $service = $gdrive->makeDrive($accessToken);

        $file = $request->file('file');
        $driveFile = new DriveFile([
            'name'    => $file->getClientOriginalName(),
            'parents' => [env('1te1SzsFpCRyDtNIM1OZisXNDodoCZDgN')],
        ]);

        $created = $service->files->create($driveFile, [
            'data' => file_get_contents($file->getRealPath()),
            'mimeType' => $file->getMimeType(),
            'uploadType' => 'multipart',
            'fields' => 'id,name,webViewLink,webContentLink',
        ]);

        // (Opsional) share link
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

        return back()->with('ok', 'Berhasil! Link: ' . $created->webViewLink);
    }
}
