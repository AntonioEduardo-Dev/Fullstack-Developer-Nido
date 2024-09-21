<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface 
{
    /**
     * Encontra um item pelo ID.
     *
     * @param int $id O ID do item a ser encontrado.
     * @return \Illuminate\Database\Eloquent\Collection|Model|null O item encontrado ou null se não encontrado.
     */
    public function find(int $id): ?Model;

    /**
     * Encontra um item por uma coluna e valor específicos.
     *
     * @param string $column_id O nome da coluna a ser pesquisada.
     * @param mixed $value O valor da coluna para encontrar o item.
     * @return \Illuminate\Database\Eloquent\Collection|Model|null O item encontrado ou null se não encontrado.
     */
    public function findBy(string $column_id, $value): ?Model;

    /**
     * Encontra um modelo pela colunas e valores especificados.
     *
     * @param array $columns valores e colunas de dados.
     * @return T|null O modelo encontrado ou null se não encontrado.
     */
    public function findByColumns(array $columns): ?Model;

    /**
     * Atualiza ou cria um item com base nos atributos e valores fornecidos.
     *
     * @param array $attr Atributos a serem usados para encontrar o item.
     * @param array $values Valores a serem atualizados ou criados.
     * @return \Illuminate\Database\Eloquent\Collection|Model O item atualizado ou criado.
     */
    public function updateOrCreate(array $attr, array $values): Model;

    /**
     * Pesquisa ou cria um item com base nas colunas fornecidas.
     *
     * @param array $attr Atributos a serem usados para encontrar o item ou criar.
     * @return \Illuminate\Database\Eloquent\Collection|Model O item atualizado ou criado.
     */
    public function firstOrNew(array $columns): Model;

    /**
     * Retorna todos os itens.
     *
     * @return \Illuminate\Database\Eloquent\Collection Uma coleção contendo todos os itens.
     */
    public function all(): Collection;

    /**
     * Cria um novo item com os atributos fornecidos.
     *
     * @param array $attributes Atributos do novo item.
     * @return \Illuminate\Database\Eloquent\Collection|Model O item criado.
     */
    public function create(array $attributes): Model;

    /**
     * Atualiza um item existente com base no ID e nos atributos fornecidos.
     *
     * @param int $id O ID do item a ser atualizado.
     * @param array $attributes Atributos a serem atualizados.
     * @return bool Retorna true se a atualização foi bem-sucedida, false caso contrário.
     */
    public function update(int $id, array $attributes): bool;

    /**
     * Exclui um item com base no ID fornecido.
     *
     * @param int $id O ID do item a ser excluído.
     * @return bool Retorna true se a exclusão foi bem-sucedida, false caso contrário.
     */
    public function delete(int $id): bool;
}
