<?php

/* layout.html.twig */
class __TwigTemplate_0f44a09138db61b8a3a3b9dad0e162e076c132231292e1394163efb20ada015c extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'head' => array($this, 'block_head'),
            'header' => array($this, 'block_header'),
            'content' => array($this, 'block_content'),
            'footer' => array($this, 'block_footer'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
    <!--[if IE 8 ]>
    <html class=\"ie ie8\" class=\"no-js\" lang=\"en\">
    <![endif]-->
    <!--[if (gte IE 9)|!(IE)]><!-->
    <html class=\"no-js\" lang=\"en\">
    <!--<![endif]-->
        <head>
            ";
        // line 9
        $this->displayBlock('head', $context, $blocks);
        // line 45
        echo "        </head>
        <body class=\"skin-green\">
            <div class=\"wrapper\">
                ";
        // line 48
        $this->displayBlock('header', $context, $blocks);
        // line 56
        echo "
                ";
        // line 57
        $this->displayBlock('content', $context, $blocks);
        // line 60
        echo "
                ";
        // line 61
        $this->displayBlock('footer', $context, $blocks);
        // line 110
        echo "            </div>
        </body>
</html>

";
    }

    // line 9
    public function block_head($context, array $blocks = array())
    {
        // line 10
        echo "                <meta http-equiv=\"content-type\" content=\"text/html; charset=UTF-8\">
                <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
                <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, maximum-scale=1\">
                <title>Spring Consulting - admin</title>

                <!-- CSS FILES -->
                <link rel=\"stylesheet\" href=\"";
        // line 16
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "css/bootstrap/bootstrap.min.css\"/>

                <!-- Font Awesome Icons -->
                <link href=\"https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css\" rel=\"stylesheet\" type=\"text/css\" />
                <!-- Ionicons -->
                <link href=\"http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css\" rel=\"stylesheet\" type=\"text/css\" />
                <!-- Morris chart -->
                <link href=\"";
        // line 23
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "js/admin/plugins/morris/morris.css\" rel=\"stylesheet\" type=\"text/css\" />
                <!-- jvectormap -->
                <link href=\"";
        // line 25
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "js/admin/plugins/jvectormap/jquery-jvectormap-1.2.2.css\" rel=\"stylesheet\" type=\"text/css\" />
                <!-- Daterange picker -->
                <link href=\"";
        // line 27
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "js/admin/plugins/daterangepicker/daterangepicker-bs3.css\" rel=\"stylesheet\" type=\"text/css\" />
                <!-- Theme style -->
                ";
        // line 30
        echo "                <link href=\"";
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "css/AdminLTE.css\" rel=\"stylesheet\" type=\"text/css\" />
                <!-- AdminLTE Skins. Choose a skin from the css/skins
                     folder instead of downloading all of them to reduce the load. -->
                <link href=\"";
        // line 33
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "css/skins/skin-green.min.css\" rel=\"stylesheet\" type=\"text/css\" />

                <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
                <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
                <!--[if lt IE 9]>
                <script src=\"https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js\"></script>
                <script src=\"https://oss.maxcdn.com/respond/1.4.2/respond.min.js\"></script>
                <![endif]-->

                ";
        // line 43
        echo "                ";
        // line 44
        echo "            ";
    }

    // line 48
    public function block_header($context, array $blocks = array())
    {
        // line 49
        echo "                    <header class=\"main-header\">
                        <!-- Logo -->
                        <a href=\"";
        // line 51
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "\" class=\"logo\">SPRINGCONSULTING</a>
                        <nav class=\"navbar navbar-static-top\" role=\"navigation\">
                        </nav>
                    </header>
                ";
    }

    // line 57
    public function block_content($context, array $blocks = array())
    {
        // line 58
        echo "                    <adm-panel></adm-panel>
                ";
    }

    // line 61
    public function block_footer($context, array $blocks = array())
    {
        // line 62
        echo "                    <div class=\"main-footer\">FOOTER</div>
                    <!-- jQuery 2.1.3 -->
                    <script src=\"";
        // line 64
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "js/admin/plugins/jQuery/jQuery-2.1.3.min.js\"></script>
                    <!-- Bootstrap 3.3.2 JS -->
                    <script src=\"";
        // line 66
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "js/admin/bootstrap/bootstrap.min.js\" type=\"text/javascript\"></script>
                    <!-- FastClick -->
                    <script src='";
        // line 68
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "js/admin/plugins/fastclick/fastclick.min.js'></script>
                    <!-- AdminLTE App -->
                    <script src=\"";
        // line 70
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "/js/admin/app.js\" type=\"text/javascript\"></script>
                    <!-- Sparkline -->
                    <script src=\"";
        // line 72
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "js/admin/plugins/sparkline/jquery.sparkline.min.js\" type=\"text/javascript\"></script>
                    <!-- jvectormap -->
                    <script src=\"";
        // line 74
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "js/admin/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js\" type=\"text/javascript\"></script>
                    <script src=\"";
        // line 75
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "js/admin/plugins/jvectormap/jquery-jvectormap-world-mill-en.js\" type=\"text/javascript\"></script>
                    <!-- daterangepicker -->
                    <script src=\"";
        // line 77
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "js/admin/plugins/daterangepicker/daterangepicker.js\" type=\"text/javascript\"></script>
                    <!-- datepicker -->
                    <script src=\"";
        // line 79
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "js/admin/plugins/datepicker/bootstrap-datepicker.js\" type=\"text/javascript\"></script>
                    <!-- iCheck -->
                    <script src=\"";
        // line 81
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "js/admin/plugins/iCheck/icheck.min.js\" type=\"text/javascript\"></script>
                    <!-- SlimScroll 1.3.0 -->
                    <script src=\"";
        // line 83
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "js/admin/plugins/slimScroll/jquery.slimscroll.min.js\" type=\"text/javascript\"></script>
                    <!-- ChartJS 1.0.1 -->
                    <script src=\"";
        // line 85
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "js/admin/plugins/chartjs/Chart.min.js\" type=\"text/javascript\"></script>

                    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
                    ";
        // line 89
        echo "
                    ";
        // line 91
        echo "                    ";
        // line 92
        echo "
                    <!-- Polyfills -->
                    <script src=\"";
        // line 94
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "app-angular/node_modules/core-js/client/shim.js\"></script>

                    <script src=\"";
        // line 96
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "app-angular/node_modules/zone.js/dist/zone.min.js\"></script>
                    <script src=\"";
        // line 97
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "app-angular/node_modules/reflect-metadata/Reflect.js\"></script>

                    <script src=\"";
        // line 99
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "app-angular/node_modules/systemjs/dist/system.js\"></script>
                    <script src=\"";
        // line 100
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "app-angular/src/systemjs.config.js\"></script>

                    <script>
                        System.import('app').catch(
                            function (err) {
                                console.error(err);
                            }
                        );
                    </script>
                ";
    }

    public function getTemplateName()
    {
        return "layout.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  239 => 100,  235 => 99,  230 => 97,  226 => 96,  221 => 94,  217 => 92,  215 => 91,  212 => 89,  206 => 85,  201 => 83,  196 => 81,  191 => 79,  186 => 77,  181 => 75,  177 => 74,  172 => 72,  167 => 70,  162 => 68,  157 => 66,  152 => 64,  148 => 62,  145 => 61,  140 => 58,  137 => 57,  128 => 51,  124 => 49,  121 => 48,  117 => 44,  115 => 43,  103 => 33,  96 => 30,  91 => 27,  86 => 25,  81 => 23,  71 => 16,  63 => 10,  60 => 9,  52 => 110,  50 => 61,  47 => 60,  45 => 57,  42 => 56,  40 => 48,  35 => 45,  33 => 9,  23 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("<!DOCTYPE html>
    <!--[if IE 8 ]>
    <html class=\"ie ie8\" class=\"no-js\" lang=\"en\">
    <![endif]-->
    <!--[if (gte IE 9)|!(IE)]><!-->
    <html class=\"no-js\" lang=\"en\">
    <!--<![endif]-->
        <head>
            {% block head %}
                <meta http-equiv=\"content-type\" content=\"text/html; charset=UTF-8\">
                <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
                <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, maximum-scale=1\">
                <title>Spring Consulting - admin</title>

                <!-- CSS FILES -->
                <link rel=\"stylesheet\" href=\"{{ getCurrentDomain() }}css/bootstrap/bootstrap.min.css\"/>

                <!-- Font Awesome Icons -->
                <link href=\"https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css\" rel=\"stylesheet\" type=\"text/css\" />
                <!-- Ionicons -->
                <link href=\"http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css\" rel=\"stylesheet\" type=\"text/css\" />
                <!-- Morris chart -->
                <link href=\"{{ getCurrentDomain() }}js/admin/plugins/morris/morris.css\" rel=\"stylesheet\" type=\"text/css\" />
                <!-- jvectormap -->
                <link href=\"{{ getCurrentDomain() }}js/admin/plugins/jvectormap/jquery-jvectormap-1.2.2.css\" rel=\"stylesheet\" type=\"text/css\" />
                <!-- Daterange picker -->
                <link href=\"{{ getCurrentDomain() }}js/admin/plugins/daterangepicker/daterangepicker-bs3.css\" rel=\"stylesheet\" type=\"text/css\" />
                <!-- Theme style -->
                {#<link href=\"{{ getCurrentDomain() }}css/admin/AdminLTE.min.css\" rel=\"stylesheet\" type=\"text/css\" />#}
                <link href=\"{{ getCurrentDomain() }}css/AdminLTE.css\" rel=\"stylesheet\" type=\"text/css\" />
                <!-- AdminLTE Skins. Choose a skin from the css/skins
                     folder instead of downloading all of them to reduce the load. -->
                <link href=\"{{ getCurrentDomain() }}css/skins/skin-green.min.css\" rel=\"stylesheet\" type=\"text/css\" />

                <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
                <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
                <!--[if lt IE 9]>
                <script src=\"https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js\"></script>
                <script src=\"https://oss.maxcdn.com/respond/1.4.2/respond.min.js\"></script>
                <![endif]-->

                {#<link rel=\"icon\" href=\"{{ getCurrentDomain() }}favicon.ico\" type=\"image/x-icon\">#}
                {#<link rel=\"shortcut icon\" href=\"{{ getCurrentDomain() }}favicon.ico\" type=\"image/x-icon\">#}
            {% endblock %}
        </head>
        <body class=\"skin-green\">
            <div class=\"wrapper\">
                {% block header %}
                    <header class=\"main-header\">
                        <!-- Logo -->
                        <a href=\"{{ getCurrentDomain() }}\" class=\"logo\">SPRINGCONSULTING</a>
                        <nav class=\"navbar navbar-static-top\" role=\"navigation\">
                        </nav>
                    </header>
                {% endblock %}

                {% block content %}
                    <adm-panel></adm-panel>
                {% endblock %}

                {% block footer %}
                    <div class=\"main-footer\">FOOTER</div>
                    <!-- jQuery 2.1.3 -->
                    <script src=\"{{ getCurrentDomain() }}js/admin/plugins/jQuery/jQuery-2.1.3.min.js\"></script>
                    <!-- Bootstrap 3.3.2 JS -->
                    <script src=\"{{ getCurrentDomain() }}js/admin/bootstrap/bootstrap.min.js\" type=\"text/javascript\"></script>
                    <!-- FastClick -->
                    <script src='{{ getCurrentDomain() }}js/admin/plugins/fastclick/fastclick.min.js'></script>
                    <!-- AdminLTE App -->
                    <script src=\"{{ getCurrentDomain() }}/js/admin/app.js\" type=\"text/javascript\"></script>
                    <!-- Sparkline -->
                    <script src=\"{{ getCurrentDomain() }}js/admin/plugins/sparkline/jquery.sparkline.min.js\" type=\"text/javascript\"></script>
                    <!-- jvectormap -->
                    <script src=\"{{ getCurrentDomain() }}js/admin/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js\" type=\"text/javascript\"></script>
                    <script src=\"{{ getCurrentDomain() }}js/admin/plugins/jvectormap/jquery-jvectormap-world-mill-en.js\" type=\"text/javascript\"></script>
                    <!-- daterangepicker -->
                    <script src=\"{{ getCurrentDomain() }}js/admin/plugins/daterangepicker/daterangepicker.js\" type=\"text/javascript\"></script>
                    <!-- datepicker -->
                    <script src=\"{{ getCurrentDomain() }}js/admin/plugins/datepicker/bootstrap-datepicker.js\" type=\"text/javascript\"></script>
                    <!-- iCheck -->
                    <script src=\"{{ getCurrentDomain() }}js/admin/plugins/iCheck/icheck.min.js\" type=\"text/javascript\"></script>
                    <!-- SlimScroll 1.3.0 -->
                    <script src=\"{{ getCurrentDomain() }}js/admin/plugins/slimScroll/jquery.slimscroll.min.js\" type=\"text/javascript\"></script>
                    <!-- ChartJS 1.0.1 -->
                    <script src=\"{{ getCurrentDomain() }}js/admin/plugins/chartjs/Chart.min.js\" type=\"text/javascript\"></script>

                    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
                    {#<script src=\"dist/js/pages/dashboard2.js\" type=\"text/javascript\"></script>#}

                    {#<!-- AdminLTE for demo purposes -->#}
                    {#<script src=\"dist/js/demo.js\" type=\"text/javascript\"></script>#}

                    <!-- Polyfills -->
                    <script src=\"{{ getCurrentDomain() }}app-angular/node_modules/core-js/client/shim.js\"></script>

                    <script src=\"{{ getCurrentDomain() }}app-angular/node_modules/zone.js/dist/zone.min.js\"></script>
                    <script src=\"{{ getCurrentDomain() }}app-angular/node_modules/reflect-metadata/Reflect.js\"></script>

                    <script src=\"{{ getCurrentDomain() }}app-angular/node_modules/systemjs/dist/system.js\"></script>
                    <script src=\"{{ getCurrentDomain() }}app-angular/src/systemjs.config.js\"></script>

                    <script>
                        System.import('app').catch(
                            function (err) {
                                console.error(err);
                            }
                        );
                    </script>
                {% endblock %}
            </div>
        </body>
</html>

", "layout.html.twig", "/var/www/springconsult-admin.loc/application/views/layout.html.twig");
    }
}
