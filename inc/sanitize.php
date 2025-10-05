<?php
/**
 * Basic HTML sanitizer for saved WYSIWYG content.
 * This is a reasonably strict, DOM-based sanitizer. It removes scripts,
 * iframes, event attributes (onclick), style attributes and disallowed URI schemes.
 * For a production-ready setup, consider installing HTMLPurifier via Composer.
 */

function sanitize_html(string $html): string
{
    // Allowed tags
    $allowed = ['p','br','b','strong','i','em','u','a','ul','ol','li','img','h1','h2','h3','blockquote'];

    // Load into DOMDocument
    $doc = new DOMDocument();
    // Suppress warnings for invalid HTML fragments
    libxml_use_internal_errors(true);
    $doc->loadHTML('<?xml encoding="utf-8" ?>' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    libxml_clear_errors();

    // Remove dangerous elements
    $dangerous = ['script','iframe','object','embed','form','input','button','style'];
    foreach ($dangerous as $tag) {
        $nodes = $doc->getElementsByTagName($tag);
        // iterate in reverse because getElementsByTagName is live
        for ($i = $nodes->length - 1; $i >= 0; $i--) {
            $node = $nodes->item($i);
            if ($node) $node->parentNode->removeChild($node);
        }
    }

    // Walk nodes and remove disallowed tags/attributes
    $xpath = new DOMXPath($doc);
    foreach ($xpath->query('//*') as $node) {
        $tag = $node->nodeName;
        if (!in_array($tag, $allowed)) {
            // Replace node with its children (strip the tag)
            while ($node->firstChild) {
                $node->parentNode->insertBefore($node->firstChild, $node);
            }
            $node->parentNode->removeChild($node);
            continue;
        }

        // Remove style and event attributes
        if ($node->hasAttributes()) {
            $attrs = [];
            foreach ($node->attributes as $attr) {
                $attrs[$attr->name] = $attr->value;
            }
            foreach ($attrs as $name => $value) {
                $lname = strtolower($name);
                if (!($node instanceof DOMElement)) continue;
                if (strpos($lname, 'on') === 0) {
                    $node->removeAttribute($name);
                    continue;
                }
                if ($lname === 'style') {
                    $node->removeAttribute($name);
                    continue;
                }
                if (in_array($lname, ['href','src'])) {
                    // Disallow javascript: and data: URIs
                    $val = trim($value);
                    $lower = strtolower($val);
                    if (strpos($lower, 'javascript:') === 0 || strpos($lower, 'data:') === 0) {
                        $node->removeAttribute($name);
                        continue;
                    }
                    // Allow relative and http(s) and protocol-relative URLs
                    // No further normalization here.
                }
            }
        }
    }

    // Return innerHTML
    $body = $doc->saveHTML();
    // Remove the added XML encoding declaration
    $body = preg_replace('/^<!DOCTYPE.+?>/', '', $body);
    $body = str_replace(['<html>', '</html>', '<body>', '</body>'], '', $body);
    return trim($body);
}
