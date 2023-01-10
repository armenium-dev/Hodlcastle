<!DOCTYPE html>
<html>
@include('layout_trainings.common.head')

<body class="training-body">
    <div class="site-wrapper">

        <div class="site-wrapper-inner">

            <div class="cover-container">

                @yield('content')


                <div class="mastfoot">
                    <div class="inner">
                        <p>Copyright © <?= date('Y'); ?> <a href="<?= env('APP_URL') ?>">phishmanager</a>. All rights reserved.</p>
                    </div>
                </div>

            </div>

        </div>

    </div>

    @include('layout_trainings.common.foot')

</body>

</html>
