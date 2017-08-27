<?php return array(
    'post/<id:\d+>/<title:.*?>' => 'post/view',
    'posts/<tag:.*?>' => 'post/index',
    'my-widgets' => 'widget/my',
    'my-courses' => 'course/admin',
    'online-widgets' => 'widget/online',
    'profile' => 'widget/online',
    'team' => 'user/team',
    'forget-password' => 'site/forgetPassword',
    'reset-password' => 'site/resetPassword',
    'register' => 'site/register',

    'preview/<alias:.*?>' => 'preview/index',

    '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
);