<?php

class Evento
{
    private $Id;
    private $Num;
    private $Title;
    private $Description;
    private $MonteSummer;

    public function __construct(?int $Id = null)
    {
        $this->Id = $Id;
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

    public function setNum(int $Num)
    {
        $this->Num = $Num;
    }
    public function getNum(): int
    {
        return $this->Num;
    }

    public function setTitle(string $Title)
    {
        $this->Title = $Title;
    }
    public function getTitle(): string
    {
        return $this->Title;
    }

    public function setDescription(string $Description)
    {
        $this->Description = $Description;
    }
    public function getDescription(): string
    {
        return $this->Description;
    }

    public function setMonteSummer(int $MonteSummer)
    {
        $this->MonteSummer = $MonteSummer;
    }
    public function getMonteSummer(): int
    {
        return $this->MonteSummer;
    }

    public function toArray(): array
    {
        return [
            'Id' => $this->Id,
            'Num' => $this->Num,
            'Title' => $this->Title,
            'Description' => $this->Description,
            'MonteSummer' => $this->MonteSummer,
        ];
    }

    // ===========================================================================================

    /**
     * Get the Evento's List
     * @return Evento Evento[] or null
     */
    public static function getEventi(): array
    {
        $queryText = 'SELECT * FROM `Evento`';
        $query = new Query($queryText);
        $result = DataBase::executeQuery($query);

        $eventi = array();
        $i = 0;
        foreach ($result as $r) {
            $eventi[$i] = new Evento($r['Id_Evento']);
            $eventi[$i]->setNum($r['Num_Evento']);
            $eventi[$i]->setTitle($r['Title_Evento']);
            $eventi[$i]->setDescription($r['Description_Evento']);
            $eventi[$i]->setMonteSummer($r['MonteSummer_Evento']);
            $i++;
        }

        return $eventi;
    }
}