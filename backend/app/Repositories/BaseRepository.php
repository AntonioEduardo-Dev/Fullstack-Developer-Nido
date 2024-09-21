<?php

namespace App\Repositories;

use App\Interfaces\{
    RepositoryInterface
};

use Illuminate\Database\Eloquent\{
    Builder,
    Model,
    Collection
};

/**
 * Classe base para repositórios que fornece operações comuns para manipulação de modelos.
 *
 * @template T of Model
 * @implements RepositoryInterface<T>
 */
abstract class BaseRepository implements RepositoryInterface
{
    /**
     * O modelo associado a este repositório.
     *
     * @var T
     */
    protected $model;

    /**
     * Construtor da classe base do repositório.
     *
     * @param Model $model A instância do modelo associado a este repositório.
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Encontra um modelo pelo seu ID.
     *
     * @param int $id O ID do modelo.
     * @return T|null O modelo encontrado ou null se não encontrado.
     */
    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * Encontra um modelo pela coluna e valor especificados.
     *
     * @param string $column O nome da coluna para a busca.
     * @param mixed $value O valor da coluna para a busca.
     * @return T|null O modelo encontrado ou null se não encontrado.
     */
    public function findBy(string $column, $value): ?Model
    {
        return $this->model->where($column, $value)->first();
    }

    /**
     * Encontra um modelo pela coluna e valor especificados.
     *
     * @param string $column O nome da coluna para a busca.
     * @param mixed $value O valor da coluna para a busca.
     * @return T|null O modelo encontrado ou null se não encontrado.
     */
    public function findByWithOrder(string $column, $value, $orderColumn = 'id', $order = 'DESC'): ?Model
    {
        return $this->model->where($column, $value)->orderBy($orderColumn, $order)->first();
    }

    /**
     * Encontra um modelo pela colunas e valores especificados.
     *
     * @param array $columns valores e colunas de dados.
     * @return T|null O modelo encontrado ou null se não encontrado.
     */
    public function findByColumns(array $columns): ?Model
    {
        $query = $this->model;
        if ($columns) {
            foreach ($columns as $column => $value) {
                $query = $query->where($column, $value);
            }
        }
        return $query->first();
    }

    /**
     * Encontra um modelo pela colunas e valores especificados.
     *
     * @param array $columns valores e colunas de dados.
     * @return Collection Uma coleção contendo todos os modelos.
     */
    public function getByColumns(array $columns): Collection
    {
        $query = $this->model;
        if ($columns) {
            foreach ($columns as $column => $value) {
                $query = $query->where($column, $value);
            }
        }
        return $query->get();
    }

    /**
     * Obtém todos os modelos.
     *
     * @return Collection Uma coleção contendo todos os modelos.
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * Cria um novo modelo com os atributos fornecidos.
     *
     * @param array $attributes Um array de atributos para criar o modelo.
     * @return T O modelo criado.
     */
    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    /**
     * Atualiza um modelo existente com os atributos fornecidos.
     *
     * @param int $id O ID do modelo a ser atualizado.
     * @param array $attributes Um array de atributos para atualizar o modelo.
     * @return bool Verdadeiro se a atualização foi bem-sucedida, falso caso contrário.
     */
    public function update(int $id, array $attributes): bool
    {
        $item = $this->find($id);
        return $item ? $item->update($attributes) : false;
    }

    /**
     * Remove um modelo pelo seu ID.
     *
     * @param int $id O ID do modelo a ser removido.
     * @return bool Verdadeiro se a remoção foi bem-sucedida, falso caso contrário.
     */
    public function delete(int $id): bool
    {
        $item = $this->find($id);
        return $item ? $item->delete() : false;
    }

    /**
     * Remove todos os registros pela coluna e valor especificados.
     *
     * @param array $columns valores e colunas de dados.
     * @return int Número de registros deletados.
     */
    public function deleteByColumns(array $columns): int
    {
        $query = $this->model;
        if ($columns) {
            foreach ($columns as $column => $value) {
                $query = $query->where($column, $value);
            }
        }
        return $query->delete();
    }

    /**
     * Atualiza um modelo existente ou cria um novo com os atributos fornecidos.
     *
     * @param array $attributes Um array de atributos para buscar ou criar o modelo.
     * @param array $values Um array de valores para atualizar o modelo.
     * @return T O modelo atualizado ou criado.
     */
    public function updateOrCreate(array $attributes, array $values = []): Model
    {
        return $this->model->updateOrCreate($attributes, $values);
    }

    /**
     * Insert or update a record matching the attributes, and fill it with values.
     *
     * @param  array  $attributes
     * @param  array  $values
     * @return bool
     */
    public function updateOrInsert(array $attributes, array $values = []): Builder
    {
        return $this->model->updateOrInsert($attributes, $values);
    }

    /**
     * Pesquisa ou cria um item com base nas colunas fornecidas.
     *
     * @param array $attr Atributos a serem usados para encontrar o item ou criar.
     * @return \Illuminate\Database\Eloquent\Collection|Model O item atualizado ou criado.
     */
    public function firstOrNew(array $columns): Model
    {
        return $this->model->firstOrNew($columns);
    }

    /**
     * pagina com valores especificados.
     *
     * @param string $string search.
     * @param int $int perPage.
     * @param string $string cursor.
     * @return Collection Uma coleção contendo todos os modelos.
     */
    public function cursorPaginate(?string $search = null, int $perPage, ?string $cursor = null)
    {
        $query = $this->model; // Começa com o modelo

        if ($search) {
            $query = $query->where("word", 'LIKE', "%$search%"); // Aplica o filtro de busca
        }

        return $query->cursorPaginate($perPage, ['*'], 'cursor', $cursor);
    }

    /**
     * pagina com valores especificados.
     *
     * @param string $string search.
     * @return int quantidade de todos os modelos.
     */
    public function count(?string $search = null)
    {
        $query = $this->model; // Começa com o modelo

        if ($search) {
            $query = $query->where("word", 'LIKE', "%$search%"); // Aplica o filtro de busca
        }

        return $query->count(); // Conta os registros filtrados
    }
}
