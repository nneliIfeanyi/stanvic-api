<?php
/**
 * cors.php
 * -----------------------------------------------------------------------
 * Shared CORS handling, included at the top of every API endpoint.
 *
 * For production, replace the '*' below with your actual front-end
 * origin (e.g. "https://myapp.com") so credentials-bearing requests
 * can't be made from arbitrary sites.
 * -----------------------------------------------------------------------
 */

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Max-Age: 86400"); // cache the preflight response for a day
header("Content-Type: application/json; charset=UTF-8");

// Browsers send a preflight OPTIONS request before the real one for
// "non-simple" requests (JSON bodies, custom headers, etc). Reply 200
// immediately with no body so the actual GET/POST can proceed.
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
