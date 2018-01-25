<?php $appPath = str_replace('/index.php','',getenv('ORIG_PATH_INFO')) ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:100,300,400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Mono" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
    <!-- FontAwesome - 4.7.0 -->
<!--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
-->
    <!-- Polyfills for IE11 -->
    <!-- Promises --><!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/es6-promise/4.0.5/es6-promise.auto.min.js"></script>-->
    <!-- Fetch --><!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/fetch/2.0.1/fetch.js"></script>-->

    <!-- Application - Javascript Add-ons -->
    <!-- Fuse.js --> <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/fuse.js/2.6.2/fuse.min.js"></script>-->
    <!-- Query String --><!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/qs/6.4.0/qs.min.js"></script>-->

    <!-- App Stylesheet -->
    <?php include '../../main_layout/head.php'; ?>
    <link rel="stylesheet" href="assets/_css/stylesheet.css">

</head>

<body>


    <div class="flex-container">
        <header>
            <?php include '../../main_layout/header.php'; ?>
        </header>
        <main>
            <div id="app"></div>
        </main>
        <footer>
            <?php include '../../main_layout/footer.php'; ?>
        </footer>
    </div>
    <!-- Catch uncaught exceptions as a last resort -->
    <script type="text/javascript">
      window.addEventListener('unhandledrejection', function(err, promise) {
        console.log('%c Caught Unhandled Rejection from a Promise', 'background: black; color: yellow');
        console.dir(err);
        console.dir(promise);
      });
    </script>
    <script src="assets/bundle.js"></script>
</body>
</html>
