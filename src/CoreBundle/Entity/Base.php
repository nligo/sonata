<?php
/**
 * Created by PhpStorm.
 * User: linpoo
 * Date: 2018/2/6
 * Time: 下午5:06
 */

namespace CoreBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
/**
 *@ORM\HasLifecycleCallbacks()
 *@ORM\MappedSuperclass()
 */
class Base
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer",options={"comment":"id"})
     */
    private $id;

    /**
     * @ORM\Column(type="boolean",name="status",nullable=false, options={"comment": "是否博主推荐:0为否，1为是"})
     */
    private $status = true;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;
    /**
     * @ORM\PrePersist
     */
    public function PrePersist(){
        if (!$this->createdAt) {
            $this->createdAt = new \DateTime();
            $this->updatedAt = new \DateTime();
        }
        if (!$this->getStatus()) {
            $this->setStatus(true);
        }

    }
    /**
     * @ORM\PreUpdate
     */
    public function PreUpdate(){
        $this->updatedAt = new \DateTime();
    }
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Base
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }
    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Base
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function getStatus()
    {
        return $this->status;
    }
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
}