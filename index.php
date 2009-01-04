<?php

function validate_file( $file, $allowed_files = '' ) {
	if ( false !== strpos( $file, '..' ))
		return 1;

	if ( false !== strpos( $file, './' ))
		return 1;

	if (':' == substr( $file, 1, 1 ))
		return 2;

	if (!empty ( $allowed_files ) && (!in_array( $file, $allowed_files ) ) )
		return 3;

	return 0;
}

/**
 * Converts a number of special characters into their HTML entities.
 *
 * Differs from htmlspecialchars as existing HTML entities will not be encoded.
 * Specifically changes: & to &#038;, < to &lt; and > to &gt;.
 *
 * $quotes can be set to 'single' to encode ' to &#039;, 'double' to encode " to
 * &quot;, or '1' to do both. Default is 0 where no quotes are encoded.
 *
 * @since 1.2.2
 *
 * @param string $text The text which is to be encoded.
 * @param mixed $quotes Optional. Converts single quotes if set to 'single', double if set to 'double' or both if otherwise set. Default 0.
 * @return string The encoded text with HTML entities.
 */
function wp_specialchars( $text, $quotes = 0 ) {
	// Like htmlspecialchars except don't double-encode HTML entities
	$text = str_replace('&&', '&#038;&', $text);
	$text = str_replace('&&', '&#038;&', $text);
	$text = preg_replace('/&(?:$|([^#])(?![a-z1-4]{1,8};))/', '&#038;$1', $text);
	$text = str_replace('<', '&lt;', $text);
	$text = str_replace('>', '&gt;', $text);
	if ( 'double' === $quotes ) {
		$text = str_replace('"', '&quot;', $text);
	} elseif ( 'single' === $quotes ) {
		$text = str_replace("'", '&#039;', $text);
	} elseif ( $quotes ) {
		$text = str_replace('"', '&quot;', $text);
		$text = str_replace("'", '&#039;', $text);
	}
	return $text;
}

/*
I trust myself :)
*/
function clean_url( $url ) { return $url; }
function balanceTags( $url ) { return $url; }
function wp_kses( $url ) { return $url; }

if(!isset($_GET['file']))
	$_GET['file'] = 'index';

$file = htmlspecialchars($_GET['file']) . '.text';

if(validate_file($file) > 0)
	die('Invalid file specified');

if(!file_exists($file))
	die('4oh4 &mdash; File not found');

require_once('markdown.php');
require_once('parse-readme.php');

$readme = new Automattic_Readme();
$contents = (object) $readme->parse_readme_contents(file_get_contents('http://svn.wp-plugins.org/really-simple-comment-validation/trunk/readme.txt'));

echo '<h1>' . $contents->name . '</h1>';
echo '<div id="content">' . $contents->sections['description'] . '</div>';

//var_dump();
/*
require_once('header.php');
echo Markdown(file_get_contents($file));
require_once('footer.php');
*/
?>