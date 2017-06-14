<?php

/* error/index.html.twig */
class __TwigTemplate_29ee7966d86ab4338e52aaff49cd3dd8f5ffb39028a0bec25c2288245e956eea extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.html.twig", "error/index.html.twig", 1);
        $this->blocks = array(
            'head' => array($this, 'block_head'),
            'header' => array($this, 'block_header'),
            'content' => array($this, 'block_content'),
            'gift' => array($this, 'block_gift'),
            'footer' => array($this, 'block_footer'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "layout.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_head($context, array $blocks = array())
    {
        // line 4
        echo "    ";
        $this->displayParentBlock("head", $context, $blocks);
        echo "
";
    }

    // line 7
    public function block_header($context, array $blocks = array())
    {
        // line 8
        echo "    ";
        $this->displayParentBlock("header", $context, $blocks);
        echo "
";
    }

    // line 10
    public function block_content($context, array $blocks = array())
    {
        // line 11
        echo "    <section class=\"wrapper\">
        <section class=\"page_head\">
            <div class=\"container\">
                <div class=\"row\">
                    <div class=\"col-lg-12 col-md-12 col-sm-12\">
                        <div class=\"page_title\">
                            <h2>404 Страница не найдена</h2>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class=\"content service\">
            <div class=\"container\">
                <div class=\"page_404\">
                    <h1>404</h1>
                    <p>Извините, страница не найдена</p>
                </div>
            </div>
        </section>
    </section>
";
    }

    // line 34
    public function block_gift($context, array $blocks = array())
    {
        // line 35
        echo "    ";
        $this->displayParentBlock("gift", $context, $blocks);
        echo "
";
    }

    // line 38
    public function block_footer($context, array $blocks = array())
    {
        // line 39
        echo "    ";
        $this->displayParentBlock("footer", $context, $blocks);
        echo "
";
    }

    public function getTemplateName()
    {
        return "error/index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  93 => 39,  90 => 38,  83 => 35,  80 => 34,  55 => 11,  52 => 10,  45 => 8,  42 => 7,  35 => 4,  32 => 3,  11 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends \"layout.html.twig\" %}

{% block head %}
    {{ parent() }}
{% endblock %}

{% block header %}
    {{ parent() }}
{% endblock %}
{% block content %}
    <section class=\"wrapper\">
        <section class=\"page_head\">
            <div class=\"container\">
                <div class=\"row\">
                    <div class=\"col-lg-12 col-md-12 col-sm-12\">
                        <div class=\"page_title\">
                            <h2>404 Страница не найдена</h2>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class=\"content service\">
            <div class=\"container\">
                <div class=\"page_404\">
                    <h1>404</h1>
                    <p>Извините, страница не найдена</p>
                </div>
            </div>
        </section>
    </section>
{% endblock %}

{% block gift %}
    {{ parent() }}
{% endblock %}

{% block footer %}
    {{ parent() }}
{% endblock %}", "error/index.html.twig", "/var/www/springconsult-admin.loc/application/views/error/index.html.twig");
    }
}
