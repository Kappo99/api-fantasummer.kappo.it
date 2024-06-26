<?php

class Giocatore
{
    private $Id;
    private $Name;
    private $Password;
    private $Role;
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

    public function setName(string $Name)
    {
        $this->Name = $Name;
    }
    public function getName(): string
    {
        return $this->Name;
    }

    public function setPassword(string $Password)
    {
        $this->Password = $Password;
    }
    public function getPassword(): string
    {
        return $this->Password;
    }

    public function setRole(string $Role)
    {
        $this->Role = $Role;
    }
    public function getRole(): string
    {
        return $this->Role;
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
            'Name' => $this->Name,
            'Password' => $this->Password,
            'Role' => $this->Role,
            'MonteSummer' => $this->MonteSummer,
        ];
    }

    // ===========================================================================================

    /**
     * Get the Giocatore with specified Name and Password
     * @param string $Name Giocatore's Name
     * @param string $Password Giocatore's Password
     * @return Giocatore Giocatore or null
     */
    public static function authenticateUser(string $Name, string $Password): Giocatore|null
    {
        $queryText = 'SELECT * FROM `Giocatore` WHERE `Name_Giocatore` = ?';
        $query = new Query($queryText, 's', $Name);
        $result = DataBase::executeQuery($query, false);

        if ($result) {
            $Giocatore = new Giocatore($result['Id_Giocatore']);
            $Giocatore->setName($result['Name_Giocatore']);
            $Giocatore->setPassword($result['Password_Giocatore']);
            $Giocatore->setRole($result['Role_Giocatore']);
        } else
            $Giocatore = null;

        $passwordOk = $Password == $Giocatore->getPassword();

        return $passwordOk ? $Giocatore : null;
    }

    /**
     * Get the Giocatore with specified Id
     * @param int $Id Giocatore's Id
     * @return Giocatore Giocatore or null
     */
    public static function getGiocatoreById(int $Id): Giocatore|null
    {
        $queryText = 'SELECT * FROM `Giocatore` WHERE `Id_Giocatore` = ?';
        $query = new Query($queryText, 'i', $Id);
        $result = DataBase::executeQuery($query, false);

        if ($result) {
            $Giocatore = new Giocatore($result['Id_Giocatore']);
            $Giocatore->setName($result['Name_Giocatore']);
            $Giocatore->setPassword($result['Password_Giocatore']);
            $Giocatore->setRole($result['Role_Giocatore']);
        } else
            $Giocatore = null;

        return $Giocatore;
    }

    /**
     * Get the Giocatore List for the Classifica
     * @return Giocatore[] Giocatore's List ordered by score
     */
    public static function getClassifica(): array
    {
        $queryText = "SELECT 
                        G.Id_Giocatore,
                        G.Name_Giocatore,
                        COALESCE(SUM(E.MonteSummer_Evento), 0) AS Total_Punteggio
                    FROM 
                        Giocatore G
                    LEFT JOIN 
                        Formazione F ON G.Id_Giocatore = F.Id_Giocatore_Formazione
                    LEFT JOIN 
                        Evento E ON F.Id_Evento_Formazione = E.Id_Evento
                    GROUP BY 
                        G.Id_Giocatore, G.Name_Giocatore
                    ORDER BY 
                        Total_Punteggio DESC
        ";
        $query = new Query($queryText);
        $result = DataBase::executeQuery($query, false);

        $classifica = array();
        $i = 0;
        foreach ($result as $r) {
            $classifica[$i] = new Giocatore($r['Id_Giocatore']);
            $classifica[$i]->setName($r['Name_Giocatore']);
            $classifica[$i]->setMonteSummer($r['Total_Punteggio']);
            $i++;
        }

        return $classifica;
    }
}