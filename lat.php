<?php

$lat1 = "17.495416";
$lon1 = "78.388841";
//17.495416, 78.388841
$lat2 = "17.496774";
$lon2 = "78.388956";
//(17.496774, 78.388956)


//17.500888

function distance($lat1, $lon1, $lat2, $lon2, $unit) 
{
  if (($lat1 == $lat2) && ($lon1 == $lon2)) {
    return 0;
  }
  else {
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);

    if ($unit == "K") {
      return ($miles * 1.609344);
    } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
      return $miles;
    }
  }
}

if(distance($lat1, $lon1, $lat2, $lon2, "K") < 2)
{
  echo "True";
}
else
{
  echo "False";
}