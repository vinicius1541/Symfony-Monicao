<?php

namespace App\Entity\Access;

use App\Repository\Access\EmployeeRepository;
use Doctrine\ORM\Mapping as ORM;
use Datetime;

/**
 * @ORM\Table(name="access.employee")
 * @ORM\Entity(repositoryClass=EmployeeRepository::class)
 */
class Employee
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="employee_id", type="integer")
     */
    private $employee_id;
    /**
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;
    /**
     * @ORM\Column(name="cpf", type="string", length=14)
     */
    private $cpf;
    /**
     * @ORM\Column(name="phone", type="string", length=100, unique=true)
     */
    private $phone;
    /**
     * @ORM\Column(name="email", type="string", length=50)
     */
    private $email;
    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="employee")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     */
    private User $user;
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

    public function getEmployeeId()
    {
        return $this->employee_id;
    }

    public function setEmployeeId($employee_id): void
    {
        $this->employee_id = $employee_id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getCpf()
    {
        return $this->cpf;
    }

    public function setCpf($cpf): void
    {
        $this->cpf = $cpf;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone($phone): void
    {
        $this->phone = $phone;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($userObject): void
    {
        $this->user = $userObject;
    }

    public function getActive()
    {
        return $this->active;
    }

    public function setActive($active): void
    {
        $this->active = $active;
    }

    public function getDateAdd()
    {
        return $this->date_add;
    }

    public function setDateAdd()
    {
        $this->date_add = new DateTime('America/Sao_Paulo');

        return $this;
    }

    public function getDateUpd()
    {
        return $this->date_upd;
    }

    public function setDateUpd(): void
    {
        $this->date_upd = new DateTime('America/Sao_Paulo');
    }


}