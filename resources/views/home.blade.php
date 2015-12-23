<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bugsy | Personal Bug Database</title>

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="description" content="Personal Bug Database">
    <!-- Loading Bootstrap -->
    <link rel="stylesheet" href="{{ asset('packages/flat-ui/bootstrap/css/bootstrap.min.css') }}">
    <!-- Loading Flat UI -->
    <link rel="stylesheet" href="{{ asset('packages/flat-ui/css/flat-ui.css') }}">
    <!-- Loading App styles -->
    <link rel="stylesheet" href="{{ asset('packages/animate.css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div id="search" class="container">
        <!--   <nav class="navbar navbar-inverse navbar-embossed text-center" role="navigation"></nav> -->
        <section class="">
            <div class="jumbotron">
                <h1>Bugsy <small class="grey">Personal Bug Database</small></h1>
                <div class="form-group">
                    <input type="text" v-on="keypress: doSearch enter " v-model="bug" class="form-control flat input-lg" name="search" placeholder="PHP DateTime bug">
                    <span class="input-icon fui-search animated tada"></span>
                </div>
                <a href="#" class="btn btn-success btn-embossed btn-lg"><i class="fui-plus "></i> Agregar bug</a>
            </div>
            <div class="row-fluid">
                <div class="col-sm-offset-3 col-sm-6 well well-sm">
                    <p>@{{ bug }}</p>
                </div>
            </div>
        </section>
    </div>
    <hr class="divisor">
    <footer id="footer">
        <div class="col-sm-offset-2 col-sm-8 text-center">
            <h6> A product by <em>Quipulabz</em> made with <i class="fui-heart red animated pulse infinite"></i> </h6>
        </div>
    </footer>

    <!-- Loading Javscript -->
    <script src="{{ asset('packages/flat-ui/js/jquery-1.10.2.min.js') }}"></script>
    <script src="{{ asset('packages/flat-ui/js/jquery-ui-1.10.3.custom.min.js') }}"></script>
    <script src="{{ asset('packages/flat-ui/js/jquery.ui.touch-punch.min.js') }}"></script>
    <script src="{{ asset('packages/flat-ui/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('packages/flat-ui/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('packages/flat-ui/js/bootstrap-switch.js') }}"></script>
    <script src="{{ asset('packages/flat-ui/js/flatui-checkbox.js') }}"></script>
    <script src="{{ asset('packages/flat-ui/js/flatui-radio.js') }}"></script>
    <script src="{{ asset('packages/flat-ui/js/jquery.placeholder.js') }}"></script>
    <script src="{{ asset('packages/flat-ui/js/jquery.tagsinput.js') }}"></script>
    <script src="{{ asset('packages/flat-ui/js/typeahead.js') }}"></script>
    <!-- <script src="{{ asset('packages/flat-ui/js/application.js') }}"></script> -->
    <script src="{{ asset('packages/vue/dist/vue.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
