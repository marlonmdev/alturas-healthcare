<?php

include('class.pdf2text.php');
$a = new PDF2Text();
$a->setFilename('test.pdf');
$a->decodePDF();
$data = $a->output();
echo $data;
echo "Hello";