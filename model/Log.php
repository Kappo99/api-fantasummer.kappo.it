<?php

class Log
{
    private $Id;
    private $IdGiocatore;
    private $Method;
    private $Uri;
    private $Params;
    private $State;
    private $Error;
    private $Timestamp;

    public function __construct(?int $IdGiocatore, string $Method, string $Uri, ?string $Params, string $State, ?string $Error = null, ?string $Timestamp = null, ?int $Id = null)
    {
        $this->Id = $Id;
        $this->IdGiocatore = $IdGiocatore;
        $this->Method = $Method;
        $this->Uri = $Uri;
        $this->Params = $Params;
        $this->State = $State;
        $this->Error = $Error;
        $this->Timestamp = $Timestamp !== null ? $Timestamp : date('Y-m-d H:i:s');
    }

    public function __toString(): string
    {
        return json_encode($this->toArray());
    }

    public function setId(int $Id)
    {
        if ($this->Id === null)
            $this->Id = $Id;
    }
    public function setIdGiocatore(int $IdGiocatore)
    {
        $this->IdGiocatore = $IdGiocatore;
    }
    public function setMethod(string $Method)
    {
        $this->Method = $Method;
    }
    public function setUri(string $Uri)
    {
        $this->Uri = $Uri;
    }
    public function setParams(string $Params)
    {
        $this->Params = $Params;
    }
    public function setState(string $State)
    {
        $this->State = $State;
    }
    public function setError(string $Error)
    {
        $this->Error = $Error;
    }
    public function setTimestamp(string $Timestamp)
    {
        $this->Timestamp = $Timestamp;
    }

    public function getId(): int
    {
        return $this->Id;
    }
    public function getIdGiocatore()
    {
        return $this->IdGiocatore;
    }
    public function getMethod()
    {
        return $this->Method;
    }
    public function getUri()
    {
        return $this->Uri;
    }
    public function getParams()
    {
        return $this->Params;
    }
    public function getState()
    {
        return $this->State;
    }
    public function getError()
    {
        return $this->Error;
    }
    public function getTimestamp()
    {
        return $this->Timestamp;
    }

    public function toArray(): array
    {
        return [
            'Id_Log' => $this->Id,
            'Id_Giocatore_Log' => $this->IdGiocatore,
            'Method_Log' => $this->Method,
            'Uri_Log' => $this->Uri,
            'Params_Log' => $this->Params,
            'State_Log' => $this->State,
            'Error_Log' => $this->Error,
            'Timestamp_Log' => $this->Timestamp,
        ];
    }

    // ===========================================================================================

    /**
     * Get the Log with specified Id
     * @param int $Id Log's Id
     * @return Log Log
     */
    public static function getLogById(int $Id): Log
    {
        $queryText = 'SELECT * FROM Log WHERE `Id_Log` = ?';
        $query = new Query($queryText, 'i', $Id);
        $result = DataBase::executeQuery($query, false);

        return new Log(
            $result['Id_Giocatore_Log'],
            $result['Method_Log'],
            $result['Uri_Log'],
            $result['Params_Log'],
            $result['State_Log'],
            $result['Error_Log'],
            $result['Timestamp_Log'],
            $result['Id_Log'],
        );
    }

    /**
     * Add a new Log
     * @param Log $log Log to add
     * @return int added Log's Id
     */
    public static function addLog(Log $log): int
    {
        $queryText = 'INSERT INTO Log(
                Id_Giocatore_Log, 
                Method_Log,
                Uri_Log,
                Params_Log,
                State_Log
            ) 
            VALUES (?, ?, ?, ?, ?)
        ';
        $query = new Query(
            $queryText,
            'issss',
            $log->getIdGiocatore(),
            $log->getMethod(),
            $log->getUri(),
            $log->getParams(),
            $log->getState(),
        );
        $result = DataBase::executeQuery($query);

        return $result;
    }

    /**
     * Update an existing Log
     * @param Log $log Log to update
     * @return bool true if updated successfully
     */
    public static function updateLog(Log $log): int
    {
        $queryText = 'UPDATE Log SET
                Id_Giocatore_Log = ?, 
                Method_Log = ?,
                Uri_Log = ?,
                Params_Log = ?,
                State_Log = ?,
                Error_Log = ?
            WHERE Id_Log = ?
        ';
        $query = new Query(
            $queryText,
            'isssssi',
            $log->getIdGiocatore(),
            $log->getMethod(),
            $log->getUri(),
            $log->getParams(),
            $log->getState(),
            $log->getError(),
            $log->getId()
        );
        $result = DataBase::executeQuery($query);

        return $result;
    }
}

abstract class Log_State
{
    const Iniziato = 'Iniziato';
    const Terminato = 'Terminato';
    const Errore = 'Errore';
}