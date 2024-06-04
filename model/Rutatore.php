<?php

class Giocatore
{
    private $Id;
    private $Num;
    private $Name;
    private $Password;
    private $Role;

    public function __construct(int $Num, string $Name, string $Password, string $Role = 'User', ?int $Id = null)
    {
        $this->Id = $Id;
        $this->Num = $Num;
        $this->Name = $Name;
        $this->Password = $Password;
        $this->Role = $Role;
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
    public function setName(string $Name)
    {
        $this->Name = $Name;
    }
    public function setPassword(string $Password)
    {
        $this->Password = $Password;
    }
    public function setRole(string $Role)
    {
        $this->Role = $Role;
    }

    public function getId(): int
    {
        return $this->Id;
    }
    public function getNum(): int
    {
        return $this->Num;
    }
    public function getName(): string
    {
        return $this->Name;
    }
    public function getPassword(): string
    {
        return $this->Password;
    }
    public function getRole(): string
    {
        return $this->Role;
    }

    public function toArray(): array
    {
        return [
            'Id' => $this->Id,
            'Num' => $this->Num,
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
    public static function authenticateUser(int $Num, string $Password): Giocatore | null
    {
        $queryText = 'SELECT * FROM `Giocatore` WHERE `Num_Giocatore` = ?';
        $query = new Query($queryText, 'i', $Num);
        $result = DataBase::executeQuery($query, false);

        $Giocatore = $result ? new Giocatore(
            $result['Num_Giocatore'],
            $result['Name_Giocatore'],
            $result['Password_Giocatore'],
            $result['Role_Giocatore'],
            $result['Id_Giocatore'],
        ) : null;
        $passwordOk = $Password == $Giocatore->getPassword();

        return $passwordOk ? $Giocatore : null;
    }

    /**
     * Get the Giocatore with specified Id
     * @param int $Id Giocatore's Id
     * @return Giocatore Giocatore or null
     */
    public static function getGiocatoreById(int $Id): Giocatore | null
    {
        $queryText = 'SELECT * FROM `Giocatore` WHERE `Id_Giocatore` = ?';
        $query = new Query($queryText, 'i', $Id);
        $result = DataBase::executeQuery($query, false);

        return $result ? new Giocatore(
            $result['Num_Giocatore'],
            $result['Name_Giocatore'],
            $result['Password_Giocatore'],
            $result['Role_Giocatore'],
            $result['Id_Giocatore'],
        ) : null;
    }
}