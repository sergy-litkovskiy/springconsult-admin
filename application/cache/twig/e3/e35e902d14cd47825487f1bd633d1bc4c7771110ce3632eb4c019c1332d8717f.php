<?php

/* /layout.html.twig */
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
        // line 46
        echo "        </head>
        <body class=\"skin-green\">
            <div class=\"wrapper\">
                ";
        // line 49
        $this->displayBlock('header', $context, $blocks);
        // line 57
        echo "
                ";
        // line 58
        $this->displayBlock('content', $context, $blocks);
        // line 61
        echo "
                ";
        // line 62
        $this->displayBlock('footer', $context, $blocks);
        // line 113
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
                <link href=\"";
        // line 31
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "css/ngx-datatable.css\" rel=\"stylesheet\" type=\"text/css\" />
                <!-- AdminLTE Skins. Choose a skin from the css/skins
                     folder instead of downloading all of them to reduce the load. -->
                <link href=\"";
        // line 34
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "css/skins/skin-green.min.css\" rel=\"stylesheet\" type=\"text/css\" />

                <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
                <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
                <!--[if lt IE 9]>
                <script src=\"https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js\"></script>
                <script src=\"https://oss.maxcdn.com/respond/1.4.2/respond.min.js\"></script>
                <![endif]-->

                ";
        // line 44
        echo "                ";
        // line 45
        echo "            ";
    }

    // line 49
    public function block_header($context, array $blocks = array())
    {
        // line 50
        echo "                    <header class=\"main-header\">
                        <!-- Logo -->
                        <a href=\"";
        // line 52
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "\" class=\"logo\">SPRINGCONSULTING</a>
                        <nav class=\"navbar navbar-static-top\" role=\"navigation\">
                        </nav>
                    </header>
                ";
    }

    // line 58
    public function block_content($context, array $blocks = array())
    {
        // line 59
        echo "                    <adm-panel></adm-panel>
                ";
    }

    // line 62
    public function block_footer($context, array $blocks = array())
    {
        // line 63
        echo "                    <div class=\"main-footer\">
                        <strong>Copyright © 2010-2017 <a href=\"http://springconsult.com.ua\">Springconsult</a></strong>
                    </div>
                    <!-- jQuery 2.1.3 -->
                    <script src=\"";
        // line 67
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "js/admin/plugins/jQuery/jQuery-2.1.3.min.js\"></script>
                    <!-- Bootstrap 3.3.2 JS -->
                    <script src=\"";
        // line 69
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "js/admin/bootstrap/bootstrap.min.js\" type=\"text/javascript\"></script>
                    <!-- FastClick -->
                    <script src='";
        // line 71
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "js/admin/plugins/fastclick/fastclick.min.js'></script>
                    <!-- AdminLTE App -->
                    <script src=\"";
        // line 73
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "/js/admin/app.js\" type=\"text/javascript\"></script>
                    <!-- Sparkline -->
                    <script src=\"";
        // line 75
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "js/admin/plugins/sparkline/jquery.sparkline.min.js\" type=\"text/javascript\"></script>
                    <!-- jvectormap -->
                    <script src=\"";
        // line 77
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "js/admin/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js\" type=\"text/javascript\"></script>
                    <script src=\"";
        // line 78
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "js/admin/plugins/jvectormap/jquery-jvectormap-world-mill-en.js\" type=\"text/javascript\"></script>
                    <!-- daterangepicker -->
                    <script src=\"";
        // line 80
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "js/admin/plugins/daterangepicker/daterangepicker.js\" type=\"text/javascript\"></script>
                    <!-- datepicker -->
                    <script src=\"";
        // line 82
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "js/admin/plugins/datepicker/bootstrap-datepicker.js\" type=\"text/javascript\"></script>
                    <!-- iCheck -->
                    <script src=\"";
        // line 84
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "js/admin/plugins/iCheck/icheck.min.js\" type=\"text/javascript\"></script>
                    <!-- SlimScroll 1.3.0 -->
                    <script src=\"";
        // line 86
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "js/admin/plugins/slimScroll/jquery.slimscroll.min.js\" type=\"text/javascript\"></script>
                    <!-- ChartJS 1.0.1 -->
                    <script src=\"";
        // line 88
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "js/admin/plugins/chartjs/Chart.min.js\" type=\"text/javascript\"></script>

                    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
                    ";
        // line 92
        echo "
                    ";
        // line 94
        echo "                    ";
        // line 95
        echo "
                    <!-- Polyfills -->
                    <script src=\"";
        // line 97
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "app-angular/node_modules/core-js/client/shim.js\"></script>

                    <script src=\"";
        // line 99
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "app-angular/node_modules/zone.js/dist/zone.min.js\"></script>
                    <script src=\"";
        // line 100
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "app-angular/node_modules/reflect-metadata/Reflect.js\"></script>

                    <script src=\"";
        // line 102
        echo twig_escape_filter($this->env, getCurrentDomain(), "html", null, true);
        echo "app-angular/node_modules/systemjs/dist/system.js\"></script>
                    <script src=\"";
        // line 103
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
        return "/layout.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  245 => 103,  241 => 102,  236 => 100,  232 => 99,  227 => 97,  223 => 95,  221 => 94,  218 => 92,  212 => 88,  207 => 86,  202 => 84,  197 => 82,  192 => 80,  187 => 78,  183 => 77,  178 => 75,  173 => 73,  168 => 71,  163 => 69,  158 => 67,  152 => 63,  149 => 62,  144 => 59,  141 => 58,  132 => 52,  128 => 50,  125 => 49,  121 => 45,  119 => 44,  107 => 34,  101 => 31,  96 => 30,  91 => 27,  86 => 25,  81 => 23,  71 => 16,  63 => 10,  60 => 9,  52 => 113,  50 => 62,  47 => 61,  45 => 58,  42 => 57,  40 => 49,  35 => 46,  33 => 9,  23 => 1,);
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
                <link href=\"{{ getCurrentDomain() }}css/ngx-datatable.css\" rel=\"stylesheet\" type=\"text/css\" />
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
                    <div class=\"main-footer\">
                        <strong>Copyright © 2010-2017 <a href=\"http://springconsult.com.ua\">Springconsult</a></strong>
                    </div>
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

", "/layout.html.twig", "/var/www/springconsult-admin.loc/application/views/layout.html.twig");
    }
}
