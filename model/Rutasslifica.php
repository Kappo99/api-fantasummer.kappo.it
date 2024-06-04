<?php

class Classifica
{
    private $Id;
    private $Giornata;
    private $IdGiocatore;
    private $MonteSummer;
    private $Giocatore;

    public function __construct(int $Giornata, int $IdGiocatore, int $MonteSummer, ?int $Id = null, ?Giocatore $Giocatore = null)
    {
        $this->Id = $Id;
        $this->Giornata = $Giornata;
        $this->IdGiocatore = $IdGiocatore;
        $this->MonteSummer = $MonteSummer;
        $this->Giocatore = $Giocatore;
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
    public function getMonteSummer(): int
    {
        return $this->MonteSummer;
    }
    public function getGiocatore(): Giocatore
    {
        return $this->Giocatore;
    }

    public function toArray(): array
    {
        return [
            'Id' => $this->Id,
            'Giornata' => $this->Giornata,
            'IdGiocatore' => $this->IdGiocatore,
            'MonteSummer' => $this->MonteSummer,
            'Giocatore' => $this->Giocatore->toArray(),
        ];
    }

    // ===========================================================================================

    /**
     * Get the Classifica List
     * @return Classifica[] Classifica List or null
     */
    public static function getClassifica(): mixed
    {
        $queryText = 'SELECT `Id_Classifica`, `Giornata_Classifica`, `Id_Giocatore_Classifica`, 
                        SUM(`MonteSummer_Classifica`) AS `MonteSummer_Classifica`,
                        `Id_Giocatore`, `Num_Giocatore`, `Name_Giocatore`, `Role_Giocatore`, `Password_Giocatore`
                        FROM `Classifica` 
                            INNER JOIN `Giocatore` ON `Id_Giocatore_Classifica` = `Id_Giocatore`
                        GROUP BY `Id_Giocatore`
                        ORDER BY `MonteSummer_Classifica` DESC';
        $query = new Query($queryText);
        $result = DataBase::executeQuery($query);

        $classifica = array();
        foreach ($result as $r) {
            $classifica[] = new Classifica(
                $r['Giornata_Classifica'],
                $r['Id_Giocatore_Classifica'],
                $r['MonteSummer_Classifica'],
                $r['Id_Classifica'],
                new Giocatore(
                    $r['Num_Giocatore'],
                    $r['Name_Giocatore'],
                    $r['Password_Giocatore'],
                    $r['Role_Giocatore'],
                    $r['Id_Giocatore'],
                )
            );
        }

        return $classifica;
    }

    /**
     * Get the Classifica List until specified Giornata
     * @return mixed 2x Classifica[] List or null (specified Giornata and prec)
     */
    public static function getClassificaByGiornata($giornata): mixed
    {
        $queryText = 'SELECT `Id_Classifica`, `Giornata_Classifica`, `Id_Giocatore_Classifica`, 
                        SUM(`MonteSummer_Classifica`) AS `MonteSummer_Classifica`,
                        `Id_Giocatore`, `Num_Giocatore`, `Name_Giocatore`, `Role_Giocatore`, `Password_Giocatore`
                        FROM `Classifica` 
                            INNER JOIN `Giocatore` ON `Id_Giocatore_Classifica` = `Id_Giocatore`
                        WHERE `Giornata_Classifica` <= ?
                        GROUP BY `Id_Giocatore`
                        ORDER BY `MonteSummer_Classifica` DESC, `Id_Giocatore`';
        $query = new Query($queryText, 'i', $giornata);
        $result = DataBase::executeQuery($query);

        $classifica = array();
        foreach ($result as $r) {
            $classifica[] = new Classifica(
                $r['Giornata_Classifica'],
                $r['Id_Giocatore_Classifica'],
                $r['MonteSummer_Classifica'],
                $r['Id_Classifica'],
                new Giocatore(
                    $r['Num_Giocatore'],
                    $r['Name_Giocatore'],
                    $r['Password_Giocatore'],
                    $r['Role_Giocatore'],
                    $r['Id_Giocatore'],
                )
            );
        }

        $queryText = 'SELECT `Id_Classifica`, `Giornata_Classifica`, `Id_Giocatore_Classifica`, 
                        SUM(`MonteSummer_Classifica`) AS `MonteSummer_Classifica`,
                        `Id_Giocatore`, `Num_Giocatore`, `Name_Giocatore`, `Role_Giocatore`, `Password_Giocatore`
                        FROM `Classifica` 
                            INNER JOIN `Giocatore` ON `Id_Giocatore_Classifica` = `Id_Giocatore`
                        WHERE `Giornata_Classifica` <= ?
                        GROUP BY `Id_Giocatore`
                        ORDER BY `MonteSummer_Classifica` DESC, `Id_Giocatore`';
        $query = new Query($queryText, 'i', $giornata - 1);
        $result = DataBase::executeQuery($query);

        $classificaPrev = array();
        foreach ($result as $r) {
            $classificaPrev[] = new Classifica(
                $r['Giornata_Classifica'],
                $r['Id_Giocatore_Classifica'],
                $r['MonteSummer_Classifica'],
                $r['Id_Classifica'],
                new Giocatore(
                    $r['Num_Giocatore'],
                    $r['Name_Giocatore'],
                    $r['Password_Giocatore'],
                    $r['Role_Giocatore'],
                    $r['Id_Giocatore'],
                )
            );
        }

        return [$classifica, $classificaPrev];
    }
}