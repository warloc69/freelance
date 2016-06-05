<?php
/**
 * File described AbstractView view class
 *
 * PHP version 5
 *
 * @namespace  view
 * @author     sivanchenko@mindk.com
 */

namespace view;

/**
 *  AbstractView view class - base class for all view
 *
 * PHP version 5
 *
 * @namespace  view
 * @author     sivanchenko@mindk.com
 */
abstract class AbstractView
{
    private $default_dir = "tmp";
    private $header = "Header.php";
    private $footer = "Footer.php";
    protected $varHolder = array();

    /**
     * render filters
     * @param $context
     */
    private function filters($context)
    {
        include "plugin/filters.php";
    }

    /**
     * render paginator
     * @param $context
     */
    private function paginator($context)
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
        if(!empty($this->header)) {
            include $this->default_dir."/".$this->header;
        }
        echo $this->generateBody($context);
        $file_content = ob_get_clean();
        $file_content = str_replace("@filters", '<?php $this->filters($context); ?>', $file_content);
        $file_content = str_replace("@paginator", '<?php $this->paginator($context); ?>', $file_content);
        $file_content = $this->extractValues($file_content);
        if(!empty($this->footer)) {
            include $this->default_dir."/".$this->footer;
        }
        return eval('?>'.$file_content);
    }

    /**
     * render body html context
     *
     * @param $context information for rendering
     */
    public abstract function generateBody($context);

    /**
     * set name of file with html Head
     * @param $name
     */
    public function setHeaderFileName($name)
    {
        $this->header = $name;
    }

    /**
     * set name of file with html Footer
     * 
     * @param $name
     */
    public function setFooterFileName($name)
    {
        $this->footer = $name;
    }

    /**
     * set path to dir with template
     * @param $name
     */
    public function setTemplatePath($name)
    {
        $this->dir = $name;
    }

    /**
     * extract values for replacing in loaded templates
     * 
     * @param $context
     *
     * @return mixed
     */
    private function extractValues($context) {
        foreach ( $this->varHolder as $key => $value ) {
            $context = str_replace($key, $value, $context);
        }
        return $context;
    }

    /**
     * add key and value to replacing in loaded templates
     * 
     * @param $key
     * 
     * @param $value
     */
    public function assignValue($key,$value){
        $this->varHolder[$key] = $value;
    }
}