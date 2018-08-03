<?php
// *********************************************************************************************************************************
//
// edit_commit.php
//
// This test gets a record, updates the ColorIndex field and then uses commit to save the data back into the database.
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

$fm = new fmPDA(FM_DATABASE, FM_HOST, FM_USERNAME, FM_PASSWORD);

$result = null;

$recordID = 6;

$record = $fm->getRecordById('Web_Project', $recordID);              // Get the existing record
if (! fmGetIsError($record)) {
   $record->setField('ColorIndex', 888);
   $result = $record->commit();

   if (! fmGetIsError($result)) {
      fmLogger('Project Name = '. $result->getField('Name'));
      fmLogger($record);
   }
   else {
      fmLogger('Error = '. $result->getCode() .' Message = '. $result->getMessage());
   }
}
else {
   fmLogger('Error = '. $record->getCode() .' Message = '. $record->getMessage());
}

echo fmGetLog();

?>
