<?php

class Giocatore
{
    private $Id;
    private $Name;
    private $Password;
    private $Role;

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

    public function toArray(): array
    {
        return [
            'Id' => $this->Id,
            'Name' => $this->Name,
            'Password' => $this->Password,
            'Role' => $this->Role,
        ];
    }

    // ===========================================================================================

    /**
     * Get the Giocatore with specified Num and Password
     * @param int $Num Giocatore's Num
     * @param string $Password Giocatore's Password
     * @return Giocatore Giocatore or null
     */
    public static function authenticateUser(int $Name, string $Password): Giocatore|null
    {
        $queryText = 'SELECT * FROM `Giocatore` WHERE `Name_Giocatore` = ?';
        $query = new Query($queryText, 'i', $Name);
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
}