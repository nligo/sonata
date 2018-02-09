<?php

namespace AdminBundle\Admin;

use CoreBundle\Entity\Category;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class CategoryAdmin extends AbstractAdmin
{
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
        $entity = new Category();
        $query = $this->modelManager->getEntityManager($entity)->createQuery('SELECT s FROM CoreBundle\Entity\Category s WHERE s.owner =:owner ORDER BY s.createdAt ASC');
        $owner = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();
        $query->setParameter('owner', $owner->getId());
        $formMapper->add('name', 'text');
        $formMapper->add('parent', 'sonata_type_model', array('required' => true, 'query' => $query,'btn_add' => false));
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('name');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('name');
        $actions = [
            'show' => [],
            'delete' => [],
        ];
        $checker = $this->getConfigurationPool()->getContainer()->get('security.authorization_checker');
        if ($checker->isGranted('ROLE_AUTHOR')) {
            $actions['edit'] = [];
        }
        $listMapper->add('_action', null, [
            'actions' => $actions,
        ]);
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
