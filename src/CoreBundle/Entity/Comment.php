<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(options={"comment": "文章评论表"})
 */
class Comment extends Base
{
    /**
     * 多个评论对应一篇文章.
     *
     * @ORM\ManyToOne(targetEntity="Article",inversedBy="comments")
     * @ORM\JoinColumn(name="article_id", referencedColumnName="id")
     */
    private $article;

    /**
     * @ORM\Column(type="text", options={"comment": "评论内容"})
     */
    private $content = '';

    /**
     * @ORM\Column(type="boolean", options={"comment": "评论的模式:0为对象用户可见，1为公开"})
     */
    private $type = false;

    /**
     * The 是否为子回复.
     *
     * @var Comment
     * @ORM\ManyToOne(targetEntity="Comment")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     **/
    private $parent;

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


    public function __toString()
    {
        return $this->content;
    }

    public function setArticle(Article $article)
    {
        $this->article = $article;
    }

    public function getArticle()
    {
        return $this->article;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setParent(self $comment)
    {
        $this->parent = $comment;
    }

}
