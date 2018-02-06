<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\AdminUserRepository")
 *
 * @UniqueEntity("username")
 */
class AdminUser extends Base implements UserInterface
{
    const ROLE_ADMIN = 'ROLE_ADMIN'; // 管理员

    public static $roles = [
        self::ROLE_ADMIN => '管理员',
    ];

    /**
     * @ORM\Column(type="string", length=32, unique=true, options={"fixed": true, "comment": "用户名"})
     */
    private $username = '';

    /**
     * @ORM\Column(type="string", length=32, options={"fixed": true})
     *
     * @Assert\NotBlank(groups={"role"})
     */
    private $role;

    /**
     * @ORM\Column(type="string", length=60, options={"comment": "登录密码"})
     */
    private $password;

    /**
     * 用户明文密码
     *
     * @var string 明文密码
     *
     * @Assert\NotBlank(groups={"plainPassword"})
     * @Assert\Length(min=6, max=16, groups={"plainPassword"})
     * @Assert\Regex("/[0-9A-Za-z.-_]$/", message="user_password_rule", groups={"plainPassword"})
     */
    private $plainPassword;

    public function __construct($username = null, $plainPassword = null, $role = self::ROLE_ADMIN)
    {
        $this->username = $username;
        $this->plainPassword = $plainPassword;

        $this->setRole($role);
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

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function getSalt()
    {
        return;
    }

    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function eraseCredentials()
    {
        return;
    }
}
