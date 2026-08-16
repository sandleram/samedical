<?php

namespace App\Interfaces\Http\Controllers\Admin;

use App\Application\Db\GetDbIndex;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

/**
 * Utilitário DB (legado DbController).
 * admin_index legado embutia phpMyAdmin com credenciais — não portado.
 * URL externa configurável via SAMED_PHPMYADMIN_URL.
 */
class DbController extends Controller
{
    public function __construct(
        private readonly GetDbIndex $getDbIndex,
    ) {}

    public function index(): View
    {
        $data = $this->getDbIndex->execute();

        return view('admin.db.index', $data);
    }
}
