<?php

namespace App\Entity\Acesso;

use App\Repository\Acesso\UsuarioRepository;
use Doctrine\ORM\Mapping as ORM;
use Datetime;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="acesso.usuario")
 * @ORM\Entity(repositoryClass=UsuarioRepository::class)
 */
class Usuario implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id_usuario", type="integer")
     */
    private $id_usuario;
    /**
     * @ORM\Column(name="nome", type="string", length=255)
     */
    private $nome;
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
     * @ORM\Column(name="data_add", type="datetimetz")
     */
    private $data_add;
    /**
     * @ORM\Column(name="data_upd", type="datetimetz")
     */
    private $data_upd;
    /**
     * @ORM\Column(name="profile", type="integer")
     */
    private $profile;
    /**
     * @ORM\Column(name="force_update", type="boolean")
     */
    private $force_update;

    /**
     * @return integer
     */
    public function getIdUsuario()
    {
        return $this->id_usuario;
    }

    /**
     * @param mixed $id_usuario
     */
    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    /**
     * @return mixed
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param mixed $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
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
    public function getDataAdd()
    {
        return $this->data_add;
    }

    /**
     * @param mixed $data_add
     */
    public function setDataAdd()
    {
        $this->data_add = new DateTime('America/Sao_Paulo');
    }

    /**
     * @return mixed
     */
    public function getDataUpd()
    {
        return $this->data_upd;
    }

    /**
     * @param mixed $data_upd
     */
    public function setDataUpd()
    {
        $this->data_upd = new DateTime('America/Sao_Paulo');
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
    public function getRoles()
    {
        return ['ROLE_ADM'];
    }
    public function getUserIdentifier()
    {
        return $this->getIdUsuario();
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
