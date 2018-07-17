<?php
// *********************************************************************************************************************************
//
// get_record_by_id.php
//
// This example uses getRecordById().
//    - Retrieves one record and dump the contents to the log.
//    - Retrieves a different record and displays the project name in the log.
//    - Attempts to retrieve a record which doesn't exist, return a null result to match the old API.
//
// *********************************************************************************************************************************
//
// Copyright (c) 2017 - 2018 Mark DeNyse
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

require_once 'startup.inc.php';

$output = '';

// Set token to something invalid if you want to force fmPDA to request a new token on it's first call.
// This is mainly useful for debugging - it will slow down performance if you always force it to get a new token.
// The main reason for passing a token here is you want to store the token some place other than the default location
// in the session. In that case you should pass in a valid token and set storeTokenInSession => false. If you do this,
// you'll be responsible for storage of the token which you can retrieve with $fm->getToken().
$options = array(
   'version'               => DATA_API_VERSION,
   'storeTokenInSession'   => true,                  // This defaults to true - here for debugging only
   'token'                 => '',  //'BAD-ROBOT',

   'authentication'        => array(                                                // Couldn't do this before!
/*                                  'method'  => 'default', */
                                    'sources' => array(
                                          array(
                                             'database'  => 'externaldb',      // should NOT include .fmp12
                                             'username'  => 'externaluser',
                                             'password'  => 'externalpass'
                                          )
                                       )
                                    )
);

$fm = new fmPDA(FM_DATABASE, FM_HOST, FM_USERNAME, FM_PASSWORD, $options);

$output .= GetTheRecord($fm, 1);
$output .= GetTheRecord($fm, 55555);            // Will fail - record doesn't exist


function GetTheRecord($fm, $recordID, $showRecordData = false)
{
   $output = '';

   $result = $fm->getRecordById('Web_Project', $recordID);
   if (fmGetIsValid($result)) {
      if ($showRecordData) {
         $output .= $result->dumpRecord();
      }
      else {
         $output .= '<strong>Record ID '. $recordID .' Name:</strong><br>'. $result->getFieldUnencoded('Name') .'<br><br>';
      }
   }
   else if (is_null($result)) {
      $output .= 'Record not found.';
   }
   else {
      $output .= '<strong>Record ID '. $recordID .' (Error):</strong><br>code='. $result->getCode() .' message='. $result->getMessage() .'<br><br>';
  }

   return $output;
}





//
// Get a record by ID 5
//
// fmLogger('');
// fmLogger('');
// fmLogger('');
// fmLogger('************************************************************************************************************************************');
// $result = $fm->getRecordById('Web_Project', 5);
// //fmLogger($result);
// if (fmGetIsValid($result)) {
//    fmLogger('Project Name = '. $result->getFieldUnencoded('Name'));
// }
// else if (is_null($result)) {
//    fmLogger('Record not found.');
// }
// else {
//    fmLogger('Error = '. $result->getCode() .' Message = '. $result->getMessage());
// }
//
//
// //
// // Get a record by ID 55555 - this will fail as the record doesn't exist.
// //
// fmLogger('');
// fmLogger('');
// fmLogger('');
// fmLogger('************************************************************************************************************************************');
// $result = $fm->getRecordById('Web_Project', 55555);
// //fmLogger($result);
// if (fmGetIsValid($result)) {
//    fmLogger('Project Name = '. $result->getFieldUnencoded('Name'));
// }
// else if (is_null($result)) {                                   // Remember, the old API returns null when the record can't be found
//    fmLogger('Record not found.');
// }
// else {
//    fmLogger('Error = '. $result->getCode() .' Message = '. $result->getMessage());
// }

?>
<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8" />
      <title>fmPDA &#9829;</title>
      <link href="../../css/normalize.css" rel="stylesheet" />
      <link href="../../css/styles.css" rel="stylesheet" />
      <link href="../../css/fontawesome-all.min.css" rel="stylesheet" />
      <link href="../../css/prism.css" rel="stylesheet" />
      <script src="../../js/prism.js"></script>
   </head>
   <body>

      <!-- Always on top: Position Fixed-->
      <header>
         fmPDA <span class="fmPDA-heart"><i class="fas fa-heart"></i></span>
         <span style="float: right;">Version <strong>1</strong> (FileMaker Server 17+)</span>
      </header>



      <!-- Fixed size after header-->
      <div class="content">

         <!-- Always on top. Fixed position, fixed width, relative to content width-->
         <div class="navigation-left">
            <div class="navigation-header1"><a href="../../index.php">Home</a></div>
            <br>

            <?php echo GetNavigationMenu(1); ?>
         </div>



          <!-- Scrollable div with main content -->
         <div class="main">

            <div class="main-header">
               fmPDA::getRecordById()
            </div>
            <div class="main-header main-sub-header-1" style="text-align: left;">
               Retrieve 3 different records by ID, last one will fail (shows error result)<br>
               <pre><code class="language-php">
                  $fm = new fmPDA(FM_DATABASE, FM_HOST, FM_USERNAME, FM_PASSWORD);<br>
                  $result = $fm->getRecordById('Web_Project', 1);<br>
               </code></pre>
            </div>

            <div class="output-header">
               Output
            </div>
            <div class="output">
               <pre><?php echo $output; ?></pre>
            </div>


            <!-- Display the debug log -->
            <?php echo fmGetLog(); ?>

         </div>

      </div>



      <!-- Always at the end of the page -->
      <footer>
         <a href="http://www.driftwoodinteractive.com"><img src="../../img/di.png" height="32" width="128" alt="Driftwood Interactive" style="vertical-align:text-bottom"></a><br>
         Copyright &copy; <?php echo date('Y'); ?> Mark DeNyse Released Under the MIT License.
      </footer>

		<script src="../../js/main.js"></script>
      <script>
         javascript:ExpandDropDown("fmPDA");
      </script>

   </body>
</html>
