<?php

namespace App\Interfaces\Http\Controllers\Admin;

use App\Application\Blob\DownloadBlob;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Download de arquivos da tabela blob (legado BlobController::admin_download).
 * Action sempre liberada via config/samed.php → always_allowed_actions.
 */
class BlobController extends Controller
{
    public function __construct(
        private readonly DownloadBlob $downloadBlob,
    ) {}

    public function download(Request $request, string $id): Response|RedirectResponse
    {
        $file = $this->downloadBlob->execute($id);

        if ($file === null) {
            return $this->missing($request, 'Arquivo Inexistente');
        }

        if ($file->content === '') {
            return $this->missing($request, 'Arquivo não existe no banco de dados!');
        }

        return response($file->content, 200, [
            'Content-Type' => $file->tipo ?: 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="'.addslashes($file->nome).'"',
            'Content-Length' => (string) ($file->tamanho ?: strlen($file->content)),
        ]);
    }

    private function missing(Request $request, string $message): Response|RedirectResponse
    {
        if ($request->expectsJson()) {
            abort(404, $message);
        }

        return redirect()
            ->back(fallback: route('admin.home'))
            ->with('error', $message);
    }
}
