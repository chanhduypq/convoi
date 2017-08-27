<?php
// Side bar menu
$this->widget('widgets.NBADMenu', array(
    'activateParents' => true,
    'htmlOptions' => array('class' => 'sidebar-menu', 'id' => 'nav-accordion'),
    'items' => array(
        array(
            'label' => Yii::t('global', 'Dashboard'),
            'url' => array('index/index'),
            'icon' => 'icon-dashboard'
        ),
        array(
            'label' => Yii::t('global', 'System'),
            'icon' => ' icon-sun',
            'linkOptions' => array('class' => 'dcjq-parent'),
            'itemOptions' => array('class' => 'sub-menu dcjq-parent-li'),
            'submenuHtmlOptions' => array(
                'class' => 'sub',
            ),
            'items' => array(
                array(
                    'label' => Yii::t('global', 'Manage Setting'),
                    'url' => array('settings/index'),
                    'icon' => 'icon-wrench'
                ),
                array(
                    'label' => Yii::t('global', 'Manage Language'),
                    'url' => array('languages/index'),
                    'icon' => 'icon-globe'
                ),
                array(
                    'label' => 'Method Payment',
                    'url' => array('paymentMethods/index'),
                    'icon' => 'icon-shopping-cart'
                ),
                array(
                    'label' => Yii::t('global', 'E-mail Template'),
                    'url' => array('emailTemplates/index'),
                    'icon' => 'icon-comment'
                ),

                array(
                    'label' => Yii::t('global', 'Manage Country'),
                    'url' => array('country/index'),
                    'icon' => ' icon-book'
                ),
            ),
        ),
        array(
            'label' => Yii::t('global', 'Manager CMS'),
            'icon' => 'icon-laptop',
            'linkOptions' => array('class' => 'dcjq-parent'),
            'itemOptions' => array('class' => 'sub-menu dcjq-parent-li'),
            'submenuHtmlOptions' => array(
                'class' => 'sub',
            ),
            'items' => array(
                array(
                    'label' => Yii::t('global', 'Pages'),
                    'url' => array('custompages/index'),
                    'icon' => 'icon-file-text'
                ),
                array(
                    'label' => Yii::t('global', 'Support Pages'),
                    'url' => array('customsupportpages/index'),
                    'icon' => ' icon-magic'
                ),
            ),
        ),
        array(
            'label' => Yii::t('global', 'Newsletter'),
            'icon' => 'icon-cogs',
            'linkOptions' => array('class' => 'dcjq-parent'),
            'itemOptions' => array('class' => 'sub-menu dcjq-parent-li'),
            'submenuHtmlOptions' => array(
                'class' => 'sub',
            ),
            'items' => array(
                array(
                    'label' => Yii::t('global', 'Manage Newsletters'),
                    'url' => array('newsletter/index'),
                    'icon' => 'icon-envelope'
                ),
            ),
        ),

        array(
            'label' => Yii::t('global', 'Manager Projects'),
            'icon' => ' icon-edit',
            'linkOptions' => array('class' => 'dcjq-parent'),
            'itemOptions' => array('class' => 'sub-menu dcjq-parent-li'),
            'submenuHtmlOptions' => array(
                'class' => 'sub',
            ),
            'items' => array(
                array(
                    'label' => Yii::t('global', 'Manager Projects'),
                    'url' => array('job/index'),
                    'icon' => ' icon-pencil'
                ),
                array(
                    'label' => Yii::t('global', 'Manager Category'),
                    'url' => array('category/index'),
                    'icon' => ' icon-ticket'
                ),
            ),
        ),
        array(
            'label' => Yii::t('global', 'Manager Domain'),
            'url' => array('domain/index'),
            'icon' => 'icon-vk'
        ),

        array(
            'label' => Yii::t('global', 'Manager Contactus'),
            'icon' => 'icon-envelope-alt',
            'linkOptions' => array('class' => 'dcjq-parent'),
            'itemOptions' => array('class' => 'sub-menu dcjq-parent-li'),
            'submenuHtmlOptions' => array(
                'class' => 'sub',
            ),
            'items' => array(
                array(
                    'label' => Yii::t('global', 'User Contactus'),
                    'url' => array('contactus/index'),
                    'icon' => 'icon-share'
                ),
            ),
        ),
        array(
            'label' => Yii::t('global', 'Manager User'),
            'icon' => 'icon-user',
            'linkOptions' => array('class' => 'dcjq-parent'),
            'itemOptions' => array('class' => 'sub-menu dcjq-parent-li'),
            'submenuHtmlOptions' => array(
                'class' => 'sub',
            ),
            'items' => array(
                array(
                    'label' => Yii::t('global', 'Jobg8 Advertisers'),
                    'url' => array('users/jobg8'),
                    'icon' => 'icon-male'
                ),
                array(
                    'label' => Yii::t('global', 'Own Candidates'),
                    'url' => array('users/ownCandidate'),
                    'icon' => 'icon-male'
                ),
                array(
                    'label' => Yii::t('global', 'Manager Users'),
                    'url' => array('users/index'),
                    'icon' => 'icon-male'
                ),
                array(
                    'label' => Yii::t('global', 'Manager Admin'),
                    'url' => array('users/admin'),
                    'icon' => 'icon-user'
                ),
                array(
                    'label' => Yii::t('global', 'Manager Roles'),
                    'url' => array('roles/index'),
                    'icon' => ' icon-user-md'
                ),
            ),
        )
    )
));
?>