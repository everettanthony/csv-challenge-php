<?php

/* 
    CSV Combiner Challenge.
    Combine an array of CSV files into one CSV file, and append a new CSV 'filename' column 
    that displays the row data's original filename on each row.
*/

    // List of the CSV files to combine into one.
    $array_of_files = array(
        'docs/clothing.csv',
        'docs/accessories.csv',
        'docs/household_cleaners.csv'
    );

    // Function combines list of CSV files into filename list in second parameter. 
    function csvCombiner(array $files, $result) {
        if(!is_array($files)) {
            echo 'The first argument is not an array.';
        }

        // Open up merged.csv for writing.
        $write = fopen($result, "w+");

        // Loop through each CSV file.
        foreach($files as $file):
            $reader = fopen($file, 'r');
            $line = FALSE; 
            $header = fgetcsv($reader, 9000, ',');

            // Add each line of the three files into merged.csv
            while(($data = fgetcsv($reader, 9000, ',')) != FALSE) {
                if (!$line){ 
                    // Skip the first line of each CSV file.
                    $line = TRUE; 
                } else { 
                    $data = str_replace(['"', '\\'], '', $data);
                    fwrite($write, implode($data, ',') . ',' . basename($file).PHP_EOL);
                } 
            }

            // Print out the results.
            rewind($write);
            $output = stream_get_contents($write);
            echo $output;

            fclose($reader);
            unset($reader);
        endforeach;

        // Add the header column values to the first line only of the new CSV file. (merged.csv)
        array_push($header, "filename");
        $newHead = implode(', ', $header) . "\n";
        $newHead .= file_get_contents($result);
        file_put_contents($result, $newHead);

        fclose($write);
        unset($write);
        echo 'Merging complete!';
    }

    // Call the csvCombiner function when the PHP file is run in the terminal.
    csvCombiner($array_of_files, 'docs/merged.csv');
?>