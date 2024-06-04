<?php

class Formazione
{
    private $Id;
    private $Giornata;
    private $IdGiocatore;
    private $IdEvento;
    private $Bonus_x2;
    private $Bonus_x5;
    private $Giocatore;
    private $Evento;

    public function __construct(int $Giornata, int $IdGiocatore, int $IdEvento, bool $Bonus_x2, bool $Bonus_x5, ?int $Id = null, ?Giocatore $Giocatore = null, ?Evento $Evento = null)
    {
        $this->Id = $Id;
        $this->Giornata = $Giornata;
        $this->IdGiocatore = $IdGiocatore;
        $this->IdEvento = $IdEvento;
        $this->Bonus_x2 = $Bonus_x2;
        $this->Bonus_x5 = $Bonus_x5;
        $this->Giocatore = $Giocatore;
        $this->Evento = $Evento;
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

    public function getId(): int
    {
        return $this->Id;
    }
    public function getGiornata(): int
    {
        return $this->Giornata;
    }
    public function getIdGiocatore(): int
    {
        return $this->IdGiocatore;
    }
    public function getIdEvento(): int
    {
        return $this->IdEvento;
    }
    public function getBonus_x2(): bool
    {
        return $this->Bonus_x2;
    }
    public function getBonus_x5(): bool
    {
        return $this->Bonus_x5;
    }
    public function getGiocatore(): Giocatore
    {
        return $this->Giocatore;
    }
    public function getEvento(): Evento
    {
        return $this->Evento;
    }

    public function toArray(): array
    {
        return [
            'Id' => $this->Id,
            'Giornata' => $this->Giornata,
            'IdGiocatore' => $this->IdGiocatore,
            'IdEvento' => $this->IdEvento,
            'Bonus_x2' => $this->Bonus_x2,
            'Bonus_x5' => $this->Bonus_x5,
            'Giocatore' => $this->Giocatore->toArray(),
            'Evento' => $this->Evento->toArray(),
        ];
    }

    // ===========================================================================================

    /**
     * Get the Formazione with specified Giornata
     * @param int $Giornata Formazione's Giornata
     * @return mixed Formazione[] or null
     */
    public static function getFormazioniByGiornata(int $Giornata): mixed
    {
        $queryText =
            'SELECT * 
            FROM `Formazione` 
                INNER JOIN `Giocatore` ON `Id_Giocatore_Formazione` = `Id_Giocatore`
                INNER JOIN `Evento` ON `Id_Evento_Formazione` = `Id_Evento`
            WHERE `Giornata_Formazione` = ?
            ORDER BY `Id_Giocatore`, `Id_Evento`';
        $query = new Query($queryText, 'i', $Giornata);
        $result = DataBase::executeQuery($query);

        $formazioni = array();
        foreach ($result as $r) {
            // $evento = new Evento(
            //     $r['Num_Evento'],
            //     $r['Giornata_Evento'],
            //     $r['Title_Evento'],
            //     $r['Description_Evento'],
            //     $r['Summers_Evento'],
            //     $r['MonteSummer_Evento'],
            //     $r['Malus_Evento'],
            //     $r['MalusText_Evento'],
            //     $r['Bonus_Evento'],
            //     $r['BonusText_Evento'],
            //     $r['IsSummerta_Evento'],
            //     $r['Id_Evento'],
            // );
            // $evento->setBonus_x5($r['Bonus_x5_Formazione']);
            $formazioni[] = new Formazione(
                $r['Giornata_Formazione'],
                $r['Id_Giocatore_Formazione'],
                $r['Id_Evento_Formazione'],
                $r['Bonus_x2_Formazione'],
                $r['Bonus_x5_Formazione'],
                $r['Id_Formazione'],
                new Giocatore(
                    $r['Num_Giocatore'],
                    $r['Name_Giocatore'],
                    $r['Password_Giocatore'],
                    $r['Role_Giocatore'],
                    $r['Id_Giocatore'],
                ),
                new Evento(
                    $r['Num_Evento'],
                    $r['Giornata_Evento'],
                    $r['Title_Evento'],
                    $r['Description_Evento'],
                    $r['Summers_Evento'],
                    $r['MonteSummer_Evento'],
                    $r['Malus_Evento'],
                    $r['MalusText_Evento'],
                    $r['Bonus_Evento'],
                    $r['BonusText_Evento'],
                    $r['IsSummerta_Evento'],
                    $r['Id_Evento'],
                ),
            );
        }

        return $formazioni;
    }

    /**
     * Insert the Formazione List
     * @param array $formazioni Formazione's List
     * @return int id of the last Formazione added
     */
    public static function insertFormazioniByList(array $formazioni): int
    {
        $giornata = $formazioni[0]->getGiornata();
        $idGiocatore = $formazioni[0]->getIdGiocatore();

        $queryText = 'DELETE FROM `Formazione` WHERE `Giornata_Formazione` = ? AND `Id_Giocatore_Formazione` = ?';
        $query = new Query($queryText, 'ii', $giornata, $idGiocatore);
        $result = DataBase::executeQuery($query);

        $values = array();
        foreach ($formazioni as $formazione)
            $values[] = '(' . $formazione->getGiornata() . ',' . $formazione->getIdGiocatore() . ',' . $formazione->getIdEvento() . ',' . ($formazione->getBonus_x5() ? 1 : 0) . ')';
        $queryText = 'INSERT INTO `Formazione`(`Giornata_Formazione`,`Id_Giocatore_Formazione`,`Id_Evento_Formazione`,`Bonus_x5_Formazione`)
                        VALUES ' . implode(',', $values);
        $query = new Query($queryText);
        $result = DataBase::executeQuery($query);

        return $result;
    }
}