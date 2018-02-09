<?php

namespace AdminBundle\Admin;

use CoreBundle\Entity\Category;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class ArticleAdmin extends AbstractAdmin
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
                $query->expr()->eq($query->getRootAlias().'.author', ':author')
            );
            $query->setParameter('author', $owner);
        }

        return $query;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('name', 'text');
        $formMapper->add('content', 'ckeditor', [
            'config_name' => 'article',
        ]);
        $formMapper->add('type');
        $formMapper->add('isUp');
        $formMapper->add('isSupport');
        $formMapper->add('cover', 'entity', [
            'class' => 'Application\Sonata\MediaBundle\Entity\Media',
        ]);

        $formMapper->add('releaseAt', 'datetime');
        $entity = new Category();
        $query = $this->modelManager->getEntityManager($entity)->createQuery('SELECT s FROM CoreBundle\Entity\Category s WHERE s.owner =:owner ORDER BY s.createdAt ASC');
        $owner = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();
        $query->setParameter('owner', $owner->getId());
        $formMapper->add('categories', 'sonata_type_model', array(
            'required' => false,
            'multiple' => true,
            'query' => $query,
            'btn_add' => false,
        ));
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('name');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $checker = $this->getConfigurationPool()->getContainer()->get('security.authorization_checker');

        $listMapper->addIdentifier('name');
        $listMapper->add('cover', 'image');
        $listMapper->add('type');
        $listMapper->add('isUp');
        $listMapper->add('isSupport');
        $listMapper->add('commentTotal');

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
            ->add('name')
            ->add('author')
            ->add('cover', 'image')
            ->add('content', 'html')
            ->add('releaseAt')
            ->add('type')
            ->add('isUp')
            ->add('isSupport')
            ->end()
            ->end()
        ;
    }

    public function prePersist($object)
    {
        $owner = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();
        $object->setAuthor($owner);
        parent::prePersist($object);
    }

    public function preUpdate($object)
    {
        $owner = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();
        $object->setAuthor($owner);
        parent::preUpdate($object);
    }
}
