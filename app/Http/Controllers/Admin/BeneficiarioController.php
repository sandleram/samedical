<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Beneficiario;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BeneficiarioController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $beneficiarios = Beneficiario::query()
            ->with(['cliente', 'empresa'])
            ->forTenant()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('nome', 'like', "%{$search}%")
                        ->orWhere('cpf', 'like', "%{$search}%")
                        ->orWhere('matricula', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.beneficiarios.index', [
            'title' => 'Beneficiários',
            'beneficiarios' => $beneficiarios,
            'search' => $search,
        ]);
    }

    public function show(int $id): View
    {
        $beneficiario = Beneficiario::query()
            ->with(['cliente', 'empresa', 'grupoEmpresarial'])
            ->forTenant()
            ->findOrFail($id);

        return view('admin.beneficiarios.show', [
            'title' => 'Beneficiário',
            'beneficiario' => $beneficiario,
        ]);
    }
}
