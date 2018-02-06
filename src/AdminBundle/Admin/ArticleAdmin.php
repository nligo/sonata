<?php

namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ArticleAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('name', 'text');
        $formMapper->add('content', 'text');
        $formMapper->add('author');
        $formMapper->add('type');
        $formMapper->add('isUp');
        $formMapper->add('isSupport');
        $formMapper->add('cover', 'entity',[
            'class' => 'Application\Sonata\MediaBundle\Entity\Media'
        ]);

        $formMapper->add('releaseAt','datetime');
        $formMapper->add('categories', 'sonata_type_model', array(
            'required' => false,
            'multiple' => true,
            'btn_add' => false,
        ));
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('name');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('name');
    }
}
