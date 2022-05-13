<?php
/**
 * DokuWiki Plugin linkprefix (Renderer Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Heiko Barth
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();

if (!defined('DOKU_LF')) define('DOKU_LF', "\n");
if (!defined('DOKU_TAB')) define('DOKU_TAB', "\t");
require_once DOKU_INC.'inc/parser/xhtml.php';

class renderer_plugin_linkprefix_n_xbr extends Doku_Renderer_xhtml {
	function getInfo() {
		return confToHash(dirname(__FILE__).'/plugin.info.txt');
	}

	/**
	* Make available as XHTML replacement renderer
	*/
	function canRender($format){
		if($format == 'xhtml') return true;
		return false;
	}

	function externallink($url, $name = null, $returnonly = false) {
		if (!$this->getConf('prefix') || $this->getConf('ignore_same_domain') == 1 && strtolower(parse_url($url, PHP_URL_HOST)) == strtolower($_SERVER["HTTP_HOST"])) {
			return parent::externallink($url, $name);
		}
		
		$name = ($name) ? $name : "$url";
		$protocol = ($_SERVER["HTTPS"]) ? "https" : "http";
		$url = $this->getConf('encode_url') ? urlencode($url) : $url;
		if ($this->getConf('prefix') == DOKU_BASE . 'lib/plugins/linkprefix/redirect.php?') {
			$url	= $protocol . "://" . $_SERVER["HTTP_HOST"] . $this->getConf('prefix') . $url;
		}
		else {
			$url	= $this->getConf('prefix') . $url;
		}
		return parent::externallink($url, $name);
	}

	function _resolveInterWiki(&$shortcut, $reference, &$exists = null) {
		if (!$this->getConf('prefix')) {
			return parent::_resolveInterWiki($shortcut,$reference);
		}

		$backup = $this->interwiki[$shortcut];
		$url = $this->getConf('encode_url') ? urlencode($this->interwiki[$shortcut]) : $this->interwiki[$shortcut];
		if ($backup) $this->interwiki[$shortcut] = $this->getConf('prefix') . $url;
		$return = parent::_resolveInterWiki($shortcut,$reference);
		$this->interwiki[$shortcut] = $backup;
		return $return;
	}
        function cdata($text) {
            $this->doc .= str_replace("\n","<br />\n",$this->_xmlEntities($text));
        }
}
