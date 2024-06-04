<?php

class Query
{
    private $query;
    private $action;
    private $callAction;
    private $parameters;
    private $parametersType;

    public function __construct(string $query, ?string $types = null, ...$parameters)
    {
        if ($query == null || strlen($query) == 0)
            throw new Exception("The query cannot be null");

        $this->query = $query;
        $this->callAction = null;

        if (strpos($query, "CALL") !== false) {
            $this->callAction = explode("_", explode(" ", $query)[1])[1]; // CALL {NomeTabella1}Has{NomeTabella2}_{OperazioneDaFare} (...)
        }

        if (strpos($query, "SELECT") !== false || $this->callAction === "List" || $this->callAction === "Read")
            $this->action = QueryActions::SELECT;
        else if (strpos($query, "INSERT") !== false || $this->callAction === "Create")
            $this->action = QueryActions::INSERT;
        else if (strpos($query, "DELETE") !== false || $this->callAction === "Delete")
            $this->action = QueryActions::DELETE;
        else if (strpos($query, "UPDATE") !== false || $this->callAction === "Update")
            $this->action = QueryActions::UPDATE;
        else
            throw new Exception("The action specified in the query is not supported");

        $this->parameters = $parameters;
        $this->parametersType = $types;
        $questionMarksCount = substr_count($query, "?");
        $typesLength = $types != null ? strlen($types) : 0;
        $parametersCount = count($parameters);

        if ($questionMarksCount != $parametersCount || $questionMarksCount != $typesLength)
            throw new Exception(
                "Errore nei parametri della query.
                    Parametri richiesti: $questionMarksCount.
                    Tipi passati: $typesLength.
                    Parametri passati: $parametersCount"
            );
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function hasParameters(): bool
    {
        return count($this->parameters) != 0;
    }

    public function getParametersType(): string
    {
        return $this->parametersType;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getCallAction(): string
    {
        return $this->callAction;
    }
}

abstract class QueryActions
{
    const SELECT = 0;
    const INSERT = 1;
    const DELETE = 2;
    const UPDATE = 3;
}
