<?php
  // If you need to parse XLS files, include php-excel-reader
    require('php-excel-reader/excel_reader2.php');

    require('SpreadsheetReader.php');


    /*All data is read from the file sequentially, with each row being returned as a numeric array. This is about the easiest way to read a file:*/
    $Reader = new SpreadsheetReader('excel.xlsx');
    foreach ($Reader as $Row)
    {
        print_r($Row);
    }

?>


<?php

/*However, now also multiple sheet reading is supported for file formats where it is possible. (In case of CSV, it is handled as if it only has one sheet.)

You can retrieve information about sheets contained in the file by calling the Sheets() method which returns an array with sheet indexes as keys and sheet names as values. Then you can change the sheet that's currently being read by passing that index to the ChangeSheet($Index) method.*/
    $Reader = new SpreadsheetReader('example.xlsx');
    $Sheets = $Reader -> Sheets();

    foreach ($Sheets as $Index => $Name)
    {
        echo 'Sheet #'.$Index.': '.$Name;

        $Reader -> ChangeSheet($Index);

        foreach ($Reader as $Row)
        {
            print_r($Row);
        }
    }
?>