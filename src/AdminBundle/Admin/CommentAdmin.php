<?php

namespace AdminBundle\Admin;

use CoreBundle\Entity\Article;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class CommentAdmin extends AbstractAdmin
{

    public function getDashboardActions()
    {
        $checker = $this->getConfigurationPool()->getContainer()->get('security.authorization_checker');
        $actions = parent::getDashboardActions();

        if (!$checker->isGranted('ROLE_AUTHOR')) {
            unset($actions['create']);
        }
        return $actions;
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        $checker = $this->getConfigurationPool()->getContainer()->get('security.authorization_checker');

        if ($checker->isGranted('ROLE_AUTHOR')) {
            $owner = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();
            $query->andWhere(
                $query->expr()->eq($query->getRootAlias() . '.owner', ':owner')
            );
            $query->setParameter('owner', $owner);
        }

        return $query;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('content','ckeditor',[
            'config_name' => 'article',
        ]);
        $entity = new Article();
        $query = $this->modelManager->getEntityManager($entity)->createQuery('SELECT s FROM CoreBundle\Entity\Article s WHERE s.author =:author ORDER BY s.createdAt ASC');
        $owner = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();
        $query->setParameter('author', $owner->getId());
        $formMapper->add('article', 'sonata_type_model', array(
            'required' => false,
            'multiple' => false,
            'query' => $query,
            'btn_add' => false,
        ));
        $formMapper->add('type');
       ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $checker = $this->getConfigurationPool()->getContainer()->get('security.authorization_checker');

        $listMapper->addIdentifier('owner');
        $listMapper->add('type');
        $listMapper->add('article');

        $actions = [
            'show' => [],
            'delete' => [],
        ];
        if ($checker->isGranted('ROLE_AUTHOR')) {
            $actions['edit'] = [];
        }
        $listMapper->add('_action', null, [
            'actions' => $actions,
        ]);
    }

    public function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('owner')
            ->add('type')
            ->add('article')
            ->add('content', 'html')
            ->end()
            ->end()
        ;
    }

    public function prePersist($object)
    {
        $owner = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();
        $object->setOwner($owner);
        parent::prePersist($object);
    }

    public function preUpdate($object)
    {
        $owner = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();
        $object->setOwner($owner);
        parent::preUpdate($object);
    }
}
