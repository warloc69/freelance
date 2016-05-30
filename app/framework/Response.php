<?php
namespace framework;
/**
*  class for sending headers and pages to client
*/
class Response
{
    private $headers = array();
    public $r;
    public function __construct($request) {
        $this->r = $request;
    }

	/**
     *  added custom header 
	 * $header - string representation of headers
     */
    public function addHeaders($header) {
        $this->headers[] = $header;
    }
		/**
     *  added no-cache headers 
     */
    public function setNoCache() {

        $this->headers[] = "Expires: Mon, 26 Jul 1997 05:00:00 GMT";
        $this->headers[] = "Cache-Control: no-store, no-cache, must-revalidate";
        $this->headers[] = "Pragma: no-cache";
        $this->headers[] = "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT";
    }
	/**
	* sending headers and page to client
	* $lokation - path to loaded page. Example "../src/View/Renderer.php"
	*/
    public function printHeader($lokation){
        foreach($this->headers as $head) {
            header($head);
        }
        $r = $this->r;
        include $lokation;
    }
}
?>