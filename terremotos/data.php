<?php
$arr = simplexml_load_file("http://www.ign.es/ign/RssTools/sismologia.xml");

$output = [];
foreach ($arr->channel[0]->item as $element){
    $description = (string)$element->description;
    $pos = explode(" ", $description);

    $date_index = array_search('fecha', $pos);
    $time_index = $date_index + 1;
    $location_index = array_search('localización:', $pos);
    $magnitude_index = array_search('magnitud', $pos);

    $date = $pos[$date_index + 1];
    $time = $pos[$time_index + 1];

    $description = $element->description;

    $magnitude = $pos[$magnitude_index + 1];

    $location_start_index = strpos($description, 'en ');
    $location_end_index = strpos($description, ' en', $location_start_index);
    $location = substr($description, $location_start_index + strlen('en '), $location_end_index - $location_start_index - strlen('en '));

    $coords = explode(",", $pos[$location_index + 1]);
    $latitude = $coords[0];
    $longitude = $coords[1];

    $output_text = substr($description, 0, $location_start_index) . "\n" .
        $date . " " . $time . "\n" .
        $location . " (" . $magnitude . ")";

    $terremoto = [
        "date" => $date,
        "time" => $time,
        "description" => $output_text,
        "magnitude" => $magnitude,
        "location" => $location,
        "lat" => $latitude,
        "long" => $longitude
    ];

    $output[] = $terremoto;
}
header('Content-Type: application/json');
echo json_encode($output);
?>