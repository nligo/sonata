<?php

namespace CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(options={"comment": "文章分类表"})
 */
class Category extends Base
{
    /**
     * @ORM\Column(type="string", length=100, options={"comment": "文章名称"})
     */
    private $name = '';

    /**
     * @ORM\ManyToMany(targetEntity="Article", mappedBy="categories")
     * @ORM\OrderBy({"createdAt": "ASC"})
     */
    private $articles;

    /**
     * The category parent.
     *
     * @var Category
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     **/
    protected $parent;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     */
    protected $owner;

    /**
     * @return mixed
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param mixed $user
     */
    public function setOwner(\Application\Sonata\UserBundle\Entity\User $owner)
    {
        $this->owner = $owner;
    }


    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
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

    public function getArticles()
    {
        return $this->articles;
    }

    public function hasArticle()
    {
        return !$this->articles->isEmpty();
    }

    public function addArticle(Article $article)
    {
        $this->articles[] = $article;

        return $this;
    }

    public function removeArticle(Article $article)
    {
        $this->articles->removeElement($article);
    }

    /**
     * Set the parent category.
     *
     * @param Category $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get the parent category.
     *
     * @return Category
     */
    public function getParent()
    {
        return $this->parent;
    }
}
