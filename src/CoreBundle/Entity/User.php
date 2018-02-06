<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 *
 * @UniqueEntity("username")
 */
class User extends Base implements UserInterface
{


    const ROLE_USER = 'ROLE_USER'; // 管理员

    public static $roles = [
        self::ROLE_USER => '用户',
    ];


    /**
     * @ORM\Column(type="string", length=32, unique=true, options={"fixed": true, "comment": "用户名"})
     */
    private $username = "";

    /**
     * @ORM\Column(type="string", length=32, unique=true, options={"fixed": true, "comment": "邮箱"})
     */
    private $email = "";

    /**
     * @ORM\Column(type="string", length=20, unique=true, options={"fixed": true, "comment": "手机号"})
     */
    private $phone = "";

    /**
     * @ORM\Column(type="boolean", options={"comment": "是否激活"})
     */
    private $isActive = false;

    /**
     * @ORM\Column(type="string", length=64, options={"comment": "激活Token"})
     */
    private $confirmToken = "";


    /**
     * @ORM\Column(type="datetime", nullable=true,options={"comment": "token有效时长"})
     */
    private $tokenVaildAt;

    /**
     * @ORM\Column(type="string", length=60, options={"comment": "登录密码"})
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=32, options={"fixed": true})
     *
     * @Assert\NotBlank(groups={"role"})
     */
    private $role;

    public function __construct($username = null, $password = null, $role = self::ROLE_USER)
    {
        $this->username = $username;
        $this->password = $password;

        $this->setRole($role);
    }

    public function __toString()
    {
        if(is_null($this->getUsername())) {
            return 'NULL';
        }
        return $this->getUsername();
    }
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function getRoleName()
    {
        return isset(self::$roles[$this->role])
            ? self::$roles[$this->role] : '-';
    }

    public function getRoles()
    {
        return (array) $this->role;
    }



    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
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
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getisActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @return mixed
     */
    public function getConfirmToken()
    {
        return $this->confirmToken;
    }

    /**
     * @param mixed $confirmToken
     */
    public function setConfirmToken($confirmToken)
    {
        $this->confirmToken = $confirmToken;
    }

    /**
     * @return mixed
     */
    public function getTokenVaildAt()
    {
        return $this->tokenVaildAt;
    }

    /**
     * @param mixed $tokenVaildAt
     */
    public function setTokenVaildAt($tokenVaildAt)
    {
        $this->tokenVaildAt = $tokenVaildAt;
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

    public function getSalt()
    {
        return;
    }

    public function eraseCredentials()
    {
        return;
    }
}
