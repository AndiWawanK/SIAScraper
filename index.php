<?php 
include "simple_html_dom.php";

$formData = array(
    "act" => "login",
    "username" => "nim",
    "password" => "tanggal lahir"
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://sia.mercubuana-yogya.ac.id/gate.php/login");
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $formData);
curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie.txt");
$response = curl_exec($ch);
// curl_close($ch);

$html = new simple_html_dom();
$html->load($response);

$matkul = "";

if($html->find("div[class=alert alert-error]")){
    foreach($html->find("div[class=alert alert-error]") as $rowFluid){
        echo $rowFluid->plaintext;
    }
}else{
    // echo "Login Success";

    curl_setopt($ch, CURLOPT_URL, "https://sia.mercubuana-yogya.ac.id/akad.php/biomhs/jadwal");
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie.txt");
    $result = curl_exec($ch);
    curl_close($ch);

    $jadwal = new simple_html_dom();
    $jadwal->load($result);
    
    foreach($jadwal->find("table[class=table table-striped table-condensed]") as $dataJadwal){
       $matkul = $dataJadwal;
    }


}
echo $matkul;

$DOM = new DOMDocument();
$DOM->loadHTML($matkul);

$header = $DOM->getElementsByTagName('th');
$items = $DOM->getElementsByTagName('td');

foreach($header as $nodeHeader){
    $dataHeader[] = trim($nodeHeader->textContent);
}
// print_r($dataHTML);
// die;

$i = 0;
$j = 0;

foreach($items as $nodeItems){
    $dataItems[$j][] = trim($nodeItems->textContent);
    $i = $i + 1;
    $j = $i % count($dataHeader) == 0 ? $j + 1 : $j;
}
echo "<pre>";
print_r($dataItems);
die;

exit();
?>