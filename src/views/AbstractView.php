<?php
/**
 * Created by PhpStorm.
 * User: warloc
 * Date: 04.06.2016
 * Time: 19:53
 */

namespace view;

abstract class AbstractView
{
    private $default_dir = "tmp";
    private $header = "Header.php";
    private $footer = "Footer.php";

    public function filters($context)
    {
        include "plugin/filters.php";
    }

    public function paginator($context)
    {
        include "plugin/paginator.php";
    }

    /**
     * render html context
     *
     * @param $context information for rendering
     */
    public function display($context)
    {
        ob_start();
        include $this->default_dir."/".$this->header;
        $this->generateBody($context);
        $file_content = ob_get_clean();
        $file_content = str_replace("@filters", '<?php $this->filters($context); ?>', $file_content);
        $file_content = str_replace("@paginator", '<?php $this->paginator($context); ?>', $file_content);
        include $this->default_dir."/".$this->footer;
        return eval('?>'.$file_content);
    }

    /**
     * render body html context
     *
     * @param $context information for rendering
     */
    abstract function generateBody($context);

    public function setHeaderFileName($name)
    {
        $this->header = $name;
    }

    public function setFooterFileName($name)
    {
        $this->footer = $name;
    }

    public function setTemplatePath($name)
    {
        $this->dir = $name;
    }
}