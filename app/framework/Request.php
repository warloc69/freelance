<?php
namespace framework;

/**
 *  class for accessing  and validation request information
 */

class Request
{
    function get($value)
    {
        $ids = explode('.', $value);
        $txt = null;
        if (count($ids) == 1 && isset($_REQUEST[$value])) {
            $txt = $_REQUEST[$value];
        } else if (isset($ids[1]) && $ids[1] == 'id') {
            $chunks = explode('/', $_SERVER["REQUEST_URI"]);
            for ($i = 0;$i < count($chunks);$i++) {
                if ($chunks[$i] == $ids[0]) {
                    if ($i + 1 < count($chunks)) {
                        $txt = $chunks[$i + 1];
                    }
                    break;
                }
            }
        }
        if ($txt != null) {
            $txt = HtmlSpecialChars($txt);
        }
        return $txt;
    }

    function getDirtyValue($value)
    {
        $result = null;
        if (isset($_REQUEST[$value])) {
            $result = $_REQUEST[$value];
        }
        return $result;
    }

    function getMethod()
    {
        return $_SERVER["REQUEST_METHOD"];
    }

    function getUri()
    {
        return $_SERVER["REQUEST_URI"];
    }

}