<?php
/*
 *
 * A simple PHP CSV reader and writer example.
 * Take an array of CSV files and merge their data into one CSV file.
 * Append a new 'filename' column to each row of the new CSV file.
 *
 *
*/

// Array of CSV files to combine into one.
$array_of_files = array(
    'docs/clothing.csv',
    'docs/accessories.csv',
    'docs/household_cleaners.csv'
);

// New CSV file with combined data from the CSV files array.
$newCSV = 'docs/merged.csv';

// Open up merged.csv for writing.
$write = fopen($newCSV, "w+");

// Create the new header with 'filename' column appended.
$header = trim(fgets(fopen($array_of_files[0], 'r')));
$newHead = explode(',', $header);
array_push($newHead, "filename");

print "<table cellpadding=\"5\">\n";
print "<tr align=\"left\">\n";
foreach($newHead as $value) {
	$newValue = str_replace('"', "", $value);
	print "<th>" . $newValue . "</th>\n";
}
print "</tr>\n";

// Loop through the CSV files array.
foreach($array_of_files as $file) {
	$read = fopen($file, 'r') or die("Can't open the array of CSV files."); // Open each file up for reading.
	$firstline = fgetcsv($read);
	$fname = trim(basename($file).PHP_EOL); // Assign the file names to a variable.

	// Print all of the rows from the CSV files array into one table.
	while(($csv_line = fgetcsv($read)) !== FALSE) {
		array_push($csv_line, $fname . "\n"); // Append the file name of each row into new 'filename' column.
		fwrite($write, implode($csv_line, ',')); // Write the combined data to the merged.csv file.

		print "<tr>\n";
		for ($i = 0, $j = count($csv_line); $i < $j; $i++) {
			print "<td>" . stripslashes(htmlentities($csv_line[$i])) . "</td>\n";
		}
		print "</tr>\n";

	}
	fclose($read) or die("Can't close the array of CSV files.");
}
print "</table>\n";

// Add single comma-separated header to the merged.csv file.
$csvHead = implode(",", $newHead) . "\n";
$csvNoQuotes = str_replace('"', "", $csvHead);
$csvNoQuotes .= file_get_contents($newCSV);
file_put_contents($newCSV, $csvNoQuotes);

fclose($write) or die("Can't close the write (merged.csv) file. ");
