<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleDriveService;
use Google\Service\Drive\DriveFile;

class DriveTestController extends Controller
{
    public function form() { return view('drive-test'); }

    public function upload(Request $r, GoogleDriveService $gdrive)
    {
        $r->validate(['file' => 'required|file|max:5120']);
        $service = $gdrive->service();

        $driveFile = new DriveFile([
            'name'    => $r->file('file')->getClientOriginalName(),
            'parents' => [env('GOOGLE_DRIVE_LOGBOOK_ROOT_ID')],
        ]);

        $created = $service->files->create($driveFile, [
            'data' => file_get_contents($r->file('file')->getRealPath()),
            'mimeType' => $r->file('file')->getMimeType(),
            'uploadType' => 'multipart',
            'fields' => 'id,name,webViewLink,webContentLink',
        ]);

        // (opsional) buat link publik sesuai .env
        if (env('GOOGLE_DRIVE_SHARE_WITH_LINK', false)) {
            $perm = new \Google\Service\Drive\Permission();
            if (env('GOOGLE_DRIVE_DOMAIN_RESTRICTED', false)) {
                $perm->setType('domain'); $perm->setRole('reader'); $perm->setDomain(env('GOOGLE_DRIVE_DOMAIN'));
            } else {
                $perm->setType('anyone'); $perm->setRole('reader');
            }
            $service->permissions->create($created->id, $perm);
        }

        return back()->with('ok', 'Berhasil! Link: '.$created->webViewLink);
    }
}
