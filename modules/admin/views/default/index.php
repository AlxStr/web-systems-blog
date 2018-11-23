<div class="admin-default-index">
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3><?= $userCount ?></h3>

                <p>User Registrations</p>
            </div>
            <div class="icon">
                <i class="fa fa-user"></i>
            </div>
            <a href="/admin/user/" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3><?= $postCount?> </h3>

                <p>Post Count</p>
            </div>
            <div class="icon">
                <i class="fa fa-clipboard"></i>
            </div>
            <a href="/admin/post/" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-green">
            <div class="inner">
                <h3><?= $categoryCount ?> </h3>

                <p>Category Count</p>
            </div>
            <div class="icon">
                <i class="fa fa-list-ol"></i>
            </div>
            <a href="/admin/category/" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>
