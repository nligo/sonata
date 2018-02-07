<?php

namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class UserAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('username', 'text');
        $formMapper->add('phone', 'text');
        $formMapper->add('email', 'email');
        $formMapper->add('password', 'password');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('username');
        $datagridMapper->add('email');
        $datagridMapper->add('phone');
        $datagridMapper->add('isActive');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('username');
        $listMapper->add('phone');
        $listMapper->add('email');
        $listMapper->add('isActive');
        $listMapper->add('_action', null, [
            'actions' => [
                'show' => [],
                'edit' => [],
                'delete' => [],
            ],
        ]);
    }

    public function prePersist($object)
    {
        $passwordEncoder = $this->getConfigurationPool()->getContainer()->get('security.encoder_factory')->getEncoder($object);
        $password = $passwordEncoder->encodePassword($object->getPassword(), $object->getSalt());
        $object->setPassword($password);
        parent::prePersist($object);
    }

    public function preUpdate($object)
    {
        $passwordEncoder = $this->getConfigurationPool()->getContainer()->get('security.encoder_factory')->getEncoder($object);
        if (!empty($object->getPassword())) {
            $password = $passwordEncoder->encodePassword($object->getPassword(), $object->getSalt());
            $object->setPassword($password);
        }

        parent::preUpdate($object);
    }
}
