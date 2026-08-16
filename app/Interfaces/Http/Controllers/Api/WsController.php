<?php

namespace App\Interfaces\Http\Controllers\Api;

use App\Application\Ws\CallBiBeneficiarios;
use App\Application\Ws\GetWsIndex;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Web Service — superfície mínima Onda F (call_bi_*).
 */
class WsController extends Controller
{
    public function __construct(
        private readonly GetWsIndex $indexUseCase,
        private readonly CallBiBeneficiarios $callBi,
    ) {}

    public function index(): Response
    {
        return response('', $this->indexUseCase->execute());
    }

    public function callBiBeneficiarios(Request $request): JsonResponse
    {
        $clienteId = $request->filled('cliente_id') ? (int) $request->query('cliente_id') : null;
        $result = $this->callBi->execute(
            (string) $request->query('token', ''),
            $clienteId,
            (int) $request->query('limit', 10),
            'call_bi_beneficiarios',
        );

        return response()->json($result->payload, $result->httpStatus);
    }

    public function callBiBeneficiarios2(Request $request): JsonResponse
    {
        $clienteId = $request->filled('cliente_id') ? (int) $request->query('cliente_id') : null;
        $result = $this->callBi->execute(
            (string) $request->query('token', ''),
            $clienteId,
            (int) $request->query('limit', 10),
            'call_bi_beneficiarios2',
        );

        return response()->json($result->payload, $result->httpStatus);
    }
}
