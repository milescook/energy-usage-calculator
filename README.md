# energy-usage-calculator
For working out octopus agile tariff data.

Ensure you have consumption.json with the half hourly consumption figures, and up to two tariff files tariffs.json and tariffs2.json. 

On Octopus, go to https://octopus.energy/dashboard/developer/ and run the curl command in a terminal, to get your unit rates (save as tariffs.json) and consumption (save as consumption.json).

Change the date at the top of the file, and run:

php calculator.php
