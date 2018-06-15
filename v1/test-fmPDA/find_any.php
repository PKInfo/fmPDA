<?php
// *********************************************************************************************************************************
//
// find_any.php
//
// This test uses the 'Find Any' query to randomly return one record. Since this isn't directly supported in the Data API,
// fmPDA will always return the first record in the table. That's as random as anything, right? :)
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

// Set token to something invalid if you want to force fmPDA to request a new token on it's first call.
// This is mainly useful for debugging - it will slow down performance if you always force it to get a new token.
// The main reason for passing a token here is you want to store the token some place other than the default location
// in the session. In that case you should pass in a valid token and set storeTokenInSession => false. If you do this,
// you'll be responsible for storage of the token which you can retrieve with $fm->getToken().
$options = array(
                  'version'             => DATA_API_VERSION,
                  'storeTokenInSession' => true,
                  'token'               => ''  //'BAD-ROBOT'
               );

$fm = new fmPDA(FM_DATABASE, FM_HOST, FM_USERNAME, FM_PASSWORD, $options);

$findAnyCommand = $fm->newFindAnyCommand('Web_Project');

$findAnyCommand->setScript('Test', 'test-params');
$findAnyCommand->setPreCommandScript('Precommand', 'precommand-params');
$findAnyCommand->setPreSortScript('Presort', 'presort-params');

$result = $findAnyCommand->execute();

if (fmGetIsValid($result)) {

   $record = $result->getFirstRecord();
   if (! is_null($record)) {
      fmLogger('Project Name = '. $record->getField('Name'));

      //
      // Dump out the fields and their values
      //
      $fields = $result->getFields();
      if (count($fields) > 0) {
         fmLogger('');
         fmLogger('Fields:');
         foreach ($fields as $field) {
            fmLogger('&nbsp;&nbsp;&nbsp;'. $field);
         }

         fmLogger(" *** Demonstration of converting time stamp. 'time' conversion will fail. ***");
         $t = $record->getFieldAsTimeStamp('Date_Start', 0, 'date');
         fmLogger('&nbsp;&nbsp;&nbsp;'. 'Date_Start (as date value)' .' = '. (fmGetIsValid($t) ? $t : $t->getMessage()));
         $t = $record->getFieldAsTimeStamp('Date_Start', 0, 'time');
         fmLogger('&nbsp;&nbsp;&nbsp;'. 'Date_Start (as time value)' .' = '. (fmGetIsValid($t) ? $t : $t->getMessage()));
         $t = $record->getFieldAsTimeStamp('Date_Start', 0, 'timestamp');
         fmLogger('&nbsp;&nbsp;&nbsp;'. 'Date_Start (as timestamp value)' .' = '. (fmGetIsValid($t) ? $t : $t->getMessage()));
      }


      //
      // Now show all the related sets (portals) attached to this record, and dump the values of all fields.
      //
      $relatedSets = $result->getRelatedSets();
      if (count($relatedSets) > 0) {
         fmLogger('');
         fmLogger('Related Sets by Result:');
         foreach ($relatedSets as $relatedSetName) {
            fmLogger('&nbsp;&nbsp;&nbsp;'. $relatedSetName);
            $relatedSet = $record->getRelatedSet($relatedSetName);
            if (fmGetIsValid($relatedSet)) {
               foreach ($relatedSet as $relatedRecord) {
                  $fieldNames = $relatedRecord->getFields();
                  foreach ($fieldNames as $fieldName) {
                     fmLogger('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. $fieldName .'='. $relatedRecord->getField($fieldName));
                  }
               }
               fmLogger('');
            }
         }
      }

      // fmPDA has very limited to support for metadata since the Data API has none. All we can do is return data about a field
      // that comes from an existing record. This may be the area of your code you have to change a lot if you've relied on metadata.
      //
      // Dump the fields on this layout.
      //
      $layout = $record->getLayout();
      fmLogger('');
      fmLogger('Fields by Layout:');
      fmLogger($layout->listFields());


      //
      // Dump the related sets on this layout.
      //
      fmLogger('Related Sets by Layout:');
      $relatedSets = $layout->listRelatedSets();
      foreach ($relatedSets as $relatedSet) {
         $set = $layout->getRelatedSet($relatedSet);
         fmLogger('&nbsp;&nbsp;&nbsp;'. $set->getName());
//       fmLogger($set->listFields());
         $fields = $set->getFields();
         foreach ($fields as $fieldname => $field) {
            fmLogger('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. $fieldname);
         }
      }
   }
}
else {
   fmLogger('Error = '. $result->getCode() .' Message = '. $result->getMessage());
}

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
               fmPDA::newFindAnyCommand()
            </div>
            <div class="main-header main-sub-header-1" style="text-align: left;">
               Find the first record and run some scripts
               <pre><code class="language-php">
                  $fm = new fmPDA(FM_DATABASE, FM_HOST, FM_USERNAME, FM_PASSWORD);

                  $findAnyCommand = $fm->newFindAnyCommand('Web_Project');

                  $findAnyCommand->setScript('Test', 'test-params');
                  $findAnyCommand->setPreCommandScript('Precommand', 'precommand-params');
                  $findAnyCommand->setPreSortScript('Presort', 'presort-params');

                  $result = $findCommand->execute();
               </code></pre>
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
