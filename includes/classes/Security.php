<?php

class Security
{
    public $filename_bad_chars =	array(
        '../', '<!--', '-->', '<', '>',
        "'", '"', '&', '$', '#',
        '{', '}', '[', ']', '=',
        ';', '?', '%20', '%22',
        '%3c',		// <
        '%253c',	// <
        '%3e',		// >
        '%0e',		// >
        '%28',		// (
        '%29',		// )
        '%2528',	// (
        '%26',		// &
        '%24',		// $
        '%3f',		// ?
        '%3b',		// ;
        '%3d'		// =
    );
    protected $_never_allowed_str =	array(
        'document.cookie' => '[removed]',
        '(document).cookie' => '[removed]',
        'document.write'  => '[removed]',
        '(document).write'  => '[removed]',
        '.parentNode'     => '[removed]',
        '.innerHTML'      => '[removed]',
        '-moz-binding'    => '[removed]',
        '<!--'            => '&lt;!--',
        '-->'             => '--&gt;',
        '<![CDATA['       => '&lt;![CDATA[',
        '<comment>'	  => '&lt;comment&gt;',
        '<%'              => '&lt;&#37;'
    );
    protected $_never_allowed_regex = array(
        'javascript\s*:',
        '(\(?document\)?|\(?window\)?(\.document)?)\.(location|on\w*)',
        'expression\s*(\(|&\#40;)', // CSS and IE
        'vbscript\s*:', // IE, surprise!
        'wscript\s*:', // IE
        'jscript\s*:', // IE
        'vbs\s*:', // IE
        'Redirect\s+30\d',
        "([\"'])?data\s*:[^\\1]*?base64[^\\1]*?,[^\\1]*?\\1?"
    );

}