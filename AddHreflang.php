<?php
/**
 * Add Hreflang to WHMCS
 *
 * @package     WHMCS
 * @copyright   Hosting.gl ApS
 * @link        https://hosting.gl
 * @author      Thomas Gravesen <support@hosting.gl>
 */

if (!defined('WHMCS')) {
    die('You cannot access this file directly.');
}

function replaceQueryParams($url, $params)
{
    $query = parse_url($url, PHP_URL_QUERY);
    parse_str($query, $oldParams);

    if (empty($oldParams)) {
        return rtrim($url, '?') . '?' . http_build_query($params);
    }

    $params = array_merge($oldParams, $params);

    return preg_replace('#\?.*#', '?' . http_build_query($params), $url);
}

add_hook('ClientAreaHeadOutput', 1, function($vars) {
    $systemUrl = 'https://YOURDOMAINHERE.COM';
    $url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $canonical = strtok($url, '?');

	$lang = $vars['language'];
	$alternates = '';
	if ($lang == 'danish') {
		$urlLang = replaceQueryParams($url, ['language' => 'en']);
		$alternates = '<link rel="alternate" hreflang="en" href="' . $urlLang . '" />';
	}
	
	if ($lang == 'english') {
		$urlLang = replaceQueryParams($url, ['language' => 'da']);
		$alternates = '<link rel="alternate" hreflang="da" href="' . $urlLang . '" />';
	}
	
    return <<<HTML
	<meta name="robots" content="all">
    <link rel="canonical" href="{$canonical}"/>
	{$alternates}
HTML;
});
