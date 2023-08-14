<?php

namespace App\Core\Database;
class QueryBuilder
{
    protected $fields;
    protected $table;
    protected $type;
    protected $params;
    protected $values;
    protected $where;
    protected $joins = [];

    public function __construct()
    {
        $this->params = [];
    }

    public function select(mixed $fields = "*"): self
    {
        $this->type = "select";
        $fields_string = $fields;
        if (is_array($fields))
            $fields_string = implode(", ", $fields);
        $this->fields = $fields_string;
        return $this;
    }

    public function from($table): self
    {
        $this->table = $table;
        return $this;
    }

    public function innerJoin($table, $condition)
    {
        $this->joins[] = "INNER JOIN {$table} ON {$condition}";
        return $this;
    }

    public function leftJoin($table, $condition)
    {
        $this->joins[] = "LEFT JOIN {$table} ON {$condition}";
        return $this;
    }

    public function rightJoin($table, $condition)
    {
        $this->joins[] = "RIGHT JOIN {$table} ON {$condition}";
        return $this;
    }

    public function crossJoin($table)
    {
        $this->joins[] = "CROSS JOIN {$table}";
        return $this;
    }

    public function insert($data, $table): self
    {
        $this->type = "insert";
        $this->table = $table;
        $columns = implode(', ', array_keys($data));
        $values = implode(', ', array_map(function ($value) {
            return ':' . $value;
        }, array_keys($data)));
        $this->fields = "($columns)";
        $this->values = "($values)";
        $this->params = $data;
        return $this;
    }

    public function update($update_data): self
    {
        $this->type = "update";
        $update_parts = [];
        foreach ($update_data as $key => $value) {
            $update_parts[] = "{$key} = :{$key}";
            $this->params[$key] = $value;
        }
        $this->fields = implode(", ", $update_parts);
        return $this;
    }

    public function delete(): self
    {
        $this->type = "delete";
        return $this;
    }

    public function getSql()
    {
        switch ($this->type) {
            case 'select':
                $sql = "SELECT {$this->fields} FROM {$this->table}";
                if (!empty($this->joins)) {
                    $sql .= " " . implode(" ", $this->joins);
                }
                if (!empty($this->where))
                    $sql .= " WHERE {$this->where}";
                return $sql;
                break;
            case "insert":
                $sql = "INSERT INTO {$this->table} {$this->fields} VALUES {$this->values}";
                return $sql;
                break;
            case 'update':
                $sql = "UPDATE {$this->table} SET {$this->fields}";
                if (!empty($this->where))
                    $sql .= " WHERE {$this->where}";
                return $sql;
                break;
            case 'delete':
                $sql = "DELETE FROM {$this->table}";
                if (!empty($this->where))
                    $sql .= " WHERE {$this->where}";
                return $sql;
                break;
        }
    }

    public function where($where): self
    {
        /*if (is_a($where)) {

        }*/
        $where_parts = [];
        foreach ($where as $key => $value) {
            $param_key = str_replace('.', '_', $key);
            $where_parts [] = "{$key} = :{$param_key}";
            $this->params[$param_key] = $value;
        }
        $this->where = implode(' AND ', $where_parts);
        return $this;
    }

    public function getParams(): array
    {
        return $this->params;
    }
}




























