<?php
namespace app\widgets;

use Yii;

class PostManagePanel extends \yii\bootstrap\Widget
{
    public $post_id;

    public function run()
    {
        if(\Yii::$app->user->can('admin'))
            return $this->render('admin_post_manage_panel', [
                'post_id' => $this->post_id,
            ]);
        if(\Yii::$app->user->can('ownPostsManage', ['post']))
            return $this->render('admin_post_manage_panel', [
                'post_id' => $this->post_id,
            ]);
    }
}
