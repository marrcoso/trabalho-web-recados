<?php
class Recado {
    public ?int $id;
    public string $mensagem;
    public string $data_criacao;
    public int $status;

    public function __construct(?int $id, string $mensagem, string $data_criacao, int $status) {
        $this->id = $id;
        $this->mensagem = $mensagem;
        $this->data_criacao = $data_criacao;
        $this->status = $status;
    }
}
