<?php

namespace App\Entity\Access;

use App\Repository\Access\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Datetime;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="access.user")
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="user_id", type="integer")
     */
    private $user_id;
    /**
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    /**
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;
    /**
     * @ORM\Column(name="login", type="string", length=100, unique=true)
     */
    private $login;
    /**
     * @ORM\Column(name="password", type="string", length=100)
     */
    private $password;
    /**
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;
    /**
     * @ORM\Column(name="date_add", type="datetimetz")
     */
    private $date_add;
    /**
     * @ORM\Column(name="date_upd", type="datetimetz")
     */
    private $date_upd;
    /**
     * @ORM\Column(name="profile", type="json")
     */
    private $profile;
    /**
     * @ORM\Column(name="force_update", type="boolean")
     */
    private $force_update;
    /**
     * To add a new ROLE accepted, you need to put the key and then the value(the name of the role)
     * Example:
     *  "ROLE_TEST" => "Test"
     */
    private static $roles = [
        "ROLE_ADM" => "Admin",
        "ROLE_DENTISTA" => "Dentista",
        "ROLE_FUNCIONARIO" => "Funcionário"
    ];
    /**
     * @ORM\OneToOne(targetEntity="Employee", mappedBy="user")
     */
    private $employee;
    /**
     * @return integer
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return mixed
     */
    public function getDateAdd()
    {
        return $this->date_add;
    }

    public function setDateAdd(): User
    {
        $this->date_add = new DateTime('America/Sao_Paulo');
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateUpd()
    {
        return $this->date_upd;
    }

    /**
     * @param mixed $date_upd
     */
    public function setDateUpd()
    {
        $this->date_upd = new DateTime('America/Sao_Paulo');
    }

    /**
     * @return mixed
     */
    public function getForceUpdate()
    {
        return $this->force_update;
    }

    /**
     * @param mixed $force_update
     */
    public function setForceUpdate($force_update)
    {
        $this->force_update = $force_update;
    }

    /**
     * @return mixed
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @param mixed $profile
     */
    public function setProfile($profile)
    {
        $this->profile = $profile;
    }
    public function cleanProfile()
    {
        $this->profile = null;
        return $this;
    }
    public function getRoles()
    {
        return ['ROLE_ADM'];
    }
    public static function getAcceptedRoles(): array
    {
        return self::$roles;
    }
    public function getUserIdentifier()
    {
        return $this->getUserId();
    }
    public function getSalt()
    {
        return null;
    }
    public function getUsername()
    {
        return $this->getNome() . ' - ' . $this->getLogin();
    }
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

}
