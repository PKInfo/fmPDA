<?php
// *********************************************************************************************************************************
//
// startup.inc.php
//
// *********************************************************************************************************************************
//
// Copyright (c) 2017 - 2019 Mark DeNyse
//
// Permission is hereby granted, free of charge, to any person obtaining a copy
// of this software and associated documentation files (the "Software"), to deal
// in the Software without restriction, including without limitation the rights
// to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
// copies of the Software, and to permit persons to whom the Software is
// furnished to do so, subject to the following conditions:
//
// The above copyright notice and this permission notice shall be included in
// all copies or substantial portions of the Software.
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
// FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
// AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
// OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
// SOFTWARE.
//
// *********************************************************************************************************************************

date_default_timezone_set('America/New_York');

// For DEBUGGING - Don't Even. Think. About. Doing. This. In. A. Production. Environment. Really.
ini_set('error_reporting', -1);
ini_set('display_startup_errors', 'on');
ini_set('display_errors', 'on');
ini_set('log_errors', 'on');


// Include *only* fmAdminAPI & fmCURL
require_once '../../../../fmPDA/vLatest/fmAdminAPI.class.php';

// As before with the old API, include only the protocol, domain, and port (optional), ie:
// https://www.example.com:8888
//
define('FM_HOST',                      'https://localhost');

define('FM_USERNAME',                  'mdenyse');
define('FM_PASSWORD',                  'admin%5%pw');   // actually something more secure for localhost :)

?>
