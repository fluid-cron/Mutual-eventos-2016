<?php    

include('qrlib.php'); 
/*
$param = $_GET["param"];

if( !isset($param) || $param=="" ){
    $param = "http://www.google.cl";
}

$filename = 'temp/'.rand(0,9999).'.png';

QRcode::png($param,$filename,QR_ECLEVEL_L,8);

echo '<img height="200" src="'.$filename.'"/>';
        
// benchmark
QRtools::timeBenchmark();
*/

    // how to build raw content - QRCode with simple Business Card (VCard) 
     
    $tempDir = 'temp/'; 
     
    // here our data 
    $name = 'John Doe'; 
    $phone = '(049)012-345-678'; 
     
    // we building raw data 
    $codeContents  = 'BEGIN:VCARD'."\n"; 
    $codeContents .= 'FN:'.$name."\n"; 
    $codeContents .= 'TEL;WORK;VOICE:'.$phone."\n"; 
    $codeContents .= 'END:VCARD'; 
     
    // generating 
    QRcode::png($codeContents, $tempDir.'025.png', QR_ECLEVEL_L, 3); 
    
    // displaying 
    echo '<img src="temp/025.png" />'; 
