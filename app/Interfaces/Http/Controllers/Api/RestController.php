<?php

namespace App\Interfaces\Http\Controllers\Api;

use App\Application\Rest\GetRestIndex;
use App\Application\Rest\GetRestProativaBeneficiarios;
use App\Application\Rest\GetRestProativaDump;
use App\Application\Rest\GetRestProativaFaturamentos;
use App\Application\Rest\GetRestProativaSinistros;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * REST Proativa — controller fino (token + UseCase + JSON).
 */
class RestController extends Controller
{
    public function __construct(
        private readonly GetRestIndex $indexUseCase,
        private readonly GetRestProativaBeneficiarios $beneficiarios,
        private readonly GetRestProativaFaturamentos $faturamentos,
        private readonly GetRestProativaSinistros $sinistros,
        private readonly GetRestProativaDump $dump,
    ) {}

    public function index(): JsonResponse
    {
        return response()->json($this->indexUseCase->execute()->payload);
    }

    public function biProativaBeneficiario(Request $request): JsonResponse
    {
        $clienteId = $request->filled('cliente_id') ? (int) $request->query('cliente_id') : null;
        $result = $this->beneficiarios->execute(
            (string) $request->query('token', ''),
            $clienteId,
            (string) $request->ip(),
        );

        return response()->json($result->payload);
    }

    public function biProativaFaturamento(Request $request): JsonResponse
    {
        $clienteId = $request->filled('cliente_id') ? (int) $request->query('cliente_id') : null;
        $result = $this->faturamentos->execute(
            (string) $request->query('token', ''),
            $clienteId,
            (string) $request->ip(),
        );

        return response()->json($result->payload);
    }

    public function biProativaSinistro(Request $request): JsonResponse
    {
        $clienteId = $request->filled('cliente_id') ? (int) $request->query('cliente_id') : null;
        $result = $this->sinistros->execute(
            (string) $request->query('token', ''),
            $clienteId,
            (string) $request->ip(),
        );

        return response()->json($result->payload);
    }

    public function biProativaBeneficio(Request $request): JsonResponse
    {
        return $this->dumpJson($request, 'beneficio');
    }

    public function biProativaCliente(Request $request): JsonResponse
    {
        return $this->dumpJson($request, 'cliente');
    }

    public function biProativaGrupoEstatistico(Request $request): JsonResponse
    {
        return $this->dumpJson($request, 'grupo_estatistico');
    }

    public function biProativaCronicos(Request $request): JsonResponse
    {
        return $this->dumpJson($request, 'cronicos');
    }

    public function biProativaSubfaturas(Request $request): JsonResponse
    {
        return $this->dumpJson($request, 'subfaturas');
    }

    public function biProativaProcedimento(Request $request): JsonResponse
    {
        return $this->dumpJson($request, 'procedimento');
    }

    private function dumpJson(Request $request, string $resource): JsonResponse
    {
        $result = $this->dump->execute(
            $resource,
            (string) $request->query('token', ''),
            (string) $request->ip(),
        );

        return response()->json($result->payload);
    }
}
