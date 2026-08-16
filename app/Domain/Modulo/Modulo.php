<?php

namespace App\Domain\Modulo;

/**
 * Entidade de domínio Módulo.
 * Sem dependências Laravel.
 */
final class Modulo
{
    public function __construct(
        public readonly ?int $id,
        public readonly int $moduloId,
        public readonly string $nome,
        public readonly string $controller,
        public readonly int $order,
        public readonly int $menu,
        public readonly ?string $icon,
        public readonly int $status,
        public readonly ?\DateTimeImmutable $dataCadastro = null,
        public readonly ?\DateTimeImmutable $dataAtualizacao = null,
        public readonly ?string $parentNome = null,
    ) {}

    public function __get(string $name): mixed
    {
        return match ($name) {
            'id' => $this->id,
            'modulo_id' => $this->moduloId,
            'nome' => $this->nome,
            'controller' => $this->controller,
            'order' => $this->order,
            'menu' => $this->menu,
            'icon' => $this->icon,
            'status' => $this->status,
            'data_cadastro' => $this->dataCadastro,
            'data_atualizacao' => $this->dataAtualizacao,
            'parent' => (object) ['nome' => $this->parentNome],
            default => null,
        };
    }

    public function __isset(string $name): bool
    {
        return $this->__get($name) !== null;
    }
}
