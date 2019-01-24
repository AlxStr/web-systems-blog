<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= \Yii::$app->user->identity->username ?></p>

                <a href=""><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>


        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
                'items' => [
                    ['label' => 'Menu Yii2', 'options' => ['class' => 'header']],
                    ['label' => 'Home', 'icon' => 'address-book', 'url' => ['/admin/default/']],
                    ['label' => 'Users', 'icon' => 'address-book', 'url' => ['/admin/user/']],
                    ['label' => 'Posts', 'icon' => 'file-text-o', 'url' => ['/admin/post/']],
                    ['label' => 'Categories', 'icon' => 'list-alt', 'url' => ['/admin/category/']],
                ],
            ]
        ) ?>

    </section>

</aside>