<?php
echo "<h2>PHP Configuration</h2>";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "<br>";
echo "post_max_size: " . ini_get('post_max_size') . "<br>";
echo "max_execution_time: " . ini_get('max_execution_time') . "<br>";
echo "memory_limit: " . ini_get('memory_limit') . "<br>";
echo "<br><h3>PHP Version: " . phpversion() . "</h3>";
echo "Loaded php.ini: " . php_ini_loaded_file() . "<br>";