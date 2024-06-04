<?php

class Evento
{
    private $Id;
    private $Num;
    private $Giornata;
    private $Title;
    private $Description;
    private $Summers;
    private $MonteSummer;
    private $Malus;
    private $MalusText;
    private $Bonus;
    private $BonusText;
    private $IsSummerta;
    private $Bonus_x5;

    public function __construct(int $Num, int $Giornata, string $Title, string $Description, int $Summers, int $MonteSummer, ?int $Malus, ?string $MalusText, ?int $Bonus, ?string $BonusText, bool $IsSummerta, ?int $Id = null)
    {
        $this->Id = $Id;
        $this->Num = $Num;
        $this->Giornata = $Giornata;
        $this->Title = $Title;
        $this->Description = $Description;
        $this->Summers = $Summers;
        $this->MonteSummer = $MonteSummer;
        $this->Malus = $Malus;
        $this->MalusText = $MalusText;
        $this->Bonus = $Bonus;
        $this->BonusText = $BonusText;
        $this->IsSummerta = $IsSummerta;
        $this->Bonus_x5 = false;
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
    public function setNum(int $Num)
    {
        $this->Num = $Num;
    }
    public function setBonus_x5(bool $Bonus_x5)
    {
        $this->Bonus_x5 = $Bonus_x5;
    }

    public function getId(): int
    {
        return $this->Id;
    }
    public function getNum(): int
    {
        return $this->Num;
    }
    public function getGiornata(): int
    {
        return $this->Giornata;
    }
    public function getTitle(): string
    {
        return $this->Title;
    }
    public function getDescription(): string
    {
        return $this->Description;
    }
    public function getSummers(): int
    {
        return $this->Summers;
    }
    public function getMonteSummer(): int
    {
        return $this->MonteSummer;
    }
    public function getMalus(): int
    {
        return $this->Malus;
    }
    public function getMalusText(): string
    {
        return $this->MalusText;
    }
    public function getBonus(): int
    {
        return $this->Bonus;
    }
    public function getBonusText(): string
    {
        return $this->BonusText;
    }
    public function getIsSummerta(): bool
    {
        return $this->IsSummerta;
    }
    public function getBonus_x5(): bool
    {
        return $this->Bonus_x5;
    }

    public function toArray(): array
    {
        return [
            'Id' => $this->Id,
            'Num' => $this->Num,
            'Giornata' => $this->Giornata,
            'Title' => $this->Title,
            'Description' => $this->Description,
            'Summers' => $this->Summers,
            'MonteSummer' => $this->MonteSummer,
            'Malus' => $this->Malus,
            'MalusText' => $this->MalusText,
            'Bonus' => $this->Bonus,
            'BonusText' => $this->BonusText,
            'IsSummerta' => $this->IsSummerta,
            'Bonus_x5' => $this->Bonus_x5,
        ];
    }

    // ===========================================================================================

    /**
     * Get the Evento's List with specified Giornata
     * @param int $Giornata Evento's Giornata
     * @return mixed [0]: Evento[] or null, [1]: num Eventi, [2]: num Eventi Summerte
     */
    public static function getEventiByGiornata(int $Giornata): mixed
    {
        $queryText = 'SELECT * FROM `Evento` WHERE `Giornata_Evento` = ?';
        $query = new Query($queryText, 'i', $Giornata);
        $result = DataBase::executeQuery($query);

        $eventi = array();
        foreach ($result as $r) {
            $eventi[] = new Evento(
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
            );
        }

        $queryText = 'SELECT COUNT(*) AS Count FROM `Evento` WHERE `Giornata_Evento` = ?';
        $query = new Query($queryText, 'i', $Giornata);
        $result = DataBase::executeQuery($query, false);
        $count = $result['Count'];

        $queryText = 'SELECT COUNT(*) AS NumSummerte FROM `Evento` WHERE `Giornata_Evento` = ? AND `IsSummerta_Evento` = 1';
        $query = new Query($queryText, 'i', $Giornata);
        $result = DataBase::executeQuery($query, false);
        $numSummerte = $result['NumSummerte'];

        return [$eventi, $count, $numSummerte];
    }

    /**
     * Get the Evento's Count with specified Id
     * @param int $Id Evento's Id
     * @return int Count of Summertori played this Evento
     */
    public static function getEventiCountById(int $Id): mixed
    {
        $queryText = 'SELECT COUNT(*) AS `Count` FROM `Formazione` WHERE `Id_Evento_Formazione` = ?';
        $query = new Query($queryText, 'i', $Id);
        $result = DataBase::executeQuery($query, false);
        $count = $result['Count'];

        return $count;
    }

    /**
     * Update the Evento with specified Id
     * @param int $id Evento's Id
     * @return int number of updated rows
     */
    public static function updateIsSummertaById(int $Id): int
    {
        $queryText = "UPDATE `Evento` SET `IsSummerta_Evento` = NOT `IsSummerta_Evento` WHERE `Id_Evento` = ?";
        $query = new Query($queryText, 'i', $Id);
        $result = DataBase::executeQuery($query);

        return $result;
    }
}